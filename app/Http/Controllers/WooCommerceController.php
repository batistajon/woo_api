<?php

namespace App\Http\Controllers;

use App\Models\WooCommerce;
use Automattic\WooCommerce\Client;
use Illuminate\Http\Request;

class WooCommerceController extends Controller
{
    private $woocommerce;

    public function __construct()
    {
        $this->woocommerce = new Client(
            env('WOO_URL').env('WOO_ENDPOINT'),
            env('WOO_CONSUMER_KEY'),
            env('WOO_CONSUMER_SECRET'),
            [
                'wp_api' => true,
                'version' => 'wc/v3',
                'query_string_auth' => true
            ]
        );
    }

    public function getCostumers()
    {
        $store_url = env('WOO_URL');
        $endpoint = '/wc-auth/v1/costumers';
        $params = [
        'oauth_consumer_key' => '',
        'oauth_timestamp' => '',
        'oauth_nonce' => '',
        'oauth_signature' => '',
        'oauth_signature_method' => 'HMAC-SHA1'
        ];

        $query_string = http_build_query( $params );

        echo $store_url . $endpoint . '?' . $query_string;
    }

    public function authenticateWoo()
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

    public function costumers()
    {
        $results = $this->woocommerce->get('costumers');

        return response()->json($results);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = $this->woocommerce->get('');

        return response()->json($results);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WooCommerce  $wooCommerce
     * @return \Illuminate\Http\Response
     */
    public function show(WooCommerce $wooCommerce)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WooCommerce  $wooCommerce
     * @return \Illuminate\Http\Response
     */
    public function edit(WooCommerce $wooCommerce)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WooCommerce  $wooCommerce
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WooCommerce $wooCommerce)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WooCommerce  $wooCommerce
     * @return \Illuminate\Http\Response
     */
    public function destroy(WooCommerce $wooCommerce)
    {
        //
    }
}
