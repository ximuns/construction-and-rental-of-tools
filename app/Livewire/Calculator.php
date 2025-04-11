<?php

namespace App\Livewire;

use App\Models\Service;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class Calculator extends Component
{
    public $selectedServiceId;
    public $services;
    public $inputs = [];
    public $result = 0;
    public $calculationLog = '';
    protected $listeners = ['range-updated' => 'updateRangeValue'];

    public function updateRangeValue($value)
    {
        $this->inputs['dl']['value'] = $value['value'];
        $this->calculate();
    }

    public function mount()
    {
        $this->services = Service::where('is_active', true)->get();
        $this->selectedServiceId = $this->services->first()->id;
        $this->loadServiceInputs();
    }


    protected function getDefaultValue($input)
    {
        if (!isset($input['type'])) {
            Log::error('Input type missing:', $input);
            return null;
        }

        return match($input['type']) {
            'range', 'number' => $input['min'] ?? 0,
            'select' => $input['options'][0]['value'] ?? '',
            'checkbox_group' => [],
            default => null
        };
    }

    public function loadServiceInputs()
    {
        $service = Service::findOrFail($this->selectedServiceId);
        $config = $service->calculator_config;

        $this->inputs = [];

        foreach ($config['inputs'] ?? [] as $input) {
            if (!isset($input['key']) || !isset($input['type'])) {
                Log::error('Invalid input configuration:', $input);
                continue;
            }

            $key = $input['key'];
            $this->inputs[$key] = [
                'type' => $input['type'],
                'label' => $input['label'] ?? '',
                'value' => $this->getDefaultValue($input),
                'min' => $input['min'] ?? null,
                'max' => $input['max'] ?? null,
                'options' => $input['options'] ?? ($input['inputs'] ?? [])
            ];
        }

        $this->calculate();
    }

    public function updatedSelectedServiceId()
    {
        $this->loadServiceInputs();
    }

    public function updatedInputs()
    {
        $this->calculate();
    }

    public function calculate()
    {
        try {
            $service = Service::findOrFail($this->selectedServiceId);
            $config = $service->calculator_config;

            $vars = ['price' => (float)$config['price']];

            foreach ($this->inputs as $key => $input) {
                $vars[$key] = is_array($input['value']) ? 1 : (float)$input['value'];

                $vars[$key.'_multiplier'] = $this->getMultiplier($key, $input);
            }

            $this->validateFormulaVariables($config['formula'], array_keys($vars));

            $el = new ExpressionLanguage();
            $this->result = round($el->evaluate($config['formula'], $vars), 2);

            Log::channel('calculator')->info('Calculation success', [
                'service' => $service->title,
                'formula' => $config['formula'],
                'vars' => $vars,
                'result' => $this->result
            ]);

        } catch (\Throwable $e) {
            Log::channel('calculator')->error('Calculation failed', [
                'error' => $e->getMessage(),
                'formula' => $config['formula'] ?? null,
                'vars' => $vars ?? null
            ]);
            $this->result = 0;
            $this->addError('calculation', 'Ошибка расчета: '.$e->getMessage());
        }
    }

    protected function validateFormulaVariables($formula, $availableVars)
    {
        preg_match_all('/[a-zA-Z_][a-zA-Z0-9_]*/', $formula, $matches);
        $usedVars = array_unique($matches[0]);
        $availableVars = array_map('strval', $availableVars);

        foreach ($usedVars as $var) {
            if (!in_array($var, $availableVars) && !is_numeric($var)) {
                throw new \Exception("Variable \"$var\" is not defined");
            }
        }
    }

    protected function getMultiplier($key, $input)
    {
        try {
            if ($input['type'] === 'select') {
                $option = collect($input['options'])->firstWhere('value', $input['value']);
                return $option ? (float)($option['multiplier'] ?? 1) : 1;
            }

            if ($input['type'] === 'checkbox_group') {
                return collect($input['options'])
                    ->filter(fn($opt) => in_array($opt['key'], $input['value'] ?? []))
                    ->reduce(fn($carry, $opt) => $carry + (float)($opt['multiplier'] ?? 1), 0);
            }

            return 1;

        } catch (\Throwable $e) {
            Log::channel('calculator')->error('Multiplier calculation failed', [
                'key' => $key,
                'input' => $input,
                'error' => $e->getMessage()
            ]);
            return 1;
        }
    }

    public function render()
    {
        return view('livewire.calculator');
    }
}
