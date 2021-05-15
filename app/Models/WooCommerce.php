<?php

namespace App\Models;

use Automattic\WooCommerce\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WooCommerce extends Model
{
    use HasFactory;

    private $woocommerce;

    public function __construct()
    {
        $this->woocommerce = new Client(
            env('WOO_URL'),
            env('WOO_CONSUMER_KEY'),
            env('WOO_CONSUMER_SECRET'),
            [
                'wp_api' => true,
                'version' => 'wc/v3',
                'query_string_auth' => true
            ]
        );
    }

    public function slackWebhook($data, $icon_url = null)
    {
        $dataWebhook = [
            "username" => $data['user_name'],
            "channel" => $data['channel_id'],
            "text" => "Mensagem retornada do Woocommerce",
            "mrkdwn" => true,
            "icon_url" => $icon_url,
            "attachments" => [
                [
                    "color" => "#b0c4de",
                    "title" => "Venda cadastrada por: " . $data['user_name'],
                    "fallback" => "fallback teste wiki - attachment",
                    "text" => $data['text'],
                    "mrkdwn_in" => [
                        "fallback",
                        "text"
                    ]
                ]
            ]
        ];
        $json_string = json_encode($dataWebhook);
        
        $slack_call = curl_init(env('SLACK_WEBHOOK_URL'));
        curl_setopt($slack_call, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($slack_call, CURLOPT_POSTFIELDS, $json_string);
        curl_setopt($slack_call, CURLOPT_CRLF, true);
        curl_setopt($slack_call, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($slack_call, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Content-Length: " . strlen($json_string))
        );
        
        $result = curl_exec($slack_call);
        curl_close($slack_call); 
    }
}
