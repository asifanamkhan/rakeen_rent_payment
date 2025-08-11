<div>
    <div wire:loading class="spinner-border text-primary custom-loading" branch="status">
        <span class="sr-only">Loading...</span>
    </div>

    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">Money Receipt Print</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a wire:navigate style="">Money Receipt Print</a></li>
            </ol>
        </nav>
    </div>

    <div class="card p-4">
        <!-- Search Filters -->
        <div class="row mb-4">
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
                <div class="form-group">
                    <label for="bill_month">Bill Month</label>
                    <input type="month" wire:model='bill_month' class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="payment_type">Payment Type</label>
                    <select wire:model='payment_type' class="form-select">
                        <option value="">All Types</option>
                        @foreach($paymentTypes as $type)
                        <option value="{{ $type['name'] }}">{{ $type['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <button style="margin-top: 20px" class="btn btn-primary" id='search' wire:click='search_payment'>
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </div>

        <!-- Filter Actions -->
        <div class="row mb-3">
            <div class="col-12">
                <button wire:click="resetFilters" class="btn btn-primary btn-sm">
                    <i class="fas fa-refresh"></i> Reset Filters
                </button>
                <div class="float-end">
                    <select class="form-select form-select-sm d-inline-block w-auto" wire:model.live='pagination'>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="responsive-table">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="bg-sidebar">
                        <td style="text-align: center">#</td>
                        <td style="text-align: center">Bill No</td>
                        <td style="text-align: center">Apartment ID</td>
                        <td style="text-align: center">Customer Name</td>
                        <td style="text-align: center">Payment Date</td>
                        <td style="text-align: center">Paid Amount</td>
                        <td style="text-align: center">Payment Type</td>
                        <td style="text-align: center">Actions</td>
                    </tr>
                </thead>
                <tbody>
                    @if (count($this->resultBills) > 0)
                    @foreach ($this->resultBills as $key => $data)
                    <tr wire:key='{{ $key }}'>
                        <td style="text-align: center">{{ $this->resultBills->firstItem() + $key }}</td>
                        <td style="text-align: center">{{ $data->auto_receipt_no ?? 'N/A' }}</td>
                        <td style="text-align: center">{{ $data->product_id ?? $data->product_id }}</td>
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
                            {{ isset($data->paid_amount) ? number_format($data->paid_amount, 2) : 'N/A' }}
                        </td>

                        <td style="text-align: center">
                            @if(isset($data->service_name))
                            @switch($data->service_name)
                            @case('SERVICE')
                            <span class="badge bg-success">Service</span>
                            @break
                            @case('ELECTRICITY')
                            <span class="badge bg-info">Electricity</span>
                            @break
                            @case('WATER')
                            <span class="badge bg-warning">Water</span>
                            @break
                            @default
                            <span class="badge bg-secondary">Unknown</span>
                            @endswitch
                            @else
                            <span class="badge bg-secondary">N/A</span>
                            @endif
                        </td>
                        <td style="text-align: center">
                            @if(isset($data->receipt_id))
                            <a target="_blank" class="btn btn-sm btn-primary"
                                href="{{ route('money-receipt-print-pdf', ['receipt_id' => $data->receipt_id, 'type' => $data->service_name]) }}">
                                <i class="fas fa-print"></i> Print
                            </a>
                            @else
                            <button class="btn btn-sm btn-secondary" disabled>
                                <i class="fas fa-print"></i> Print
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
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
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
