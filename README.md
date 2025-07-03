
# Chatway PHP SDK

The **Chatway live chat PHP SDK** is a lightweight, framework-agnostic PHP library that allows you to easily embed the [Chatway](https://chatway.app/?utm_source=packagist) live chat customer support widget on your website and securely identify your visitors using a simple, chainable API.

Chatway is a powerful live chat solution for websites, offering features such as live chat customer support, real-time visitor tracking, customizable widgets, canned responses, FAQs, multilingual support, private notes, visitor segmentation, analytics, and more. Chatway is available as a web app, as well as native iOS and Android apps.

This SDK works with any PHP application, including Laravel, WordPress, custom frameworks, or plain PHP projects.

![chatway-pack](https://github.com/user-attachments/assets/23333c76-e427-487d-8341-d8579fe537de)

---

## Key Features

- Easily embed the Chatway live chat widget using your project’s widget ID
- Securely verify visitors by ID or email (optional)
- Assign tags with custom colors
- Attach custom fields to visitor sessions
- Clean, chainable syntax
- Fully HTML-escaped output for security

---

## Installation

To install the SDK via Composer:

```bash
composer require chatway-live-chat/chatway-live-chat-php
```

---

## Basic Usage

Embed the Chatway live chat widget without visitor verification:

```php
echo Chatway\Chatway::make('your-widget-id')->getScript();
```

You can find your widget ID on the [Chatway Installation Page](https://go.chatway.app/installation) (`id=WIDGET_ID`).

---

## Visitor Verification (Optional)

To securely verify logged-in visitors, provide:

- The **user ID**
- The **email address**
- A **secret key** from the [Visitor Verification page](https://go.chatway.app/visitors-verification)
- Whether to generate the signature based on `'id'` or `'email'`

All four parameters are required for visitor verification.

Example:

```php
echo Chatway\Chatway::make('your-widget-id', 'your-secret-key', 'id')
    ->withVisitor('123', 'user@example.com')
    ->getScript();
```

This will generate a secure `window.chatwaySettings` block containing a signature and load the chat widget.

---

## Adding Tags

You can assign tags to visitors, each with a name and color.

**Single Tag:**

```php
->setTag('VIP', '#FFD700')
```

**Multiple Tags:**

```php
->setTag([
    'VIP' => '#FFD700',
    'Supporter' => '#00FF00'
]);
```

---

## Custom Fields

You can attach custom fields to enrich visitor session data.

**Single Field:**

```php
->setCustomField('Plan', 'Premium')
```

**Multiple Fields:**

```php
->setCustomField([
    'Subscription' => 'Gold',
    'Status' => 'Active'
]);
```

---

## Full Example

```php
echo Chatway\Chatway::make('your-widget-id', 'your-secret-key', 'email')
    ->withVisitor('123', 'user@example.com')
    ->setTag('VIP', '#FFD700')
    ->setTag(['Supporter' => '#00FF00'])
    ->setCustomField('Plan', 'Pro')
    ->setCustomField([
        'Country' => 'USA',
        'Language' => 'English'
    ])
    ->getScript();
```

---

## Important Notes

- For visitor verification, you **must** provide:
  - `make(widgetId, secretKey, basedOn)`
  - `withVisitor(userId, email)`
- The `basedOn` parameter must be either `'id'` or `'email'`
- All tag names, tag colors, field names, and field values must be strings
- Tags must be an associative array in the format `name => color`
- Custom fields must be an associative array in the format `name => value`

Invalid input will result in an `InvalidArgumentException`.

---

## License For This Package

MIT License © [Chatway](https://chatway.app)
