<?php

namespace Tests\Feature\Api;

use App\Models\Transaction;
use Illuminate\Testing\Fluent\AssertableJson;

class TransactionControllerTest extends ApiTestCase
{
  /**
   * @test
   */
  public function it_can_list_transactions()
  {
    // Arrange
    $transactions = [];
    for ($i = 0; $i < 3; $i++) {
      $transactions[] = Transaction::create($this->createTransaction());
    }

    // Act
    $response = $this->getJson('/api/v1/transactions');

    // Assert
    $response
      ->assertStatus(200)
      ->assertJson(
        fn(AssertableJson $json) =>
        $json
          ->has('data', 3)
          ->has(
            'data.0',
            fn($json) =>
            $json->hasAll([
              'id',
              'transaction_id',
              'account_number_from',
              'account_number_type_from',
              'account_number_to',
              'account_number_type_to',
              'amount',
              'type',
              'description',
              'reference',
              'creation_date'
            ])
          )
      );
  }

  /**
   * @test
   */
  public function it_can_filter_transactions_by_date_range()
  {
    // Arrange
    $oldTransaction = Transaction::create($this->createTransaction([
      'creation_date' => now()->subDays(10)
    ]));

    $newTransaction = Transaction::create($this->createTransaction([
      'creation_date' => now()
    ]));

    // Act
    $response = $this->getJson('/api/v1/transactions?' . http_build_query([
      'date_from' => now()->subDays(5)->format('Y-m-d'),
      'date_to' => now()->format('Y-m-d')
    ]));

    // Assert
    $response
      ->assertStatus(200)
      ->assertJson(
        fn(AssertableJson $json) =>
        $json
          ->has('data', 1)
          ->has(
            'data.0',
            fn($json) =>
            $json->where('transaction_id', $newTransaction->transaction_id)
          )
      );
  }

  /**
   * @test
   */
  public function it_can_create_a_new_transaction()
  {
    // Arrange
    $transactionData = $this->createTransaction();

    // Act
    $response = $this->postJson('/api/v1/transactions', $transactionData);

    // Assert
    $response
      ->assertStatus(201)
      ->assertJson([
        'message' => 'Transaction created successfully',
        'data' => [
          'account_number_from' => $transactionData['account_number_from'],
          'account_number_type_from' => $transactionData['account_number_type_from'],
          'account_number_to' => $transactionData['account_number_to'],
          'account_number_type_to' => $transactionData['account_number_type_to'],
          'amount' => $transactionData['amount'],
          'type' => $transactionData['type']
        ]
      ]);

    $this->assertDatabaseHas('transactions', [
      'account_number_from' => $transactionData['account_number_from'],
      'type' => $transactionData['type']
    ]);
  }

  /**
   * @test
   */
  public function it_validates_required_fields_when_creating_transaction()
  {
    // Act
    $response = $this->postJson('/api/v1/transactions', []);

    // Assert
    $response
      ->assertStatus(422)
      ->assertJsonValidationErrors([
        'account_number_from',
        'account_number_type_from',
        'account_number_to',
        'account_number_type_to',
        'amount',
        'type'
      ]);
  }

  /**
   * @test
   */
  public function it_validates_amount_is_numeric_and_positive()
  {
    // Arrange
    $transactionData = $this->createTransaction(['amount' => -100]);

    // Act
    $response = $this->postJson('/api/v1/transactions', $transactionData);

    // Assert
    $response
      ->assertStatus(422)
      ->assertJsonValidationErrors(['amount']);
  }

  /**
   * @test
   */
  public function it_can_delete_a_transaction()
  {
    // Arrange
    $transaction = Transaction::create($this->createTransaction());

    // Act
    $response = $this->deleteJson("/api/v1/transactions/{$transaction->transaction_id}");

    // Assert
    $response->assertStatus(200);
    $this->assertDatabaseMissing('transactions', [
      'id' => $transaction->id
    ]);
  }

  /**
   * @test
   */
  public function it_returns_404_when_deleting_non_existent_transaction()
  {
    // Act
    $response = $this->deleteJson('/api/v1/transactions/non-existent-id');

    // Assert
    $response->assertStatus(404);
  }

  /**
   * @test
   */
  public function it_can_filter_transactions_by_type()
  {
    // Arrange
    Transaction::create($this->createTransaction(['type' => 'credit']));
    Transaction::create($this->createTransaction(['type' => 'debit']));

    // Act
    $response = $this->getJson('/api/v1/transactions?type=credit');

    // Assert
    $response
      ->assertStatus(200)
      ->assertJson(
        fn(AssertableJson $json) =>
        $json
          ->has('data')
          ->where('data.0.type', 'credit')
      );
  }
}
