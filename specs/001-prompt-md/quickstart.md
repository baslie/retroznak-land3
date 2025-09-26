# Quickstart: Лендинг Ретрознак

## Быстрый старт разработки

### 1. Настройка окружения

```bash
# Переход в корень проекта
cd C:\Users\Roman\Desktop\retroznak-land3

# Установка DaisyUI (если требуется)
npm init -y
npm install daisyui@latest
```

### 2. Создание базовой структуры

```bash
# Создание папок
mkdir css js images backend

# Создание основных файлов
touch index.html
touch css/styles.css
touch js/main.js
touch js/forms.js
touch backend/contact-form.php
touch daisyui.config.js
```

### 3. Базовая настройка DaisyUI

**daisyui.config.js**:
```javascript
module.exports = {
  content: ["./*.html", "./js/*.js"],
  plugins: [require("daisyui")],
  daisyui: {
    themes: [{
      "retroznak": {
        "primary": "#f97316",      // Оранжевый акцент
        "secondary": "#374151",    // Темно-серый
        "accent": "#f97316",       // Оранжевый
        "neutral": "#1f2937",      // Темный фон
        "base-100": "#111827",     // Основной темный
        "base-200": "#1f2937",     // Темнее
        "base-300": "#374151",     // Еще темнее
      }
    }],
    base: false,
    utils: true,
  },
}
```

### 4. Базовый HTML каркас

**index.html** (минимальная версия):
```html
<!DOCTYPE html>
<html lang="ru" data-theme="retroznak">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ретрознак - Домовые знаки советской эпохи</title>
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <!-- Навигация -->
    <div class="navbar bg-base-100 fixed top-0 z-50">
        <div class="navbar-start">
            <div class="dropdown lg:hidden">
                <div tabindex="0" role="button" class="btn btn-ghost">☰</div>
                <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                    <li><a href="#catalog">Каталог</a></li>
                    <li><a href="#production">О производстве</a></li>
                    <li><a href="#reviews">Отзывы</a></li>
                    <li><a href="#faq">FAQ</a></li>
                </ul>
            </div>
            <a class="btn btn-ghost text-xl">Ретрознак</a>
        </div>
        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <li><a href="#catalog">Каталог</a></li>
                <li><a href="#production">О производстве</a></li>
                <li><a href="#reviews">Отзывы</a></li>
                <li><a href="#faq">FAQ</a></li>
            </ul>
        </div>
        <div class="navbar-end">
            <a class="btn btn-primary">Задать вопрос</a>
        </div>
    </div>

    <!-- Hero секция -->
    <div class="hero min-h-screen bg-base-200" id="hero">
        <div class="hero-content text-center">
            <div class="max-w-md">
                <h1 class="text-5xl font-bold">ДОМОВЫЕ ЗНАКИ СОВЕТСКОЙ ЭПОХИ</h1>
                <p class="py-6">Душевные адресные указатели из металла с подсветкой. От 1 990 рублей.</p>
                <button class="btn btn-primary">Выбрать модель</button>
            </div>
        </div>
    </div>

    <!-- История (Timeline) -->
    <section class="bg-base-100" id="history">
        <div class="hero py-16">
            <div class="hero-content text-center">
                <div class="max-w-4xl">
                    <h2 class="text-3xl font-bold mb-12">История, которая украсит ваш дом</h2>
                    <ul class="timeline timeline-vertical lg:timeline-horizontal">
                        <li>
                            <div class="timeline-start timeline-box">1920-е</div>
                            <div class="timeline-middle">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <hr/>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Товарный каталог (Cards) -->
    <section class="bg-base-200" id="catalog">
        <div class="hero py-16">
            <div class="hero-content">
                <div class="text-center">
                    <h2 class="text-3xl font-bold mb-12">Выберите свой ретрознак</h2>
                    <div class="flex flex-wrap gap-8 justify-center">
                        <div class="card bg-base-100 shadow-xl">
                            <div class="card-body">
                                <h3 class="card-title">
                                    Обычный
                                    <div class="badge badge-secondary">Доступное решение</div>
                                </h3>
                                <p>От 1 990 рублей</p>
                                <div class="card-actions justify-end">
                                    <button class="btn btn-primary">Заказать</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ (Collapse) -->
    <section class="bg-base-100" id="faq">
        <div class="hero py-16">
            <div class="hero-content w-full">
                <div class="text-center w-full">
                    <h2 class="text-3xl font-bold mb-12">Ответы на главные вопросы</h2>
                    <div class="join join-vertical w-full max-w-4xl">
                        <div class="collapse collapse-arrow join-item border border-base-300">
                            <input type="radio" name="my-accordion-4" checked="checked" />
                            <div class="collapse-title text-xl font-medium">Сколько стоит ретрознак?</div>
                            <div class="collapse-content">
                                <p>От 1 990 рублей за базовую модель</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer footer-center bg-base-200 text-base-content p-10">
        <aside>
            <p class="font-bold text-xl">Ретрознак</p>
            <p>ООО «Три Кита» © 2017–2025</p>
        </aside>
        <nav>
            <div class="grid grid-flow-col gap-4">
                <a class="link link-hover">Инструкция по монтажу</a>
                <a class="link link-hover">Блог о типографике</a>
            </div>
        </nav>
    </footer>

    <script src="js/main.js"></script>
    <script src="js/forms.js"></script>
</body>
</html>
```

### 5. Проверка работоспособности

#### Тест навигации:
1. Открыть `index.html` в браузере
2. Проверить отображение на разных размерах экрана (320px+)
3. Кликнуть по пунктам меню - должны работать якорные ссылки

#### Тест форм:
1. Открыть консоль разработчика
2. Заполнить форму контактов
3. Отправить форму - проверить AJAX запрос к PHP backend
4. Проверить получение email уведомления

#### Тест производительности:
1. Открыть DevTools → Performance
2. Перезагрузить страницу
3. Проверить First Contentful Paint < 1.5 секунд

### 6. Интеграция с существующими файлами

**Использование docs/wireframe.md**:
- Контент для каждой секции берется из wireframe.md
- Следовать структуре и последовательности секций
- Сохранить эмоционально-исторический тон

**Адаптация etc/send-form.php**:
- Скопировать логику в backend/contact-form.php
- Добавить валидацию для новых полей
- Настроить email отправку

### 7. Финальная проверка

- [ ] Все DaisyUI компоненты используются корректно
- [ ] Адаптивность работает от 320px
- [ ] Формы отправляются на PHP backend
- [ ] Темная тема с оранжевыми акцентами применена
- [ ] FCP < 1.5 секунд
- [ ] Исторический контент интегрирован

## Готовность к разработке

После выполнения quickstart-а проект готов к полной реализации согласно техническому заданию и конституции.