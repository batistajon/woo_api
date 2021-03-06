<?php

namespace App\Http\Controllers;

use App\Models\WooCommerce;
use Automattic\WooCommerce\Client;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\SlackMessage;

class WooCommerceController extends Controller 
{    
    /**
     * woocommerce
     *
     * @var mixed
     */
    private $woocommerce;
    
    /**
     * Method __construct
     *
     * @param WooCommerce $woocommerce [explicite description]
     *
     * @return void
     */
    public function __construct(WooCommerce $woocommerce)
    {
        $this->woocommerce = $woocommerce->__get('woocommerce');
    }
    
    /**
     * Method customers
     *
     * @param Request $request [explicite description]
     *
     * @return JsonResponse
     */
    public function customers(Request $request): JsonResponse
    {
        try {

            $data = $request->all();

            $userId = $this->woocommerce->retriveIdByEmail($data['email']); 

            if($userId == '') {
                return response()->json(['error' => 'Usuario nao cadastrado']);
            }
            
            $results = (object) $this->woocommerce->get("customers/$userId");

            return response()->json($results, 200);

        } catch (Exception $e) {
            
            return response()->json($e->getMessage());
        }
    }
    
    /**
     * Method orders
     *
     * @return void
     */
    public function orders()
    {
        try {

            $results = $this->woocommerce->get('orders', [
                'orderby' => 'date',
                'per_page' => 50
            ]);

            return response()->json($results);

        } catch (Exception $e) {
            
            return response()->json($e->getMessage());
        }
    }
    
    /**
     * Method products
     *
     * @return void
     */
    public function products()
    {
        try {

            $results = $this->woocommerce->get('products', [
                'per_page' => 100
            ]);

            return response()->json($results);

        } catch (Exception $e) {
            
            return response()->json($e->getMessage());
        }
    }
    
    /**
     * Method productsArray
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function productsArray(Request $request)
    {
        try {

            $array = $request->all();

            /* $results = $this->woocommerce->get('products', [
                'per_page' => 100
            ]); */

            return response()->json($array, 200);

        } catch (Exception $e) {
            
            return response()->json($e->getMessage());
        }
    }
    
    /**
     * Method productsCategory
     *
     * @param $category $category [explicite description]
     *
     * @return void
     */
    public function productsCategory($category)
    {
        try {
            $results = $this->woocommerce->get('products', [
                'per_page' => 100,
                'category' => $category,
                'stock_status' => 'instock'
            ]);

            return response()->json($results);

        } catch (Exception $e) {
            
            return response()->json($e->getMessage());
        }
    }
    
    /**
     * Method productDetails
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function productDetails($id)
    {
        try {
            $results = $this->woocommerce->get("products/$id", [
                'per_page' => 100
            ]);

            return response()->json($results);

        } catch (Exception $e) {
            
            return response()->json($e->getMessage());
        }
    }
    
    /**
     * Method productVariations
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function productVariations($id)
    {
        try {
            $results = $this->woocommerce->get("products/$id/variations", [
                'per_page' => 100
            ]);

            return response()->json($results);

        } catch (Exception $e) {
            
            return response()->json($e->getMessage());
        }
    }
    
    /**
     * Method categories
     *
     * @return void
     */
    public function categories()
    {
        try {
            
            $results = $this->woocommerce->get("products/categories", [
                'include' => [16, 42, 199, 222, 223],
                'orderby' => 'id'
            ]);

            return response()->json($results);

        } catch (Exception $e) {
            
            return response()->json($e->getMessage());
        }
    }

    public function slackSelling()
    {
        
    }

    public function getFormSlack(Request $request)
    {
        $data = $request->all();

        try {

            //fazer get para buscar produtos no woo e compor formulario - Enviar prods por parametro
            
            $slackFormWebhook = new WooCommerce();
            $form = $slackFormWebhook->slackFormWebhook();
            
            $slack_call = curl_init(env('SLACK_WEBHOOK_URL'));
            curl_setopt($slack_call, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($slack_call, CURLOPT_POSTFIELDS, $form);
            curl_setopt($slack_call, CURLOPT_CRLF, true);
            curl_setopt($slack_call, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($slack_call, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "Content-Length: " . strlen($form))
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

            $dataToSendWoo = [
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

            return response()->json($results);

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
