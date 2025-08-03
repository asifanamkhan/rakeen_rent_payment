<div>
    <div>
        <div wire:loading class="spinner-border text-primary custom-loading"></div>
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa-solid fa-cart-shopping"></i> Transaction Statement
        </h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="" style="color: #3C50E0">Payments
                        report</a></li>
            </ol>
        </nav>
    </div>
    <div class="card p-4">
        <form action="" wire:submit='search'>
            <div class="row g-3 mb-3 align-items-center">
                <div class="col-md-3">
                    <div class="form-group mb-2" wire:ignore>
                        <label for="">Branch</label>
                        <select class="form-select select2" id='branch'>
                            <option value="">Select branch</option>
                            @forelse ($branchs as $branch)
                            <option wire:key='{{ $branch->branch_id }}' value="{{ $branch->branch_id }}">
                                {{ $branch->branch_name }}
                            </option>
                            @empty
                            <option value=""></option>
                            @endforelse
                        </select>
                    </div>
                    @error('st_group_item_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-3">
                    <div class="form-group mb-2">
                        <label for="">Payment method<span style="color: red"> *
                            </span></label>
                        <select class="form-select" wire:model='state.pay_mode'>
                            <option value="">Select</option>
                            @forelse ($payment_methods as $method)
                            <option value="{{ $method->p_mode_id }}">{{ $method->p_mode_name }}
                            </option>
                            @empty
                            <option value=""></option>
                            @endforelse
                        </select>
                        @error('pay_mode')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-2 ">
                    <x-input required_mark='' wire:model='state.start_date' name='start_date' type='date'
                        label='Start Date' />
                </div>
                <div class="col-md-2 ">
                    <x-input required_mark='' wire:model='state.end_date' name='end_date' type='date'
                        label='End Date' />
                </div>

                <div class="col-md-2 ">
                    <button class="btn btn-primary" id='search'>Search</button>
                </div>
            </div>
        </form>
        @if (count($ledgers) > 0)
        <div>
            <div style="display: flex; justify-content: space-between" class="p-2">
                <div></div>
                <div style="float: right">
                    <form target="_blank" action="{{route('account-transaction-pdf')}}" method="post">
                        @csrf
                        <input type="hidden" name="start_date" value="{{ $state['start_date'] }}">
                        <input type="hidden" name="end_date" value="{{ $state['end_date'] }}">
                        <input type="hidden" name="branch_id" value="{{ $state['branch_id'] }}">
                        <button class="btn btn-sm btn-success">
                            <i class="fa-solid fa-file-pdf"></i> Generate PDF
                        </button>
                    </form>

                </div>
            </div>
            <div class="responsive-table" style="font-size: 0.9em !important;">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="bg-sidebar">
                            <td style="">#</td>
                            <td>Date</td>
                            <td style="">Memo no</td>
                            <td style="">Branch</td>
                            <td style="text-align: center">Tran Type</td>
                            <td style="text-align: center">Pay mode</td>
                            <td style="text-align: center">Cash In</td>
                            <td style="text-align: center">Cash Out</td>
                            <td style="text-align: center">Balance</td>
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

                            <td>{{ $key+1 }}</td>
                            <td>{{ date('d-M-y', strtotime($ledger->voucher_date)) }}</td>
                            <td>{{ $ledger->ref_memo_no }}</td>
                            <td>{{ $ledger->branch_name }}</td>
                            <td>{{ App\Service\Accounts::tranTypeCheck($ledger->tran_type) }}</td>
                            <td>{{ $ledger->p_mode_name }}</td>
                            <td style="text-align: right">
                                @php
                                if($ledger->cash_type == 'IN'){
                                    $balance += (float)$ledger->amount;
                                    $t_cashIn += (float)$ledger->amount;
                                }else{
                                    $balance -= (float)$ledger->amount;
                                    $t_cashOut += (float)$ledger->amount;
                                }
                                @endphp
                                @if ($ledger->cash_type == 'IN')
                                {{ number_format($ledger->amount, 2, '.', ',') }}
                                @endif

                            </td>
                            <td style="text-align: right">
                                @if ($ledger->cash_type == 'OUT')
                                {{ number_format($ledger->amount, 2, '.', ',') }}
                                @endif
                            </td>

                            <td style="text-align: right">{{ number_format($balance, 2, '.', ',') }}</td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">No data found</td>
                        </tr>
                        @endforelse

                    </tbody>

                    <tfoot>
                        <tr>
                            <tr>
                                <th colspan="6" style="text-align: right">Total  </th>
                                <th style="text-align: right">{{ number_format($t_cashIn, 2, '.', ',') }}</th>
                                <th style="text-align: right">{{ number_format($t_cashOut, 2, '.', ',') }}</th>
                                <th style="text-align: right">{{ number_format($balance, 2, '.', ',') }}</th>
                            </tr>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @else
        <div class="">
            {{-- <h4 style="text-align: center">No data found</h4> --}}
        </div>
        @endif

    </div>
</div>

@script
<script data-navigate-once>
    document.addEventListener('livewire:navigated', () => {
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap-5",
            });
        });
    });


</script>
@endscript
