# Chatway PHP SDK

The **Chatway PHP SDK** is a lightweight, framework-agnostic PHP package to embed the [Chatway](https://chatway.app) live chat widget and securely verify your users (visitors) using a simple, chainable API.

It works with any PHP application — Laravel, WordPress, custom frameworks, or plain PHP.

---

## Features

- Embed the Chatway widget using your project’s widget ID
- Optionally verify visitors (users) by ID/email
- Assign tags and custom fields
- Clean, chainable syntax
- Fully HTML-escaped output

---

## Installation

```bash
composer require chatway-live-chat/chatway-live-chat-php
```

---

## Basic Usage

Just embed the chat widget without any visitor verification:

```php
echo Chatway\Chatway::make('your-widget-id')->getScript();
```

This will output the Chatway script tag.

---

## Visitor Verification

To securely verify logged-in users (optional), pass:

- The **user ID**
- The **email address**
- A **secret key** provided from your Chatway dashboard
- Whether the signature should be based on `id` or `email`

All four fields are required to enable visitor verification.

Example:

```php
echo Chatway\Chatway::make('your-widget-id', 'your-secret-key', 'id')
    ->withVisitor('123', 'user@example.com')
    ->getScript();
```

This will output a verified `window.chatwaySettings` block with a hash and load the widget script.

---

## Tags

You can assign tags to the visitor. Each tag has a name and a color.

### Single Tag

```php
->setTag('VIP', '#FFD700')
```

### Multiple Tags

```php
->setTag([
    'VIP' => '#FFD700',
    'Supporter' => '#00FF00'
])
```

---

## Custom Fields

Attach custom fields to the visitor session.

### Single Field

```php
->setCustomField('Plan', 'Premium')
```

### Multiple Fields

```php
->setCustomField([
    'Subscription' => 'Gold',
    'Status' => 'Active'
])
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

## Notes

- If visitor verification is enabled, all of these are required:
  - `make(widgetId, secretKey, basedOn)`
  - `withVisitor(userId, email)`
- `basedOn` must be either `'id'` or `'email'`
- All tag and custom field names and values must be strings
- Tags must be an associative array of name => color
- Custom fields must be an associative array of name => value

Invalid inputs will throw `InvalidArgumentException`.

---

## License

MIT © [Chatway](https://chatway.app)
