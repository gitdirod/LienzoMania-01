<?php

namespace App\Http\Controllers;

use App\Models\SoldOrder;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Services\AddressService;
use App\Services\ProductService;
use App\Services\SoldOrderService;
use Illuminate\Support\Facades\Auth;
use App\Services\SoldOrderAddressService;
use App\Services\SoldOrderPaymentService;
use App\Services\SoldOrderTrackingService;
use App\Http\Resources\SoldOrderCollection;
use App\Http\Requests\StoreSoldOrderRequest;
use App\Http\Requests\UpdateSoldOrderRequest;
use App\Models\CustomerAddress;
use App\Services\CustomerAddressService;
use App\Services\PurchaseOrderProductService;
use App\Services\SoldOrderProductService;
use Illuminate\Support\Facades\DB;

class SoldOrderController extends Controller
{

    protected $soldOrderService;
    protected $soldOrderPaymentService;
    protected $soldOrderTrackingService;
    protected $soldOrderAddressService;
    protected $soldOrderProductService;
    protected $customerAddressService;
    use ApiResponse;
    public function __construct(SoldOrderService $soldOrderService, SoldOrderPaymentService $soldOrderPaymentService, SoldOrderTrackingService $soldOrderTrackingService, CustomerAddressService $customerAddressService, SoldOrderAddressService $soldOrderAddressService, SoldOrderProductService $soldOrderProductService)
    {
        $this->middleware('auth');
        $this->soldOrderService = $soldOrderService;
        $this->soldOrderPaymentService = $soldOrderPaymentService;
        $this->soldOrderTrackingService = $soldOrderTrackingService;
        $this->soldOrderAddressService = $soldOrderAddressService;
        $this->soldOrderProductService = $soldOrderProductService;
        $this->customerAddressService = $customerAddressService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $baseQuery = SoldOrder::orderBy('id', 'DESC');

        if ($user && $user->isAdmin()) {
            $orders = $baseQuery->with('user', 'addresses')->get();
        } else {
            $orders = $baseQuery->where('user_id', $user->id)->get();
        }
        $orders = new SoldOrderCollection($orders);
        return $this->successResponse('Ordenes recuperdas correctamente.', $orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSoldOrderRequest $request, ProductService $productService,  AddressService $addressService, ImageService $imageService)
    {
        $soldOrderService = $this->soldOrderService;
        $soldOrderTrackingService = $this->soldOrderTrackingService;
        $soldOrderPaymentService = $this->soldOrderPaymentService;
        $soldOrderAddressService = $this->soldOrderAddressService;
        $soldOrderProductService = $this->soldOrderProductService;
        $customerAddressService = $this->customerAddressService;


        try {
            return DB::transaction(function () use ($request, $productService, $customerAddressService, $addressService, $imageService, $soldOrderService, $soldOrderTrackingService, $soldOrderPaymentService, $soldOrderAddressService, $soldOrderProductService) {
                $user = Auth::user();
                $datos = $request->validated();
                $products = $productService->validateStockAndPreparePurchaseDetails($datos['products']);
                $soldOrder = $soldOrderService->createOrder($user, $datos);


                // Crear estados de la order, pago y ubicacion
                $soldOrderTrackingService->createSoldOrderTracking($soldOrder->id);
                $soldOrderPaymentService->createSoldOrderPayment($soldOrder->id);

                $address_send = null;
                $address_envoice = null;
                // Address

                if (isset($datos['addresses']) && isset($datos['addresses']['send_id']) && isset($datos['addresses']['envoice_id'])) {
                    $address_send = $addressService->getAddress($datos['addresses']['send_id']);
                    $address_envoice = $addressService->getAddress($datos['addresses']['envoice_id']);
                } elseif (isset($datos['customer_addresses']) && isset($datos['customer_addresses']['send_id']) && isset($datos['customer_addresses']['envoice_id'])) {
                    $address_send = $customerAddressService->getAddress($datos['customer_addresses']['send_id']);
                    $address_envoice = $customerAddressService->getAddress($datos['customer_addresses']['envoice_id']);
                }
                // return $this->successResponse('ok',  $request);

                $soldOrderAddressService->createOrderAddressFromAddress($soldOrder->id, $address_send);
                $soldOrderAddressService->createOrderAddressFromAddress($soldOrder->id, $address_envoice);

                // Inserta imagen de pago en archivo y base de datos
                if (isset($datos['image'])) {
                    $imageService->insertImagePayment($datos['image'], $soldOrder->id, $user->id);
                }

                // Inserta la lista de productos a la orden
                $soldOrderProductService->insertSoldOrderProducts($products, $soldOrder->id);

                return $this->successResponse('Orden realizada correctamente.');
            });
        } catch (\Exception $e) {
            return $this->errorResponse('Error inesperado.', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SoldOrder  $soldOrder
     * @return \Illuminate\Http\Response
     */
    public function show(SoldOrder $soldOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SoldOrder  $soldOrder
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSoldOrderRequest $request, PurchaseOrderProductService $purchaseOrderProductService, ProductService $productService)
    {
        $soldOrderService = $this->soldOrderService;
        $soldOrderPaymentService = $this->soldOrderPaymentService;
        $soldOrderTrackingService = $this->soldOrderTrackingService;
        $soldOrderProductService = $this->soldOrderProductService;

        try {
            return DB::transaction(function () use ($request, $purchaseOrderProductService, $productService, $soldOrderService, $soldOrderPaymentService, $soldOrderTrackingService, $soldOrderProductService) {

                $datos = $request->validated();

                $soldOrderService->updateEnvoice($datos);
                $soldOrderPaymentService->updateSoldOrderPayment($datos['id'], $datos['sold_order_payment']);
                $soldOrderTrackingService->updateSoldOrderTracking($datos['id'], $datos['sold_order_tracking']);

                $Idproducts = $soldOrderService->getIdsSoldOrderProduct($datos['id']);
                $purchasedList = $purchaseOrderProductService->getPurchasedOrderProductsTotalsById($Idproducts->toArray());
                $solledList = $soldOrderProductService->getSelledProductsTotalsById($Idproducts->toArray());

                $productService->updateProductsStatsByIds($Idproducts->toArray(), $purchasedList, $solledList);

                return $this->successResponse('Orden actualizada.');
            });
        } catch (\Exception $e) {
            return $this->errorResponse("Ha ocurrido un error al procesar la actualización", $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SoldOrder  $soldOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(SoldOrder $soldOrder)
    {
        //
    }
}
