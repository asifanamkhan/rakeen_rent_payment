<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product list</title>
    <style>
        body{
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 9px;
            color: #333;
        }
        .invoice-items {
            width: 100%;
            border-collapse: collapse;
            position: absolute;
        }

        .invoice-items th,
        .invoice-items td {
            border: 1px solid #ddd;
        }

        .invoice-items-head {
            background-color: #4CAF50;
            color: #fff;
        }
    </style>
</head>

<body>
    <br />
    <table class="invoice-items" cellspacing="0" cellpadding="4">
        <thead >
            <tr >
                <th class="invoice-items-head"  style="width:5%">SL</th>
                <th class="invoice-items-head"  style="width:20%">Apartment</th>
                <th class="invoice-items-head"  style="width:30%">Customer</th>
                <th class="invoice-items-head"  style="width:15%">Prev. Due</th>
                <th class="invoice-items-head" style="width:15%">Bill Amount</th>
                <th class="invoice-items-head" style="width:15%">Total Due</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
                $total_op = 0;
            @endphp
            @forelse ($rows as $row)
                @php
                    $total += ($row->opening + $row->total_unpaid_amount);
                    $total_op += abs($row->opening);
                @endphp
                <tr style="page-break-inside: avoid">
                    <td style="width: 5%">{{ $loop->iteration }}</td>
                    <td style="width: 20%">{{ $row->product_id }} ({{ $row->product_type }})</td>
                    <td style="width: 30%">{{ $row->customer_name }} ({{ $row->customer_id }})</td>
                    <td style="width: 15%;text-align: right">
                        {{ number_format(abs($row->opening), 1, '.', ',') }}
                    </td>
                    <td style="width: 15%;text-align: right">
                        {{ number_format($row->total_unpaid_amount, 1, '.', ',') }}</td>
                    <td style="width: 15%;text-align: right">{{ number_format(($row->opening + $row->total_unpaid_amount), 1, '.', ',') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No data found</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="4" style="text-align: right"><b>Total:</b></td>
                <td style="text-align: right"><b>{{ number_format($total_op, 1, '.', ',') }}</b></td>
                <td style="text-align: right"><b>{{ number_format($total, 1, '.', ',') }}</b></td>
            </tr>
        </tbody>

    </table>
</body>

</html>

