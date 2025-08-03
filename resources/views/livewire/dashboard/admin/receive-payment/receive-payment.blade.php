<div>
    <div wire:loading class="spinner-border text-primary custom-loading" user="status">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">Service Payment Receive</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Payment Receive</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('apartment-info') }}" style="color: #3C50E0">payment receive</a></li>
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
                        <td  style="">Bill No</td>
                        <td  style="">Apartment ID</td>
                        <td  style="">Month</td>
                        <td  style="">Bill Amt</td>
                        <td  style="">Paid Amt</td>
                        <td  style="">Status</td>
                    </tr>
                </thead>
                <tbody>
                    @if (count($this->resultPayments) > 0)
                    @foreach ($this->resultPayments as $key => $data)
                    <tr wire:key='{{ $key }}'>
                        <td>{{ $this->resultPayments->firstItem() + $key }}</td>
                        <td>{{ $data->auto_bill_no }}</td>
                        <td>{{ $data->apartment_id }}</td>
                        <td>{{ Carbon\Carbon::parse($data->bill_month)->format('M, Y') }}</td>
                        <td>{{ $data->tot_bill_amt }}</td>
                        <td>{{ $data->paid_amount }}</td>
                        <td>
                            @if ($data->status == 'PAID')
                            <span class="badge bg-success">PAID</span>
                            @else
                            <span class="badge bg-danger">UNPAID</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <span>{{ $this->resultPayments->links() }}</span>
    </div>
</div>

<script>

</script>



