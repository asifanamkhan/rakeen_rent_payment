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
        border: 0.5px solid #ddd;
    }

    .invoice-items-head {
        background-color: #4CAF50;
        color: #fff;
        font-size: 10px;
        font-weight: bold
    }

    .signature-table {
        width: 100%;
        border-collapse: collapse;
    }

    .signature-cell {
        width: 50%;
        text-align: center;
        padding: 10px;
        border: 0.5px solid #ccc;
        background-color: #fafafa;
    }

    .signature-area {
        height: 60px;
        border-bottom: 0.5px solid #333;
    }
    </style>
</head>

<body>

    <table cellspacing="0" cellpadding="2.5" style="">
        <thead>
            <tr style="">
                <td style="width: 20%;border-bottom: 1px solid #000;font-size: 11px; text-align:left;"><b>Received From:</b></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td style="width: 17%"><b>Apartment ID </b> </td>
                <td style="width: 54%">: {{ $bill->product_id }} ({{ $bill->product_type }})</td>
                <td style="width:13%"><b>Receipt no</b></td>
                <td style="width: 2%">:</td>
                <td style="width:16%; text-align: right"> {{ $bill->auto_receipt_no }}</td>
            </tr>
            <tr>
                <td style="width: 17%"><b>Customer </b> </td>
                <td style="width: 54%">: {{ $bill->customer_name }} ({{ $bill->customer_id }})</td>
                <td style="width: 13%;"><b>Date</b></td>
                <td style="width: 2%">:</td>
                <td style="width:16%; text-align: right"> {{ date('d-M-y', strtotime($bill->payment_date)) }}</td>

            </tr>
            <tr>
                <td style="width: 17%"><b>Phone</b> </td>
                <td style="width: 54%">: {{ $bill->cell_no }}</td>
                <td></td>
            </tr>
        </thead>

    </table>
    <br />
    <br />
    <table class="invoice-items" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th class="invoice-items-head" style="width: 40%; text-align: center">
                    Bill Month
                </th>
                <th class="invoice-items-head" style="width: 30%; text-align: center">
                    Bill Type
                </th>
                <th class="invoice-items-head" style="width: 30%; text-align: center">
                    Paid Amount
                </th>
            </tr>
        </thead>
        @if ($bill->service_name == 'SERVICE')
        <tbody>
                @php
                    $total = 0;
                    $paid_bill_month = DB::table('SRV_APARTMENT_BILL')
                        ->where('auto_receipt_no', $bill->receipt_id)
                        ->get();

                @endphp
                @if (count($paid_bill_month) > 0)
                @foreach ($paid_bill_month as $key => $data)
                    @php
                        $total += (float)$data->paid_amount;
                    @endphp
                    <tr>
                        <td style="width: 40%; text-align: center"> {{ \Carbon\Carbon::parse($data->bill_month)->format('F-Y') }}</td>
                        <td style="width: 30%; text-align: center">{{  $bill->service_name   }}</td>
                        <td style="width: 30%; text-align: right">{{ number_format($data->paid_amount, 2) }}</td>
                    </tr>
                @endforeach
                @endif
                @if ($bill->paid_amount > $total)
                @php
                    $rest = (float)$bill->paid_amount - $total;
                @endphp
                    <tr>
                        <td style="text-align: center">From Previous Deus</td>
                        <td style="text-align: center">-</td>
                        <td style="text-align: right">{{ number_format($rest, 2) }}</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" style="text-align: center"><b>Total: </b></th>

                    <th style="text-align: right"><b>{{ number_format($bill->paid_amount, 2) }}</b></th>
                </tr>
            </tfoot>
        @else
        <tbody>
            <tr>
                <td style="width: 40%; text-align: center">
                   {{ \Carbon\Carbon::parse($bill->bill_month)->format('F-Y') }}
                </td>
                <td style="width: 30%; text-align: center">
                    {{  $bill->service_name }}
                </td>
                <td style="width: 30%; text-align: right"><b>{{ number_format($bill->paid_amount, 2) }}</b></td>
            </tr>
        </tbody>
        @endif
    </table>
    <p style="font-weight: bold">
        @php
        $number_to_word = \App\Service\NumberToWords::numberToWords($bill->paid_amount);
        @endphp
        Taka In Words: {{ $number_to_word }} Only
    </p>

    <p style="color:white">..</p>
    <p style="color:white">..</p>
    <p style="color:white">..</p>


    <!-- Simple Signature Section -->
    <table class="signature-table" cellspacing="0" cellpadding="10">
        <tr>
            <td class="signature-cell">
                <div class="signature-area">
                    <div style="color:white">..</div>
                </div>
                <br>
                <b>Client Signature</b><br>
                <div>{{ $bill->customer_name }}</div>
            </td>
            <td class="signature-cell">
                <div class="signature-area">
                    <div style="color:white">..</div>
                </div>
                <br>
                <b>Authorized Signature</b><br>
                <div>Received By</div>
            </td>
        </tr>
    </table>

</body>

</html>
