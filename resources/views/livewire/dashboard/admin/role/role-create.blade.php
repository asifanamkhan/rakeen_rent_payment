<div>
    <div wire:loading class="spinner-border text-primary custom-loading" branch="status">
        <span class="sr-only">Loading...</span>
    </div>
    @if($brearcums)
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">Role new create</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">administrator</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('role') }}" style="">roles</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('role-create') }}"
                        style="color: #3C50E0">role create</a></li>
            </ol>
        </nav>
    </div>
    @endif
    <form wire:submit='save' action="">
        <div class="card p-4">

            <div class="col-md-4 offset-3">
                @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                </div>
                @elseif (session('error'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                </div>
                @endif
                <x-input required_mark='true' wire:model='role_name' name='role_name' type='text' label='Role name' />
                <x-input required_mark='' wire:model='role_desc' name='role_desc' type='text' label='Role description' />
                <div class="form-group mb-3">
                    <label for="">Select module</label>
                    <select wire:change='module_change' wire:model='module' name="" id="" class="form-select">
                        <option value="">select</option>
                        @foreach ($modules as $module)
                        <option value="{{ $module->module_mst_id }}">{{ $module->module_name }}</option>
                        @endforeach
                    </select>
                    @error('module')
                    <small id="" class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            @if (count($sub_modules) > 0)

            <div class="responsive-table">
                <div class="d-flex align-items-center m-2" style="justify-content: flex-end">
                    <label for="" style="margin-right: 10px; color: #3C50E0">All </label>
                    <input wire:model.live='allCheck.{{ (int)$this->module }}' class="form-check-input" type="checkbox">

                </div>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="bg-sidebar">
                            <td  style="">Sub Module</td>
                            <td class="text-center" style="">All</td>
                            <td class="text-center" style="">View</td>
                            <td class="text-center" style="">Write</td>
                            <td class="text-center" style="">Edit</td>
                            <td class="text-center" style="">Read</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sub_modules as $key => $sub_module)
                        <tr wire:key='{{ $sub_module->module_dtl_id }}'>
                            <td>
                                {{ $sub_module->module_dtl_name }}
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <input id='lineCheck.{{ $sub_module->module_dtl_id }}.all'
                                        wire:model='lineCheck.{{ $sub_module->module_dtl_id }}.all'
                                        wire:input='upLineCheck({{ $sub_module->module_dtl_id }})' class="form-check-input"
                                        type="checkbox">
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <input id='singleCheck.{{ $sub_module->module_dtl_id }}.view'
                                        wire:model='singleCheck.{{ $sub_module->module_dtl_id }}.view'
                                        class="form-check-input" type="checkbox">
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <input id='singleCheck.{{ $sub_module->module_dtl_id }}.write'
                                        wire:model='singleCheck.{{ $sub_module->module_dtl_id }}.write'
                                        class="form-check-input" type="checkbox">
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <input id='singleCheck.{{ $sub_module->module_dtl_id }}.edit'
                                        wire:model='singleCheck.{{ $sub_module->module_dtl_id }}.edit'
                                        class="form-check-input" type="checkbox">
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <input id='singleCheck.{{ $sub_module->module_dtl_id }}.read'
                                        wire:model='singleCheck.{{ $sub_module->module_dtl_id }}.read'
                                        class="form-check-input" type="checkbox">
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                <div class="text-center">
                    <button class="btn btn-primary">Save</button>
                </div>

            </div>

            @endif
        </div>
    </form>

</div>
