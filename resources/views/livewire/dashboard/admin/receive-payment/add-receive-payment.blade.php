<div>
    <style>
        .clac{
            height: 35px !important
        }
    </style>
    <div wire:loading class="spinner-border text-primary custom-loading" branch="status">
        <span class="sr-only">Loading...</span>
    </div>

    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">Receive Payments</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a wire:navigate style="">Receive Payments</a></li>
            </ol>
        </nav>
    </div>

    <form id="confirmationForm" action="">
        <div class="card p-4">
            @if (session('success'))
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
                <div class="col-md-3" style="border-right: 1px solid rgb(240, 239, 239)">
                    <div class="">
                        <x-input readonly required_mark='true'  wire:model='state.payment_date' name='payment_date' type='text' label='Payment Date' />
                    </div>
                    <div class="">
                        <x-input readonly required_mark='true'  wire:model='state.bill_month' name='bill_month' type='text' label='Bill Month' />
                    </div>
                    <div class="mb-3">
                        <div class="form-group" wire:ignore>
                            <label for="">Select Apartment<span style="color: red"> * </span></label>
                            <select name="product_id" class="form-select select2" id='product_id'>
                                <option value="">Select </option>
                                @forelse ($products as $product)
                                <option
                                    value="{{ $product->product_id }}">
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
                    <div class="form-group mt-4">
                        <label for="">Select Bill Type<span style="color: red"> * </span></label>
                        <select name="" wire:model='state.bill_type' id="bill_type" class="form-select">
                            <option value="">Select Type</option>
                            <option value="1">Service Bill</option>
                            <option value="2">Electricity Bill</option>
                            <option value="3">Water Bill</option>
                        </select>
                        @error('bill_type')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mt-4 text-center">
                        <a wire:click='search_payment' class="btn btn-primary">
                          <i class="fas fa-plus"></i>  ADD
                        </a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div id="payment_details">
                        <div class="responsive-table">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr class="bg-sidebar">
                                        <td  style="width: 15%">Bill Type</td>
                                        <td  style="width: 20%">Opening Bal.</td>
                                        <td  style="width: 15%">Current Month</td>
                                        <td  style="width: 15%">Total Bill</td>
                                        <td  style="width: 20%">Payment Amt</td>
                                        <td  style="width: 10%">Due</td>
                                        <td  style="width: 5%">Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @dump($this->payment_details) --}}
                                    @if (count($this->payment_details) > 0)
                                        @foreach ($this->payment_details as $key => $value  )
                                            @if ($key == 'service' && count($value) > 1)
                                                <tr>
                                                    <td>{{ $key }}</td>
                                                    <td style="text-align: center">
                                                        @if ($value['opening'] < 0)
                                                            <span class="text-danger">
                                                                (DUE)
                                                            </span>
                                                        @elseif ($value['opening'] > 0)
                                                            <span class="text-success">
                                                                (ADV) &nbsp;
                                                            </span>
                                                        @endif
                                                        {{ number_format(abs($value['opening']), 2)   }}

                                                    </td>
                                                    <td style="text-align: center">{{ number_format($value['monthly'], 2) }}</td>
                                                    <td style="text-align: center">{{ number_format($value['total'], 2) }}</td>
                                                    <td>
                                                        <input type="number" wire:input.debounce.500ms='calculation' class="form-control clac" wire:model.lazy='state.service_payment_amount'>
                                                    </td>
                                                    <td style="text-align: center">{{ number_format($value['due'], 2) }}</td>
                                                    <td style="text-align: center; color:red; font-size:18px">
                                                        <i wire:click='remove_bill("{{ $key }}")' style="cursor:pointer" class="fas fa-trash"></i>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($key == 'electricity' )
                                                <tr>
                                                    <td>{{ $key }}</td>
                                                    <td colspan="3" style="text-align: center"></td>
                                                    <td>
                                                        <input type="number" wire:input.debounce.500ms='calculation' class="form-control clac" wire:model.lazy='state.electricity_payment_amount'>
                                                    </td>
                                                    <td style="text-align: center"></td>
                                                    <td style="text-align: center; color:red; font-size:18px">
                                                        <i wire:click='remove_bill("{{ $key }}")' style="cursor:pointer" class="fas fa-trash"></i>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($key == 'water' )
                                                <tr>
                                                    <td>{{ $key }}</td>
                                                    <td colspan="3" style="text-align: center"></td>

                                                    <td>
                                                        <input type="number" wire:input.debounce.500ms='calculation' class="form-control clac" wire:model.lazy='state.water_payment_amount'>
                                                    </td>
                                                    <td style="text-align: right"></td>
                                                    <td style="text-align: center; color:red; font-size:18px">
                                                        <i wire:click='remove_bill("{{ $key }}")' style="cursor:pointer" class="fas fa-trash"></i>
                                                    </td>
                                                </tr>
                                            @endif

                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="text-align: center"></th>
                                        <th colspan="3" style="text-align: center">Total: </th>
                                        <th style="text-align: center">{{ number_format($this->state['total_bill'], 2) }}</th>
                                        <th></th>
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
