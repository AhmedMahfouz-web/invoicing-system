<?php

namespace Database\Factories;

use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 1000, 50000);
        $taxRate = 0.14; // 14% tax rate
        $taxAmount = $subtotal * $taxRate;
        $total = $subtotal + $taxAmount;

        return [
            'client_id' => Client::factory(),
            'invoice_number' => 'INV-' . $this->faker->unique()->numberBetween(1000, 999999),
            'invoice_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
