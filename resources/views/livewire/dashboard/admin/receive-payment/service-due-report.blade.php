<div>
    <div>
        <div wire:loading class="spinner-border text-primary custom-loading"></div>
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa-solid fa-table-list"></i> Service Due Report
        </h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="" style="color: #3C50E0">Service Due
                        Report</a></li>
            </ol>
        </nav>
    </div>
    <div class="card p-4">
        <form wire:submit="search_report">
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="form-group" wire:ignore>
                        <label for="">Select Apartment<span style="color: red"> * </span></label>
                        <select name="product_id" class="form-select select2" id='product_id'>
                            <option value="">Select </option>
                            @forelse ($products as $product)
                            <option value="{{ $product->product_id }}">
                                {{ $product->product_id }}
                                ({{ $product->product_type }})
                                => {{ $product->customer_id }}
                            </option>
                            @empty
                            <option value=""></option>
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <x-input required_mark='' wire:model='from_month' name='from_month' type='month'
                        label='From month' />
                </div>
                <div class="col-md-3">
                    <x-input required_mark='' wire:model='to_month' name='to_month' type='month' label='To month' />
                </div>
            </div>
            <div style="display: flex; justify-content: center; gap:10px">
                <div class=" ">
                    <button class="btn btn-primary">Search</button>
                </div>
                <div class=" ">
                    <button type="button" wire:click="resetFilters" class="btn btn-secondary">Reset</button>
                </div>
            </div>
        </form>
        @if (count($this->dues) > 0)
        <div>
            <div style="display: flex; justify-content: space-between" class="p-2">
                <div style="float: left">
                    <select class="form-select form-select-sm d-inline-block w-auto" wire:model.live='pagination'>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div style="float: right">
                    <form target="_blank" action="{{ route('service-due-report-pdf') }}" method="post">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product_id }}">
                        <input type="hidden" name="from_month" value="{{ $from_month }}">
                        <input type="hidden" name="to_month" value="{{ $to_month }}">
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
                            <td style="width: 5%">#</td>
                            <td style="width: 20%">Apartment</td>
                            <td style="width: 35%">Customer</td>
                            <td style="width: 15%">Bill Month - Amount</td>
                            <td style="width: 10%">Opening</td>
                            <td style="text-align: right;width: 10%">Total Due</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach ($this->dues as $index => $row)
                        @php $total += abs($row->paid_amount - $row->total_unpaid_amount); @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $row->product_id }} ({{ $row->product_type }})</td>
                            <td>{{ $row->customer_name }} ({{ $row->customer_id }})</td>
                            @php
                            $months = explode('|',$row->unpaid_months_with_amounts);

                            @endphp
                            <td>
                                @if (count($months) > 0)
                                @foreach ($months as $month)
                                {{ $month }}
                                <br>
                                @endforeach
                                @endif

                            </td>
                            <td style="text-align: right">{{ number_format(abs($row->paid_amount),2) }}</td>
                            <td style="text-align: right">{{ number_format(( abs($row->paid_amount -
                                $row->total_unpaid_amount) ), 2, '.', ',') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" style="text-align: right">Total:</th>
                            <th style="text-align: right">{{ number_format($total, 2, '.', ',') }}</th>
                        </tr>
                    </tfoot>
                </table>
                <div>
                    {{-- {{ $this->dues->links() }} --}}
                </div>
            </div>
        </div>
        @else
        <div></div>
        @endif
    </div>
</div>
<script>
    document.addEventListener('livewire:navigated', () => {
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap-5",
            });
        });
    });
    $('#product_id').on('change', function(e){
        @this.set('product_id', e.target.value, false);
    });
</script>
