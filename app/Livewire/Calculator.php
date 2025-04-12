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

        if ($this->services->isEmpty()) {
            throw new \Exception("No active services found");
        }

        $this->selectedServiceId = $this->services->first()->id;
        $this->loadServiceInputs();
    }


    protected function getDefaultValue($input)
    {
        if (!isset($input['type'])) {
            Log::error('Input type missing:', ['input' => $input]);
            return null;
        }

        switch ($input['type']) {
            case 'range':
            case 'number':
                return $input['min'] ?? 0;
            case 'select':
                return $input['options'][0]['value'] ?? '';
            case 'checkbox_group':
                return [];
            default:
                Log::warning('Unknown input type:', ['type' => $input['type']]);
                return null;
        }
    }

    public function loadServiceInputs()
    {
        try {
            $this->resetErrorBag();
            $service = Service::findOrFail($this->selectedServiceId);

            $this->inputs = [];

            $config = $service->calculator_config;
            if (empty($config['inputs'])) {
                throw new \Exception("No inputs in calculator config");
            }

            foreach ($config['inputs'] as $input) {
                if (empty($input['key']) || empty($input['type'])) {
                    continue;
                }

                $this->inputs[$input['key']] = [
                    'type' => $input['type'],
                    'label' => $input['label'] ?? '',
                    'value' => $this->getDefaultValue($input),
                    'min' => $input['min'] ?? null,
                    'max' => $input['max'] ?? null,
                    'options' => $input['options'] ?? ($input['inputs'] ?? [])
                ];
            }

            $this->calculate();
        } catch (\Throwable $e) {
            Log::error("Service inputs load failed", [
                'service_id' => $this->selectedServiceId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->inputs = [];
            $this->addError('service', 'Ошибка загрузки услуги');
        }
    }

    public function updatedSelectedServiceId()
    {
        Log::channel('calculator')->debug('Service changed', [
            'old_service' => $this->selectedServiceId,
            'new_service' => $this->selectedServiceId,
            'inputs_before' => $this->inputs
        ]);

        $this->loadServiceInputs();

        Log::channel('calculator')->debug('Service changed - after load', [
            'inputs_after' => $this->inputs
        ]);
    }

    public function updatedInputs($value, $key)
    {
        $currentService = Service::find($this->selectedServiceId);
        $validKeys = collect($currentService->calculator_config['inputs'] ?? [])
            ->pluck('key')
            ->toArray();

        foreach ($this->inputs as $inputKey => $input) {
            if (!in_array($inputKey, $validKeys)) {
                unset($this->inputs[$inputKey]);
            }
        }

        $this->calculate();
    }

    public function calculate()
    {
        try {
            $service = Service::findOrFail($this->selectedServiceId);
            $config = $service->calculator_config;

            $vars = ['price' => (float)$config['price']];

            Log::debug('Calculation inputs', [
                'inputs' => $this->inputs,
                'config' => $config
            ]);

            foreach ($this->inputs as $key => $input) {
                $vars[$key] = is_array($input['value']) ? 1 : (float)$input['value'];
                $vars[$key.'_multiplier'] = $this->getMultiplier($key, $input);

                Log::debug('Calculation var', [
                    'key' => $key,
                    'value' => $vars[$key],
                    'multiplier' => $vars[$key.'_multiplier']
                ]);
            }

            $this->validateFormulaVariables($config['formula'], array_keys($vars));

            $el = new ExpressionLanguage();
            $this->result = round($el->evaluate($config['formula'], $vars), 2);

            Log::info('Calculation result', [
                'formula' => $config['formula'],
                'vars' => $vars,
                'result' => $this->result
            ]);

        } catch (\Throwable $e) {
            Log::error('Calculation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->result = 0;
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
            if (!is_array($input) || !isset($input['type'])) {
                return 1;
            }

            if ($input['type'] === 'select') {
                $selectedOption = collect($input['options'] ?? [])
                    ->firstWhere('value', $input['value'] ?? '');

                return $selectedOption['multiplier'] ?? 1;
            }

            if ($input['type'] === 'checkbox_group') {
                return collect($input['options'] ?? [])
                    ->filter(fn($opt) => in_array($opt['key'] ?? '', $input['value'] ?? []))
                    ->sum('multiplier');
            }

            if (in_array($input['type'], ['number', 'range'])) {
                return $input['multiplier'] ?? 1;
            }

            return 1;
        } catch (\Throwable $e) {
            Log::error("Multiplier calculation failed", [
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
