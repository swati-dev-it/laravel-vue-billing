<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id', 'item', 'amount'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
