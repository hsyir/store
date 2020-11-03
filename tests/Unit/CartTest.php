<?php

namespace Hsy\Store\Tests;


use Hsy\Store\Models\Invoice;
use Hsy\Store\Models\InvoiceItem;
use Hsy\Store\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Hsy\Store\Facades\Store;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
    }


    /** @test  */
    public function add_to_cart()
    {
        $product = factory(Product::class)->create();
        Store::cart()->add($product, 100);
        $this->assertCount(1, Store::cart()->content());
    }

    /** @test  */
    public function add_to_cart_from_model()
    {
        $product = factory(Product::class)->create();
        $product->addToCart();
        $this->assertCount(1, Store::cart()->content());
    }

    /** @test  */
    public function price_total_amount()
    {
        $products = factory(Product::class, 20)->create();
        foreach ($products as $product) {
            Store::cart()->add($product, 10);
        }

        $this->assertEquals(
            $products->map(function ($item) {
                return $item->price * 10;
            })->sum(),
            Store::cart()->priceTotal()
        );

    }

    /** @test  */
    public function all_contents_count()
    {
        $products = factory(Product::class, 20)->create();
        foreach ($products as $product) {
            Store::cart()->add($product, 10);
        }

        $this->assertEquals(Store::cart()->count(),200);
    }


    /** @test  */
    public function attach_products()
    {
        $product = factory(Product::class)->create();
        $product->addToCart();

        Store::cart()->attachProducts();

        $this->assertEquals(
            $product->id,
            Store::cart()->content()->first()->options->product->id
        );

    }

}
