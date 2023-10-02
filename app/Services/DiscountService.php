<?php

namespace App\Services;

use App\Models\CustomerOrder;
use App\Models\CustomerOrderItem;
use App\Models\DiscountRule;

class DiscountService
{
    public function discounts($order_id)
    {
        $order = CustomerOrder::find($order_id);
        $typeOneDiscounts = $this->totalAmount($order);
        $typeTwoDiscounts = $this->totalCategoryProductCount($order);
        $typeThreeDiscounts = $this->totalCategoryCount($order);

        $discounts = collect([$typeOneDiscounts, $typeTwoDiscounts, $typeThreeDiscounts])->collapse();


        return [
            'orderId' => $order_id,
            'discounts' => $discounts->toArray(),
            'totalDiscount' => $discounts->sum('discountAmount'),
            'discountedTotal' => $order->items->sum('total')
        ];
    }

    public function totalAmount(CustomerOrder $order)
    {
        $result = [];

        $discounts = DiscountRule::where('type', 'totalAmount')->get();
        $orderTotal = $order->items->sum('total');

        foreach ($discounts as $discount) {
            if ($orderTotal >= $discount->threshold) {

                $discountAmount = $this->calculateDiscountAmount($orderTotal, $discount->action_type, $discount->discount);
                $result[] = [
                    'discountReason' => $discount->reason,
                    'discountAmount' => $discountAmount,
                    'subtotal' => $orderTotal - $discountAmount
                ];
            }
        }

        return $result;
    }

    public function totalCategoryProductCount(CustomerOrder $order): array
    {
        $result = [];

        $discounts = DiscountRule::where('type', 'totalCategoryProductCount')->get();

        foreach ($discounts as $discount) {
            foreach ($order->items as $item) {

                if ($discount->category_id == $item->product->category_id
                    && $item->quantity >= $discounts->threshold) {

                    $discountAmount = $this->calculateDiscountAmount($item->total,
                        $discount->action_type, ($item->unit_price * $item->discount));

                    $result[] = [
                        'discountReason' => $discount->reason,
                        'discountAmount' => $discountAmount,
                        'subtotal' => $item->total - $discountAmount
                    ];
                }
            }
        }

        return $result;
    }

    public function totalCategoryCount(CustomerOrder $order): array
    {
        $result = [];

        $discounts = DiscountRule::where('type', 'totalCategoryCount')->get();

        foreach ($discounts as $discount) {
            $customerOrderItems = CustomerOrderItem::where('customer_order_id', $order->id)
                ->join('products', 'products.id', '=', 'customer_order_items.product_id')
                ->where('products.category_id', $discount->category_id)
                ->get();



            if ($customerOrderItems->sum('quantity') >= $discount->threshold) {
                $discountAmount = $this->calculateDiscountAmount($customerOrderItems->min('unit_price'),
                    $discount->action_type, $discount->discount);

                $result[] = [
                    'discountReason' => $discount->reason,
                    'discountAmount' => $discountAmount,
                    'subtotal' => $customerOrderItems->sum('total') - $discountAmount
                ];
            }
        }

        return $result;
    }

    public function calculateDiscountAmount($total, $actionType, $discount): float
    {
        return match ($actionType) {
            'percent' => ($total * $discount) / 100,
            'fixed' => $total - $discount
        };
    }
}
