<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            padding: 40px;
            color: #333;
            position: relative;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .text-success {
            color: #198754;
        }

        .text-orange {
            color: #a64b00;
        }

        .small {
            font-size: 12px;
        }

        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-4 { margin-bottom: 16px; }
        .mt-4 { margin-top: 16px; }

        .logo {
            height: 60px;
            margin-bottom: 10px;
        }

        .section {
            margin-bottom: 20px;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 8px;
            font-size: 13px;
        }

        thead {
            border-bottom: 1px solid #ccc;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            z-index: 0;
        }

        .watermark img {
            height: 80px;
        }

        .content {
            position: relative;
            z-index: 1;
        }

        .totals {
            margin-top: 20px;
            text-align: right;
        }

        .totals p {
            margin: 0;
            font-size: 14px;
        }

        .totals h4 {
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">
        <img src="{{ public_path('images/taptik_logo.png') }}" alt="Watermark Logo">
    </div>

    <div class="content">
        <!-- Logo + Company Info -->
        <div class="text-center mb-2">
            <img src="{{ public_path('images/taptik_logo.png') }}" class="logo" alt="Logo"><br>
            <strong>{{ config('app.company.name') }}</strong><br>
            <span class="small">{{ config('app.company.address') }}</span><br>
            <span class="small">{{ config('app.company.contact') }}</span>
        </div>

        <!-- Invoice Title -->
        <h2 class="text-center fw-bold text-orange mt-4">INVOICE</h2>

        <!-- Bill To -->
        <div class="section">
            <h4 class="fw-bold text-success mb-1">Bill To</h4>
            <p class="mb-0 fw-bold">{{ $invoice->user->name ?? 'N/A' }}</p>
            <p class="small mb-0">{{ $invoice->user->address ?? '' }}</p>
            <p class="small">{{ $invoice->user->phone ?? '' }}</p>
        </div>

        <!-- Invoice Meta -->
        <table style="width: 100%; margin-bottom: 20px; font-size: 13px;">
            <tr>
                <td>
                    <span class="fw-bold text-muted">Invoice#:</span><br>
                    {{ $invoice->invoice_number }}
                </td>
                <td>
                    <span class="fw-bold text-muted">Invoice Date:</span><br>
                    {{ $invoice->invoice_date }}
                </td>
                <td>
                    <span class="fw-bold text-muted">Due Date:</span><br>
                    {{ $invoice->due_date }}
                </td>
            </tr>
        </table>

        <!-- Invoice Items -->
        <table>
            <thead>
                <tr>
                    <th style="text-align: left;">#</th>
                    <th style="text-align: left;">Items</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->item }}</td>
                        <td class="text-end">₹{{ number_format($item->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Notes & Totals -->
        <div class="row">
            <div style="width: 50%; float: left; font-size: 13px;">
                <p class="fw-bold mb-1">Notes</p>
                <p class="text-muted">Thanks for your business.</p>
            </div>
            <div style="width: 50%; float: right;" class="totals">
                <p>Sub Total: ₹{{ number_format($invoice->sub_total, 2) }}</p>
                <p>Tax: {{ $invoice->tax_rate }}%</p>
                <h4 class="fw-bold">Total: ₹{{ number_format($invoice->total_amount, 2) }}</h4>
            </div>
        </div>
    </div>
</body>
</html>
