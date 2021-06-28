<?php

namespace Tests\Feature;

use App\Models\WooCommerce;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WooCommerceTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }
    
    /**
     * Method test_return_a_valid_id_by_email
     *
     * @return void
     */
    public function test_return_a_valid_id_by_email()
    {
        $id = new WooCommerce();
        $id->retriveIdByEmail('batista.jonathas@gmail.com');

        $this->assertNotNull($id);
    }
    
    /**
     * Method test_call_endpoint_products
     *
     * @return void
     */
    public function test_call_endpoint_products()
    {
        $response = $this->call('GET', '/api/products');

        $this->assertEquals(200, $response->status());
    }
    
    /**
     * Method test_call_endpoint_post_products_array_to_search
     *
     * @return void
     */
    public function test_call_endpoint_post_products_array_to_search()
    {
        $response = $this->call('POST', '/api/products/array');

        $this->assertEquals(200, $response->status());
    }
}
