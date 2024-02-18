<?php

namespace App\events;

use App\email\Email;
use App\entitys\UserEntity;
use Cascata\Framework\events\EventInterface;

class UserRegistered implements EventInterface
{
    private string $event = 'user-registered-event';

    public function __invoke(UserEntity $userEntity, string $body = "")
    {
        $email = new Email();
        $email->send(
            [
                'address' => 'jpppedreira@gmail.com',
                'name' => 'cascata'
            ],
            [
                'address' => $userEntity->getEmail(),
                'name' => 'cascata'
            ],
            'OIIIIIIIIIII',
            $body
        );
    }
}