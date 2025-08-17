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
                <th class="invoice-items-head"  style="width:15%">Apartment</th>
                <th class="invoice-items-head"  style="width:20%">Customer</th>
                <th class="invoice-items-head"  style="width:15%; text-align:center">Prev. Dues</th>
                <th class="invoice-items-head" style="width:30%; text-align:center">Bill Month-AMT</th>
                <th class="invoice-items-head" style="width:15%; text-align:center">Total Due</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
                $m_total = 0;
                $o_total = 0;
            @endphp
            @forelse ($rows as $row)
                @php
                    $total += $row->opening + $row->total_unpaid_amount;
                    $o_total += $row->opening;
                @endphp
                <tr style="page-break-inside: avoid">
                    <td style="width: 5%">{{ $loop->iteration }}</td>
                    <td style="width: 15%">{{ $row->product_id }} ({{ $row->product_type }})</td>
                    <td style="width: 20%">{{ $row->customer_name }} ({{ $row->customer_id }})</td>
                    <td style="width: 15%;text-align: right">
                        {{ number_format($row->opening, 1, '.', ',') }}
                    </td>
                    <td style="text-align: center; width: 30%">
                        @php
                            $m_t = 0;
                            $months = explode('|',$row->unpaid_months_with_amounts);
                        @endphp
                        @if (count($months) > 1)
                        <table style="width: 100%">
                            <tbody>
                                @foreach ($months as $month)
                                @php
                                    $month_t = explode('-',$month);
                                    $m_t += (float) $month_t[1];
                                    $m_total += (float) $month_t[1];
                                @endphp
                                <tr>
                                    <td>{{ $month_t[0] }}</td>
                                    <td style="text-align: right">{{ $month_t[1] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="border-top: 1px solid; font-weight: bold">
                                    <th>Total</th>
                                    <th style="text-align: right">{{ number_format($m_t, 0, '.', ',') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                        @else
                        <span class="badge bg-success">Cleared</span>
                        @endif

                    </td>
                    <td style="width: 15%;text-align: right">{{ number_format(abs($row->opening + $row->total_unpaid_amount), 1, '.', ',') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No data found</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="3" style="text-align: right"><b>Total:</b></td>
                <td style="text-align: right"><b>{{ number_format($o_total, 1, '.', ',') }}</b></td>
                <td style="text-align: right"><b>{{ number_format($m_total, 0, '.', ',') }}</b></td>
                <td style="text-align: right"><b>{{ number_format($total, 1, '.', ',') }}</b></td>
            </tr>
        </tbody>

    </table>
</body>

</html>

