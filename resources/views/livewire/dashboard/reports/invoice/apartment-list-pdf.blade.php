<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Apartment List</title>
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
    @php($sl = 1)
</head>

<body>
    <br />
    <table class="invoice-items" cellspacing="0" cellpadding="4">
        <thead>
        <tr>
            <th class="invoice-items-head" style="width:5%">SL</th>
            <th class="invoice-items-head" style="width:18%">Project</th>
            <th class="invoice-items-head" style="width:8%">Tower</th>
            <th class="invoice-items-head" style="width:13%">Apartment</th>
            <th class="invoice-items-head" style="width:13%">Type</th>
            <th class="invoice-items-head" style="width:15%">Customer</th>
            <th class="invoice-items-head" style="width:14%">Booking</th>
            <th class="invoice-items-head" style="width:13%">Handover</th>
        </tr>
        </thead>
        <tbody>
        @forelse($rows as $row)
            <tr style="page-break-inside: avoid">
                <td style="width:5%">{{ $sl++ }}</td>
                <td style="width:18%">{{ $row->project_name }}</td>
                <td style="width:8%">{{ $row->tower_id }}</td>
                <td style="width:13%">{{ $row->product_id }}</td>
                <td style="width:13%">{{ $row->product_type }}</td>
                <td style="width:15%">{{ $row->customer_id }}</td>
                <td style="width:14%">{{ $row->booking_id }}</td>
                <td style="width:13%">{{ \Carbon\Carbon::parse($row->handover_date)->format('d-M-y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8">No data found</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>

</html>


