<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    protected $fillable = [
        'user_id', 'invoice_number', 'invoice_date','due_date', 'sub_total', 'tax_rate','total_amount', 'gst_number'
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
