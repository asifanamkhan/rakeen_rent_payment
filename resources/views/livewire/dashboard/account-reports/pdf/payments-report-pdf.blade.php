<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product list</title>
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
        <thead>
            <tr>
                <td class="invoice-items-head" style="width: 5%; ">#</td>
                <td class="invoice-items-head" style="width: 10%;"> Date</td>
                <td class="invoice-items-head" style="width: 15%; ">Memo no</td>
                <td class="invoice-items-head" style="width: 15%; ">Branch</td>
                <td class="invoice-items-head" style="width: 20%; text-align: center">Tran Type</td>
                <td class="invoice-items-head" style="width: 8%; text-align: center">Cash</td>
                <td class="invoice-items-head" style="width: 10%; text-align: center">Pay mode</td>
                <td class="invoice-items-head" style="width: 17%; text-align: center">Amount</td>
            </tr>
        </thead>
        <tbody>
            @php
            $t_cashIn = 0;
            $t_cashOut = 0;
            @endphp
            @forelse ($ledgers as $key => $ledger)
            <tr wire:key='{{ $key }}'>

                <td style="width:5%">{{ $key+1 }}</td>
                <td style="width:10%">{{ date('d-M-y', strtotime($ledger->voucher_date)) }}</td>
                <td style="width:15%">{{ $ledger->ref_memo_no }}</td>
                <td style="width:15%">{{ $ledger->branch_name }}</td>
                <td style="width:20%">{{ App\Service\Accounts::tranTypeCheck($ledger->tran_type) }}</td>
                <td style="width: 8%">
                    @php
                    if($ledger->cash_type == 'IN'){
                    $t_cashIn += (float)$ledger->amount;
                    }else{
                    $t_cashOut += (float)$ledger->amount;
                    }
                    @endphp
                    {{ $ledger->cash_type }}

                </td>
                <td style="width: 10%">{{ $ledger->p_mode_name }}</td>
                <td style="text-align: right; width: 17%">{{ number_format($ledger->amount, 2, '.', ',') }}</td>

            </tr>
            @empty
            <tr>
                <td colspan="7">No data found</td>
            </tr>
            @endforelse

           <tfoot style="font-weight: bold">
            @if ($state['cash_type'] == 'IN' || $state['tran_type'] == 'PRT' || $state['tran_type'] == 'SL')

            <tr>
                <th colspan="7" style="text-align: right;font-weight: bold">Total: </th>
                <th style="text-align: right;font-weight: bold">{{ number_format($t_cashIn, 2, '.', ',') }}</th>
            </tr>

            @elseif ($state['cash_type'] == 'OUT' || $state['tran_type'] == 'PR' || $state['tran_type'] == 'SRT' || $state['tran_type'] == 'EXP')

            <tr>
                <th colspan="7" style="text-align: right;font-weight: bold">Total: </th>
                <th style="text-align: right;font-weight: bold">{{ number_format($t_cashOut, 2, '.', ',') }}</th>
            </tr>

            @elseif (!$state['cash_type'] && !$state['tran_type'])

            <tr style="border: none">
                <th style="border: none" colspan="8"></th>
            </tr>
            <tr>
                <th colspan="7" style="text-align: right;font-weight: bold">Total Cash In: </th>
                <th style="text-align: right;font-weight: bold">{{ number_format($t_cashIn, 2, '.', ',') }}</th>
            </tr>
            <tr>
                <th colspan="7" style="text-align: right;font-weight: bold">Total Cash Out: </th>
                <th style="text-align: right;font-weight: bold">{{ number_format($t_cashOut, 2, '.', ',') }}</th>
            </tr>
            <tr>
                <th colspan="7" style="text-align: right;font-weight: bold">Net balance: </th>
                <th style="text-align: right;font-weight: bold">{{ number_format(($t_cashIn - $t_cashOut), 2, '.', ',') }}</th>
            </tr>
            @endif
           </tfoot>


        </tbody>


    </table>
</body>

</html>
