<?php

namespace Tests\Feature\Commands;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImportTransactionsCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Asegurarse que las migraciones se ejecuten
        $this->artisan('migrate:fresh');
        
        Storage::fake('local');
    }

    /**
     * @test
     */
    public function it_can_import_transactions_from_csv()
    {
        // Arrange
        Storage::fake('local');
        $csvContent = implode("\n", [
            'account_number_from,account_number_type_from,account_number_to,account_number_type_to,amount,type,description,reference,creation_date',
            sprintf(
                'BANK1000001,SAVINGS,BANK2000001,CHECKING,1000.00,credit,Test transaction,REF-12345,%s',
                now()->format('Y-m-d H:i:s')
            )
        ]);
        
        $filePath = Storage::path('test_transactions.csv');
        File::put($filePath, $csvContent);

        // Act
        $this->artisan('transactions:import', ['file' => $filePath])
             ->assertSuccessful();

        // Assert
        $this->assertDatabaseHas('transactions', [
            'account_number_from' => 'BANK1000001',
            'account_number_type_from' => 'SAVINGS',
            'amount' => 1000.00,
            'type' => 'credit'
        ]);
    }

    /**
     * @test
     */
    public function it_handles_invalid_csv_file()
    {
        $this->artisan('transactions:import', ['file' => 'nonexistent.csv'])
             ->assertFailed();
    }

    /**
     * @test
     */
    public function it_handles_invalid_data_in_csv()
    {
        // Arrange
        $csvContent = implode("\n", [
            'account_number_from,account_number_type_from,account_number_to,account_number_type_to,amount,type,description,reference,creation_date',
            sprintf(
                'BANK1000001,INVALID,BANK2000001,CHECKING,invalid,invalid,Test,REF-12345,%s',
                now()->format('Y-m-d H:i:s')
            )
        ]);
        
        $filePath = Storage::path('invalid_transactions.csv');
        File::put($filePath, $csvContent);

        // Act
        $this->artisan('transactions:import', ['file' => $filePath])
             ->assertSuccessful();

        // Assert
        $this->assertDatabaseCount('transactions', 0);
    }
}