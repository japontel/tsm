<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportTransactionsCommand extends Command
{
    protected $signature = 'transactions:import';
    protected $description = 'Import transactions from CSV file';

    public function handle()
    {
        $file = storage_path('app/transactions.csv');

        $this->info("Starting import...");

        DB::beginTransaction();

        try {
            $handle = fopen($file, 'r');
            $header = fgetcsv($handle);
            $batchSize = 1000;
            $totalImported = 0;

            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($header, $row);
                $data['transaction_id'] = 'TXN-' . Str::random(10);
                $data['trace_number'] = 'TRC-' . time() . '-' . Str::random(10);

                $batch[] = $data;

                if (count($batch) >= $batchSize) {
                    Transaction::insert($batch);
                    $totalImported += count($batch);
                    $this->info("Imported {$totalImported} transactions");
                    $batch = [];
                }
            }

            // Insert last batch
            if (!empty($batch)) {
                Transaction::insert($batch);
                $totalImported += count($batch);
            }

            fclose($handle);
            DB::commit();

            $this->info("Import completed. Total records: {$totalImported}");
            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error importing: " . $e->getMessage());
            return 1;
        }
    }
}
