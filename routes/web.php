<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\Admin\DataManagementController;
use App\Http\Controllers\Admin\ListableSectionController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\VariationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ExportLogController;
use App\Http\Controllers\Front\AccountController;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Ecommerce\CheckoutController;
use App\Http\Controllers\Ecommerce\EcommerceController;
use Illuminate\Support\Facades\Route;


Route::prefix('/')->name('front.')->group(function () {
    Route::get('/', [FrontController::class, 'index'])->name('index');
    Route::get('/about', [FrontController::class, 'about'])->name('about');
    Route::get('/contact', [FrontController::class, 'contact'])->name('contact');
    Route::get('/electric-bike', [FrontController::class, 'electricBike'])->name('electric-bike');
    Route::get('/gallery', [FrontController::class, 'gallery'])->name('gallery');
    Route::get('/services', [FrontController::class, 'services'])->name('services');
    Route::get('/shop', [EcommerceController::class, 'productListing'])->name('shop');
    Route::get('/product-details/{slug}', [EcommerceController::class, 'productDetails'])->name('product-details');
    Route::get('/privacy-policy', [FrontController::class, 'privacyPolicy'])->name('privacy-policy');
    Route::get('/terms-of-service', [FrontController::class, 'termsOfService'])->name('terms-of-service');
});

