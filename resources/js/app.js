import './bootstrap';

const loader = document.querySelector('[data-loader]');
if (loader) {
    document.body.classList.add('loading');
    let progress = 0;
    const timer = setInterval(() => {
        progress = Math.min(100, progress + Math.ceil(Math.random() * 13));
        loader.querySelector('[data-loader-number]').textContent = `${progress}%`;
        loader.querySelector('[data-loader-bar]').style.width = `${progress}%`;
        if (progress === 100) {
            clearInterval(timer);
            setTimeout(() => { loader.classList.add('hidden'); document.body.classList.remove('loading'); }, 250);
        }
    }, 45);
}

const header = document.querySelector('[data-header]');
const setHeader = () => header?.classList.toggle('scrolled', window.scrollY > 30);
setHeader(); window.addEventListener('scroll', setHeader, { passive: true });

const menuButton = document.querySelector('[data-menu-button]');
const menu = document.querySelector('[data-menu]');
menuButton?.addEventListener('click', () => menu?.classList.toggle('open'));
menu?.querySelectorAll('a').forEach(link => link.addEventListener('click', () => menu.classList.remove('open')));

const observer = new IntersectionObserver(entries => entries.forEach(entry => {
    if (entry.isIntersecting) { entry.target.classList.add('visible'); observer.unobserve(entry.target); }
}), { threshold: .12 });
document.querySelectorAll('.reveal').forEach(element => observer.observe(element));

const dialog = document.querySelector('[data-product-dialog]');
document.querySelectorAll('[data-product]').forEach(card => card.addEventListener('click', () => {
    dialog.querySelector('[data-dialog-image]').src = card.dataset.image;
    dialog.querySelector('[data-dialog-image]').alt = card.dataset.name;
    dialog.querySelector('[data-dialog-name]').textContent = card.dataset.name;
    dialog.querySelector('[data-dialog-market]').textContent = card.dataset.market;
    dialog.querySelector('[data-dialog-description]').textContent = card.dataset.description;
    dialog.showModal();
}));
document.querySelectorAll('[data-dialog-close]').forEach(button => button.addEventListener('click', () => dialog?.close()));
dialog?.addEventListener('click', event => { if (event.target === dialog) dialog.close(); });
