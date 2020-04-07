<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Cart;

class CartApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_cart()
    {
        $cart = factory(Cart::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/carts', $cart
        );

        $this->assertApiResponse($cart);
    }

    /**
     * @test
     */
    public function test_read_cart()
    {
        $cart = factory(Cart::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/carts/'.$cart->id
        );

        $this->assertApiResponse($cart->toArray());
    }

    /**
     * @test
     */
    public function test_update_cart()
    {
        $cart = factory(Cart::class)->create();
        $editedCart = factory(Cart::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/carts/'.$cart->id,
            $editedCart
        );

        $this->assertApiResponse($editedCart);
    }

    /**
     * @test
     */
    public function test_delete_cart()
    {
        $cart = factory(Cart::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/carts/'.$cart->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/carts/'.$cart->id
        );

        $this->response->assertStatus(404);
    }
}
