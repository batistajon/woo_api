<?php

namespace App\Http\Controllers;

use App\Models\WooCommerce;
use Automattic\WooCommerce\Client;
use Exception;
use Illuminate\Http\Request;

class WooCommerceController extends Controller 
{
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

    public function slack(Request $request)
    {
        $data = $request->all();

        $icon_url = '';    

        try {

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

        } catch (\Throwable $th) {

            echo 'erros';
            //throw $th;
        }
    }
}
