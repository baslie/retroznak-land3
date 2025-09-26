# Research: Лендинг для компании «Ретрознак»

## DaisyUI Components Selection

**Decision**: Использовать следующие DaisyUI компоненты для секций лендинга:
- `hero` - для главной секции с `hero-content`
- `navbar` - для фиксированного меню с `navbar-start`, `navbar-center`, `navbar-end`
- `timeline` - для секции истории с `timeline-vertical lg:timeline-horizontal`
- `card` - для товарной матрицы с `card-body`, `card-title`, `card-actions`
- `badge` - для акцентных элементов (`badge-secondary`, `badge-ghost`)
- `btn` - для CTA элементов (`btn-primary`, `btn-ghost`)
- `collapse` - для FAQ с `collapse-arrow` и `join-vertical`
- `footer` - с `footer-center` и semantic структурой
- `dropdown` - для мобильного меню с правильными `tabindex` и `role`

**Rationale**: DaisyUI компоненты обеспечивают семантическую структуру, встроенную адаптивность и избавляют от длинных цепочек Tailwind классов. Вместо 20+ utility классов достаточно одного semantic класса (например, `btn` вместо `inline-block cursor-pointer rounded-sm bg-zinc-900 px-4 py-2.5...`).

**Alternatives considered**: Чистый Tailwind CSS отклонен из-за избыточности классов, Bootstrap отклонен согласно конституции.

## DaisyUI vs Pure Tailwind Approach

**Decision**: Максимально использовать DaisyUI semantic компоненты, избегая utility-first подхода Tailwind.

**Examples of simplification**:
- `<button class="btn btn-primary">` вместо `<button class="inline-block cursor-pointer rounded-sm bg-zinc-900 px-4 py-2.5 text-center text-sm font-semibold text-white shadow-[0_.2rem_0.3rem_-.25rem_black] active:shadow-none transition duration-200 ease-in-out focus-visible:ring-2 focus-visible:ring-zinc-700 focus-visible:ring-offset-2 focus-visible:outline-none active:translate-y-[1px]">`
- `<input class="input">` вместо `<input class="w-full rounded-sm border border-zinc-300 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-700 focus:ring-offset-2 focus:ring-offset-zinc-100 focus:outline-none focus-visible:border-zinc-900">`
- `<div class="card">` вместо множественных flex/grid классов

**Rationale**: DaisyUI философия "Write less, do more" критично важна для поддерживаемости кода. Один semantic класс заменяет десятки utility классов.

**Alternatives considered**: Utility-first Tailwind подход создает нечитаемый HTML и усложняет поддержку.

## Color Scheme Implementation

**Decision**: Использовать DaisyUI тему с кастомизацией через CSS переменные:
- Базовая тема: `dark`
- Кастомные переменные для темных оттенков вместо чистого черного
- Оранжевые акценты через `--accent` переменную

**Rationale**: DaisyUI темы поддерживают централизованное управление цветами через CSS переменные, что соответствует требованию FR-005.

**Alternatives considered**: Inline стили отклонены из-за сложности управления, SCSS отклонен из-за дополнительной сборки.

## Form Handling Strategy

**Decision**: AJAX формы с PHP backend на основе `etc/send-form.php`:
- Vanilla JavaScript для AJAX запросов
- PHP скрипт в папке `backend/`
- Email отправка через PHP mail() или PHPMailer
- Валидация на клиенте и сервере

**Rationale**: Соответствует конституции (Vanilla JS + PHP), простота реализации, быстрая интеграция.

**Alternatives considered**: Node.js backend отклонен согласно конституции, внешние сервисы не требуются.

## Performance Optimization

**Decision**:
- Оптимизация DaisyUI сборки (только нужные компоненты)
- Сжатие изображений JPEG/PNG
- Минификация CSS/JS
- Lazy loading для изображений ниже fold

**Rationale**: Необходимо достичь FCP < 1.5 секунд согласно FR-011.

**Alternatives considered**: WebP изображения отклонены согласно конституции, CDN не требуется для локального развертывания.

## Responsive Design Strategy

**Decision**:
- Использовать встроенные responsive классы DaisyUI
- Контрольные точки: 320px (mobile), 768px (tablet), 1024px (desktop)
- Mobile-first подход

**Rationale**: Требование поддержки минимальной ширины 320px (FR-004) и универсальной адаптивности.

**Alternatives considered**: CSS Grid/Flexbox без фреймворка требует больше разработки, media queries вручную сложнее поддерживать.

## Testing Exclusion Confirmation

**Decision**: Исключить все виды тестирования по явному запросу пользователя.

**Rationale**: Пользователь указал "не делай тестов, я сам все протестирую вручную".

**Alternatives considered**: Unit тесты, integration тесты, contract тесты - все исключены по требованию.