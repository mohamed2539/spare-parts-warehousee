<?php
use App\Http\Controllers\InboundController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\IncomingItemController;
use App\Http\Controllers\ManualWithdrawalController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DepartmentController;
Route::get('/', function () {
    return view('welcome');
});







// Route::get('/imports/list', [ImportController::class, 'list'])->name('imports.list');
Route::get('/imports/list', [ImportController::class, 'listPage'])->name('imports.listPage');
Route::post('/imports/upload', [ImportController::class, 'upload'])->name('imports.upload');
Route::get('/imports/create', [ImportController::class, 'create'])->name('imports.create');


Route::get('/imports/export', [ImportController::class, 'export'])->name('imports.export');



Route::get('/incoming', [IncomingItemController::class, 'index'])->name('incoming.index');
Route::get('/incoming/create', [IncomingItemController::class, 'create'])->name('incoming.create');
Route::post('/incoming/store', [IncomingItemController::class, 'store'])->name('incoming.store');
Route::get('/incoming/{id}/edit', [IncomingItemController::class, 'edit'])->name('incoming.edit');
Route::put('/incoming/{id}', [IncomingItemController::class, 'update'])->name('incoming.update');
Route::delete('/incoming/{id}', [IncomingItemController::class, 'destroy'])->name('incoming.destroy');
Route::post('/incoming/delete-multiple', [IncomingItemController::class, 'destroyMultiple'])->name('incoming.destroyMultiple');





    Route::get('/withdrawals/create', [ManualWithdrawalController::class, 'create'])->name('withdrawals.create');
    Route::post('/withdrawals/store', [ManualWithdrawalController::class, 'store'])->name('withdrawals.store');
    Route::get('/withdrawals', [ManualWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::delete('/withdrawals/{id}', [ManualWithdrawalController::class, 'destroy'])->name('withdrawals.destroy');
    Route::post('/withdrawals/bulk-delete', [ManualWithdrawalController::class, 'bulkDelete'])->name('withdrawals.bulk-delete');
    Route::post('/withdrawals/destroyMultiple', [ManualWithdrawalController::class, 'destroyMultiple'])->name('withdrawals.destroyMultiple');
    Route::get('/withdrawals/search-item', [ManualWithdrawalController::class, 'searchItem'])->name('withdrawals.searchItem');







// صفحة البحث
Route::get('/items/search', [ItemController::class, 'searchPage'])->name('items.searchPage');

// API البحث
Route::get('/items/search-api', [ItemController::class, 'searchApi'])->name('items.searchApi');

// صفحة إضافة الكمية
Route::get('/items/add', [ItemController::class, 'addStockForm'])->name('items.addForm');

// إضافة كمية بالـ AJAX
Route::post('/items/add-stock-ajax', [ItemController::class, 'addStockAjax'])->name('items.addStockAjax');



Route::get('/deletion-logs', [App\Http\Controllers\DeletionLogController::class, 'index'])->name('deletionLogs.index');




Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'registerForm'])->name('auth.registerForm');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// صفحة محمية (تظهر بعد تسجيل الدخول)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');










Route::middleware(['auth'])->group(function () {

    // طلبات شراء
    Route::get('/purchase-requests', [PurchaseRequestController::class, 'index'])->name('purchase-requests.index');
    Route::post('/purchase-requests', [PurchaseRequestController::class, 'store'])->name('purchase-requests.store');
    Route::post('/purchase-requests/{purchaseRequest}/status', [PurchaseRequestController::class, 'updateStatus'])->name('purchase-requests.updateStatus');

    // الرصيد الحالي
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/export', [StockController::class, 'export'])->name('stock.export');

    // تقارير
    Route::get('/reports/incoming', [ReportController::class, 'incoming'])->name('reports.incoming');
    Route::get('/reports/incoming/export', [ReportController::class, 'exportIncoming'])->name('reports.incoming.export');

    Route::get('/reports/withdrawals', [ReportController::class, 'withdrawals'])->name('reports.withdrawals');
    Route::get('/reports/withdrawals/export', [ReportController::class, 'exportWithdrawals'])->name('reports.withdrawals.export');
});








Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
// Route::delete('/stocks/clear', [StockController::class, 'clear'])->name('stocks.clear');
// Route::delete('/stocks/delete-selected', [StockController::class, 'deleteSelected'])->name('stocks.deleteSelected');

Route::delete('/stock/bulk-delete', [StockController::class, 'bulkDelete'])->name('stock.bulkDelete');
Route::delete('/stock/clear-all', [StockController::class, 'clearAll'])->name('stock.clearAll');



Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');




// // Reporet 

// // طلبات الشراء
// Route::resource('purchase-requests', PurchaseRequestController::class);

// // الرصيد الحالي
// Route::get('/stock', [StockController::class, 'index'])->name('stock.index');


// Route::get('/stock/export', [StockController::class, 'export'])->name('stock.export');
// // تقارير
// Route::prefix('reports')->group(function () {
//     Route::get('/incoming', [ReportController::class, 'incoming'])->name('reports.incoming');
//     Route::get('/withdrawals', [ReportController::class, 'withdrawals'])->name('reports.withdrawals');
// });




































// Route::get('/items/search', [ItemController::class, 'search'])->name('items.search');
    // Route::get('/items/search-page', [ItemController::class, 'searchPage'])->name('items.searchPage');
    // Route::get('/items', [ItemController::class, 'index'])->name('items.index');



    // Route::get('/items/add', [ItemController::class, 'addStockForm'])->name('items.addForm');
    // Route::post('/items/add', [ItemController::class, 'addStock'])->name('items.addStock');
    // Route::post('/items/add-stock-ajax', [ItemController::class, 'addStockAjax'])->name('items.addStockAjax');







// Route::resource('outgoings', OutgoingController::class)->except(['edit', 'update', 'show']);

// Route::delete('/outgoings/destroy-selected', [OutgoingController::class, 'destroySelected'])->name('outgoings.destroySelected');
// Route::delete('/outgoings/destroy-all', [OutgoingController::class, 'destroyAll'])->name('outgoings.destroyAll');




// // صفحة عرض قائمة المخازن
// Route::get('/warehouses', [App\Http\Controllers\WarehouseController::class, 'index'])->name('warehouses.index');

// // صفحة إضافة مخزن جديد
// Route::get('/warehouses/create', [App\Http\Controllers\WarehouseController::class, 'create'])->name('warehouses.create');
// Route::post('/warehouses', [App\Http\Controllers\WarehouseController::class, 'store'])->name('warehouses.store');




// Route::get('/', fn() => redirect()->route('inbound.import.form'));

// Route::get('/inbound/import', [InboundImportController::class, 'create'])->name('inbound.import.form');
// Route::post('/inbound/import', [InboundImportController::class, 'store'])->name('inbound.import.upload');          // رفع الملف (AJAX)
// Route::post('/inbound/import/{batch}/start', [InboundImportController::class, 'start'])->name('inbound.import.start'); // بدء المعالجة (AJAX)
// Route::get('/inbound/import/{batch}/progress', [InboundImportController::class, 'progress'])->name('inbound.import.progress'); // متابعة (AJAX)
// Route::get('/inbound/import/{batch}/errors', [InboundImportController::class, 'downloadErrors'])->name('inbound.import.errors'); // تنزيل الأخطاء


// Route::get('/import', [InboundController::class, 'importForm'])->name('inbound.import.form');
// Route::post('/import', [InboundController::class, 'import'])->name('inbound.import');