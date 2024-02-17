<?php

namespace App\events;

use App\email\Email;

class UserRegistered
{
    private string $event = 'user-registered-event';

    public function __invoke(string $recipient, string $body = "")
    {
        $email = new Email();
        $email->send(
            [
                'address' => 'jpppedreira@gmail.com',
                'name' => 'cascata'
            ],
            [
                'address' => $recipient,
                'name' => 'cascata'
            ],
            'OIIIIIIIIIII',
            'oi do cascata'
        );
    }
}