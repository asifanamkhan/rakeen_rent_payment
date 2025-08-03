<div>
    <div wire:loading class="spinner-border text-primary custom-loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fas fa-edit"></i> Supplier edit</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Hrm</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('supplier') }}">suppliers</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('supplier-create') }}"
                        style="color: #3C50E0">edit</a></li>
            </ol>
        </nav>
    </div>
    <div class="card p-4">
        <form wire:submit='update' action="">
            @include('livewire.dashboard.hrm.supplier.supplier-form')
            <div class="mt-4 d-flex justify-content-center">
                <button class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
