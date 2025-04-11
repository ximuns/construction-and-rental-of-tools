<section class="calculator" id="calculator">
    <div class="calculator__container container">
        <div class="calculator__content">
            <div class="calculator__main">
                <h1 class="calculator__title" data-aos="fade-up">Рассчитайте стоимость услуг</h1>
                <p class="calculator__subtitle" data-aos="fade-up">Получите предварительную оценку стоимости вашего проекта</p>
            </div>

            <div class="calculator__form">
                <!-- Выбор услуги -->
                <div class="calculator__formGroup">
                    <label class="calculator__name">Тип проекта</label>
                    <div class="calculator__radioButtons">
                        @foreach($services as $service)
                            <div
                                class="calculator__radioButton {{ $selectedServiceId == $service->id ? 'calculator__radioButton_active' : '' }}"
                                wire:click="$set('selectedServiceId', {{ $service->id }})"
                            >
                                <div class="calculator__icon" style="--icon-url: url('{{ url('storage', $service->icon) }}')"></div>
                                <label class="calculator__typeName">{{ $service->titleCalculator }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Динамические поля -->
                @foreach($inputs as $key => $input)
                    @if(!isset($input['type']))
                        @continue
                    @endif

                    <div class="calculator__formGroup">
                        @if($input['type'] === 'range')
                            <div class="calculator__input">
                                <div class="calculator__label">
                                    <label class="calculator__nameInput">{{ $input['label'] }}</label>
                                    <p class="calculator__inputNumber">{{ $input['value'] }}</p>
                                </div>
                                <input
                                    type="range"
                                    class="calculator__range"
                                    wire:model.lazy="inputs.{{ $key }}.value"
                                    min="{{ $input['min'] ?? 0 }}"
                                    max="{{ $input['max'] ?? 100 }}"
                                >
                            </div>
                        @elseif($input['type'] === 'number')
                            <div class="calculator__select">
                                <label class="calculator__nameInput">{{ $input['label'] }}</label>
                                <input type="number" class="calculator__selectOptions" min="{{ $input['min'] ?? 0 }}" max="{{ $input['max'] ?? 100 }}" wire:model.lazy="inputs.{{ $key }}.value">
                            </div>
                        @elseif($input['type'] === 'select')
                            <div class="calculator__select">
                                <label class="calculator__nameInput">{{ $input['label'] }}</label>
                                <select class="calculator__selectOptions" wire:model.lazy="inputs.{{ $key }}.value">
                                    @foreach($input['options'] as $option)
                                        <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @elseif($input['type'] === 'checkbox_group')
                            <div class="calculator__services">
                                <label class="calculator__nameInput">{{ $input['label'] }}</label>
                                <div class="calculator__toggles">
                                    @foreach($input['options'] as $optionKey => $option)
                                        <div class="calculator__toggle">
                                            <label class="calculator__switch">
                                                <input
                                                    type="checkbox"
                                                    value="{{ $option['key'] ?? $optionKey }}"
                                                    wire:model.lazy="inputs.{{ $key }}.value"
                                                >
                                                <span class="calculator__slider"></span>
                                            </label>
                                            <p class="calculator__toggleText">{{ $option['label'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach

                <!-- Результат -->
                <div class="calculator__result">
                    <div class="calculator__resultGroup">
                        <p class="calculator__resultText">Предварительная стоимость:</p>
                        <p class="calculator__resultPrice">{{ number_format($result, 0, '', ' ') }} ₽</p>
                    </div>
                    <p class="calculator__resultDescr">*Финальная цена может отличаться после осмотра объекта</p>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        // Обновление значения для range-инпутов
        document.addEventListener('livewire:load', function() {
            Livewire.hook('element.updated', (el, component) => {
                if (el.classList.contains('calculator__range')) {
                    const valueDisplay = el.closest('.calculator__input').querySelector('.calculator__inputNumber');
                    valueDisplay.textContent = el.value;
                }
            });
        });
    </script>
@endpush
