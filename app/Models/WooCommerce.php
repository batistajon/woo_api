<?php

namespace App\Models;

use Automattic\WooCommerce\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WooCommerce extends Model
{
    use HasFactory;

    public function slackFormWebhook()
    {
        $dataWebhook = [
            "blocks" => [
                [
                    "type" => "input",
                    "element" => [
                        "type" => "plain_text_input",
                        "action_id" => "plain_text_input-action"
                    ],
                    "label" => [
                        "type" => "plain_text",
                        "text" => "Nome do Cliente"
                    ]
                ],
                [
                    "type" => "input",
                    "element" => [
                        "type" => "plain_text_input",
                        "action_id" => "plain_text_input-action"
                    ],
                    "label" => [
                        "type" => "plain_text",
                        "text" => "Sobrenome do Cliente"
                    ]      
                ],
                [
                    "type" => "actions",
                    "elements" => [
                        [
                            "type" => "button",
                            "text" => [
                                "type" => "plain_text",
                                "text" => "Enviar venda"
                            ],
                            "value" => "click_me_123",
                            "action_id" => "actionId-0"
                        ]
                    ]     
                ]
            ]
        ];

        $json_string = json_encode($dataWebhook);

        return $json_string;
    }

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
        
        return $json_string;
    }
}
