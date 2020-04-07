<?php namespace Tests\Repositories;

use App\Models\Cart;
use App\Repositories\CartRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CartRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CartRepository
     */
    protected $cartRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->cartRepo = \App::make(CartRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_cart()
    {
        $cart = factory(Cart::class)->make()->toArray();

        $createdCart = $this->cartRepo->create($cart);

        $createdCart = $createdCart->toArray();
        $this->assertArrayHasKey('id', $createdCart);
        $this->assertNotNull($createdCart['id'], 'Created Cart must have id specified');
        $this->assertNotNull(Cart::find($createdCart['id']), 'Cart with given id must be in DB');
        $this->assertModelData($cart, $createdCart);
    }

    /**
     * @test read
     */
    public function test_read_cart()
    {
        $cart = factory(Cart::class)->create();

        $dbCart = $this->cartRepo->find($cart->id);

        $dbCart = $dbCart->toArray();
        $this->assertModelData($cart->toArray(), $dbCart);
    }

    /**
     * @test update
     */
    public function test_update_cart()
    {
        $cart = factory(Cart::class)->create();
        $fakeCart = factory(Cart::class)->make()->toArray();

        $updatedCart = $this->cartRepo->update($fakeCart, $cart->id);

        $this->assertModelData($fakeCart, $updatedCart->toArray());
        $dbCart = $this->cartRepo->find($cart->id);
        $this->assertModelData($fakeCart, $dbCart->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_cart()
    {
        $cart = factory(Cart::class)->create();

        $resp = $this->cartRepo->delete($cart->id);

        $this->assertTrue($resp);
        $this->assertNull(Cart::find($cart->id), 'Cart should not exist in DB');
    }
}
