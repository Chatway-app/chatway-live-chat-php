<?php

namespace Chatway;

use InvalidArgumentException;

class Chatway
{
    /** @var string */
    protected $userIdentifier = null;

    /** @var string|null */
    protected $hmacSecret = null;

    /** @var string */
    protected $hmacBasedOn = self::HMAC_BASE_ON_OPTIONS['id'];

    /** @var array */
    protected $tags = [];

    /** @var array */
    protected $customFields = [];

    /** @var string|null */
    protected $userId = null;

    /** @var string|null */
    protected $email = null;

    const HMAC_BASE_ON_OPTIONS = [
        'id' => 'id',
        'email' => 'email',
    ];

    /**
     * @param string $userIdentifier
     * @param string|null $hmacSecret
     * @param string $hmacBasedOn
     * @return self
     */
    public static function make($userIdentifier, $hmacSecret = null, $hmacBasedOn = self::HMAC_BASE_ON_OPTIONS['id'])
    {
        $instance = new self();
        $instance->userIdentifier = is_string($userIdentifier) ? $userIdentifier : null;
        $instance->hmacSecret = is_string($hmacSecret) ? $hmacSecret : null;
        $instance->hmacBasedOn = is_string($hmacBasedOn) && in_array($hmacBasedOn, array_values(self::HMAC_BASE_ON_OPTIONS), true) ? $hmacBasedOn : self::HMAC_BASE_ON_OPTIONS['id'];

        return $instance;
    }

    /**
     * @param string|array $key
     * @param string|null $color
     * @return self
     */
    public function setTags($key, $color = null)
    {
        if (is_array($key)) {
            if (!$this->isAssoc($key)) {
                throw new InvalidArgumentException('Tags must be an associative array: name => color.');
            }

            foreach ($key as $name => $value) {
                if (!is_string($name) || !is_string($value)) {
                    throw new InvalidArgumentException('Both tag name and color must be strings.');
                }

                $this->tags[] = ['name' => $name, 'color' => $value];
            }
        } else {
            if (!is_string($key) || !is_string($color)) {
                throw new InvalidArgumentException('Tag name and color must be strings.');
            }

            $this->tags[] = ['name' => $key, 'color' => $color];
        }

        return $this;
    }

    /**
     * @param string|array $key
     * @param string|null $value
     * @return self
     */
    public function setCustomFields($key, $value = null)
    {
        if (is_array($key)) {
            if (!$this->isAssoc($key)) {
                throw new InvalidArgumentException('Custom fields must be an associative array: name => value.');
            }

            foreach ($key as $name => $val) {
                if (!is_string($name) || !is_string($val)) {
                    throw new InvalidArgumentException('Both custom field name and value must be strings.');
                }

                $this->customFields[] = ['name' => $name, 'value' => $val];
            }
        } else {
            if (!is_string($key) || !is_string($value)) {
                throw new InvalidArgumentException('Custom field name and value must be strings.');
            }

            $this->customFields[] = ['name' => $key, 'value' => $value];
        }

        return $this;
    }

    /**
     * @param string $userId
     * @param string $email
     * @return self
     */
    public function withVisitor($userId, $email)
    {
        $this->userId = is_string($userId) || is_int($userId) || is_float($userId) ? $userId : null;
        $this->email = is_string($email) ? $email : null;

        return $this;
    }

    /**
     * @param array $array
     * @return bool
     */
    protected function isAssoc(array $array)
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * @return string
     */
    public function getScript()
    {
        $script = '';

        $shouldVerify = $this->hmacSecret &&
            $this->userId !== null &&
            $this->email !== null &&
            in_array($this->hmacBasedOn, array_values(self::HMAC_BASE_ON_OPTIONS), true);

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
