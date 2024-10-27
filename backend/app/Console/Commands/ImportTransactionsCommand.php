<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportTransactionsCommand extends Command
{
    // /**
    //  * The name and signature of the console command.
    //  *
    //  * @var string
    //  */
    // protected $signature = 'app:import-transactions-command';

    // /**
    //  * The console command description.
    //  *
    //  * @var string
    //  */
    // protected $description = 'Command description';

    // /**
    //  * Execute the console command.
    //  */
    // public function handle()
    // {
    //     //
    // }

    protected $signature = 'transactions:import {file}';
    protected $description = 'Import transactions from CSV file';

    public function handle()
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("File does not exist!");
            return 1;
        }

        $this->info("Starting import...");

        DB::beginTransaction();

        try {
            $handle = fopen($file, 'r');
            $header = fgetcsv($handle); // Leer encabezados
            $batch = [];
            $batchSize = 1000;
            $totalImported = 0;

            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($header, $row);
                $data['transaction_id'] = 'TXN-' . Str::random(10);
                $data['trace_number'] = 'TRC-' . time() . '-' . Str::random(5);

                $batch[] = $data;

                if (count($batch) >= $batchSize) {
                    Transaction::insert($batch);
                    $totalImported += count($batch);
                    $this->info("Imported {$totalImported} transactions");
                    $batch = [];
                }
            }

            // Insertar el último lote
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
