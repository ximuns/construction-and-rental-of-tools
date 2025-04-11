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
        $this->inputs['pl']['value'] = $value['value'];
        $this->calculate();
    }

    public function mount()
    {
        try {
            $this->services = Service::where('is_active', true)->get();

            if ($this->services->isEmpty()) {
                throw new \Exception('No active services found');
            }

            $this->selectedServiceId = $this->services->first()->id;
            $this->loadServiceInputs();

        } catch (\Throwable $e) {
            Log::channel('calculator')->error('Initialization failed: '.$e->getMessage());
            $this->addError('global', 'Ошибка инициализации калькулятора');
        }
    }

    protected function prepareFormula(string $formula, array $vars): string
    {
        foreach ($vars as $key => $value) {
            $formula = str_replace($key, (string)$value, $formula);
        }

        return $formula;
    }

    public function loadServiceInputs()
    {
        try {
            $service = Service::findOrFail($this->selectedServiceId);
            $this->inputs = [];

            Log::channel('calculator')->debug('Loading inputs for service', [
                'service_id' => $service->id,
                'config' => $service->calculator_config
            ]);

            foreach ($service->calculator_config['inputs'] ?? [] as $input) {
                $this->inputs[$input['key']] = [
                    'type' => $input['type'],
                    'label' => $input['label'],
                    'value' => $input['type'] === 'checkbox_group' ? [] : ($input['min'] ?? ''),
                    'options' => $input['options'] ?? ($input['inputs'] ?? [])
                ];
            }

            $this->calculationLog .= "Loaded service: {$service->title}\n";
            $this->calculate();

        } catch (\Throwable $e) {
            Log::channel('calculator')->error('Failed to load inputs: '.$e->getMessage(), [
                'service_id' => $this->selectedServiceId
            ]);
            $this->addError('global', 'Ошибка загрузки параметров услуги');
        }
    }

    public function updatedSelectedServiceId()
    {
        $this->loadServiceInputs();
        $this->calculate();
    }

    public function updatedInputs()
    {
        $this->calculate();
    }

    protected function calculate()
    {
        try {
            $service = Service::findOrFail($this->selectedServiceId);
            $config = $service->calculator_config;

            $vars = [
                'price' => (float)$config['price'],
                'pl' => (float)($this->inputs['pl']['value'] ?? 0),
                'select_multiplier' => $this->getSelectMultiplier(),
                'check_multiplier' => $this->getCheckMultiplier()
            ];

            $formula = str_replace(
                '(check_multiplier + select_multiplier)',
                '(check_multiplier * select_multiplier)',
                $config['formula']
            );

            $el = new ExpressionLanguage();
            $this->result = round($el->evaluate($formula, $vars), 2);

        } catch (\Throwable $e) {
            Log::channel('calculator')->error('Calculation failed', [
                'error' => $e->getMessage(),
                'formula' => $formula ?? null,
                'vars' => $vars ?? []
            ]);
            $this->result = 0;
        }
    }

    protected function getSelectMultiplier(): float
    {
        $selected = $this->inputs['select']['value'] ?? null;
        $option = collect($this->inputs['select']['options'] ?? [])
            ->firstWhere('value', $selected);

        return $option ? (float)$option['multiplier'] : 1.0;
    }

    protected function getCheckMultiplier(): float
    {
        return collect($this->inputs['check']['options'] ?? [])
            ->filter(fn($opt) => in_array($opt['key'], $this->inputs['check']['value'] ?? []))
            ->reduce(fn($carry, $opt) => $carry * (float)$opt['multiplier'], 1.0);
    }

    protected function getMultiplier($key, $input)
    {
        try {
            if ($input['type'] === 'select') {
                $option = collect($input['options'])->firstWhere('value', $input['value']);
                return $option ? (float)$option['multiplier'] : 1;
            }

            if ($input['type'] === 'checkbox_group') {
                return collect($input['options'])
                    ->filter(fn($opt) => in_array($opt['key'], $input['value'] ?? []))
                    ->reduce(fn($carry, $opt) => $carry * (float)$opt['multiplier'], 1);
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
