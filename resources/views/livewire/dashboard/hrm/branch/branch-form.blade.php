<div>
    <div wire:loading class="spinner-border text-primary custom-loading" branch="status">
        <span class="sr-only">Loading...</span>
    </div>
    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
    </div>

    @endif

    <form wire:submit="@if($editForm) update @else store @endif" action="">
        <div class="form-group mb-3">
            <label for="">Name</label>
            <input wire:model='branch_name' type='text' label='Name' class="form-control @error('branch_name') is-invalid @enderror">
            @error('branch_name')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mt-4 d-flex justify-content-center">
            <button class="btn btn-primary">Save</button>
        </div>
    </form>

</div>
