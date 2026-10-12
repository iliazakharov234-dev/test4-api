// Бургер-меню (мобильный)
const burger = document.getElementById('burger');
const overlay = document.getElementById('overlay');

if (burger) {
    burger.addEventListener('click', () => {
        document.body.classList.toggle('sidebar-open');
    });
}

if (overlay) {
    overlay.addEventListener('click', () => {
        document.body.classList.remove('sidebar-open');
    });
}

// Закрытие alert
document.querySelectorAll('.alert__close').forEach(btn => {
    btn.addEventListener('click', () => {
        const alert = btn.closest('.alert--success, .alert--danger');
        if (alert) alert.remove();
    });
});
