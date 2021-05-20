<?php

namespace App\Http\Controllers;

use App\Models\WooCommerce;
use Automattic\WooCommerce\Client;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\SlackMessage;

class WooCommerceController extends Controller 
{
    private $woocommerce;

    public function __construct(WooCommerce $woocommerce)
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $results = $this->woocommerce->get('');

            return response()->json($results);

        } catch (Exception $e) {

            return response()->json($e->getMessage());
        }
    }

    public function customers(Request $request)
    {
        try {
            $results = $this->woocommerce->get('customers', [
                'page' => 1,
                'per_page' => 100,
                'role' => 'customer'
            ]);

            $results = collect($results);

            return response()->json($results);

        } catch (Exception $e) {
            
            return response()->json($e->getMessage());
        }
    }

    public function orders()
    {
        try {
            $results = $this->woocommerce->get('orders', [
                'page' => 1,
                'per_page' => 10,
                'role' => 'customer'
            ]);

            return response()->json($results[0]);

        } catch (Exception $e) {
            
            return response()->json($e->getMessage());
        }
    }

    public function slackSelling()
    {
        
    }

    public function getFormSlack(Request $request): string
    {
        $data = $request->all();

        try {
            
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
                            "text" => "Cliente"
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
                            "text" => "Sobrenome"
                        ]      
                    ],
                    [
                        "type" => "actions",
                        "element" => [
                            [
                                "type" => "button",
                                "text" => [
                                    "type" => "button",
                                    "text" => "Enviar venda"
                                ],
                                "value" => "click_me_123",
                                "action_id" => "actionId-0"
                            ]
                        ],
                        "label" => [
                            "type" => "plain_text",
                            "text" => "Sobrenome"
                        ]      
                    ]
                ]
            ];


            //return response()->json($dataWebhook);



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

        } catch (Exception $e) {
            
            return response()->json($e->getMessage());
        }  
    }

    public function slack(Request $request)
    {
        $data = $request->all();

        $icon_url = '';

        try {

            /* $dataToSendWoo = [
                'payment_method' => 'bacs',
                'payment_method_title' => 'teste de compra pelo slack',
                'set_paid' => true,
                'billing' => [
                    'first_name' => 'Jon',
                    'last_name' => 'Batista',
                    'address_1' => 'Av. Henfil, 25',
                    'address_2' => 'Bl2',
                    'city' => 'Rio de Janeiro',
                    'state' => 'RJ',
                    'postcode' => '21555300',
                    'country' => 'BR',
                    'email' => 'batista.jonathas@gmail.com',
                    'phone' => '(21) 98201-9916'
                ],
                'shipping' => [
                    'first_name' => 'Jon',
                    'last_name' => 'Batista',
                    'address_1' => 'Av. Henfil, 25',
                    'address_2' => 'Bl2',
                    'city' => 'Rio de Janeiro',
                    'state' => 'RJ',
                    'postcode' => '22795641',
                    'country' => 'BR'
                ],
                'line_items' => [
                    [
                        'product_id' => 36336,
                        'quantity' => 2,
                        'total' => 48.00
                    ]
                ]
            ];

            $results = $this->woocommerce->post('orders', $dataToSendWoo);

            return response()->json($results); */

            $dataWebhook = [ "text" => "requisicao chegando"]
                /* "username" => $data['user_name'],
                "channel" => $data['channel_id'],
                "text" => "Numero do novo pedido: " . $data['text'],
                "mrkdwn" => true,
                "icon_url" => $icon_url,
                "attachments" => [
                    [
                        "color" => "#b0c4de",
                        "title" => "Venda cadastrada para: " . $nome[0],
                        "fallback" => 'fallback teste',
                        "text" => "CPF: " . $cpf[0],
                        "text" => "Produtos: " . $produtos[0],
                        "mrkdwn_in" => [
                            "fallback",
                            "text"
                        ]
                    ]
                ]
            ] */; 
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

        } catch (\Exception $e) {

            return response()->json($e->getMessage());
        }
    }
}