Route::post('/cart/add', [EcommerceController::class, 'addToCart'])->name('cart.add');
Route::get('/cart/view', [EcommerceController::class, 'viewCart'])->name('cart.view');
Route::post('/cart/update', [EcommerceController::class, 'update'])->name('cart.update');
Route::get('/cart/remove/{id}', [EcommerceController::class, 'remove'])->name('cart.remove');
Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/success', function () {
    return view('ecommerce.success');
})->name('checkout.success');

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [AccountController::class, 'showOrder'])->name('orders.show');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'loginView'])->name('loginView');
    Route::post('/login', [AdminController::class, 'login'])->name('login');
    Route::get('/logout', [AdminController::class, 'adminLogout'])->name('logout');
    Route::resource('permissions', PermissionController::class);
    Route::resource('roles', RoleController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('permissions', PermissionController::class)->except(['show']);

    Route::middleware(['auth:web', 'IsAdmin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/calendar', [AdminController::class, 'calendar'])->name('calendar');

        Route::prefix('cms-builder')->name('cms-builder.')->group(function () {
            Route::resource('pages', PageController::class);
            Route::get('cms-pages/{page}/view', [CmsPageController::class, 'show'])->name('page.view');
            Route::put('cms-pages/{page}/update-data', [CmsPageController::class, 'updatePageData'])->name('pages.update-data');
            Route::get('/{page}/listable-sections', [ListableSectionController::class, 'index'])->name('pages.listable-sections.index');
            Route::get('/{page}/sections/{section}/items/create', [ListableSectionController::class, 'createItem'])->name('pages.sections.items.create');
            Route::post('/{page}/sections/{section}/items', [ListableSectionController::class, 'storeItem'])->name('pages.sections.items.store');
            Route::get('/{page}/sections/{section}/items/{item}/edit', [ListableSectionController::class, 'editItem'])->name('pages.sections.items.edit');
            Route::put('/{page}/sections/{section}/items/{item}', [ListableSectionController::class, 'updateItem'])->name('pages.sections.items.update');
            Route::delete('/{page}/sections/{section}/items/{item}', [ListableSectionController::class, 'destroyItem'])->name('pages.sections.items.destroy');
            Route::get('/cms-pages/{page}/sections', [PageController::class, 'getPageSections'])->name('pages.sections.fetch');
            Route::get('/cms-sections/{section}/fields', [PageController::class, 'getSectionFields'])->name('sections.fields.fetch');
        });

        Route::prefix('modules-data')->name('modules-data.')->group(function () {
            Route::post('modules', [ListableSectionController::class, 'importData'])->name('import.data');
            Route::post('modules/{sectionId}/process-import', [ListableSectionController::class, 'processImport'])->name('process-import');
            Route::delete('modules/{page}/sections/{section}/items/bulk-delete', [ListableSectionController::class, 'bulkDelete'])->name('bulk-delete');
        });

        Route::resource('data-management', DataManagementController::class)->except(['show']);


        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');

            Route::post('/bulk-action', [CategoryController::class, 'bulkAction'])->name('bulk.action');
            Route::post('/check-slug', [CategoryController::class, 'checkSlug'])->name('check.slug');
        });

        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-action', [ProductController::class, 'bulkAction'])->name('bulk.action');
        });

        Route::prefix('variations')->name('variations.')->group(function () {
            Route::get('/', [VariationController::class, 'index'])->name('index');
            Route::get('/create', [VariationController::class, 'create'])->name('create');
            Route::post('/', [VariationController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [VariationController::class, 'edit'])->name('edit');
            Route::put('/{id}', [VariationController::class, 'update'])->name('update');
            Route::delete('/{id}', [VariationController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-action', [VariationController::class, 'bulkAction'])->name('bulk.action');

            Route::post('/variations/get-options', [VariationController::class, 'getOptions'])->name('getOptions');

            Route::post('/{variationId}/options', [VariationController::class, 'storeOption'])->name('options.store');
            Route::put('/options/{optionId}', [VariationController::class, 'updateOption'])->name('options.update');
            Route::delete('/options/{optionId}', [VariationController::class, 'destroyOption'])->name('options.destroy');
        });

        Route::prefix('units')->name('units.')->group(function () {
            Route::get('/', [UnitController::class, 'index'])->name('index');
            Route::get('/create', [UnitController::class, 'create'])->name('create');
            Route::post('/', [UnitController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [UnitController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UnitController::class, 'update'])->name('update');
            Route::delete('/{id}', [UnitController::class, 'destroy'])->name('destroy');
        });

        Route::resource('suppliers', VendorController::class);
        Route::resource('purchase', PurchaseController::class);

        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        });

        Route::group(['prefix' => 'export/logs', 'as' => 'export.logs.'], function () {
            Route::get('/', [ExportLogController::class, 'index'])->name('index');
            Route::get('selective/create', [ExportLogController::class, 'createSelectiveExport'])->name('selective.create');
            Route::post('selective', [ExportLogController::class, 'storeSelectiveExport'])->name('selective.store');
            Route::get('{log}', [ExportLogController::class, 'download'])->name('download');
            Route::post('{log}/re-export', [ExportLogController::class, 'reExport'])->name('re-export');
            Route::delete('{log}', [ExportLogController::class, 'destroy'])->name('destroy');

            Route::get('import/create', [ExportLogController::class, 'createImport'])->name('import.create');
            Route::post('import', [ExportLogController::class, 'storeImport'])->name('import.store');
            Route::get('import/{importLog}/preview', [ExportLogController::class, 'preview'])->name('import.preview');
            Route::post('import/{importLog}/confirm', [ExportLogController::class, 'confirmImport'])->name('import.confirm');
            Route::get('import/{importLog}/details', [ExportLogController::class, 'detailImport'])->name('import.detail');
            Route::get('import/logs', [ExportLogController::class, 'importLogsIndex'])->name('import.index');
            Route::post('import-logs/{importLog}/retry', [ExportLogController::class, 'retryImport'])->name('import.retry');
        });

        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'store'])->name('settings.store');

        Route::get('profile-settings', [AdminController::class, 'profileSettings'])->name('settings.profile');
        Route::put('profile-settings', [AdminController::class, 'profileSettingsUpdate'])->name('settings.profile.update');
    });
});

// require __DIR__ . '/auth.php';
// Route::get('/login', function () {
//     return redirect()->route('admin.loginView')->with('error', 'Please login first.');
// })->name('login');
