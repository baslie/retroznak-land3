# Дорожная карта проекта «Ретрознак»

## 1. Общий обзор
- Лендинг о домовых знаках советской эпохи. Главные артефакты: `index.html` (dev-версия) и `index.production.html` (оптимизированный билд).
- Основной стек: HTML + DaisyUI/Tailwind, кастомный CSS (`css/`), Vanilla JS (`js/`), PHP для приёма форм (`backend/`, `etc/`).
- Пакетный менеджер (npm) используется только для DaisyUI/Tailwind (`package.json`, `package-lock.json`). Специальных сборочных команд нет.

## 2. Фронтенд
### 2.1 HTML
- `index.html` — полнофункциональная страница: фиксированное меню, hero, таймлайн истории, применение, каталог, производство, отзывы, процесс заказа, FAQ, финальный CTA, футер. Инлайн-критический CSS и ленивые шрифты обеспечивают быстрый FCP.
- `index.production.html` — тот же контент, но сжатыми ресурсами и готовой версией для публикации.

### 2.2 Стили
- `css/styles.css` — импорт DaisyUI, глобальные переменные темы «retroznak», базовые кастомные стили (типографика, кнопки, lazy loading, адаптивность).
- `css/styles.min.css` — минифицированная версия для продакшена.

### 2.3 Скрипты
- `js/main.js` — навигация (smooth scroll, active state), поведение меню, hover-эффекты CTA, lazy loading изображений, IntersectionObserver-анимации, заглушки модалок и утилиты.
- `js/forms.js` — валидация (на основе `specs/001-prompt-md/data-model.md`), AJAX отправка форм, динамические модальные окна с формами, уведомления.
- `js/main.min.js` — минифицированный бандл для продакшена.

## 3. Бэкенд
- `backend/contact-form.php` — основная серверная логика: конфигурация, валидация разных типов заявок (`contact`, `product_inquiry`, `history_inquiry`), формирование HTML-письма, отправка email, JSON-ответы.
- `etc/send-form.php` — почти идентичный пример обработчика (используется как референс/резервная копия).

## 4. Активы и медиа
- `images/logo.svg`, `images/retroznak-placeholder.svg` — базовая графика.
- `.playwright-mcp/*.png` — заскриншотенные секции лендинга (генерируются инструментом Playwright MCP).

## 5. Документация и требования
- `README.md` — высокоуровневое описание, структура, запуск.
- `docs/wireframe.md` — текстовый вайрфрейм всех секций.
- `playwright-test-report.md` — отчёт о прогоне Playwright.
- `specs/001-prompt-md/` — полный комплект дизайн-артефактов: `spec.md`, `research.md`, `plan.md`, `data-model.md`, `quickstart.md`, `tasks.md`, API-контракт в `contracts/`.

## 6. Конфигурация и зависимости
- `daisyui.config.js` — тема и настройки DaisyUI.
- `package.json` / `package-lock.json` — npm-пакеты (`daisyui`, `tailwindcss`), скриптов минимум.
- `.gitignore` — исключения для Git.

## 7. Инструменты для агентов
- `CLAUDE.md` — указание общаться по-русски.
- `.claude/` — набор команд (analyze, plan, specify и т.д.) и локальные разрешения `settings.local.json`.
- `.specify/` — экосистема Specify: `memory/constitution.md` (принципы DaisyUI/аутентичности), PowerShell-скрипты (`scripts/`) и шаблоны (`templates/`).
- `.playwright-mcp/` — артефакты браузерных тестов.

## 8. Контроль версий и метаданные
- `.git/` — репозиторий Git.
- `CLAUDE.md`, `README.md`, `AGENTS.md` — основные точки входа для людей/агентов.

## 9. Рабочие процессы
1. **Запуск**: при необходимости установить зависимости `npm install`. Для тестов/просмотра достаточно открыть `index.html` (или поднять PHP-сервер, если требуется бэкенд).
2. **Разработка UI**: менять `index.html`, `css/styles.css`, `js/main.js`; затем синхронизировать минифицированные версии.
3. **Формы**: фронтенд (`js/forms.js`) ↔ бэкенд (`backend/contact-form.php`). Контракт описан в `specs/001-prompt-md/contracts/contact-form.md`.
4. **Документация**: актуализировать `docs/` и `specs/` при изменениях требований.

## 10. Полезные напоминания
- Сохраняем стиль DaisyUI и историческую аутентичность (см. `.specify/memory/constitution.md`).
- Минификацию (`styles.min.css`, `main.min.js`) обновлять вручную или через внешний тул, авто-сборки нет.
- Бинарные файлы (изображения, скриншоты) не выгружаем из Codex в GitHub.
