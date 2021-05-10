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

    public function authenticate()
    {
        $store_url = env('WOO_URL');
        $endpoint = '/wc-auth/v1/authorize';
        $params = [
        'app_name' => 'Bario',
        'scope' => 'write',
        'user_id' => 355,
        'return_url' => 'https://bariocafes.com.br',
        'callback_url' => 'https://bariocafes.com.br'
        ];

        $query_string = http_build_query( $params );

        echo $store_url . $endpoint . '?' . $query_string;
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
}
