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

    <table cellspacing="0" cellpadding="2.5" style="">
        <thead>
            <tr style="">
                <td style="width: 18%;border-bottom: 1px solid #000;font-size: 11px; text-align:left;"><b>To be paid
                        By:</b></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td style="width: 17%"><b>Apartment ID </b> </td>
                <td style="width: 54%">: {{ $bill->product_id }} ({{ $bill->product_type }})</td>
                <td style="width:13%"><b>Bill no</b></td>
                <td style="width: 2%">:</td>
                <td style="width:16%; text-align: right"> {{ $bill->auto_bill_no }}</td>
            </tr>
            <tr>
                <td style="width: 17%"><b>Customer Name </b> </td>
                <td style="width: 54%">: {{ $bill->customer_name }} ({{ $bill->customer_id }})</td>
                <td style="width:13%"><b>Bill month</b></td>
                <td style="width: 2%">:</td>
                <td style="width:16%; text-align: right"> {{ \Carbon\Carbon::parse($bill->bill_month)->format('F-Y') }}
                </td>

            </tr>
            <tr>
                <td style="width: 17%"><b>Email </b></td>
                <td style="width: 54%">: {{ $bill->email_id }}</td>

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
                <th class="invoice-items-head" style="width: 60%; text-align: center">
                    Particulars
                </th>
                <th class="invoice-items-head" style="width: 40%; text-align: center">
                    Bill Amount
                </th>

            </tr>
        </thead>

        <tbody>
            @php
            $total = 0;
            $total_paid = 0;
            $product_id = DB::table('VW_SRV_APARTMENT_BILL_INFO')
            ->where('bill_id', $bill->bill_id)
            ->first()
            ->product_id;

            $unpaid_bill_month = DB::table('SRV_APARTMENT_BILL')
            ->where('apartment_id', $product_id)
            ->where('status', 'UNPAID')
            ->where('bill_month','<=', $bill->bill_month)
                ->get();

                $opening = DB::table('SRV_PAYMENT_RECEIPT')
                ->where('apartment_id', $product_id)
                ->where('status', 'OP')
                ->sum('paid_amount');

                @endphp
                <tr>
                    <td style="width: 60%; text-align: center">
                        Previous (Dues):
                    </td>
                    <td style="width: 40%;text-align: right">
                        {{ number_format($opening, 2) }}
                    </td>
                </tr>
                @if (count($unpaid_bill_month) > 0)
                @foreach ($unpaid_bill_month as $key => $data)
                @php
                $total += (float)$data->tot_bill_amt;
                $total_paid += (float)$data->paid_amount;
                @endphp
                <tr>
                    <td style="width: 60%; text-align: center"> {{
                        \Carbon\Carbon::parse($data->bill_month)->format('F-Y') }}</td>
                    <td style="width: 40%; text-align: right">{{ number_format($data->tot_bill_amt, 2) }}</td>
                </tr>
                @endforeach
                @endif

        </tbody>
        <tfoot>
            <tr>
                <th style="text-align: center"><b>Paid Amount</b></th>
                <th style="text-align: right"><b>{{ number_format($total_paid, 2) }}</b></th>
            </tr>
            <tr>
                <th style="text-align: center"><b>Total Amount to be Paid</b></th>
                @php
                $dues = ($opening + $total) - $total_paid;
                @endphp
                <th style="text-align: right; color: red"><b>{{ number_format($dues, 2) }}</b></th>
            </tr>
        </tfoot>
    </table>
    <p style="font-weight: bold">
        @php
        $number_to_word = \App\Service\NumberToWords::numberToWords($dues);
        @endphp
        Taka In Words: {{ $number_to_word }} Only
    </p>
    <p>
    <div>
        <b>Remarks:</b>
    </div>
    </p>
    <p>
    <div style="margin-top: 25px">
        <span>For Rakeen Development Company (BD) Ltd.</span>
    </div>
    </p>
</body>

</html>
