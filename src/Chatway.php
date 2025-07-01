<?php

namespace Chatway;

class Chatway {
    public static function script( ?string $userIdentifier ): string 
    {
        return sprintf(
            '<script id="chatway" async="true" src="https://cdn.chatway.app/widget.js?id=%s"></script>',
            htmlspecialchars( $userIdentifier, ENT_QUOTES, 'UTF-8' )
        );
    }

    public static function visitorVerification( string $userId, string $email, string $hmacSecret, string $hmacBasedOn = 'id', array $tags = [], array $customFields = [] ): string 
    {
        $safeId = htmlspecialchars( $userId, ENT_QUOTES, 'UTF-8' );
        $safeEmail = htmlspecialchars( $email, ENT_QUOTES, 'UTF-8' );

        $hmacBase = $hmacBasedOn === 'email' ? $email : $userId;
        $hmac = hash_hmac( 'sha256', $hmacBase, $hmacSecret );

        $tagsJson = json_encode( $tags );
        $customFieldsJson = json_encode( $customFields );

        return <<<SCRIPT
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
}
