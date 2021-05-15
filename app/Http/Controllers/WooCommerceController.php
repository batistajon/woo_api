<?php

namespace App\Http\Controllers;

use App\Models\WooCommerce; 
use Exception;
use Illuminate\Http\Request;

class WooCommerceController extends Controller 
{
    private $woocommerce;

    public function __construct(WooCommerce $woocommerce)
    {
        $this->woocommerce = $woocommerce;
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

            $this->woocommerce->slackWebhook($data, $icon_url); 

        } catch (\Throwable $th) {

            echo 'erros';
            //throw $th;
        }
    }
}
