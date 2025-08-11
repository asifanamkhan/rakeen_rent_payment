<div>
    <style>
        .clac {
            height: 35px !important
        }
    </style>
    <div wire:loading class="spinner-border text-primary custom-loading" branch="status">
        <span class="sr-only">Loading...</span>
    </div>

    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">Service Payments</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a wire:navigate style="">Service Payments</a></li>
            </ol>
        </nav>
    </div>

    <form id="confirmationForm" action="">
        <div class="card p-4">
            @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
            </div>
            @elseif (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
            </div>
            @elseif (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
            </div>
            @endif
            <div class="row">
                <div class="col-md-4" style="border-right: 1px solid rgb(240, 239, 239)">
                    <div class="">
                        <x-input required_mark='true' wire:model='state.payment_date' name='payment_date' type='date'
                            label='Payment Date' />
                    </div>
                    <div class="">
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
                        @error('product_id')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <x-input required_mark='' wire:model='state.money_receipt' name="money_receipt" type='number'
                            label='Money Receipt' />
                    </div>
                    <div class="mt-4 text-center">
                        <a style="background-color: #39A33C !important" wire:click='search_payment' class="btn btn-primary">
                            <i class="fas fa-search"></i> SEARCH
                        </a>
                    </div>
                </div>
                <div class="col-md-8">
                    <div id="payment_details">
                        <div class="responsive-table mt-4">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr class="bg-sidebar">
                                        <td style="">Bill Month</td>
                                        <td style="">Bill Amount</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($this->payment_details) > 0)
                                    <tr style="text-align: center">
                                        <td>Previous Balance</td>
                                        <td style="text-align: right">

                                                @if ($this->payment_details['opening'] < 0)
                                                <span class="text-danger">
                                                    (DUE)
                                                </span>
                                                @elseif ($this->payment_details['opening'] > 0)
                                                <span class="text-success">
                                                    (ADV) &nbsp;
                                                </span>
                                                @endif

                                            {{ number_format(abs($this->payment_details['opening']), 2) }}
                                        </td>
                                    </tr>

                                    @if (count($this->payment_details['monthly']) > 0)
                                    @foreach ($this->payment_details['monthly'] as $key => $value )
                                    <tr>
                                        <td style="text-align: center; background: #F8D7DA; border: 1px solid lightgray" >
                                            {{ Carbon\Carbon::parse($value->bill_month)->format('M, Y') }}
                                        </td>
                                        <td style="text-align: right">
                                            {{ number_format($value->tot_bill_amt, 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="text-align: center">Total: </th>
                                        <th style="text-align: right">
                                            {{ number_format($this->state['total_bill'], 2) }}</th>

                                    </tr>
                                    <tr>
                                        <th style="text-align: center">Payment amount: </th>
                                        <td>
                                            <input style="text-align: right" type="number" wire:blur='calculation'
                                                class="form-control clac" wire:model.lazy='state.service_payment_amount'>
                                                <span style="color:#fb6e7a">*** after input press tab or click outside for calculation</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center">Due: </th>
                                        <th style="text-align: right">
                                            {{ number_format($this->state['due'], 2) }}</th>

                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="mt-2 d-flex justify-content-center">
                        <button class="btn btn-primary">
                            <i class="fas fa-save"></i> Payemnt
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


</div>

</form>

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

    $('#product_id').on('change', function(e){
        @this.set('state.product_id', e.target.value, false);
    });

    document.getElementById('confirmationForm').addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent the form from submitting automatically

            // Show SweetAlert confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to submit the form?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, submit it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatch('save_form'); // Trigger the Livewire submit function
                }
            });
        });
</script>
@endscript
