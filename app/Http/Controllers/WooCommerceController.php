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

    public function slack(Request $request, WooCommerce $woocommerce)
    {
        $data = $request->all();

        $icon_url = '';    

        try {

            $woocommerceModel = new WooCommerce();

            $results = $this->woocommerce->get('orders/36158');

            //return response()->json($results);

            $woocommerceModel->slackWebhook($data, $results, $icon_url); //TODO fazer chamada ao woocommerce e inserir dados para esse metodo

        } catch (\Exception $e) {

            return response()->json($e->getMessage());
        }
    }
}
