<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Traits\ApiResponse;
use App\Services\ImageService;
use App\Services\OrderService;
use App\Services\AddressService;
use App\Services\ProductService;
use App\Services\OrderStateService;
use Illuminate\Support\Facades\Auth;
use App\Services\OrderAddressService;
use App\Services\OrderPaymentService;
use App\Services\OrderProductService;
use App\Http\Resources\OrderCollection;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Services\PurchaseOrderProductService;

class OrderController extends Controller
{
    protected $orderService;
    use ApiResponse;
    public function __construct(OrderService $orderService)
    {
        $this->middleware('auth');
        $this->orderService = $orderService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $user = Auth::user();
        $baseQuery = Order::orderBy('id', 'DESC');

        if ($user && $user->isAdmin()) {
            $orders = $baseQuery->with('user', 'addresses')->get();
        } else {
            $orders = $baseQuery->where('user_id', $user->id)->get();
        }
        $orders = new OrderCollection($orders);
        return $this->successResponse('Ordenes recuperdas correctamente.', $orders);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request, ProductService $productService, OrderStateService $orderStateService, OrderPaymentService $orderPaymentService, AddressService $addressService, OrderAddressService $orderAddressService, ImageService $imageService, OrderProductService $orderProductService)
    {
        $user = Auth::user();
        $datos = $request->validated();
        $products = $productService->validateStockAndPreparePurchaseDetails($datos['products']);
        $order = $this->orderService->createOrder($user, $datos);

        // Crear estados de la order, pago y ubicacion
        $orderStateService->createOrderState($order->id);
        $orderPaymentService->createOrderPayment($order->id);

        // Address
        $address_send = $addressService->getAddress($datos['addresses']['send_id']);
        $address_envoice = $addressService->getAddress($datos['addresses']['envoice_id']);

        $orderAddressService->createOrderAddressFromAddress($order->id, $address_send);
        $orderAddressService->createOrderAddressFromAddress($order->id, $address_envoice);

        // Inserta imagen de pago en archivo y base de datos
        if (isset($datos['image'])) {
            $imageService->insertImagePayment($datos['image'], $order->id, $user->id);
        }
        // Inserta la lista de productos a la orden
        $orderProductService->createOrderProduct($products, $order->id);

        return $this->successResponse('Orden realizada correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Order $order, OrderService $orderService, OrderPaymentService $orderPaymentService, OrderStateService $orderStateService, PurchaseOrderProductService $purchaseOrderProductService)
    {
        $user = $request->user();

        $datos = $request->validated();

        $orderPaymentService->updateOrderPayment($datos['id'], $datos['order_payment']);
        $orderStateService->updateOrderPayment($datos['id'], $datos['order_state']);

        $Idproducts = $orderService->getIdsOrderProduct($datos['id']);
        $purchasedList = $purchaseOrderProductService->getPurchasedProductsById($Idproducts->toArray());
        return [
            'state' => false,
            'message' => "Orden actualizada",
            'purchasedList' => $purchasedList,
            'Idproducts' => $Idproducts
        ];

        // $order->updateProductsUnits();

        // if (isset($datos['envoice'])) {
        //     $order->envoice = $datos['envoice'];
        // }

        $order->save();
        return [
            'state' => true,
            'message' => "Orden actualizada"
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}

// if (!$user->email_verified_at) {
//     return [
//         'state' => false,
//         'message' => "Verifica primero tu cuenta"
//     ];
// }
// if ($user->role != "admin" || !Auth::user()->email_verified_at) {
//     return [
//         'message' => "Usuario NO autorizado",
//         'state' => false
//     ];
// }