<div>
    <div wire:loading class="spinner-border text-primary custom-loading" branch="status">
        <span class="sr-only">Loading...</span>
    </div>

    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">Other Payment - Electricity & Water</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a wire:navigate style="">Other Payment</a></li>
            </ol>
        </nav>
    </div>

    <form id="confirmationForm" action="">
        <div class="card p-4">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
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
                <div class="col-md-4">
                    <div class="mb-3">
                        <x-input required_mark='true' wire:model='state.payment_date' name='payment_date'
                            type='date' label='Payment Date' />
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3 form-group">
                        <x-input required_mark='true' wire:model='state.bill_month' name='bill_month'
                        type='month' label='Bill Month' />
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3 form-group">
                        <label for="">Bill Type <span style="color: red"> * </span></label>
                        <select required wire:model='state.bill_type' class="form-select" id="">
                                <option value="">Select Bill</option>
                                <option value="2">Electricity Bill</option>
                                <option value="3">Water Bill</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <div class="form-group" wire:ignore>
                            <label for="">Select Apartment<span style="color: red"> * </span></label>
                            <select required name="product_id" class="form-select select2" id='product_id'>
                                <option value="">Select Apartment</option>
                                @forelse ($products as $product)
                                <option value="{{ $product->product_id }}">
                                    {{ $product->product_id }}
                                    ({{ $product->product_type }})
                                    => {{ $product->customer_id }}
                                </option>
                                @empty
                                <option value="">No apartments found</option>
                                @endforelse
                            </select>
                        </div>
                        @error('product_id')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <x-input required_mark='true' wire:model='state.amount' name='amount' type='number' step="0.01" min="0"
                            label='Payment Amount' placeholder='Enter amount' />
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <div class="form-group" wire:ignore>
                            <label for="">Payment Method<span style="color: red"> * </span></label>
                            <select required name="p_mode_id" class="form-select select2" id='p_mode_id'>
                                <option value="">Select payment method</option>
                                @forelse ($payment_methods as $payment)
                                <option value="{{ $payment->p_mode_id }}">
                                    {{ $payment->p_mode_name }}
                                </option>
                                @empty
                                <option value="">No Payment methods found</option>
                                @endforelse
                            </select>
                        </div>
                        @error('product_id')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-sm btn-primary btn-lg">
                        <i class="fas fa-save"></i> Save Payment
                    </button>
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

    $('#p_mode_id').on('change', function(e){
        @this.set('state.p_mode_id', e.target.value, false);
    });

    document.getElementById('confirmationForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent the form from submitting automatically

        // Show SweetAlert confirmation dialog
        Swal.fire({
            title: 'Confirm Payment',
            text: "Are you sure you want to save this payment?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, save it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.dispatch('save_form'); // Trigger the Livewire submit function
            }
        });
    });
</script>
@endscript
