//RangeNumber
const rangeInput = document.getElementById('areaRange');
const rangeValue = document.getElementById('rangeValue');

if (rangeInput) {
    rangeInput.addEventListener('input', function() {
        rangeValue.textContent = this.value;
    });
}

//RadioButton
const radioInputs = document.querySelectorAll('.calculator__radioButton');
let selectedRadio;

radioInputs.forEach(input => {
    input.addEventListener('click', () => {
        if (selectedRadio) {
            selectedRadio.checked = false;
            selectedRadio.classList.remove('calculator__radioButton_active');
        }
        selectedRadio = input;
        input.checked = true;
        if (selectedRadio.checked) {
            selectedRadio.classList.add('calculator__radioButton_active');
        }
    });
});

//notification
document.addEventListener('livewire:initialized', () => {
    Livewire.on('notification-show', (event) => {
        const notification = document.createElement('div');
        notification.className = `notification ${event.type || 'success'}`;
        notification.textContent = event.message || 'Ваша заявка отправлена! Ожидайте ответа.';

        document.body.appendChild(notification);
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 5000);
    });
});



