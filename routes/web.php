<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Livewire\Dashboard\Admin\Apartment\ApartmentInfo;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

use App\Livewire\Dashboard\Admin\Company\CompanyInfo;
use App\Livewire\Dashboard\Admin\Module\Module;
use App\Livewire\Dashboard\Admin\ReceivePayment\AddReceivePayment;
use App\Livewire\Dashboard\Admin\ReceivePayment\ReceivePayment;
use App\Livewire\Dashboard\Admin\ReceivePayment\Service\AddServicePayment;
use App\Livewire\Dashboard\Admin\ReceivePayment\Service\ServicePayment;
use App\Livewire\Dashboard\Admin\ReceivePayment\ServiceDueReport;
use App\Livewire\Dashboard\Admin\ReceivePayment\ServiceDueReportSummary;
use App\Livewire\Dashboard\Admin\Role\{Role, RoleCreate, RoleDetails};
use App\Livewire\Dashboard\Admin\Service\ServiceType;
use App\Livewire\Dashboard\Admin\User\{User, UserCreate};

use App\Livewire\Dashboard\Hrm\Branch\Branch;
use App\Livewire\Dashboard\Hrm\Customer\{Customer, CustomerCreate, CustomerEdit};
use App\Livewire\Dashboard\Hrm\Department\Department;
use App\Livewire\Dashboard\Hrm\Designation\Designation;
use App\Livewire\Dashboard\Hrm\Employee\Employee;

use Illuminate\Support\Facades\DB;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Dashboard\Hrm\Employee\EmployeeCreate;

use Illuminate\Support\Facades\Auth;

Livewire::setUpdateRoute(function ($handle) {
    $path = env('LIVEWIRE_UPDATE_PATH') . '/livewire/update';
    return Route::post($path, $handle);
});

require __DIR__ . '/auth.php';


Route::middleware(['auth', 'verified', 'throttle:60,1'])->group(function () {


    Route::get('/', Dashboard::class)->name('dashboard');
    // ------------- admin start ----------------
    Route::get('role', Role::class)->name('role')->middleware('permission:4,visible_flag');
    Route::get('role/create', RoleCreate::class)->name('role-create')->middleware('permission:4,visible_flag');
    Route::get('role/details/{role_id}', RoleDetails::class)->name('role-details')->middleware('permission:4,visible_flag');

    Route::get('module', Module::class)->name('module')->middleware('permission:2,visible_flag');

    Route::get('company-info', CompanyInfo::class)->name('company-info')->middleware('permission:1,visible_flag');

    Route::get('user', User::class)->name('user')->middleware('permission:3,visible_flag');
    Route::get('user/create', UserCreate::class)->name('user-create')->middleware('permission:3,visible_flag');


    Route::get('apartment-info', ApartmentInfo::class)->name('apartment-info');
    Route::get('service-types', ServiceType::class)->name('service-types');

    // ------------- admin end ----------------


    // ------------- hrm start ----------------

    Route::get('hrm/branch', Branch::class)->name('branch')->middleware('permission:19,visible_flag');
    Route::get('hrm/department', Department::class)->name('department')->middleware('permission:20,visible_flag');
    Route::get('hrm/designation', Designation::class)->name('designation')->middleware('permission:21,visible_flag');
    Route::get('hrm/employee', Employee::class)->name('employee')->middleware('permission:22,visible_flag');
    Route::get('hrm/employee/create', EmployeeCreate::class)->name('employee-create')->middleware('permission:22,visible_flag');

    Route::middleware(['permission:24,visible_flag'])->group(function () {
        Route::get('customer', Customer::class)->name('customer');
        Route::get('customer/create', CustomerCreate::class)->name('customer-create');
        Route::get('customer/{customer_id}/edit', CustomerEdit::class)->name('customer-edit');
    });
    // ------------- hrm end ----------------

    // ------------- receive-payment start ----------------

    Route::get('service-bill-info', ReceivePayment::class)->name('service-bill-info');
    Route::get('add-receive-payment', AddReceivePayment::class)->name('add-receive-payment');

    Route::get('service-payment', ServicePayment::class)->name('service-payment');
    Route::get('add-service-payment/{product_id}', AddServicePayment::class)->name('add-service-payment');

    Route::get('money-receipt-print', \App\Livewire\Dashboard\Admin\ReceivePayment\MoneyReceiptPrint::class)->name('money-receipt-print');
    Route::get('service-bill-invoice-print', \App\Livewire\Dashboard\Admin\ReceivePayment\ServiceBillInvoicePrint::class)->name('service-bill-invoice-print');

    // ------------- receive-payment end ----------------

    //--------------- Report -----------------------------
    Route::get('apartment-list-pdf', [InvoiceController::class, 'apartment_list_pdf'])->name('apartment-list-pdf');
    Route::get('customer-list-pdf', [InvoiceController::class, 'customer_list_pdf'])->name('customer-list-pdf');
    Route::get('service-bill-invoice/{bill_id}', [InvoiceController::class, 'service_bill_invoice'])->name('service-bill-invoice');
    Route::get('money-receipt-print-pdf/{receipt_id}/{type}', [InvoiceController::class, 'money_receipt_print'])->name('money-receipt-print-pdf');

    // Service payment report (screen + PDF)
    Route::get('service-payment-report', \App\Livewire\Dashboard\Admin\ReceivePayment\ServicePaymentReport::class)->name('service-payment-report');
    Route::post('service-payment-report-pdf', [InvoiceController::class, 'service_payment_report_pdf'])->name('service-payment-report-pdf');

    // Service Due Report (screen + PDF)
    Route::get('service-due-report', ServiceDueReport::class)->name('service-due-report');
    Route::post('service-due-report-pdf', [InvoiceController::class, 'service_due_report_pdf'])->name('service-due-report-pdf');

    // Service Due Report Summary (screen + PDF)
    Route::get('service-due-report-summary', ServiceDueReportSummary::class)->name('service-due-report-summary');
    Route::post('service-due-report-summary-pdf', [InvoiceController::class, 'service_due_report_summary_pdf'])->name('service-due-report-summary-pdf');

    Route::get('service-due-report-excel', [InvoiceController::class, 'service_due_report_csv'])->name('service.due.report.excel');
    // routes/web.php

    Route::get('/service-due-summary-report-csv', [InvoiceController::class, 'service_due_summary_report_csv'])->name('service.due.summary.report.csv');

    // routes/web.php

    Route::get('/service-payment-report-excel', [InvoiceController::class, 'service_payment_report_excel'])->name('service.payment.report.excel');
});




Route::get('test', function () {

    // $data = DB::select("
    // SELECT account_code, parent_code, LEVEL as depth
    // FROM ACC_CHART_OF_ACCOUNTS
    // START WITH parent_code IS NULL
    // CONNECT BY PRIOR account_code = parent_code
    // ORDER SIBLINGS BY parent_code
    // ");

    // dd($data);
});