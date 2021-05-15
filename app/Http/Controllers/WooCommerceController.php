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

        $slack_webhook_url = 'https://hooks.slack.com/services/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';
        $icon_url = '';    
        $command = $data['command'];
        $text = $data['text'];
        $token = $data['token'];
        $channel_id = $data['channel_id'];
        $user_name = $data['user_name'];

        return response()->json($data);

        try {

            $dataWebhook = array(
                "username" => "Bario Vendas",
                "channel" => $channel_id,
                "text" => "Mensagem retornada do Woocommerce",
                "mrkdwn" => true,
                "icon_url" => $icon_url,
                "attachments" => array(
                     array(
                        "color" => "#b0c4de",
                        "title" => "title teste wiiki attach",
                        "fallback" => "fallback teste wiki - attachment",
                        "text" => "text teste wiki - attachment",
                        "mrkdwn_in" => array(
                            "fallback",
                            "text"
                        ),
                        "fields" => array(
                            array(
                                "title" => "title campo fields teste wiki",
                                "value" => "value campo fields teste wiki"
                            )
                        )
                    )
                )
            );
            $json_string = json_encode($dataWebhook);
            
            $slack_call = curl_init($slack_webhook_url);
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
