<nav id="sidebar">

    <div class="sidebar-header">
        <div>

            @php
            $company = DB::table('HRM_COMPANY_INFO')->first();
            @endphp
            @if (@$company->short_name)
            {{ $company->short_name }}
            @else
            INVENTORY
            @endif

        </div>
    </div>
    <ul class="list-unstyled components">
        <li>
            <a wire:navigate href="{{ route('dashboard') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li>
            <a href="#adminSubmenu" data-toggle="collapse" aria-expanded="true"
                class="dropdown-toggle dropdown-custom-toggle main-list">
                <i class="fa-solid fa-screwdriver-wrench"></i> Settings
            </a>
            <ul class="collapse list-unstyled
        {{ request()->routeIs('role') ? 'show' : ' ' }}
        {{ request()->routeIs('role-create') ? 'show' : ' ' }}
        {{ request()->routeIs('role-details') ? 'show' : ' ' }}
        {{ request()->routeIs('module') ? 'show' : ' ' }}
        {{ request()->routeIs('company-info') ? 'show' : ' ' }}
        {{ request()->routeIs('user') ? 'show' : ' ' }}
        {{ request()->routeIs('user-create') ? 'show' : ' ' }}
        {{ request()->routeIs('service-types') ? 'show' : ' ' }}

        " id="adminSubmenu">
                <li class="{{ request()->routeIs('company-info') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('company-info') }}"> - Company Info</a>
                </li>

                <li class="{{ request()->routeIs('service-types') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('service-types') }}"> - Service Type</a>
                </li>
                <li class="
                    {{ request()->routeIs('role') ? 'active' : ' ' }}
                    {{ request()->routeIs('role-create') ? 'active' : ' ' }}
                    {{ request()->routeIs('role-details') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('role') }}"> - Role </a>
                </li>
                <li class="
                {{ request()->routeIs('user') ? 'active' : ' ' }}
                {{ request()->routeIs('user-create') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('user') }}"> - Create New Users</a>
                </li>



            </ul>
        </li>

        <li>
            <a href="#HRMSubmenu" data-toggle="collapse" aria-expanded="true"
                class="dropdown-toggle dropdown-custom-toggle main-list">
                <i class="fa-solid fa-user-gear"></i> HRM settings
            </a>
            <ul class="collapse list-unstyled
        {{ request()->routeIs('branch') ? 'show' : ' ' }}
        {{ request()->routeIs('department') ? 'show' : ' ' }}
        {{ request()->routeIs('designation') ? 'show' : ' ' }}
        {{ request()->routeIs('employee') ? 'show' : ' ' }}
        {{ request()->routeIs('supplier') ? 'show' : ' ' }}
        {{ request()->routeIs('supplier-create') ? 'show' : ' ' }}
        {{ request()->routeIs('supplier-edit') ? 'show' : ' ' }}
        {{ request()->routeIs('customer') ? 'show' : ' ' }}
        {{ request()->routeIs('customer-create') ? 'show' : ' ' }}
        {{ request()->routeIs('customer-edit') ? 'show' : ' ' }}

        " id="HRMSubmenu">
                <li class="{{ request()->routeIs('branch') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('branch') }}"> - Create New Branch</a>
                </li>
                <li class="{{ request()->routeIs('department') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('department') }}"> - Create New Department</a>
                </li>

                <li class="{{ request()->routeIs('designation') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('designation') }}"> - Create New Designation</a>
                </li>
                <li class="{{ request()->routeIs('employee') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('employee') }}"> - Create New Employee</a>
                </li>
                <li class="
                {{ request()->routeIs('customer') ? 'active' : ' ' }}
                {{ request()->routeIs('customer-create') ? 'active' : ' ' }}
                {{ request()->routeIs('customer-edit') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('customer') }}"> - Create New Customer</a>
                </li>

            </ul>
        </li>

        <li class="{{ request()->routeIs('apartment-info') ? 'active' : ' ' }}">
            <a wire:navigate href="{{ route('apartment-info') }}"> <i class="fas fa-house-chimney"></i> Appertment
                Info</a>
        </li>
        <li class="{{ request()->routeIs('service-bill-info') ? 'active' : ' ' }}">
            <a wire:navigate href="{{ route('service-bill-info') }}"><i class="fas fa-dollar-sign"></i></i> Service Bill
                Info</a>
        </li>
        <li>
            <a href="#RPSubmenu" data-toggle="collapse" aria-expanded="true"
                class="dropdown-toggle dropdown-custom-toggle main-list">
                <i class="fas fa-hand-holding-dollar"></i>
                Receive Payments
            </a>
            <ul class="collapse list-unstyled
        {{ request()->routeIs('add-service-payment') ? 'show' : ' ' }}
        {{ request()->routeIs('add-receive-payment') ? 'show' : ' ' }}

        " id="RPSubmenu">
                <li class="{{ request()->routeIs('add-service-payment') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('add-service-payment','new') }}"> - Service Payment</a>
                </li>
                <li class="{{ request()->routeIs('add-receive-payment') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('add-receive-payment') }}"> - Other Payment</a>
                </li>

            </ul>
        </li>
        <li>
            <a href="#ReportSubmenu" data-toggle="collapse" aria-expanded="true"
                class="dropdown-toggle dropdown-custom-toggle main-list">
                <i class="fa-solid fa-chart-line"></i>
                Reports
            </a>
            <ul class="collapse list-unstyled
        {{ request()->routeIs('service-payment-report') ? 'show' : ' ' }}
        {{ request()->routeIs('service-due-report') ? 'show' : ' ' }}
        {{ request()->routeIs('service-due-report-summary') ? 'show' : ' ' }}
        {{ request()->routeIs('apartment-list-pdf') ? 'show' : ' ' }}
        {{ request()->routeIs('customer-list-pdf') ? 'show' : ' ' }}
        {{ request()->routeIs('money-receipt-print') ? 'show' : ' ' }}
        {{ request()->routeIs('service-bill-invoice-print') ? 'show' : ' ' }}

        " id="ReportSubmenu">
                <li class="{{ request()->routeIs('service-bill-invoice-print') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('service-bill-invoice-print') }}"> -
                        Service Bill Invoice</a>
                </li>
                <li class="{{ request()->routeIs('money-receipt-print') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('money-receipt-print') }}"> - Money Receipt</a>
                </li>
                <li class="{{ request()->routeIs('service-payment-report') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('service-payment-report') }}"> - Payment Report</a>
                </li>
                <li class="{{ request()->routeIs('service-due-report') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('service-due-report') }}"> - Service Due Report</a>
                </li>
                <li class="{{ request()->routeIs('service-due-report-summary') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('service-due-report-summary') }}"> - Service Due Report
                        Summary</a>
                </li>
                <li class="{{ request()->routeIs('apartment-list-pdf') ? 'active' : ' ' }}">
                    <a target="_blank" class="list" href="{{ route('apartment-list-pdf') }}"> - Apartment List </a>
                </li>
                <li class="{{ request()->routeIs('customer-list-pdf') ? 'active' : ' ' }}">
                    <a target="_blank" class="list" href="{{ route('customer-list-pdf') }}"> - Customer List </a>
                </li>
            </ul>
        </li>


        <li class="">
            <a href="https://www.infotechitsolutionsbd.com/contact"><i class="fa fa-question-circle"
                    aria-hidden="true"></i></i> Help</a>
        </li>
    </ul>
</nav>
