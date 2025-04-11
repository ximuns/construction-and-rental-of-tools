//RangeNumber
const rangeInput = document.getElementById('areaRange');
const rangeValue = document.getElementById('rangeValue');

if (rangeInput) {
    let animationFrame;
    let targetValue = rangeInput.value;
    let currentValue = targetValue;

    rangeValue.textContent = currentValue;

    rangeInput.addEventListener('input', function() {
        targetValue = this.value;

        cancelAnimationFrame(animationFrame);

        const animate = () => {
            const diff = targetValue - currentValue;

            currentValue += diff * 0.2;

            if (Math.abs(diff) < 0.5) {
                currentValue = targetValue;
            }

            rangeValue.textContent = Math.round(currentValue);

            if (currentValue === targetValue || Math.abs(diff) >= 1) {
                Livewire.dispatch('range-updated', { value: Math.round(currentValue) });
            }

            if (currentValue !== targetValue) {
                animationFrame = requestAnimationFrame(animate);
            }
        };

        animate();
    });
}


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



//burgerMenu
document.querySelector('.burger').addEventListener('click', function() {
    this.classList.toggle('active');
    document.querySelector('.root__menu').classList.toggle('active');
});

document.querySelectorAll('.root__link a').forEach(link => {
    link.addEventListener('click', () => {
        document.querySelector('.burger').classList.remove('active');
        document.querySelector('.root__menu').classList.remove('active');
    });
});
