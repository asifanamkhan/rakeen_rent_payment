<div>
    <div wire:loading class="spinner-border text-primary custom-loading" user="status">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">Apartment Information</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Settings</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('apartment-info') }}" style="color: #3C50E0">apartmens</a></li>
            </ol>
        </nav>
    </div>
    <div class="card p-4">
        <div class="row g-3 mb-3 align-items-center">
            <div class="col-auto">
                <input type="text" wire:model.live.debounce.300ms='search' class="form-control" placeholder="search here">
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
                        <td  style="">#</td>
                        <td  style="">Product Id</td>
                        <td  style="">Product Type</td>
                        <td  style="">Tower ID</td>
                        <td  style="">Handover Date</td>
                        <td  style="">Customer ID</td>
                        <td  style="">Booking ID</td>
                        <td  style="">Status</td>
                    </tr>
                </thead>
                <tbody>
                    @if (count($this->resultApartments) > 0)
                    @foreach ($this->resultApartments as $key => $data)
                    <tr wire:key='{{ $key }}'>
                        <td>{{ $this->resultApartments->firstItem() + $key }}</td>
                        <td>{{ $data->product_id }}</td>
                        <td>{{ $data->product_type }}</td>
                        <td>{{ $data->tower_id }}</td>
                        <td>{{ Carbon\Carbon::parse($data->handover_date)->format('d-M-Y') }}</td>
                        <td>{{ $data->customer_id }}</td>
                        <td>{{ $data->booking_id }}</td>
                        <td>
                            @if ($data->status == 'ACTIVE')
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <span>{{ $this->resultApartments->links() }}</span>
    </div>
</div>

<script>

</script>



