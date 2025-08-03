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
                {{-- <td class="invoice-items-head" style="width: 4%; ">#</td> --}}
                <td class="invoice-items-head" style="width: 9%;"> Date</td>
                <td class="invoice-items-head" style="width: 12%; ">Memo no</td>
                <td class="invoice-items-head" style="width: 10%; ">Branch</td>
                <td class="invoice-items-head" style="width: 12%; text-align: center">Tran Type</td>
                <td class="invoice-items-head" style="width: 8%; text-align: center">P mode</td>
                <td class="invoice-items-head" style="width: 15%; text-align: center">Debit</td>
                <td class="invoice-items-head" style="width: 15%; text-align: center">Credit</td>
                <td class="invoice-items-head" style="width: 20%; text-align: center">Balance</td>
            </tr>
        </thead>
        <tbody>
            @php
            $t_cashIn = 0;
            $t_cashOut = 0;
            $balance = 0;
            @endphp
            @forelse ($ledgers as $key => $ledger)
            <tr wire:key='{{ $key }}'>

                {{-- <td style="width:4%">{{ $key+1 }}</td> --}}
                <td style="width:9%">{{ date('d/m/y', strtotime($ledger->voucher_date)) }}</td>
                <td style="width:12%">{{ $ledger->ref_memo_no }}</td>
                <td style="width:10%">{{ $ledger->branch_name }}</td>
                <td style="width:12%">{{ App\Service\Accounts::tranTypeCheck($ledger->tran_type) }}</td>
                <td style="width: 8%">{{ $ledger->p_mode_name }}</td>
                @php
                if($ledger->voucher_type == 'DR'){
                $balance += (float)$ledger->amount;
                $t_cashIn += (float)$ledger->amount;
                }else{
                $balance -= (float)$ledger->amount;
                $t_cashOut += (float)$ledger->amount;
                }
                @endphp
                <td style="text-align: right;width: 15%">
                    @if ($ledger->voucher_type == 'DR')
                    {{ number_format($ledger->amount, 2, '.', ',') }}
                    @endif</td>

                <td style="text-align: right;width: 15%">
                    @if ($ledger->voucher_type == 'CR')
                    {{ number_format($ledger->amount, 2, '.', ',') }}
                    @endif</td>
                @php
                $dr_cr = '';
                if($balance < 0){ $dr_cr='CR' ; } else{ $dr_cr='DR' ; } @endphp <td
                    style="text-align: right; width: 20%">{{ number_format( abs($balance), 2, '.', ',') }} {{ $dr_cr }}
                    </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">No data found</td>
            </tr>
            @endforelse
        <tfoot>

            <tr>
                <th colspan="5" style="text-align: right">Total </th>
                <th style="text-align: right">{{ number_format($t_cashIn, 2, '.', ',') }}</th>
                <th style="text-align: right">{{ number_format($t_cashOut, 2, '.', ',') }}</th>
                @php
                $dr_cr = '';
                if($balance < 0){ $dr_cr='CR' ; } else{ $dr_cr='DR' ; } @endphp <th style="text-align: right">{{
                    number_format( abs($balance), 2, '.', ',') }} {{ $dr_cr }}</th>
            </tr>
        </tfoot>
        </tbody>


    </table>
</body>

</html>
