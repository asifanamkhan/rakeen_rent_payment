<div>
    <div wire:loading class="spinner-border text-primary custom-loading" user="status">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">Service Bill Info</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Service Bill Info</a></li>
            </ol>
        </nav>
    </div>
    <div class="card p-4">
        <div class="row g-3 mb-3 align-items-center">
            <div class="col-auto">
                <input type="text" wire:model.live.debounce.300ms='search' class="form-control" placeholder="search here">
            </div>
            <div class="col-auto">
                <select class="form-select" wire:model.change='status' name="" id="">
                    <option value="">Search status</option>
                    <option value="PAID">PAID</option>
                    <option value="UNPAID">UNPAID</option>
                </select>
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
                        <td  style="width:5%">#</td>
                        <td  style="width:12%">Bill No</td>
                        <td  style="width:10%">Apartment ID</td>
                        <td  style="width:25%">Customer</td>
                        <td  style="width:10%">Bill Month</td>
                        <td  style="width:8%">Bill Amt</td>
                        <td  style="width:8%">Paid Amt</td>
                        <td  style="width:7%">Status</td>
                        <td  style="width:15%">Action</td>
                    </tr>
                </thead>
                <tbody>
                    @if (count($this->resultPayments) > 0)
                    @foreach ($this->resultPayments as $key => $data)
                    <tr wire:key='{{ $key }}'>
                        <td>{{ $this->resultPayments->firstItem() + $key }}</td>
                        <td>{{ $data->auto_bill_no }}</td>
                        <td>{{ $data->product_id }}</td>
                        <td>
                            {{ $data->customer_name }}
                            <span class="text-muted">({{ $data->customer_id }})</span>
                        </td>
                        <td>{{ Carbon\Carbon::parse($data->bill_month)->format('M, Y') }}</td>
                        <td style="text-align: center">{{ number_format($data->tot_bill_amt, 2) }}</td>
                        <td style="text-align: center">{{ number_format($data->paid_amount, 2) }}</td>
                        <td>
                            @if ($data->status == 'PAID')
                            <span class="badge bg-success">PAID</span>
                            @else
                            <span class="badge bg-danger">UNPAID</span>
                            @endif
                        </td>
                        <td>
                            @if ($data->status != 'PAID')
                            <a class="btn btn-sm btn-primary" wire:navigate href="">
                                Payment
                            </a>
                            @endif
                            <a target="_blank" class="btn btn-sm btn-success" href="{{ route('service-bill-invoice', $data->bill_id) }}">
                                Invoice
                            </a>
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



