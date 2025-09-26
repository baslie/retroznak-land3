// JavaScript для обработки форм лендинга Ретрознак
// Включает валидацию, AJAX отправку и модальные окна

document.addEventListener('DOMContentLoaded', function() {

    // Правила валидации на основе data-model.md
    const validationRules = {
        contactForm: {
            name: {
                required: true,
                minLength: 2,
                pattern: /^[а-яА-Яa-zA-Z\s]+$/,
                message: 'Имя должно содержать от 2 символов, только буквы и пробелы'
            },
            email: {
                required: true,
                pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                message: 'Неверный формат email адреса'
            },
            phone: {
                required: false,
                pattern: /^(\+7|8)?\s?\(?\d{3}\)?\s?\d{3}[\-\s]?\d{2}[\-\s]?\d{2}$/,
                message: 'Неверный формат телефона (используйте российский формат)'
            },
            message: {
                required: false,
                maxLength: 500,
                message: 'Сообщение не должно превышать 500 символов'
            }
        },
        productInquiry: {
            name: {
                required: true,
                minLength: 2,
                message: 'Имя должно содержать минимум 2 символа'
            },
            email: {
                required: true,
                pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                message: 'Неверный формат email адреса'
            },
            product_type: {
                required: true,
                enum: ['obychny', 'petrogradsky', 'leningradsky'],
                message: 'Выберите тип ретрознака'
            },
            address: {
                required: false,
                maxLength: 200,
                message: 'Адрес не должен превышать 200 символов'
            }
        }
    };

    // Функция валидации поля
    function validateField(value, rules) {
        if (rules.required && (!value || value.trim() === '')) {
            return { valid: false, message: rules.message || 'Поле обязательно для заполнения' };
        }

        if (value && rules.minLength && value.length < rules.minLength) {
            return { valid: false, message: rules.message };
        }

        if (value && rules.maxLength && value.length > rules.maxLength) {
            return { valid: false, message: rules.message };
        }

        if (value && rules.pattern && !rules.pattern.test(value)) {
            return { valid: false, message: rules.message };
        }

        if (value && rules.enum && !rules.enum.includes(value)) {
            return { valid: false, message: rules.message };
        }

        return { valid: true };
    }

    // Функция валидации формы
    function validateForm(formData, formType) {
        const rules = validationRules[formType];
        const errors = {};
        let isValid = true;

        for (const [field, fieldRules] of Object.entries(rules)) {
            const value = formData.get(field);
            const validation = validateField(value, fieldRules);

            if (!validation.valid) {
                errors[field] = validation.message;
                isValid = false;
            }
        }

        return { isValid, errors };
    }

    // Функция отображения ошибок в форме
    function displayFormErrors(form, errors) {
        // Очищаем предыдущие ошибки
        const errorElements = form.querySelectorAll('.field-error');
        errorElements.forEach(el => el.remove());

        // Добавляем новые ошибки
        for (const [field, message] of Object.entries(errors)) {
            const fieldElement = form.querySelector(`[name="${field}"]`);
            if (fieldElement) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'field-error text-error text-sm mt-1';
                errorDiv.textContent = message;
                fieldElement.parentNode.appendChild(errorDiv);
                fieldElement.classList.add('input-error');
            }
        }
    }

    // AJAX отправка формы
    async function submitForm(formData, formType) {
        try {
            const response = await fetch('/backend/contact-form.php', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const result = await response.json();
            return result;
        } catch (error) {
            console.error('Ошибка отправки формы:', error);
            return {
                success: false,
                error: 'Ошибка отправки. Проверьте подключение к интернету.',
                timestamp: new Date().toISOString()
            };
        }
    }

    // Функция показа уведомлений
    function showNotification(message, type = 'info') {
        // Создаем уведомление
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} fixed top-20 right-4 z-50 max-w-sm shadow-lg`;
        notification.innerHTML = `
            <svg class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>${message}</span>
        `;

        document.body.appendChild(notification);

        // Убираем уведомление через 5 секунд
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    // Создание модального окна
    function createModal(title, content, size = 'modal-box') {
        const modal = document.createElement('div');
        modal.className = 'modal modal-open';
        modal.innerHTML = `
            <div class="modal-box ${size}">
                <h3 class="font-bold text-lg mb-4">${title}</h3>
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 modal-close">✕</button>
                ${content}
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        `;

        document.body.appendChild(modal);

        // Закрытие модального окна
        const closeButtons = modal.querySelectorAll('.modal-close, .modal-backdrop button');
        closeButtons.forEach(button => {
            button.addEventListener('click', () => {
                modal.remove();
            });
        });

        return modal;
    }

    // Глобальные функции для модальных окон
    window.openContactModal = function() {
        const content = `
            <form id="contact-form" class="space-y-4">
                <input type="hidden" name="form_type" value="contact">

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Имя *</span>
                    </label>
                    <input type="text" name="name" placeholder="Ваше имя" class="input input-bordered" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email *</span>
                    </label>
                    <input type="email" name="email" placeholder="your@email.com" class="input input-bordered" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Телефон</span>
                    </label>
                    <input type="tel" name="phone" placeholder="+7 (999) 123-45-67" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Предпочтительный способ связи</span>
                    </label>
                    <select name="preferred_contact" class="select select-bordered">
                        <option value="email">Email</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="telegram">Telegram</option>
                    </select>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Сообщение</span>
                    </label>
                    <textarea name="message" class="textarea textarea-bordered" placeholder="Расскажите, что вас интересует..."></textarea>
                </div>

                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">Отправить заявку</button>
                    <button type="button" class="btn modal-close">Отмена</button>
                </div>
            </form>
        `;

        const modal = createModal('Связаться с нами', content);
        setupFormSubmission(modal.querySelector('#contact-form'), 'contactForm');
    };

    window.openHistoryModal = function() {
        const content = `
            <form id="history-form" class="space-y-4">
                <input type="hidden" name="form_type" value="history_inquiry">

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Имя *</span>
                    </label>
                    <input type="text" name="name" placeholder="Ваше имя" class="input input-bordered" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email *</span>
                    </label>
                    <input type="email" name="email" placeholder="your@email.com" class="input input-bordered" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Адрес дома *</span>
                    </label>
                    <input type="text" name="address" placeholder="Город, улица, дом" class="input input-bordered" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Комментарий</span>
                    </label>
                    <textarea name="message" class="textarea textarea-bordered" placeholder="Что вы знаете об истории вашего дома?"></textarea>
                </div>

                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">Узнать историю</button>
                    <button type="button" class="btn modal-close">Отмена</button>
                </div>
            </form>
        `;

        const modal = createModal('История вашего дома', content);
        setupFormSubmission(modal.querySelector('#history-form'), 'contactForm');
    };

    window.openOrderModal = function() {
        const content = `
            <form id="order-form" class="space-y-4">
                <input type="hidden" name="form_type" value="product_inquiry">

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Имя *</span>
                    </label>
                    <input type="text" name="name" placeholder="Ваше имя" class="input input-bordered" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email *</span>
                    </label>
                    <input type="email" name="email" placeholder="your@email.com" class="input input-bordered" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Тип ретрознака *</span>
                    </label>
                    <select name="product_type" class="select select-bordered" required>
                        <option value="">Выберите модель</option>
                        <option value="obychny">Обычный (от 1 990 ₽)</option>
                        <option value="petrogradsky">Петроградский (от 3 490 ₽)</option>
                        <option value="leningradsky">Ленинградский (от 7 990 ₽)</option>
                    </select>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Адрес установки</span>
                    </label>
                    <input type="text" name="address" placeholder="Город, улица, дом" class="input input-bordered">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Ценовой диапазон</span>
                    </label>
                    <select name="budget_range" class="select select-bordered">
                        <option value="">Не важно</option>
                        <option value="до_5000">До 5 000 ₽</option>
                        <option value="5000_10000">5 000 - 10 000 ₽</option>
                        <option value="10000_plus">Более 10 000 ₽</option>
                    </select>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Дополнительные опции</span>
                    </label>
                    <div class="space-y-2">
                        <label class="cursor-pointer label justify-start">
                            <input type="checkbox" name="additional_options[]" value="led_lighting" class="checkbox checkbox-primary mr-2">
                            <span class="label-text">LED подсветка (+2 100 ₽)</span>
                        </label>
                        <label class="cursor-pointer label justify-start">
                            <input type="checkbox" name="additional_options[]" value="street_plate" class="checkbox checkbox-primary mr-2">
                            <span class="label-text">Табличка с названием улицы (+1 400 ₽)</span>
                        </label>
                        <label class="cursor-pointer label justify-start">
                            <input type="checkbox" name="additional_options[]" value="custom_color" class="checkbox checkbox-primary mr-2">
                            <span class="label-text">Индивидуальная цветовая палитра (+300 ₽ за элемент)</span>
                        </label>
                    </div>
                </div>

                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">Заказать расчёт</button>
                    <button type="button" class="btn modal-close">Отмена</button>
                </div>
            </form>
        `;

        const modal = createModal('Заказать ретрознак', content, 'modal-box max-w-2xl');
        setupFormSubmission(modal.querySelector('#order-form'), 'productInquiry');
    };

    // Настройка отправки формы
    function setupFormSubmission(form, validationType) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');

            // Показываем состояние загрузки
            const originalText = submitButton.textContent;
            submitButton.textContent = 'Отправляем...';
            submitButton.disabled = true;

            // Валидируем форму
            const validation = validateForm(formData, validationType);

            if (!validation.isValid) {
                displayFormErrors(form, validation.errors);
                submitButton.textContent = originalText;
                submitButton.disabled = false;
                return;
            }

            // Очищаем ошибки
            const errorElements = form.querySelectorAll('.field-error, .input-error');
            errorElements.forEach(el => {
                if (el.classList.contains('field-error')) {
                    el.remove();
                } else {
                    el.classList.remove('input-error');
                }
            });

            // Отправляем форму
            const result = await submitForm(formData, validationType);

            if (result.success) {
                showNotification('Заявка успешно отправлена. Мы свяжемся с вами в ближайшее время.', 'success');
                form.closest('.modal').remove(); // Закрываем модальное окно
            } else {
                showNotification(result.error || 'Произошла ошибка при отправке заявки', 'error');

                if (result.details) {
                    displayFormErrors(form, result.details);
                }
            }

            submitButton.textContent = originalText;
            submitButton.disabled = false;
        });
    }

    console.log('Ретрознак: скрипты форм загружены');
});