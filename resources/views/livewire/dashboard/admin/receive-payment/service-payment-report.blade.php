<div>
    <div>
        <div wire:loading class="spinner-border text-primary custom-loading"></div>
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa-solid fa-table-list"></i> Bill Payments Report
        </h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="" style="color: #3C50E0">Bill Payments
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

                <div class="col-md-3">
                    <div class="form-group mb-2">
                        <label for="service_type">Bill type</label>
                        <select class="form-select" id="service_type" wire:model="service_type">
                            <option value="">All</option>
                            @foreach ($paymentTypes as $type)
                            <option value="{{ $type['name'] }}">{{ $type['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
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

        @if (count($this->payments) > 0)
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
                    <form target="_blank" action="{{ route('service-payment-report-pdf') }}" method="post">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product_id }}">
                        <input type="hidden" name="from_month" value="{{ $from_month }}">
                        <input type="hidden" name="to_month" value="{{ $to_month }}">
                        <input type="hidden" name="service_type" value="{{ $service_type }}">
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
                            <td>#</td>
                            <td>Bill month</td>
                            <td>Apartment</td>
                            <td>Customer</td>
                            <td>Bill Type</td>
                            <td>Receipt no</td>
                            <td style="text-align: right">Paid amount</td>
                            <td style="text-align: right">Payment date</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                            @foreach ($this->payments as $index => $row)
                        @php $total += (float) $row->paid_amount; @endphp
                        <tr>
                            <td>{{ $this->payments->firstItem() + $index }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->bill_month)->format('F-Y') }}</td>
                            <td>{{ $row->product_id }}</td>
                            <td>{{ $row->customer_name }} ({{ $row->customer_id }})</td>
                            <td>{{ $row->service_name }}</td>
                            <td>{{ $row->auto_receipt_no }}</td>
                            <td style="text-align: right">{{ number_format($row->paid_amount, 2, '.', ',') }}</td>
                            <td style="text-align: right">{{ date('d-M-y', strtotime($row->payment_date)) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" style="text-align: right">Total:</th>
                            <th style="text-align: right">{{ number_format($total, 2, '.', ',') }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
                <div>
                    {{ $this->payments->links() }}
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
