# Chatway PHP Embed SDK

A simple, framework-agnostic PHP SDK to embed the Chatway live chat widget and verify visitors. Works with any PHP-based application including Laravel, plain PHP, etc

---

## ✨ Features

- Embed Chatway widget with a single method.
- Secure visitor verification
- Add visitor tags and custom fields.

---

## 📦 Installation

Install via Composer:

```bash
composer require chatway-live-chat/chatway-live-chat-php
```

---

## 📘 Usage

### Chatway::script()

Embed the Chatway widget using your identifier.

```php
Chatway::script(?string $userIdentifier): string
```

**Parameters:**

- `userIdentifier` (string|null): The unique widget ID from Chatway.

**Example:**

```php
echo \Chatway\Chatway::script('your-widget-id');
```

---

### Chatway::visitorVerification

Generate a script to securely identify and verify a visitor.

```php
Chatway::visitorVerification(string $userId, string $email, string $hmacSecret, string $hmacBasedOn = 'id', array $tags = [], array $customFields = []): string
```

**Parameters:**

- `userId` (string): Unique user identifier.
- `email` (string): Visitor’s email address.
- `hmacSecret` (string): Your security key from Chatway.
- `hmacBasedOn` (string): Identifier based on `'id'` or `'email'`. Default is `'id'`.
- `tags` (array): (Optional) Array of tags for the visitor.
- `customFields` (array): (Optional) Key-value pairs of custom fields.

**Example:**

```php
echo \Chatway\Chatway::visitorVerification(
    '123',
    'user@example.com',
    'your-hmac-secret',
    'id',
    [['name' => 'VIP', 'color' => '#FFD700']],
    ['Subscription Plan' => 'Premium']
);
```