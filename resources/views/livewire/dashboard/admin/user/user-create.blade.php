<div>
    <div wire:loading class="spinner-border text-primary custom-loading" user="status">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">Users</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Administration</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('user') }}">users</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('user-create') }}"
                        style="color: #3C50E0">user create</a></li>
            </ol>
        </nav>
    </div>
    <form wire:submit='save' action="">
        <div class="card p-4">
            @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
            </div>
            @elseif (session('error'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('status') }}
            </div>
            @endif
            <div class="row gx-5 mb-3">
                <div class="col-5  mb-3">
                    <div wire:ignore class="form-group mb-3">
                        <label for="">Select user</label>
                        <select class="form-select select2" id='employee'>
                            <option value="">select</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->employee_id }}">{{ $user->emp_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('user')
                    <small id="" class="form-text text-danger">{{ $message }}</small>
                    @enderror
                    <x-input required_mark='true' wire:model='email' name='email' type='email' label='Email' readonly />
                    <x-input required_mark='true' wire:model='phone_number' name='phone_number' type='text' label='Phone' readonly />
                    <x-input required_mark='true' wire:model='password' name='password' type='password' label='Password' />
                    <x-input required_mark='true' wire:model='password_confirmation' name='password_confirmation' type='password'
                        label='Password confirmation' />
                </div>
                <div class='col-7'>
                    <x-large-modal :class='$roleModalEvent'>
                        <livewire:dashboard.admin.role.role-create>
                    </x-large-modal>
                    <div class="d-flex gap-3 justify-content-between mb-3">
                        <h5>Roles</h5>
                        <button type="button" @click="$dispatch('{{ $roleModalEvent }}')" class="btn btn-sm btn-success"
                            data-toggle="modal" data-target=".{{ $roleModalEvent }}">
                            <i class="fa fa-plus"></i> Add new role
                        </button>
                    </div>

                    <div class="row">
                        @error('role')
                        <small id="" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                        @foreach ($roles as $role)
                        <div class="col-md-4 pb-2 d-flex align-items-center">
                            <span style="padding-right: 10px">
                                <input wire:key='{{ $role->role_id }}' type="checkbox" wire:model='role'
                                    value="{{ $role->role_id }}" class="form-check-input">
                            </span>
                            <span><a href="#" style="color: rgba(33, 37, 41, 0.75)">{{ $role->role_name }}</a></span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class=" d-flex justify-content-center">
                <button class="btn btn-primary">Save</button>
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
    })

    $('#employee').on('change', function(){
        let data = $(this).val();
        $wire.dispatch('employee-select-user-create', {id: data});
    })

</script>
@endscript
