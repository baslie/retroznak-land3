# Contact Form API Contract

## Endpoint: `/backend/contact-form.php`

### Request Specification

**Method**: POST
**Content-Type**: application/x-www-form-urlencoded
**Headers**:
- `X-Requested-With: XMLHttpRequest` (для определения AJAX запроса)

**Parameters**:
```php
[
  'name' => string (required, 2-50 chars),
  'email' => string (required, valid email format),
  'phone' => string (optional, russian phone format),
  'message' => string (optional, max 500 chars),
  'preferred_contact' => enum (whatsapp|telegram|email, default: email),
  'form_type' => string (contact|product_inquiry)
]
```

**Example Request**:
```javascript
const formData = new FormData();
formData.append('name', 'Иван Петров');
formData.append('email', 'ivan@example.com');
formData.append('phone', '+79161234567');
formData.append('message', 'Интересует ретрознак для дачи');
formData.append('preferred_contact', 'whatsapp');
formData.append('form_type', 'contact');

fetch('/backend/contact-form.php', {
  method: 'POST',
  headers: {
    'X-Requested-With': 'XMLHttpRequest'
  },
  body: formData
});
```

### Response Specification

**Success Response** (HTTP 200):
```json
{
  "success": true,
  "message": "Заявка успешно отправлена. Мы свяжемся с вами в ближайшее время.",
  "timestamp": "2025-09-26T15:30:00+03:00"
}
```

**Validation Error Response** (HTTP 400):
```json
{
  "success": false,
  "error": "Ошибка валидации",
  "details": {
    "name": "Имя должно содержать от 2 до 50 символов",
    "email": "Неверный формат email адреса"
  },
  "timestamp": "2025-09-26T15:30:00+03:00"
}
```

**Server Error Response** (HTTP 500):
```json
{
  "success": false,
  "error": "Ошибка отправки сообщения. Попробуйте позже или свяжитесь по телефону.",
  "timestamp": "2025-09-26T15:30:00+03:00"
}
```

### Email Template

**To Admin**:
- Subject: `Новая заявка с сайта Ретрознак - {form_type}`
- Body: HTML шаблон с информацией о заявке
- From: `noreply@retro-znak.ru`

**Auto-reply to User** (optional):
- Subject: `Спасибо за обращение - Ретрознак`
- Body: Благодарственное сообщение + контакты
- From: `info@retro-znak.ru`

### Security Requirements

1. **CSRF Protection**: Проверка referrer или токен
2. **Rate Limiting**: Максимум 5 заявок в час с одного IP
3. **Input Sanitization**: Очистка всех входных данных
4. **Email Validation**: Проверка формата и существования домена