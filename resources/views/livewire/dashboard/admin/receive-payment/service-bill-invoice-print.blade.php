<div>
    <div wire:loading class="spinner-border text-primary custom-loading" branch="status">
        <span class="sr-only">Loading...</span>
    </div>

    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">Service Bill Invoice Print</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a wire:navigate style="">Service Bill Invoice Print</a></li>
            </ol>
        </nav>
    </div>

    <div class="card p-4">
        <!-- Search Filters -->
        <div class="row mb-4">
            <div class="form-group col-auto" style="margin-top: 20px">
                <select class="form-select" wire:model.live='pagination'>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="col-md-3" style="margin-top: 20px">
                <div class="form-group" wire:ignore>
                    <select name="product_id" class="form-select select2" id='product_id'>
                        <option value="">All Apartment</option>
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
                <div class="form-group">
                    <label for="bill_month">Bill Month</label>
                    <input type="month" wire:model='bill_month' class="form-control">
                </div>
            </div>

            <div class="col-auto">
                <button style="margin-top: 20px" class="btn btn-primary" id='search' wire:click='search_bills'>
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
            <div class="col-auto">
                <button wire:click="resetFilters" style="margin-top: 20px" class="btn btn-warning btn">
                    <i class="fas fa-refresh"></i> Reset Filters
                </button>

            </div>
        </div>

        <!-- Results Table -->
        <div class="responsive-table">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="bg-sidebar">
                        <td style="text-align: center; width: 5%">#</td>
                        <td style="text-align: center; width: 10%">Bill No</td>
                        <td style="text-align: center; width: 20%">Apartment ID</td>
                        <td style="text-align: center; width: 30%">Customer Name</td>
                        <td style="text-align: center; width: 10%">Bill Month</td>
                        <td style="text-align: center; width: 10%">Bill Amount</td>
                        <td style="text-align: center; width: 10%">Paid Amount</td>
                        <td style="text-align: center; width: 5%">Actions</td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $bill_amount = 0;
                        $paid_amount = 0;
                    @endphp
                    @if (count($this->resultBills) > 0)
                    @foreach ($this->resultBills as $key => $data)
                    <tr wire:key='{{ $key }}'>
                        <td style="text-align: center">{{ $this->resultBills->firstItem() + $key }}</td>
                        <td style="text-align: center">{{ $data->auto_bill_no ?? 'N/A' }}</td>
                        <td style="text-align: center">
                            {{ $data->product_id ?? $data->product_id }}
                            <span class="text-muted">({{ $data->product_type }})</span>
                        </td>
                        <td>
                            {{ $data->customer_name ?? 'N/A' }}
                            @if(isset($data->customer_id))
                            <span class="text-muted">({{ $data->customer_id }})</span>
                            @endif
                        </td>
                        <td style="text-align: center">
                            @if(isset($data->bill_month))
                            {{ \Carbon\Carbon::parse($data->bill_month)->format('M, Y') }}
                            @else
                            N/A
                            @endif
                        </td>

                        <td style="text-align: right">
                            {{ isset($data->tot_bill_amt) ? number_format($data->tot_bill_amt, 2) : 'N/A' }}
                        </td>

                        <td style="text-align: right">
                            {{ isset($data->paid_amount) ? number_format($data->paid_amount, 2) : 'N/A' }}
                        </td>
                        <td style="text-align: center">
                            @if(isset($data->bill_id))
                            <a target="_blank" class="btn btn-sm btn-success"
                                href="{{ route('service-bill-invoice', ['bill_id' => $data->bill_id]) }}">
                                <i class="fas fa-print"></i>
                            </a>
                            @else
                            <button class="btn btn-sm btn-secondary" disabled>
                                <i class="fas fa-print"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="10" class="text-center">No bills found matching the criteria.</td>
                    </tr>
                    @endif
                </tbody>
                <tfoot>
                    @php
                        $bill_amount = $this->resultBills->sum('tot_bill_amt');
                        $paid_amount = $this->resultBills->sum('paid_amount');
                    @endphp
                    <tr>
                        <th colspan="5" style="text-align: right">Total</th>
                        <th style="text-align: right">{{ number_format($bill_amount, 2) }}</th>
                        <th style="text-align: right">{{ number_format($paid_amount, 2) }}</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center" style="gap:2px">
            {{ $this->resultBills->links() }}
        </div>
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
