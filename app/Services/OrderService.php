<?php

namespace App\Services;

use App\Http\Requests\Order\CreateOrderRequest;
use App\Models\CustomerOrder;
use App\Models\CustomerOrderItem;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class OrderService
{
    /**
     *
     * @param int|null $customerId
     * @return Collection
     */
    public function list(int|null $customerId): Collection
    {
        $query = CustomerOrder::with('items');

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        return $query->orderByDesc('created_at')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'items' => $order->items->map(function ($item) {
                        return [
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price,
                            'total' => $item->total,
                        ];
                    }),
                    'total' => $order->items->sum('total')
                ];
            });
    }

    /**
     *
     * @param int $customerId
     * @return array
     */
    public function find(int $customerId): array
    {
        $customerOrder = CustomerOrder::find($customerId);

        abort_if(!$customerOrder, 404);

        return [
            'id' => $customerOrder->id,
            'customer_id' => $customerOrder->customer_id,
            'items' => $customerOrder->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total' => $item->total,
                ];
            }),
            'total' => $customerOrder->items->sum('total')
        ];
    }

    /**
     *
     * @param CreateOrderRequest $request
     * @throws ValidationException
     */
    public function store(CreateOrderRequest $request): void
    {
        $items = $request->input('items');
        $productService = new ProductService();
        $productService->checkStock($items);

        $order = CustomerOrder::create([
            'customer_id' => $request->input('customer_id')
        ]);


        foreach ($items as $item) {
            $product = Product::find($item['product_id']);

            CustomerOrderItem::create([
                'customer_order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'unit_price' => $product->price,
                'total' => $item['quantity'] * $product->price
            ]);

            $product->update([
                'stock' => $product->stock - $item['quantity']
            ]);
        }
    }

    /**
     *
     * @param int $customerId
     */
    public function destroy(int $customerId): void
    {
        $customerOrder = CustomerOrder::find($customerId);

        abort_if(!$customerOrder, 404);

        $customerOrder->delete();
    }
}
