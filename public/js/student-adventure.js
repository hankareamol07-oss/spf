// This theme changes presentation only. Laravel remains responsible for
// authentication, lesson availability, answer checking, XP and mastery.
document.querySelectorAll('[data-journey-target]').forEach((button) => {
    button.addEventListener('click', () => {
        const target = document.getElementById(button.dataset.journeyTarget);
        if (!target) return;
        document.querySelectorAll('[data-journey-panel]').forEach((panel) => {
            panel.hidden = panel !== target;
        });
        document.querySelectorAll('[data-journey-target]').forEach((choice) => {
            choice.setAttribute('aria-pressed', String(choice === button));
        });
    });
});

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;
    document.querySelectorAll('.spf-account-menu[open]').forEach((menu) => {
        menu.removeAttribute('open');
        menu.querySelector('summary')?.focus();
    });
});

document.addEventListener('click', (event) => {
    document.querySelectorAll('.spf-account-menu[open]').forEach((menu) => {
        if (!menu.contains(event.target)) menu.removeAttribute('open');
    });
});
