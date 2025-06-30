<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class InvoiceItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $invoices = Invoice::all();

        foreach ($invoices as $invoice) {
            $subTotal = 0;

            for ($i = 1; $i <= rand(2, 4); $i++) {
                $amount = rand(5000, 15000) / 100;
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item' => "Item design $i",
                    'amount' => $amount,
                ]);
                $subTotal += $amount;
            }

            $tax = $subTotal * ($invoice->tax_rate / 100);
            $total = $subTotal + $tax;

            $invoice->update([
                'sub_total' => $subTotal,
                'total_amount' => $total,
            ]);
        }
    }
}
