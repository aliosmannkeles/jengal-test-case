<?php

namespace App\Http\Controllers;

use App\Http\Requests\Discount\DiscountRequest;
use App\Services\DiscountService;
use Illuminate\Http\JsonResponse;

class DiscountController extends Controller
{
    private DiscountService $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    public function discounts(DiscountRequest $request): JsonResponse
    {
        $discounts = $this->discountService->discounts($request->input('order_id'));

        return response()->json([
            'data' => $discounts
        ]);
    }
}
