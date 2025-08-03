<div class="row">
    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
    </div>
    @elseif (session('error'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('status') }}
    </div>
    @endif
    <div class="col-md-4" style="border-right: 1px solid rgb(240, 239, 239)">
        <div class="form-group mb-3">
            <div class="text-center mb-3">
                @if($editForm)
                @if (@$state['photo'])
                <img src="{{ $state['photo']->temporaryUrl() ?? '' }}" alt=""
                    style="border-radius: 50% !important; width:150px; height: 150px" class="img-fluid img-thumbnail">
                @elseif(!@$state['photo'] && @$state['old_photo'])
                <img src="{{ asset('storage/app/'.$state['old_photo']) }}" alt=""
                    style="border-radius: 50% !important; width:150px; height: 150px" class="img-fluid img-thumbnail">
                @else
                <img src="{{ asset('public/img/avatar.jpg') }}" alt=""
                    style="border-radius: 50% !important; width:150px; height: 150px" class="img-fluid img-thumbnail">
                @endif
                @else
                @if (@$state['photo'])
                <img src="{{ $state['photo']->temporaryUrl() ?? '' }}" alt=""
                    style="border-radius: 50% !important; width:150px; height: 150px" class="img-fluid img-thumbnail">
                @else
                <img src="{{ asset('public/img/avatar.jpg') }}" alt=""
                    style="border-radius: 50% !important; width:150px; height: 150px" class="img-fluid img-thumbnail">
                @endif
                @endif

            </div>
            <label for="" class="text-center">Image</label>
            <input wire:model='state.photo' type='file' class="form-control">
        </div>
        <div class="form-group mb-3">
            <label for="">Name <span style="color: red"> * </span></label>
            <input wire:model='state.customer_name' type='text'
                class="form-control @error('customer_name') is-invalid @enderror">
            @error('customer_name')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="">Phone <span style="color: red"> * </span></label>
            <input wire:model='state.phone_no' type='text' class="form-control @error('phone_no') is-invalid @enderror">
            @error('phone_no')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="">Status <span style="color: red"> * </span></label>
            <select class="form-select @error('status') is-invalid @enderror" wire:model='state.status' name="" id="">
                <option name="" id="">Select Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            @error('status')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>

    </div>
    <div class="col-8">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3" wire:ignore>
                    <label for="">customer type <span style="color: red"> * </span></label>
                    <select class="form-select select2" id='customer_type'>
                        <option value="">Select type</option>
                        @forelse ($customer_types as $type)
                        <option @if($customer_type==$type->customer_type_code) selected @endif value="{{
                            $type->customer_type_code }}">{{ $type->customer_type_name }}</option>
                        @empty
                        <option value=""></option>
                        @endforelse
                    </select>

                </div>
                @error('customer_type')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Occupation</label>
                    <input wire:model='state.occupation' type='text'
                        class="form-control @error('occupation') is-invalid @enderror">
                    @error('occupation')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>


        </div>

        <div class="row">

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Opening balance</label>
                    <input wire:model='state.op_bal' type='text'
                        class="form-control @error('op_bal') is-invalid @enderror">
                    @error('op_bal')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">NID</label>
                    <input wire:model='state.nid' type='text' class="form-control @error('nid') is-invalid @enderror">
                    @error('nid')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Birth date</label>
                    <input wire:model='state.birth_date' type='date'
                        class="form-control @error('birth_date') is-invalid @enderror">
                    @error('birth_date')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label for="">Email</label>
                    <input wire:model='state.email' type='text'
                        class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label for="">Address</label>
                    <input wire:model='state.customer_address' type='text'
                        class="form-control @error('customer_address') is-invalid @enderror">
                    @error('customer_address')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

        </div>

    </div>

</div>
@assets
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@endassets

@script

<script data-navigate-once>
    document.addEventListener('livewire:navigated', () => {
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap-5",
            });
        });
    })

    $('#customer_type').on('change', function(e){
        let data = $(this).val();
        @this.set('customer_type', e.target.value, false);
    })

</script>
@endscript
