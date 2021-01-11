<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    //Ces cles sont personnel et rattachés à notre compte Mailjet
    private $api_key = 'd9fcbb1041df804ec36ca94ee83a2db0';
    private $api_key_secret = '706e7d53d172da0b82f99d736a2ee15a';

    public function send($to_mail,$to_name,$subject,$content)
{
    $mj = new Client($this->api_key,$this->api_key_secret,true,['version' => 'v3.1']);
    $body = [
        'Messages' => [
            [
                'From' => [
                    'Email' => "romain.alix89@gmail.com",
                    'Name' => "Mon site| E-commerce"
                ],
                'To' => [
                    [
                        'Email' => $to_mail,
                        'Name' => $to_name
                    ]
                ],
                'TemplateID' => 2145964,
                'TemplateLanguage' => true,
                'Subject' => $subject,
                'Variables' => [
                    'content' => $content,
                ]
            ]
        ]
    ];
    $response = $mj->post(Resources::$Email, ['body' => $body]);
    $response->success();
}
}