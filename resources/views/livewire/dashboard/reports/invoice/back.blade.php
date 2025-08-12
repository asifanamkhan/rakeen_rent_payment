<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Service Due Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 9px;
            color: #333;
        }

        .invoice-items {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .invoice-items th,
        .invoice-items td {
            border: 1px solid #ddd;
            padding: 4px 6px;
            word-wrap: break-word;
            overflow: hidden;
            box-sizing: border-box;
        }

        .invoice-items-head {
            background-color: #4CAF50;
            color: #fff;
            font-size: 10px;
            font-weight: bold;
        }

        .filter-table {
            width: 100%;
            border-collapse: collapse;
        }

        .filter-table td {
            padding: 2.5px;
            border: none;
        }

        /* Add this to ensure consistent column widths */
        .col-1 { width: 7% !important; }
        .col-2 { width: 15% !important; }
        .col-3 { width: 28% !important; }
        .col-4 { width: 20% !important; }
        .col-5 { width: 15% !important; }
        .col-6 { width: 15% !important; }
    </style>
</head>

<body>
    <table class="filter-table">
        <tr>
            <td width="10%"><b>Filters: </b></td>
            <td width="30%">Apartment: {{ $filters['product_id'] ?: 'All' }}</td>
            <td width="30%">From: {{ $filters['from_month'] ?: '-' }}</td>
            <td width="30%">To: {{ $filters['to_month'] ?: '-' }}</td>
        </tr>
    </table>
    <br>

    <table class="invoice-items" cellpadding="2">
        <thead>
            <tr>
                <td class="invoice-items-head col-1">#</td>
                <td class="invoice-items-head col-2">Apartment</td>
                <td class="invoice-items-head col-3">Customer</td>
                <td class="invoice-items-head col-4">Bill Month - AMT</td>
                <td class="invoice-items-head col-5">Opening</td>
                <td class="invoice-items-head col-6">Total Due</td>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
                $total_op = 0;
            @endphp
            @foreach ($rows as $index => $row)
            @php
            $total += abs($row->paid_amount - $row->total_unpaid_amount);
            $total_op += abs($row->paid_amount);
            @endphp
            <tr nobr="true">
                <td class="col-1" align="center">{{ $index + 1 }}</td>
                <td class="col-2">{{ $row->product_id }} ({{ $row->product_type }})</td>
                <td class="col-3">{{ $row->customer_name }} ({{ $row->customer_id }})</td>
                <td class="col-4">
                    @php
                        $months = explode('|', $row->unpaid_months_with_amounts);
                    @endphp
                    @if (count($months) > 0)
                        @foreach ($months as $month)
                            {{ $month }}<br>
                        @endforeach
                    @endif
                </td>
                <td class="col-5" align="right">{{ number_format(abs($row->paid_amount), 1, '.', ',') }}</td>
                <td class="col-6" align="right">{{ number_format(abs($row->paid_amount - $row->total_unpaid_amount), 1, '.', ',') }}</td>
            </tr>
            @endforeach
            <tr nobr="true">
                <td colspan="4" align="center"><b>Total:</b></td>
                <td class="col-5" align="right"><b>{{ number_format($total_op, 1, '.', ',') }}</b></td>
                <td class="col-6" align="right"><b>{{ number_format($total, 1, '.', ',') }}</b></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
