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
        /* position: absolute; */
    }

    .invoice-items th,
    .invoice-items td {
        border: 1px solid #ddd;
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
    <table cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <td style="font-size: 10px; text-align:left;"><b>Bill To:</b></td>
                <td style="text-align: right; font-weight:bold; font-size: 10px">Invoice no: {{ $bill->auto_bill_no }}
                </td>
            </tr>

            <tr>
                <td>{{ $bill->customer_name }}</td>
                <td style="text-align: right; font-weight:bold; font-size: 10px">Invoice date:
                    {{ date('d-M-y', strtotime($bill->payment_date)) }}</td>
            </tr>
            <tr>
                <td>{{ $bill->add_present }}</td>
                <td></td>
            </tr>
            <tr>
                <td>Phone: {{ $bill->cell_no }}</td>
                <td></td>
            </tr>
        </thead>

    </table>
    <br />
    <br />
    <table class="invoice-items" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th class="invoice-items-head" style="width: 60%">Particulars</th>
                <th class="invoice-items-head" style="width: 25%">Amount</th>
            </tr>
        </thead>


    </table>
</body>

</html>
