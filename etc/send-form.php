<?php

// =====================================================================================
// СЕКЦИЯ КОНФИГУРАЦИИ
// =====================================================================================

// Отключены для этого лендинга, так как не требуется капча
define("SMARTCAPTCHA_SERVER_KEY", "");
define("RECIPIENT_EMAILS", ["admin@retroznak.ru", "info@retroznak.ru"]);
define("SITE_NAME", "Ретрознак - Домовые знаки советской эпохи");

// Константы для валидации согласно data-model.md
define("MIN_NAME_LENGTH", 2);
define("MAX_NAME_LENGTH", 50);
define("MAX_MESSAGE_LENGTH", 500);
define("MAX_ADDRESS_LENGTH", 200);
define("CAPTCHA_TIMEOUT", 30);
define("CAPTCHA_CONNECT_TIMEOUT", 10);

// Отключаем вывод ошибок в продакшене
error_reporting(0);
ini_set("display_errors", 0);
ini_set("log_errors", 0);

// Заголовки для JSON ответа и CORS
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");

// Обработка preflight запросов
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

// =====================================================================================
// СЕКЦИЯ УТИЛИТ
// =====================================================================================

/**
 * Отправляет JSON ответ согласно contract API
 * @param bool $success Статус успеха
 * @param string $message Сообщение
 * @param array $details Детали ошибок валидации (опционально)
 */
function sendJsonResponse($success, $message, $details = null) {
    $response = [
        "success" => $success,
        "message" => $message,
        "timestamp" => date("c") // ISO 8601 формат
    ];

    if ($details && !$success) {
        $response["details"] = $details;
    }

    // Устанавливаем HTTP код
    http_response_code($success ? 200 : ($details ? 400 : 500));

    ob_clean();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit();
}

/**
 * Получает реальный IP адрес пользователя
 * @return string IP адрес
 */
