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
    <form id="confirmationForm" action="" >
        <div class="row">
            <div class="col-md-4" style="border-right: 1px solid rgb(240, 239, 239)">
                <div class="form-group mb-3">
                    <div class="mb-3" style="display: flex; justify-content: center">
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
                    <div class="form-group mb-3">
                        <x-input required_mark='true' wire:model='state.emp_name' name='emp_name' type='text'
                        label='Employee name' />
                    </div>
                </div>

                <div class="form-group mb-3">
                    <div class="form-group mb-3">
                        <x-input required_mark='true' wire:model='state.mobile' name='mobile' type='text'
                        label='Mobile' />
                    </div>
                </div>

                <div class="form-group mb-3">
                    <div class="form-group mb-3">
                        <x-input required_mark='true' wire:model='state.email' name='mobile' type='text'
                        label='Email' />
                    </div>
                </div>

                <div class="form-group mb-3" wire:ignore>
                    <label for="">Department <span style="color: red"> * </span></label>
                    <select class="form-select select2" id='employee_type'>
                        <option value="">Select type</option>
                        @forelse ($departments as $department)
                        <option value="{{ $department->dept_id }}">
                            {{ $department->dept_name }}</option>
                        @empty
                        <option value=""></option>
                        @endforelse
                    </select>

                </div>
                @error('p_type')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror

                <div class="form-group mb-3" wire:ignore>
                    <label for="">Designation <span style="color: red"> * </span></label>
                    <select class="form-select select2 @error('p_category') is-invalid @enderror" name=""
                        id="employee_category">
                        <option>Select category</option>
                        @forelse ($designations as $designation)
                        <option value="{{ $designation->desig_id }}">
                            {{ $designation->desig_name }}
                        </option>
                        @empty
                        <option value=""></option>
                        @endforelse
                    </select>

                </div>
                @error('p_catagory')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror

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
                        <div class="form-group mb-3">
                            <x-input required_mark='' wire:model='state.father_name' name='father_name' type='text'
                            label='Father name' />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <x-input required_mark='' wire:model='state.mother_name' name='mother_name' type='text'
                            label='Mother name' />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <x-input required_mark='' wire:model='state.blood_group' name='blood_group' type='text'
                            label='Blood group' />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <x-input required_mark='true' wire:model='state.permanent_address' name='permanent_address' type='text'
                            label='Permanent address' />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <x-input required_mark='true' wire:model='state.present_address' name='present_address' type='text'
                            label='Present address' />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <x-input required_mark='true' wire:model='state.date_of_birth' name='date_of_birth' type='date'
                            label='Date of birth' />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="">Gender <span style="color: red"> * </span></label>
                            <select wire:model='state.sex' name="" class="form-select" id="">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="">Religion</label>
                            <select wire:model='state.religion' name="" class="form-select" id="">
                                <option value="">Select Religion</option>
                                <option value="islam">Islam</option>
                                <option value="hindu">Hindu</option>
                                <option value="christian">Christian</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="">Maritial status</label>
                            <select wire:model='state.marital_status' name="" class="form-select" id="">
                                <option value="">Select Maritial Status</option>
                                <option value="single">Single</option>
                                <option value="married">Married</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <x-input required_mark='true' wire:model='state.joining_date' name='joining_date' type='date'
                            label='Joining date' />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <x-input required_mark='' wire:model='state.pre_basic' name='pre_basic' steps='0.01' type='number'
                            label='Present basic' />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <x-input required_mark='' wire:model='state.current_net_salary' steps='0.01' name='current_net_salary' type='number'
                            label='Current net salary' />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <x-input required_mark='' wire:model='state.dayoff' name='dayoff' type='number'
                            label='Day Off' />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <select wire.model="probation_period" name="" class="form-select" id="">
                                <option value="">Select Probation Period</option>
                                <option value="YES">Yes</option>
                                <option value="NO">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <x-input required_mark='' wire:model='state.shift_id' name='shift_id' type='text'
                            label='Shift' />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <x-input required_mark='' wire:model='state.passport_no' name='passport_no' type='text'
                            label='Passport no' />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <x-input required_mark='' wire:model='state.no_of_family_member' name='no_of_family_member' type='number'
                            label='No of family member' />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <x-input required_mark='' wire:model='state.nationality' name='nationality' type='text'
                            label='Nationality' />
                        </div>
                    </div>

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
                </div>

            </div>
        </div>
        <div class="mt-4 d-flex justify-content-center">
            <button class="btn btn-primary">Save</button>
        </div>
    </form>

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


</script>
@endscript
