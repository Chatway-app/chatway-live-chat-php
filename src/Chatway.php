<?php

namespace Chatway;

class Chatway
{
    protected string $userIdentifier;

    protected ?string $hmacSecret = null;

    protected string $hmacBasedOn = 'id';

    protected array $tags = [];

    protected array $customFields = [];

    protected ?string $userId = null;
    
    protected ?string $email = null;

    public static function make(string $userIdentifier, ?string $hmacSecret = null, string $hmacBasedOn = 'id'): self
    {
        $instance = new self();
        $instance->userIdentifier = $userIdentifier;
        $instance->hmacSecret = $hmacSecret;
        $instance->hmacBasedOn = $hmacBasedOn;

        return $instance;
    }

    public function setTag(string|array $key, ?string $color = null): self
    {
        if (is_array($key)) {
            foreach ($key as $name => $value) {
                $this->tags[] = ['name' => $name, 'color' => $value];
            }
        } else {
            $this->tags[] = ['name' => $key, 'color' => $color];
        }

        return $this;
    }

    public function setCustomField(string|array $key, ?string $value = null): self
    {
        if (is_array($key)) {
            foreach ($key as $name => $val) {
                $this->customFields[] = ['name' => $name, 'value' => $val];
            }
        } else {
            $this->customFields[] = ['name' => $key, 'value' => $value];
        }

        return $this;
    }

    public function withVisitor(string $userId, string $email): self
    {
        $this->userId = $userId;
        $this->email = $email;

        return $this;
    }

    public function getScript(): string
    {
        $script = '';

        $shouldVerify =
            $this->hmacSecret &&
            $this->userId !== null &&
            $this->email !== null &&
            in_array($this->hmacBasedOn, ['id', 'email'], true);

        if ($shouldVerify) {
            $safeId = htmlspecialchars($this->userId, ENT_QUOTES, 'UTF-8');
            $safeEmail = htmlspecialchars($this->email, ENT_QUOTES, 'UTF-8');
            $hmacBase = $this->hmacBasedOn === 'email' ? $this->email : $this->userId;
            $hmac = hash_hmac('sha256', $hmacBase, $this->hmacSecret);

            $tagsJson = json_encode($this->tags);
            $customFieldsJson = json_encode($this->customFields);

            $script .= <<<SCRIPT
                <script>
                    window.chatwaySettings = {
                        visitor: {
                            data: {
                                id: "{$safeId}",
                                email: "{$safeEmail}"
                            },
                            hmac: "{$hmac}"
                        },
                        tags: {$tagsJson},
                        customFields: {$customFieldsJson}
                    };
                </script>
            SCRIPT;
        }

        $userIdentifier = htmlspecialchars($this->userIdentifier, ENT_QUOTES, 'UTF-8');
        $script .= <<<SCRIPT
            <script id="chatway" async="true" src="https://cdn.chatway.app/widget.js?id={$userIdentifier}"></script>
        SCRIPT;

        return $script;
    }
}