function getRealUserIP() {
    $ipHeaders = [
        "HTTP_CF_CONNECTING_IP",
        "HTTP_X_REAL_IP",
        "HTTP_X_FORWARDED_FOR",
        "HTTP_CLIENT_IP",
        "REMOTE_ADDR",
    ];

    foreach ($ipHeaders as $header) {
        if (array_key_exists($header, $_SERVER) && !empty($_SERVER[$header])) {
            $ips = explode(",", $_SERVER[$header]);
            foreach ($ips as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
    }

    return $_SERVER["REMOTE_ADDR"] ?? "127.0.0.1";
}

// =====================================================================================
// ОСНОВНОЙ КЛАСС ОБРАБОТЧИКА ФОРМЫ РЕТРОЗНАК
// =====================================================================================

class RetroZnakFormHandler {

    private $formData = [];
    private $errors = [];
    private $userIP;
    private $formType;

    // Валидные типы форм согласно data-model.md
    private $validFormTypes = ['contact', 'product_inquiry', 'history_inquiry'];

    // Валидные типы продуктов согласно data-model.md
    private $validProductTypes = ['obychny', 'petrogradsky', 'leningradsky'];

    // Валидные способы связи согласно data-model.md
    private $validContactMethods = ['whatsapp', 'telegram', 'email'];

    /**
     * Конструктор класса
     */
    public function __construct() {
        $this->userIP = getRealUserIP();
        $this->initializeFormData();
    }

    /**
     * Инициализация данных формы из POST запроса
     */
    private function initializeFormData() {
        $this->formType = $_POST["form_type"] ?? 'contact';

        $this->formData = [
            'form_type' => $this->formType,
            'name' => trim($_POST["name"] ?? ""),
            'email' => trim($_POST["email"] ?? ""),
            'phone' => trim($_POST["phone"] ?? ""),
            'message' => trim($_POST["message"] ?? ""),
            'address' => trim($_POST["address"] ?? ""),
            'preferred_contact' => $_POST["preferred_contact"] ?? 'email',
            'product_type' => $_POST["product_type"] ?? '',
            'budget_range' => $_POST["budget_range"] ?? '',
            'additional_options' => $_POST["additional_options"] ?? []
        ];
    }

    // =====================================================================================
    // СЕКЦИЯ ВАЛИДАЦИИ СОГЛАСНО DATA-MODEL.MD
    // =====================================================================================

    /**
     * Валидация имени согласно data-model.md
     * @param string $name Имя для проверки
     * @return array Результат валидации
     */
    private function validateName($name) {
        $cleanName = trim($name);

        if (empty($cleanName)) {
            return ['isValid' => false, 'message' => 'Имя обязательно для заполнения'];
        }

        $nameLength = mb_strlen($cleanName);
        if ($nameLength < MIN_NAME_LENGTH) {
            return ['isValid' => false, 'message' => 'Имя должно содержать от ' . MIN_NAME_LENGTH . ' символов'];
        }

        if ($nameLength > MAX_NAME_LENGTH) {
            return ['isValid' => false, 'message' => 'Имя не должно превышать ' . MAX_NAME_LENGTH . ' символов'];
        }

        // Проверка на корректные символы (буквы и пробелы)
        if (!preg_match('/^[а-яА-Яa-zA-Z\s]+$/u', $cleanName)) {
            return ['isValid' => false, 'message' => 'Имя должно содержать только буквы и пробелы'];
        }

        return ['isValid' => true, 'value' => $cleanName];
    }

    /**
     * Валидация email согласно data-model.md
     * @param string $email Email для проверки
     * @return array Результат валидации
     */
    private function validateEmail($email) {
        if (empty($email)) {
            return ['isValid' => false, 'message' => 'Email обязателен для заполнения'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['isValid' => false, 'message' => 'Неверный формат email адреса'];
        }

        return ['isValid' => true, 'value' => strtolower(trim($email))];
    }

    /**
     * Валидация телефона (российский формат, опционально)
     * @param string $phone Телефон для проверки
     * @param bool $required Обязательное ли поле
     * @return array Результат валидации
     */
    private function validatePhone($phone, $required = false) {
        $cleanPhone = trim($phone);

        if (empty($cleanPhone)) {
            if ($required) {
                return ['isValid' => false, 'message' => 'Телефон обязателен для заполнения'];
            }
            return ['isValid' => true, 'value' => ''];
        }

        // Российский формат телефона согласно data-model.md
        if (!preg_match('/^(\+7|8)?\s?\(?\d{3}\)?\s?\d{3}[\-\s]?\d{2}[\-\s]?\d{2}$/', $cleanPhone)) {
            return ['isValid' => false, 'message' => 'Неверный формат российского номера телефона'];
        }

        return ['isValid' => true, 'value' => $cleanPhone];
    }

    /**
     * Валидация сообщения
     * @param string $message Сообщение для проверки
     * @param bool $required Обязательное ли поле
     * @return array Результат валидации
     */
    private function validateMessage($message, $required = false) {
        $cleanMessage = trim($message);

        if (empty($cleanMessage)) {
            if ($required) {
                return ['isValid' => false, 'message' => 'Сообщение обязательно для заполнения'];
            }
            return ['isValid' => true, 'value' => ''];
        }

        if (mb_strlen($cleanMessage) > MAX_MESSAGE_LENGTH) {
            return ['isValid' => false, 'message' => 'Сообщение не должно превышать ' . MAX_MESSAGE_LENGTH . ' символов'];
        }

        return ['isValid' => true, 'value' => $cleanMessage];
    }

    /**
     * Валидация адреса
     * @param string $address Адрес для проверки
     * @param bool $required Обязательное ли поле
     * @return array Результат валидации
     */
    private function validateAddress($address, $required = false) {
        $cleanAddress = trim($address);

        if (empty($cleanAddress)) {
            if ($required) {
                return ['isValid' => false, 'message' => 'Адрес обязателен для заполнения'];
            }
            return ['isValid' => true, 'value' => ''];
        }

        if (mb_strlen($cleanAddress) > MAX_ADDRESS_LENGTH) {
            return ['isValid' => false, 'message' => 'Адрес не должен превышать ' . MAX_ADDRESS_LENGTH . ' символов'];
        }

        return ['isValid' => true, 'value' => $cleanAddress];
    }

    /**
     * Валидация типа продукта
     * @param string $productType Тип продукта
     * @param bool $required Обязательное ли поле
     * @return array Результат валидации
     */
    private function validateProductType($productType, $required = false) {
        if (empty($productType)) {
            if ($required) {
                return ['isValid' => false, 'message' => 'Выберите тип ретрознака'];
            }
            return ['isValid' => true, 'value' => ''];
        }

        if (!in_array($productType, $this->validProductTypes)) {
            return ['isValid' => false, 'message' => 'Некорректный тип ретрознака'];
        }

        return ['isValid' => true, 'value' => $productType];
    }

    /**
     * Валидация способа связи
     * @param string $contactMethod Способ связи
     * @return array Результат валидации
     */
    private function validateContactMethod($contactMethod) {
        if (!in_array($contactMethod, $this->validContactMethods)) {
            return ['isValid' => false, 'message' => 'Некорректный способ связи'];
        }

        return ['isValid' => true, 'value' => $contactMethod];
    }

    /**
     * Валидация типа формы
     * @param string $formType Тип формы
     * @return array Результат валидации
     */
    private function validateFormType($formType) {
        if (!in_array($formType, $this->validFormTypes)) {
            return ['isValid' => false, 'message' => 'Некорректный тип формы'];
        }

        return ['isValid' => true, 'value' => $formType];
    }

    /**
     * Выполняет валидацию всех полей в зависимости от типа формы
     * @return bool Результат валидации
     */
    private function validateAllFields() {
        $this->errors = [];
        $validationErrors = [];

        // Валидация типа формы
        $formTypeValidation = $this->validateFormType($this->formData['form_type']);
        if (!$formTypeValidation['isValid']) {
            $validationErrors['form_type'] = $formTypeValidation['message'];
        }

        // Общие поля для всех типов форм
        $nameValidation = $this->validateName($this->formData['name']);
        if (!$nameValidation['isValid']) {
            $validationErrors['name'] = $nameValidation['message'];
        } else {
            $this->formData['name'] = $nameValidation['value'];
        }

        $emailValidation = $this->validateEmail($this->formData['email']);
        if (!$emailValidation['isValid']) {
            $validationErrors['email'] = $emailValidation['message'];
        } else {
            $this->formData['email'] = $emailValidation['value'];
        }

        // Валидация в зависимости от типа формы
        switch ($this->formData['form_type']) {
            case 'contact':
                $this->validateContactForm($validationErrors);
                break;

            case 'product_inquiry':
                $this->validateProductInquiryForm($validationErrors);
                break;

            case 'history_inquiry':
                $this->validateHistoryInquiryForm($validationErrors);
                break;
        }

        if (!empty($validationErrors)) {
            $this->errors = $validationErrors;
            return false;
        }

        return true;
    }

    /**
     * Валидация контактной формы
     * @param array &$validationErrors Массив ошибок валидации
     */
    private function validateContactForm(&$validationErrors) {
        // Телефон опционален для контактной формы
        $phoneValidation = $this->validatePhone($this->formData['phone'], false);
        if (!$phoneValidation['isValid']) {
            $validationErrors['phone'] = $phoneValidation['message'];
        } else {
            $this->formData['phone'] = $phoneValidation['value'];
        }

        // Сообщение опционально
        $messageValidation = $this->validateMessage($this->formData['message'], false);
        if (!$messageValidation['isValid']) {
            $validationErrors['message'] = $messageValidation['message'];
        } else {
            $this->formData['message'] = $messageValidation['value'];
        }

        // Способ связи
        $contactMethodValidation = $this->validateContactMethod($this->formData['preferred_contact']);
        if (!$contactMethodValidation['isValid']) {
            $validationErrors['preferred_contact'] = $contactMethodValidation['message'];
        } else {
            $this->formData['preferred_contact'] = $contactMethodValidation['value'];
        }
    }

    /**
     * Валидация формы запроса продукта
     * @param array &$validationErrors Массив ошибок валидации
     */
    private function validateProductInquiryForm(&$validationErrors) {
        // Тип продукта обязателен
        $productTypeValidation = $this->validateProductType($this->formData['product_type'], true);
        if (!$productTypeValidation['isValid']) {
            $validationErrors['product_type'] = $productTypeValidation['message'];
        } else {
            $this->formData['product_type'] = $productTypeValidation['value'];
        }

        // Адрес опционален
        $addressValidation = $this->validateAddress($this->formData['address'], false);
        if (!$addressValidation['isValid']) {
            $validationErrors['address'] = $addressValidation['message'];
        } else {
            $this->formData['address'] = $addressValidation['value'];
        }
    }

    /**
     * Валидация формы запроса истории
     * @param array &$validationErrors Массив ошибок валидации
     */
    private function validateHistoryInquiryForm(&$validationErrors) {
        // Адрес обязателен для истории дома
        $addressValidation = $this->validateAddress($this->formData['address'], true);
        if (!$addressValidation['isValid']) {
            $validationErrors['address'] = $addressValidation['message'];
        } else {
            $this->formData['address'] = $addressValidation['value'];
        }

        // Сообщение опционально
        $messageValidation = $this->validateMessage($this->formData['message'], false);
        if (!$messageValidation['isValid']) {
            $validationErrors['message'] = $messageValidation['message'];
        } else {
            $this->formData['message'] = $messageValidation['value'];
        }
    }

    // =====================================================================================
    // СЕКЦИЯ EMAIL
    // =====================================================================================

    /**
     * Получает красивое название типа формы
     * @param string $formType Тип формы
     * @return string Читаемое название
     */
    private function getFormTypeTitle($formType) {
        $titles = [
            'contact' => 'Обратная связь',
            'product_inquiry' => 'Запрос продукта',
            'history_inquiry' => 'Запрос истории дома'
        ];

        return $titles[$formType] ?? 'Неизвестный тип';
    }

    /**
     * Получает красивое название типа продукта
     * @param string $productType Тип продукта
     * @return string Читаемое название
     */
    private function getProductTypeTitle($productType) {
        $titles = [
            'obychny' => 'Обычный',
            'petrogradsky' => 'Петроградский',
            'leningradsky' => 'Ленинградский'
        ];

        return $titles[$productType] ?? $productType;
    }

    /**
     * Получает красивое название способа связи
     * @param string $contactMethod Способ связи
     * @return string Читаемое название
     */
    private function getContactMethodTitle($contactMethod) {
        $titles = [
            'whatsapp' => 'WhatsApp',
            'telegram' => 'Telegram',
            'email' => 'Email'
        ];

        return $titles[$contactMethod] ?? $contactMethod;
    }

    /**
     * Формирует HTML содержимое письма
     * @return string HTML контент
     */
    private function buildEmailContent() {
        $systemInfo = $this->getSystemInfo();
        $formTypeTitle = $this->getFormTypeTitle($this->formData['form_type']);

        $html = '
        <html>
        <head><meta charset="UTF-8"></head>
        <body style="font-family: Georgia, Arial, sans-serif; color: #333; line-height: 1.6;">
            <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
                <h2 style="color: #f97316; border-bottom: 2px solid #f97316; padding-bottom: 10px;">
                    ' . htmlspecialchars($formTypeTitle) . ' с сайта Ретрознак
                </h2>

                <table style="border-collapse: collapse; width: 100%; margin-bottom: 20px; background: #f9fafb; border-radius: 8px;">
                    ' . $this->buildFormDataRows() . '
                </table>

                <h3 style="color: #374151; margin-top: 30px;">Техническая информация</h3>
                <table style="border-collapse: collapse; width: 100%; background: #f3f4f6; border-radius: 8px;">
                    ' . $this->buildSystemInfoRows($systemInfo) . '
                </table>

                <div style="margin-top: 30px; padding: 15px; background: #111827; color: #f9fafb; border-radius: 8px; text-align: center;">
                    <p style="margin: 0; font-style: italic;">
                        🏠 Домовые знаки советской эпохи - Превратите адрес в часть семейной истории
                    </p>
                </div>
            </div>
        </body>
        </html>';

        return $html;
    }

    /**
     * Формирует строки таблицы с данными формы
     * @return string HTML строки
     */
    private function buildFormDataRows() {
        $rows = '';

        // Общие поля
        $rows .= $this->createTableRow('Имя', htmlspecialchars($this->formData['name']));
        $rows .= $this->createTableRow('Email', htmlspecialchars($this->formData['email']));

        if (!empty($this->formData['phone'])) {
            $rows .= $this->createTableRow('Телефон', htmlspecialchars($this->formData['phone']));
        }

        // Поля в зависимости от типа формы
        switch ($this->formData['form_type']) {
            case 'contact':
                if (!empty($this->formData['preferred_contact'])) {
                    $contactMethod = $this->getContactMethodTitle($this->formData['preferred_contact']);
                    $rows .= $this->createTableRow('Предпочтительный способ связи', $contactMethod);
                }
                break;

            case 'product_inquiry':
                if (!empty($this->formData['product_type'])) {
                    $productType = $this->getProductTypeTitle($this->formData['product_type']);
                    $rows .= $this->createTableRow('Тип ретрознака', $productType);
                }
                if (!empty($this->formData['budget_range'])) {
                    $rows .= $this->createTableRow('Бюджет', htmlspecialchars($this->formData['budget_range']));
                }
                if (!empty($this->formData['additional_options']) && is_array($this->formData['additional_options'])) {
                    $options = implode(', ', $this->formData['additional_options']);
                    $rows .= $this->createTableRow('Дополнительные опции', htmlspecialchars($options));
                }
                break;
        }

        if (!empty($this->formData['address'])) {
            $rows .= $this->createTableRow('Адрес', htmlspecialchars($this->formData['address']));
        }

        if (!empty($this->formData['message'])) {
            $message = nl2br(htmlspecialchars($this->formData['message']));
            $rows .= $this->createTableRow('Сообщение', $message);
        }

        return $rows;
    }

    /**
     * Формирует строки таблицы с системной информацией
     * @param array $systemInfo Системная информация
     * @return string HTML строки
     */
    private function buildSystemInfoRows($systemInfo) {
        return $this->createTableRow('Дата и время', $systemInfo['date']) .
               $this->createTableRow('IP адрес', htmlspecialchars($systemInfo['ip'])) .
               $this->createTableRow('Источник перехода', htmlspecialchars($systemInfo['referer']));
    }

    /**
     * Создает строку таблицы для email
     * @param string $label Метка
     * @param string $value Значение
     * @return string HTML строка
     */
    private function createTableRow($label, $value) {
        if (empty($value)) {
            $value = '<span style="color: #888; font-style: italic;">Не указано</span>';
        }

        return '<tr>
            <td style="padding: 12px; border-bottom: 1px solid #e5e7eb; font-weight: bold; vertical-align: top; width: 30%;">' . $label . ':</td>
            <td style="padding: 12px; border-bottom: 1px solid #e5e7eb; vertical-align: top;">' . $value . '</td>
        </tr>';
    }

    /**
     * Получает системную информацию для письма
     * @return array Массив с системной информацией
     */
    private function getSystemInfo() {
        $moscowTime = new DateTime('now', new DateTimeZone('Europe/Moscow'));

        return [
            'date' => $moscowTime->format("d.m.Y H:i:s") . " (МСК)",
            'ip' => $this->userIP,
            'referer' => $_SERVER["HTTP_REFERER"] ?? "Прямой переход",
            'userAgent' => $_SERVER["HTTP_USER_AGENT"] ?? "Неизвестно"
        ];
    }

    /**
     * Формирует заголовки для email
     * @return array Массив заголовков
     */
    private function prepareEmailHeaders() {
        $domain = $_SERVER["HTTP_HOST"] ?? "retroznak.ru";
        $fromEmail = "noreply@{$domain}";
        $replyEmail = $this->formData['email'];

        return [
            "MIME-Version: 1.0",
            "Content-type: text/html; charset=UTF-8",
            "From: " . SITE_NAME . " <" . $fromEmail . ">",
            "Reply-To: " . $replyEmail,
            "X-Mailer: RetroZnak-Form/2.0",
            "X-Priority: 3",
            "Return-Path: " . $fromEmail,
        ];
    }

    /**
     * Отправляет email всем получателям
     * @return bool Результат отправки
     */
    private function sendEmails() {
        $formTypeTitle = $this->getFormTypeTitle($this->formData['form_type']);
        $subject = "{$formTypeTitle} с сайта Ретрознак - " . $this->formData['name'];
        $message = $this->buildEmailContent();
        $headers = implode("\r\n", $this->prepareEmailHeaders());

        $allSent = true;
        foreach (RECIPIENT_EMAILS as $recipient) {
            if (!mail($recipient, $subject, $message, $headers)) {
                $allSent = false;
            }
        }

        return $allSent;
    }

    // =====================================================================================
    // СЕКЦИЯ ОСНОВНОЙ ЛОГИКИ
    // =====================================================================================

    /**
     * Проверяет AJAX запрос
     * @return bool Результат проверки
     */
    private function isAjaxRequest() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Основной метод обработки формы
     */
    public function processForm() {
        // Проверяем метод запроса
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            sendJsonResponse(false, "Неверный метод запроса");
        }

        // Проверяем AJAX запрос (опционально)
        if (!$this->isAjaxRequest()) {
            // Для non-AJAX запросов можем разрешить
            // sendJsonResponse(false, "Требуется AJAX запрос");
        }

        // Валидируем поля формы
        if (!$this->validateAllFields()) {
            sendJsonResponse(false, "Ошибка валидации", $this->errors);
        }

        // Отправляем письма
        if ($this->sendEmails()) {
            sendJsonResponse(true, "Заявка успешно отправлена. Мы свяжемся с вами в ближайшее время.");
        } else {
            sendJsonResponse(false, "Ошибка отправки сообщения. Попробуйте позже или свяжитесь по телефону.");
        }
    }
}

// =====================================================================================
// ИНИЦИАЛИЗАЦИЯ И ВЫПОЛНЕНИЕ
// =====================================================================================

// Начинаем буферизацию вывода
ob_start();

try {
    // Создаем экземпляр обработчика и запускаем обработку
    $formHandler = new RetroZnakFormHandler();
    $formHandler->processForm();
} catch (Exception $e) {
    // В случае критической ошибки
    sendJsonResponse(false, "Внутренняя ошибка сервера. Попробуйте позже.");
}

?>