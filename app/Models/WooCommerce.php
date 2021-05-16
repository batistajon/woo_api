<?php

namespace App\Models;

use Automattic\WooCommerce\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WooCommerce extends Model
{
    use HasFactory;

    public function slackWebhook($dataFromSlashCommand, $dataToWebhook, $icon_url = null)
    {
        $dataWebhook = [
            "username" => $dataFromSlashCommand['user_name'],
            "channel" => $dataFromSlashCommand['channel_id'],
            "text" => "Numero do novo pedido: " . $dataToWebhook['id'],
            "mrkdwn" => true,
            "icon_url" => $icon_url,
            "attachments" => [
                [
                    "color" => "#b0c4de",
                    "title" => "Venda cadastrada por: " . $dataFromSlashCommand['user_name'],
                    "fallback" => 'fallback teste',
                    "text" => 'Total da compra: '.$dataToWebhook['total'],
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
