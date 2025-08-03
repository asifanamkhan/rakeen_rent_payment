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
        {{ request()->routeIs('apartment-info') ? 'show' : ' ' }}
        {{ request()->routeIs('service-types') ? 'show' : ' ' }}

        " id="adminSubmenu">
                <li class="{{ request()->routeIs('company-info') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('company-info') }}"> - Company Info</a>
                </li>
                <li class="{{ request()->routeIs('apartment-info') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('apartment-info') }}"> - Appertment Info</a>
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

        <li class="{{ request()->routeIs('receive-payment') ? 'active' : ' ' }}">
            <a wire:navigate href="{{ route('receive-payment') }}"><i class="fas fa-dollar-sign"></i></i> Receive Payemnts</a>
        </li>
        <li class="">
            <a wire:navigate href=""><i class="fa fa-question-circle" aria-hidden="true"></i></i> Help</a>
        </li>
    </ul>
</nav>
