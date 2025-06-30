<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Invoice;
use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Support\Str;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            for ($i = 1; $i <= 12; $i++) {
                $invoiceDate = now()->subDays(rand(10, 90));
                $dueDate = (clone $invoiceDate)->addDays(30);

                Invoice::create([
                    'user_id' => $user->id,
                    'invoice_number' => 'INV-' . strtoupper(Str::random(6)),
                    'invoice_date' => $invoiceDate,
                    'due_date' => $dueDate,
                    'sub_total' => 0, // set in item seeder
                    'tax_rate' => 10,
                    'total_amount' => 0,
                    'gst_number' => strtoupper(Str::random(10)),
                ]);
            }
        }
    }
}
