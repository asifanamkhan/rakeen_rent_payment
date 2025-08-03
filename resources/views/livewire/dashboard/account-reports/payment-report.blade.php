<div>
    <div>
        <div wire:loading class="spinner-border text-primary custom-loading"></div>
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa-solid fa-cart-shopping"></i> Payments report
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
                <div class="col-md-4">
                    <div class="form-group mb-2" wire:ignore>
                        <label for="">Branch</label>
                        <select class="form-select select2" id='branch'>
                            <option value="">All</option>
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

                <div class="col-md-4">
                    <div class="form-group mb-2" wire:ignore>
                        <label for="">Tran Type</label>
                        <select class="form-select select2" id='tran_type'>
                            <option value="">All</option>
                            @forelse ($trancastionType as $key => $catagory)
                            <option wire:key='{{ $loop->iteration }}' value="{{ $key }}">
                                {{ $catagory }}
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

                <div class="col-md-4">
                    <div class="form-group mb-3" wire:ignore>
                        <label for="">Cash Type</label>
                        <select class="form-select select2"  id='cash_type'>
                            <option value="">All</option>
                            <option value=""></option>
                            <option value="IN">Cash In</option>
                            <option value="OUT">Cash Out</option>
                        </select>
                    </div>
                    @error('st_group_item_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-2">
                        <label for="">Payment method<span style="color: red"> *
                            </span></label>
                        <select class="form-select" wire:model='state.pay_mode' id='pay_mode'>
                            <option value="">All </option>
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

                <div class="col-md-3 ">
                    <x-input required_mark='' wire:model='state.start_date' name='start_date' type='date'
                        label='Start Date' />
                </div>
                <div class="col-md-3 ">
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
                    <form target="_blank" action="{{route('account-payments-pdf')}}" method="post">
                        @csrf
                        <input type="hidden" name="start_date" value="{{ $state['start_date'] }}">
                        <input type="hidden" name="end_date" value="{{ $state['end_date'] }}">
                        <input type="hidden" name="branch_id" value="{{ $state['branch_id'] }}">
                        <input type="hidden" name="tran_type" value="{{ $state['tran_type'] }}">
                        <input type="hidden" name="cash_type" value="{{ $state['cash_type'] }}">
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
                            <td style="text-align: center">Cash type</td>
                            <td style="text-align: center">Pay mode</td>
                            <td style="text-align: center">Amount</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $t_cashIn = 0;
                        $t_cashOut = 0;
                        @endphp
                        @forelse ($ledgers as $key => $ledger)
                        <tr wire:key='{{ $key }}'>

                            <td>{{ $key+1 }}</td>
                            <td>{{ date('d-M-y', strtotime($ledger->voucher_date)) }}</td>
                            <td>{{ $ledger->ref_memo_no }}</td>
                            <td>{{ $ledger->branch_name }}</td>
                            <td>{{ App\Service\Accounts::tranTypeCheck($ledger->tran_type) }}</td>
                            <td>
                                @php
                                if($ledger->cash_type == 'IN'){
                                $t_cashIn += (float)$ledger->amount;
                                }else{
                                $t_cashOut += (float)$ledger->amount;
                                }
                                @endphp
                                @if ($ledger->cash_type == 'IN')
                                Cash In
                                @else
                                Cash Out
                                @endif

                            </td>
                            <td>{{ $ledger->p_mode_name }}</td>
                            <td style="text-align: right">{{ number_format($ledger->amount, 2, '.', ',') }}</td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">No data found</td>
                        </tr>
                        @endforelse

                    </tbody>
                    @if ($state['cash_type'] == 'IN' || $state['tran_type'] == 'PRT' || $state['tran_type'] == 'SL')
                    <tfoot>
                        <tr>
                            <th colspan="7" style="text-align: right">Total: </th>
                            <th style="text-align: right">{{ number_format($t_cashIn, 2, '.', ',') }}</th>
                        </tr>
                    </tfoot>
                    @elseif ($state['cash_type'] == 'OUT' || $state['tran_type'] == 'PR' || $state['tran_type'] == 'SRT' || $state['tran_type'] == 'EXP')
                    <tfoot>
                        <tr>
                            <th colspan="7" style="text-align: right">Total: </th>
                            <th style="text-align: right">{{ number_format($t_cashOut, 2, '.', ',') }}</th>
                        </tr>
                    </tfoot>
                    @elseif (!$state['cash_type'] && !$state['tran_type'])
                    <tfoot>
                        <tr>
                            <th colspan="7"></th>
                        </tr>
                        <tr>
                            <tr>
                                <th colspan="7" style="text-align: right">Total Cash In: </th>
                                <th style="text-align: right">{{ number_format($t_cashIn, 2, '.', ',') }}</th>
                            </tr>
                            <tr>
                                <th colspan="7" style="text-align: right">Total Cash Out: </th>
                                <th style="text-align: right">{{ number_format($t_cashOut, 2, '.', ',') }}</th>
                            </tr>
                            <tr>
                                <th colspan="7" style="text-align: right">Net balance: </th>
                                <th style="text-align: right">{{ number_format(($t_cashIn - $t_cashOut), 2, '.', ',') }}</th>
                            </tr>
                        </tr>
                    </tfoot>

                    @endif


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

    $('#cash_type').on('change', function(e){
        @this.set('state.cash_type', e.target.value, false);
    });
    $('#tran_type').on('change', function(e){
        @this.set('state.tran_type', e.target.value, false);
    });


</script>
@endscript
