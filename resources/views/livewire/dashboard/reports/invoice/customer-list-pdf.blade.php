<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Customer List</title>
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
            <th class="invoice-items-head" style="width:25%">Customer</th>
            <th class="invoice-items-head" style="width:12%">ID</th>
            <th class="invoice-items-head" style="width:15%">Cell</th>
            <th class="invoice-items-head" style="width:18%">Email</th>
            <th class="invoice-items-head" style="width:10%">Booking</th>
            <th class="invoice-items-head" style="width:15%">Apartment</th>
        </tr>
        </thead>
        <tbody>
        @forelse($rows as $row)
            <tr style="page-break-inside: avoid">
                <td style="width:5%">{{ $sl++ }}</td>
                <td style="width:25%">{{ $row->customer_name }}</td>
                <td style="width:12%">{{ $row->customer_id }}</td>
                <td style="width:15%">{{ $row->cell_no }}</td>
                <td style="width:18%">{{ $row->email_id }}</td>
                <td style="width:10%">{{ $row->booking_id }}</td>
                <td style="width:15%">{{ $row->product_id }} ({{ $row->product_type }})</td>
            </tr>
        @empty
            <tr>
                <td colspan="7">No data found</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>

</html>


