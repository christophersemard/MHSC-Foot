<?php

namespace App\Security\Exception;

use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class EmailAlreadyUsedException extends CustomUserMessageAuthenticationException
{
    public function __construct(
        string $message = 'L\'email est déjà utilisé par un autre service.',
        array $messageData = [],
        int $code = 0,
        \Throwable $previous = null,
    ) {
        parent::__construct(
            $message,
            // \TranslatorInterface->trans($message),
            $messageData,
            $code,
            $previous
        );
    }
}
