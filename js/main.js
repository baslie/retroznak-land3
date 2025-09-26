// Главный JavaScript файл для лендинга Ретрознак
// Обеспечивает плавную навигацию и интерактивность

document.addEventListener('DOMContentLoaded', function() {

    // Плавная прокрутка для якорных ссылок
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                const offsetTop = targetElement.offsetTop - 80; // Учитываем высоту fixed navbar

                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Активное состояние пунктов меню при скролле
    const sections = document.querySelectorAll('section[id]');
    const menuItems = document.querySelectorAll('.navbar a[href^="#"]');

    function updateActiveMenuItem() {
        let current = '';

        sections.forEach(section => {
            const sectionTop = section.offsetTop - 100;
            const sectionHeight = section.offsetHeight;

            if (window.pageYOffset >= sectionTop &&
                window.pageYOffset < sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });

        menuItems.forEach(item => {
            item.classList.remove('active');
            if (item.getAttribute('href') === '#' + current) {
                item.classList.add('active');
            }
        });
    }

    window.addEventListener('scroll', updateActiveMenuItem);

    // Закрытие мобильного меню при клике на пункт
    const mobileMenuItems = document.querySelectorAll('.dropdown .menu a');
    const mobileMenuToggle = document.querySelector('.dropdown [tabindex="0"]');

    mobileMenuItems.forEach(item => {
        item.addEventListener('click', function() {
            // Убираем фокус с toggle элемента, что закрывает dropdown
            if (mobileMenuToggle) {
                mobileMenuToggle.blur();
            }
        });
    });

    // Добавление эффекта hover для кнопок CTA
    const ctaButtons = document.querySelectorAll('.btn-cta');
    ctaButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 12px rgba(249, 115, 22, 0.3)';
        });

        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });

    // Lazy loading для изображений
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.classList.remove('loading');
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                }
            });
        });

        const lazyImages = document.querySelectorAll('img[data-loading="lazy"]');
        lazyImages.forEach(img => imageObserver.observe(img));
    }

    // Анимация появления элементов при скролле
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    }, observerOptions);

    // Добавляем наблюдение для карточек и секций
    const animatedElements = document.querySelectorAll('.card, .timeline, .hero-content');
    animatedElements.forEach(el => observer.observe(el));

    console.log('Ретрознак: основные скрипты загружены');
});

// Глобальные функции для модальных окон
window.openContactModal = function() {
    // TODO: Реализовать модальное окно контактов
    // Временно используем alert
    alert('Модальное окно контактов будет реализовано в forms.js');
};

window.openHistoryModal = function() {
    // TODO: Реализовать модальное окно истории дома
    alert('Модальное окно истории дома будет реализовано в forms.js');
};

window.openOrderModal = function() {
    // TODO: Реализовать модальное окно заказа
    alert('Модальное окно заказа будет реализовано в forms.js');
};

window.openPdfModal = function(type) {
    // TODO: Реализовать открытие PDF файлов
    alert(`PDF "${type}" будет реализован позже`);
};

window.openBlog = function() {
    // TODO: Реализовать переход в блог
    alert('Блог о советской типографике будет реализован позже');
};

// Утилитарные функции
const utils = {
    // Проверка видимости элемента
    isElementVisible: function(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    },

    // Дебаунс для оптимизации событий скролла
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    // Плавная прокрутка к элементу
    scrollToElement: function(element, offset = 80) {
        if (element) {
            const elementTop = element.offsetTop - offset;
            window.scrollTo({
                top: elementTop,
                behavior: 'smooth'
            });
        }
    }
};

// Экспорт утилит для использования в других файлах
window.retroznakUtils = utils;