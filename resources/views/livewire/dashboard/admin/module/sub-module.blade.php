<div class="card p-4">
    <div wire:loading class="spinner-border text-primary custom-loading" module="status">
        <span class="sr-only">Loading...</span>
    </div>
    @if($pageLoad)
    <div style="display: flex; justify-content: space-between;">
        <h4 class="text-center pb-4" style="color: #3C50E0">Module: {{ $module_name }}</h4>
        <b type="button" class="modal-close-icon" class="close" data-dismiss="modal"
        aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </b>
    </div>

    <div>
        <div class="row g-3 mb-3 align-items-center">
            <div class="col-auto">
                <input type="text" wire:model.live.debounce.300ms='search' class="form-control"
                    placeholder="search here">
            </div>
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
                        <td  style="width: 10%">#</td>
                        <td  style="width: 60%">Sub Module name</td>
                    </tr>
                </thead>
                <tbody>
                    @if (count($this->resultSubModule) > 0)
                    @foreach ($this->resultSubModule as $subModule)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $subModule->module_dtl_name }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <span>{{ $this->resultSubModule->links() }}</span>
    </div>
    @endif
</div>
