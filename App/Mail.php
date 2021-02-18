<?php

namespace App;

use Mailgun\Mailgun;

class Mail
{

    public static function send($to, $subject, $text, $html)
    {
        /*
        $mg = new Mailgun(Config::MAILGUN_API_KEY);
        $domain = Config::MAILGUN-DOMAIN;

        $mg->sendMessage($domain, array('from'     => 'your-sender@example.com',
                                        'to'       => $to,
                                        'subject'  => $subject,
                                        'text'     => $text,
                                        'html'     => $html));
        */
    }
}