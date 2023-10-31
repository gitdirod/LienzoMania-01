<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\PurchaseOrderService;
use App\Services\SoldOrderProductService;
use App\Services\PurchaseOrderProductService;
use App\Http\Resources\PurchaseOrderCollection;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Http\Requests\StoragePurchaseOrderRequest;

class PurchaseOrderController extends Controller
{
    use ApiResponse;
    protected $purchaseOrderService;
    protected $purchaseOrderProductService;
    protected $soldOrderProductService;
    protected $productService;
    public function __construct(PurchaseOrderService $purchaseOrderService, PurchaseOrderProductService $purchaseOrderProductService, SoldOrderProductService $soldOrderProductService, ProductService $productService)
    {
        $this->middleware('auth');
        $this->middleware('can:admin');
        $this->purchaseOrderService = $purchaseOrderService;
        $this->purchaseOrderProductService = $purchaseOrderProductService;
        $this->soldOrderProductService = $soldOrderProductService;
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = new PurchaseOrderCollection(
            PurchaseOrder::orderBy('id', 'DESC')->get()
        );
        return $this->successResponse('Compras recuperadas con exito.', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoragePurchaseOrderRequest $request)
    {
        $soldOrderProductService = $this->soldOrderProductService;
        $purchaseOrderService = $this->purchaseOrderService;
        $purchaseOrderProductService = $this->purchaseOrderProductService;
        $productService = $this->productService;
        try {
            return DB::transaction(function () use ($request, $purchaseOrderService, $purchaseOrderProductService, $soldOrderProductService, $productService) {
                $data = $request->validated();
                $user = Auth::user();
                $order = $purchaseOrderService->createPurchaseOrder($request, $user);
                $purchaseOrderProductService->insertPurchaseOrderProducts($data['products'], $order->id);

                // Aqui debo agregar  el resto
                $idsProductsToUpdate = $purchaseOrderProductService->getIdsProductToUpdate($data['products'], []);
                $purchasedList = $purchaseOrderProductService->getPurchasedOrderProductsTotalsById($idsProductsToUpdate->toArray());
                $solledList = $soldOrderProductService->getSelledProductsTotalsById($idsProductsToUpdate->toArray());

                $productService->updateProductsStatsByIds($idsProductsToUpdate->toArray(), $purchasedList, $solledList);


                return $this->successResponse('Compra realizada correctamente.', 201);
            });
        } catch (\Exception $e) {
            return $this->errorResponse('Error inesperado.', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        $soldOrderProductService = $this->soldOrderProductService;
        $purchaseOrderService = $this->purchaseOrderService;
        $purchaseOrderProductService = $this->purchaseOrderProductService;
        $productService = $this->productService;
        try {
            //
            return DB::transaction(function () use ($request, $purchaseOrder, $purchaseOrderProductService, $purchaseOrderService, $soldOrderProductService, $productService) {
                $datos = $request->validated();

                $IdproductsAndIdPurchaseOrderProducts = $purchaseOrderService->getIdProductsAndIdsPurchassedOrderProduct($datos['id']);


                $purchaseOrderProductService->syncPurchaseOrderProducts($datos['products'], $IdproductsAndIdPurchaseOrderProducts->toArray(), $purchaseOrder->id);
                $idsProductsToUpdate = $purchaseOrderProductService->getIdsProductToUpdate($datos['products'], $IdproductsAndIdPurchaseOrderProducts->toArray());
                $purchaseOrderService->updatePurchaseOrder($datos, $purchaseOrder->id);



                $purchasedList = $purchaseOrderProductService->getPurchasedOrderProductsTotalsById($idsProductsToUpdate->toArray());
                $solledList = $soldOrderProductService->getSelledProductsTotalsById($idsProductsToUpdate->toArray());

                $productService->updateProductsStatsByIds($idsProductsToUpdate->toArray(), $purchasedList, $solledList);
                return $this->successResponse('Compra actualizada.', $idsProductsToUpdate);
            });
        } catch (\Exception $e) {
            return $this->errorResponse('Error inesperado.', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        //
    }
}
