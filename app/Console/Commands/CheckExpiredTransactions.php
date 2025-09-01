<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckExpiredTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update expired transactions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactions = Transaction::where('status', 'pending')
                                ->where('created_at', '<', now()->subHours(24))
                                ->get();

        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction', false);
        
        foreach ($transactions as $transaction) {
            try {
                // Buat order_id dengan format yang sama seperti di controller
                $orderId = 'INV-' . $transaction->id . '-' . $transaction->created_at->timestamp;

                Log::info('Checking transaction: ' . $transaction->id . ' with order_id: ' . $orderId);

                $status = \Midtrans\Transaction::status($orderId);
                $transaction->status = $status->transaction_status;
                $transaction->save();

                Log::info('Updated transaction ' . $transaction->id . ' to status: ' . $status->transaction_status);
            } catch (\Exception $e) {
                Log::error('Failed to check transaction ' . $transaction->id . ': ' . $e->getMessage());

                // Jika transaksi lebih dari 24 jam, tandai sebagai expired
                if ($transaction->created_at < now()->subHours(24)) {
                    $transaction->status = 'expired';
                    $transaction->save();
                    Log::info('Marked transaction ' . $transaction->id . ' as expired due to age');
                }
            }
        }

        $this->info('Processed ' . $transactions->count() . ' pending transactions');
    }
}
