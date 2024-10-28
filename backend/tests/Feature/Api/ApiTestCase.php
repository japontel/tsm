<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;

class ApiTestCase extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->withHeaders([
            'Accept' => 'application/json'
        ]);
    }

    protected function createTransaction(array $overrides = []): array
    {
        return array_merge([
            'transaction_id' => 'TXN-' . Str::random(10),
            'account_number_from' => 'BANK1' . $this->faker->numerify('######'),
            'account_number_type_from' => $this->faker->randomElement(['SAVINGS', 'CHECKING']),
            'account_number_to' => 'BANK2' . $this->faker->numerify('######'),
            'account_number_type_to' => $this->faker->randomElement(['SAVINGS', 'CHECKING']),
            'amount' => $this->faker->randomFloat(2, 10, 10000),
            'type' => $this->faker->randomElement(['credit', 'debit']),
            'description' => $this->faker->sentence,
            'reference' => 'REF-' . $this->faker->unique()->randomNumber(8),
            'trace_number' => 'TRC-' . time() . '-' . Str::random(5),
            'creation_date' => now()->format('Y-m-d H:i:s')
        ], $overrides);
    }
}