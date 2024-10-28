<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateTransactionsCsv extends Command
{
    protected $signature = 'transactions:generate-csv {count=500000}';
    protected $description = 'Generate a CSV file with sample transaction data';

    private $accountTypes = ['SAVINGS', 'CHECKING', 'CREDIT', 'INVESTMENT'];
    private $banks = ['BANK1', 'BANK2', 'BANK3', 'BANK4', 'BANK5'];

    public function handle()
    {
        $count = $this->argument('count');
        $filename = storage_path('app/transactions.csv');
        
        $this->info("Generating {$count} transactions...");
        
        $progress = $this->output->createProgressBar($count);
        
        $file = fopen($filename, 'w');
        
        fputcsv($file, [
            'account_number_from',
            'account_number_type_from',
            'account_number_to',
            'account_number_type_to',
            'amount',
            'type',
            'description',
            'reference',
            'creation_date'
        ]);
        
        for ($i = 0; $i < $count; $i++) {
            $fromBank = $this->banks[array_rand($this->banks)];
            $toBank = $this->banks[array_rand($this->banks)];
            $fromType = $this->accountTypes[array_rand($this->accountTypes)];
            $toType = $this->accountTypes[array_rand($this->accountTypes)];
            
            $amount = mt_rand(100, 1000000) / 100; // Valores entre 1.00 y 10000.00
            $type = mt_rand(0, 1) ? 'credit' : 'debit';
            
            $date = date('Y-m-d H:i:s', strtotime('-' . mt_rand(0, 365) . ' days'));
            
            $data = [
                $fromBank . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT),
                $fromType,
                $toBank . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT),
                $toType,
                $amount,
                $type,
                $this->generateDescription($type, $amount),
                'REF-' . Str::random(8),
                $date
            ];
            
            fputcsv($file, $data);
            $progress->advance();
            
            // Liberar memoria
            if ($i % 1000 === 0) {
                gc_collect_cycles();
            }
        }
        
        fclose($file);
        $progress->finish();
        
        $this->newLine();
        $this->info("CSV file generated successfully at: {$filename}");
        $this->info("File size: " . round(filesize($filename) / 1024 / 1024, 2) . " MB");
    }

    private function generateDescription($type, $amount): string
    {
        $descriptions = [
            'credit' => [
                'Deposit',
                'Transfer received',
                'Payment received',
                'Salary deposit',
                'Refund'
            ],
            'debit' => [
                'Payment',
                'Transfer sent',
                'Purchase',
                'Bill payment',
                'Withdrawal'
            ]
        ];
        
        $desc = $descriptions[$type][array_rand($descriptions[$type])];
        return "{$desc} - $" . number_format($amount, 2);
    }
}