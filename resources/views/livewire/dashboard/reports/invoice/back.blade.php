<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
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
        }

        .invoice-items th,
        .invoice-items td {
            border: 1px solid #ddd;
            padding: 4px 6px;
        }

        .invoice-items-head {
            background-color: #4CAF50;
            color: #fff;
            font-size: 10px;
            font-weight: bold
        }
    </style>
</head>

<body>

    <table cellspacing="0" cellpadding="2.5" style="width:100%">
        <thead>
            <tr>
                <td style="width: 10%;"><b>Filters: </b></td>
                <td style="width: 25%;">Apartment: {{ $filters['product_id'] ?: 'All' }}</td>
                <td style="width: 25%;">Service: {{ $filters['service_type'] ?: 'All' }}</td>
                <td style="width: 20%;">From: {{ $filters['from_month'] ?: '-' }}</td>
                <td style="width: 20%;">To: {{ $filters['to_month'] ?: '-' }}</td>
            </tr>
        </thead>
    </table>

    <br>

    <table cellspacing="0" cellpadding="2.5" style="width:100%" class="invoice-items">
        <thead class="">
            <tr style="text-align: center">
                <th class="invoice-items-head" style="width: 6%">#</th>
                <th class="invoice-items-head" style="width: 10%">Month</th>
                <th class="invoice-items-head" style="width: 13%">Apartment</th>
                <th class="invoice-items-head" style="width: 18%">Customer</th>
                <th class="invoice-items-head" style="width: 12%">Type</th>
                <th class="invoice-items-head" style="width: 15%">Receipt no</th>
                <th class="invoice-items-head" style="width: 14%">Paid amount</th>
                <th class="invoice-items-head" style="width: 14%">Payment date</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach ($rows as $index => $row)
            @php $total += (float) $row->paid_amount; @endphp
            <tr style="page-break-inside: avoid">
                <td style="width: 6%;text-align:center">{{ $index + 1 }}</td>
                <td style="width: 10%">{{ \Carbon\Carbon::parse($row->bill_month)->format('M,Y') }}</td>
                <td style="width: 13%">{{ $row->product_id }}</td>
                <td style="width: 18%">{{ $row->customer_name }} ({{ $row->customer_id }})</td>
                <td style="width: 12%">{{ $row->service_name }}</td>
                <td style="width: 15%">{{ $row->auto_receipt_no }}</td>
                <td style="width: 14%;text-align: right">{{ number_format($row->paid_amount, 2, '.', ',') }}</td>
                <td style="width: 14%; text-align: right">{{ date('d-M-y', strtotime($row->payment_date)) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" style="text-align: right"><b>Total:</b></th>
                <th style="text-align: right"><b>{{ number_format($total, 2, '.', ',') }}</b></th>
                <th></th>
            </tr>
        </tfoot>
    </table>

</body>

</html>
