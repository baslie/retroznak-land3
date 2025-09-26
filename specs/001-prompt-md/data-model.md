# Data Model: Лендинг для компании «Ретрознак»

## Core Entities

### ContactForm
**Description**: Форма обратной связи для заявок пользователей

**Fields**:
- `name`: string (required) - Имя клиента
- `email`: string (required, validated) - Email для обратной связи
- `phone`: string (optional) - Номер телефона
- `message`: string (optional) - Дополнительное сообщение
- `preferred_contact`: enum (whatsapp|telegram|email) - Предпочтительный способ связи
- `timestamp`: datetime - Время отправки заявки

**Validation Rules**:
- Name: минимум 2 символа, только буквы и пробелы
- Email: валидный email формат
- Phone: российский формат (необязательно)
- Message: максимум 500 символов

**State Transitions**: N/A (stateless формы)

### ProductInquiry
**Description**: Специальная форма для запроса информации о товарах

**Fields**:
- `name`: string (required) - Имя клиента
- `email`: string (required, validated) - Email для ответа
- `product_type`: enum (obychny|petrogradsky|leningradsky) - Тип ретрознака
- `address`: string (optional) - Адрес установки
- `additional_options`: array - Дополнительные опции (подсветка, табличка и т.д.)
- `budget_range`: enum (до_5000|5000_10000|10000_plus) - Ценовой диапазон
- `timestamp`: datetime - Время отправки

**Validation Rules**:
- Name: минимум 2 символа
- Email: валидный формат
- Product_type: один из допустимых типов
- Address: максимум 200 символов

**Relationships**: N/A (независимые сущности)

### NavigationAnchor
**Description**: Якорные ссылки для навигации по одностранице

**Fields**:
- `anchor_id`: string - ID якоря на странице
- `display_name`: string - Отображаемое название в меню
- `section_title`: string - Заголовок соответствующей секции
- `sort_order`: integer - Порядок в меню

**Static Data**:
```
[
  {anchor_id: "catalog", display_name: "Каталог", section_title: "Товарная матрица", sort_order: 1},
  {anchor_id: "production", display_name: "О производстве", section_title: "Производство и команда", sort_order: 2},
  {anchor_id: "reviews", display_name: "Отзывы", section_title: "Отзывы", sort_order: 3},
  {anchor_id: "faq", display_name: "FAQ", section_title: "FAQ", sort_order: 4}
]
```

## Email Notifications

### ContactEmail
**Description**: Email уведомление администратору о новой заявке

**Structure**:
- `to`: admin email (из конфигурации)
- `from`: system email
- `subject`: "Новая заявка с сайта Ретрознак"
- `body`: HTML шаблон с данными формы
- `attachments`: N/A

### AutoReply
**Description**: Автоответ пользователю (опционально)

**Structure**:
- `to`: email пользователя из формы
- `from`: admin email
- `subject`: "Спасибо за обращение - Ретрознак"
- `body`: Благодарственный текст + контакты

## Configuration Data

### SiteSettings
**Description**: Настройки сайта и контактная информация

**Fields**:
- `admin_email`: string - Email для получения заявок
- `company_phone`: string - Телефон компании
- `whatsapp_link`: string - Ссылка на WhatsApp
- `telegram_link`: string - Ссылка на Telegram
- `company_address`: string - Адрес компании
- `working_hours`: string - Время работы

## Data Flow

1. **Form Submission**: Пользователь заполняет форму → JS валидация → AJAX отправка
2. **Server Processing**: PHP скрипт валидирует данные → формирует email → отправляет администратору
3. **Response**: Возвращает JSON с результатом → JS показывает уведомление пользователю
4. **Storage**: Данные не сохраняются локально (только email отправка)

## Validation Schema

```javascript
// Client-side validation rules
const validationRules = {
  contactForm: {
    name: { required: true, minLength: 2, pattern: /^[а-яА-Я\s]+$/ },
    email: { required: true, pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/ },
    phone: { required: false, pattern: /^(\+7|8)?\d{10}$/ },
    message: { required: false, maxLength: 500 }
  },
  productInquiry: {
    name: { required: true, minLength: 2 },
    email: { required: true, pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/ },
    product_type: { required: true, enum: ['obychny', 'petrogradsky', 'leningradsky'] },
    address: { required: false, maxLength: 200 }
  }
};
```