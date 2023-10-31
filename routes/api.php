<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfirmCount;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MemoryController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SoldOrderController;
use App\Http\Controllers\SuggestedController;
use App\Http\Controllers\OrderStateController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\NumberColorController;
use App\Http\Controllers\TypeProductController;
use App\Http\Controllers\LandingImageController;
use App\Http\Controllers\OrderPaymentController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\CustomerAddressController;
use App\Http\Controllers\EmailVerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\SoldOrderPaymentImageController;
// use PDF;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum', 'verified')->group(function () {
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return new UserResource(User::findOrFail($request->user()->id));
    });
    Route::get('/users', [AuthController::class, 'index']);

    Route::post('/logout', [AuthController::class, 'logout']);

    //Almacenar ordenes

    Route::apiResource('orders', OrderController::class);
    Route::apiResource('sold_orders', SoldOrderController::class);
    Route::apiResource('purchase_orders', PurchaseOrderController::class);


    Route::apiResource('/likes', LikeController::class);

    Route::delete('payments', [PaymentController::class, 'destroy']);
    Route::apiResource('payments', PaymentController::class);

    Route::apiResource('sold-order-payments', SoldOrderPaymentImageController::class);

    Route::apiResource('order_payments', OrderPaymentController::class);

    Route::apiResource('order_state', OrderStateController::class);

    Route::apiResource('addresses', AddressController::class);


    Route::delete('sizes', [SizeController::class, 'destroy']);
    Route::apiResource('sizes', SizeController::class);

    Route::apiResource('number_colors', NumberColorController::class);

    Route::apiResource('inventory', InventoryController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('customer-addresses', CustomerAddressController::class);
    Route::apiResource('products', ProductController::class);
});
Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('throttle:fourByHour', 'auth:sanctum',)->name('verification.send');
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware(['throttle:2'])->name('verification.verify');


// Confirm count
Route::post('/confirm', [ConfirmCount::class, 'store']);

// Memories
Route::apiResource('memories', MemoryController::class)->except([
    'create', 'store', 'update', 'destroy'
]);
Route::middleware('auth:sanctum')->post('memories', [MemoryController::class, 'store']);
Route::middleware('auth:sanctum')->put('memories/{memory}', [MemoryController::class, 'update']);
Route::middleware('auth:sanctum')->delete('memories/{memory}', [MemoryController::class, 'destroy']);

// suggesteds
Route::apiResource('/suggesteds', SuggestedController::class)->except([
    'create', 'store', 'update', 'destroy'
]);
Route::middleware('auth:sanctum')->post('suggesteds', [SuggestedController::class, 'store']);
Route::middleware('auth:sanctum')->delete('suggesteds/{suggested}', [SuggestedController::class, 'update']);

// suggestions
Route::apiResource('suggestions', SuggestionController::class)->except([
    'create', 'store', 'update', 'destroy'
]);
Route::middleware('auth:sanctum')->post('suggestions', [SuggestionController::class, 'store']);
Route::middleware('auth:sanctum')->put('suggestions/{suggestion}', [SuggestionController::class, 'update']);

// Landing
Route::apiResource('/landings', LandingImageController::class)->except([
    'create', 'store', 'update', 'destroy'
]);
Route::middleware('auth:sanctum')->post('/landings', [LandingImageController::class, 'store']);

// Products
Route::get('/public-products', [ProductController::class, 'publicIndex']);


// Categories
Route::apiResource('/categories', CategoryController::class)->except([
    'create', 'store', 'update', 'destroy'
]);
Route::middleware('auth:sanctum')->post('/categories', [CategoryController::class, 'store']);
Route::middleware('auth:sanctum')->put('/categories/{category}', [CategoryController::class, 'update']);

// Type products
Route::get('/type_products', [TypeProductController::class, 'index']);
Route::middleware('auth:sanctum')->post('/type_products', [TypeProductController::class, 'store']);
Route::get('/type_products/{type_product}', [TypeProductController::class, 'show']);
Route::middleware('auth:sanctum')->put('/type_products/{type_product}', [TypeProductController::class, 'update']);

// Groups
Route::get('/groups', [GroupController::class, 'index']);
Route::middleware('auth:sanctum')->post('/groups', [GroupController::class, 'store']);
Route::get('/groups/{group}', [GroupController::class, 'show']);
Route::middleware('auth:sanctum')->put('/groups/{group}', [GroupController::class, 'update']);

// Groups
Route::apiResource('products.product_image', ProductImageController::class);



//Autenticacion
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/login', function () {

    return "ok";
})->name('login');

Route::get('/mostrar/{request}', function ($request) {

    echo $request;
})->name('mostrar');

// Route::get('/generate-pdf/{invoiceId}', [InvoiceController::class, 'generatePDF']);
// use PDF;


Route::get('/generate-pdf', function () {

    // $snappy = App::make('snappy.pdf');
    $pdf = PDF::loadView('pdf.example', [
        'title' => "quieroLab"
    ]);

    $pdf->setOptions([
        'margin-top' => '50',
        'page-size' => 'a4',
        // 'orientation' => 'landscape',
        'enable-local-file-access' => true,
        // 'enable-external-links' => true
    ]);

    // $pdf->setOption('margin-top', '50');
    // $pdf->setOption('page-size', 'a4');
    // $pdf->setOption('orientation', 'landscape');
    return $pdf->inline('example.pdf');
});

// Route::get('/view-pdf/{soldOrderId}', [InvoiceController::class, 'showPDF']);
Route::get('/view-pdf', function () {
    return view('pdf.example', [
        'title' => "quieroLab"
    ]);
});
