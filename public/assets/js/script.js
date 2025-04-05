//RangeNumber
const rangeInput = document.getElementById('areaRange');
const rangeValue = document.getElementById('rangeValue');

rangeInput.addEventListener('input', function() {
    rangeValue.textContent = this.value;
});

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
