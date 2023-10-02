<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\ListOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @param ListOrderRequest $request
     * @return JsonResponse
     */
    public function index(ListOrderRequest $request): JsonResponse
    {
        $orders = $this->orderService->list($request->input('customer_id'));

        return response()->json(['data' => $orders]);
    }

    /**
     *
     * @param CreateOrderRequest $request
     * @return JsonResponse
     */
    public function store(CreateOrderRequest $request): JsonResponse
    {
        $this->orderService->store($request);

        return response()->json(['data' => ['success' => true]]);
    }

    /**
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $order = $this->orderService->find($id);

        return response()->json(['data' => $order]);
    }


    /**
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $order = $this->orderService->destroy($id);

        return response()->json(['data' => ['success' => true]]);
    }
}
