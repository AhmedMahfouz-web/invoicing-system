<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceItemFactory extends Factory
{
    public function definition(): array
    {
        $product = Product::factory()->create();
        $quantity = $this->faker->numberBetween(1, 10);
        $price = $product->price;
        $total = $quantity * $price;

        return [
            'invoice_id' => Invoice::factory(),
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $price,
            'total' => $total,
        ];
    }
}
