<div>
    <div wire:loading class="spinner-border text-primary custom-loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">Modules</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Administrator</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('module') }}"
                        style="color: #3C50E0">modules</a>
                </li>
            </ol>
        </nav>
    </div>
    <x-large-modal :class='$submoduleEvent'>
        <livewire:dashboard.admin.module.sub-module>
    </x-large-modal>
    <div class="card p-4">
        <div class="row g-3 mb-3 align-items-center">
            {{-- @permission('25') --}}
            <div class="col-auto">
                <input type="text" wire:model.live.debounce.300ms='search' class="form-control"
                    placeholder="search here">
            </div>
            {{-- --}}
            <div class="col-auto">
                <select class="form-select" wire:model.live='pagination' name="" id="">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
        <div class="responsive-table">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="bg-sidebar">
                        <td style="width: 10%">#</td>
                        <td style="width: 60%">Module name</td>
                        <td class="text-center" style="width: 30%">Action</td>
                    </tr>
                </thead>
                <tbody>
                    @if (count($this->resultModule) > 0)
                    @foreach ($this->resultModule as $key => $module)
                    <tr wire:key='{{ $key }}'>
                        <td>{{ $this->resultModule->firstItem() + $key }}</td>
                        <td>{{ $module->module_name }}</td>
                        <td style="display: flex; justify-content:center">
                            <div class="">
                                <button
                                    @click="$dispatch('module-submodule-view-modal', {id: {{ $module->module_mst_id }}})"
                                    type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                    data-target=".{{ $submoduleEvent }}">Sub modules</button>

                                {{-- <button
                                    @click="$dispatch('module-submodule-view-modal', {id: {{ $module->module_mst_id }}})"
                                    data-toggle="modal" data-target="#exampleModal" class="btn btn-sm btn-success">
                                    sub modules
                                </button> --}}

                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <span>{{ $this->resultModule->links() }}</span>
    </div>
</div>

<script>

</script>
