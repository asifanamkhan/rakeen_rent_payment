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
            <input wire:model='state.p_name' type='text' class="form-control @error('p_name') is-invalid @enderror">
            @error('p_name')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="">Phone <span style="color: red"> * </span></label>
            <input wire:model='state.phone' type='text' class="form-control @error('phone') is-invalid @enderror">
            @error('phone')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="">Email</label>
            <input wire:model='state.email' type='text' class="form-control @error('email') is-invalid @enderror">
            @error('email')
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
                    <label for="">Supplier type <span style="color: red"> * </span></label>
                    <select class="form-select select2" id='supplier_type'>
                        <option value="">Select type</option>
                        @forelse ($supplier_types as $type)
                        <option @if($p_type == $type->supplier_type_code) selected @endif value="{{ $type->supplier_type_code }}">{{ $type->supplier_type_name }}</option>
                        @empty
                        <option value=""></option>
                        @endforelse
                    </select>

                </div>
                @error('p_type')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3" wire:ignore>
                    <label for="">Supplier category <span style="color: red"> * </span></label>
                    <select class="form-select select2 @error('p_category') is-invalid @enderror" name=""
                        id="supplier_category">
                        <option>Select category</option>
                        @forelse ($supplier_categories as $cat)
                        <option @if($p_category == $cat->supplier_cat_code) selected @endif value="{{ $cat->supplier_cat_code }}">{{ $cat->supplier_cat_name }}</option>
                        @empty
                        <option value=""></option>
                        @endforelse
                    </select>

                </div>
                @error('p_catagory')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Company Id</label>
                    <input wire:model='state.comp_id' type='text'
                        class="form-control @error('comp_id') is-invalid @enderror">
                    @error('comp_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            {{-- <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">County Name</label>
                    <input wire:model='state.county_name' type='text'
                        class="form-control @error('county_name') is-invalid @enderror">
                    @error('county_name')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div> --}}
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Bank code</label>
                    <input wire:model='state.party_bank_code' type='text'
                        class="form-control @error('party_bank_code') is-invalid @enderror">
                    @error('party_bank_code')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Bank Name</label>
                    <input wire:model='state.party_bank_name' type='text'
                        class="form-control @error('party_bank_name') is-invalid @enderror">
                    @error('party_bank_name')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Branch Name</label>
                    <input wire:model='state.party_bank_br_name' type='text'
                        class="form-control @error('party_bank_br_name') is-invalid @enderror">
                    @error('party_bank_br_name')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Account Number</label>
                    <input wire:model='state.party_bank_account_no' type='text'
                        class="form-control @error('party_bank_account_no') is-invalid @enderror">
                    @error('party_bank_account_no')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Opening balance</label>
                    <input wire:model='state.p_opbal' type='text'
                        class="form-control @error('p_opbal') is-invalid @enderror">
                    @error('p_opbal')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Contact person</label>
                    <input wire:model='state.contact_person' type='text'
                        class="form-control @error('contact_person') is-invalid @enderror">
                    @error('contact_person')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group mb-3">
                    <label for="">Website</label>
                    <input wire:model='state.web' type='text' class="form-control @error('web') is-invalid @enderror">
                    @error('web')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label for="">Address</label>
                    <input wire:model='state.address' type='text'
                        class="form-control @error('address') is-invalid @enderror">
                    @error('address')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

        </div>

    </div>

</div>
@script
<script data-navigate-once>
    console.log();
    document.addEventListener('livewire:navigated', () => {
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap-5",
            });
        });
    })

    $('#supplier_type').on('change', function(e){
        let data = $(this).val();
        @this.set('p_type', e.target.value, false);
    })

    $('#supplier_category').on('change', function(e){
        let data = $(this).val();
        @this.set('p_category', e.target.value, false);
    })

</script>
@endscript
