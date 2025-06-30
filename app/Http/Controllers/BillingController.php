<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        return view('billing.index');
    }

    // Invoice listing
    public function data(Request $request)
    {
        $sortField = $request->input('sort', 'invoice_date');
        $sortDir = $request->input('direction', 'desc');
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $allowedSorts = [
            'invoice_number',
            'invoice_date',
            'due_date',
            'sub_total',
            'tax_rate',
            'total_amount',
            'gst_number',
        ];

        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'invoice_date';
        }

        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        $query = Invoice::where('user_id', Auth::id());

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%$search%")
                ->orWhere('invoice_date', 'like', "%$search%")
                ->orWhere('due_date', 'like', "%$search%")
                ->orWhere('total_amount', 'like', "%$search%")
                ->orWhere('gst_number', 'like', "%$search%");
            });
        }

        if ($startDate && $endDate) {
            $query->whereBetween('invoice_date', [$startDate, $endDate]);
        }

        $invoices = $query->orderBy($sortField, $sortDir)
            ->paginate(10);

        return response()->json($invoices);
    }

    // Invoice detail popup data
    public function show($id)
    {
        $invoice = Invoice::with(['user', 'items'])->findOrFail($id);

        return response()->json([
            'invoice' => $invoice,
            'items' => $invoice->items,
            'company' => config('app.company'),
        ]);
    }

    // Download pdf
    public function download($id)
    {
        $invoice = Invoice::with(['user', 'items'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));

        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }
}
