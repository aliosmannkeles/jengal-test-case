<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Validation\ValidationException;

class ProductService
{
    public function checkStock(array $items): void
    {
        $productErrors = [];

        foreach ($items as $item) {
            $product = Product::find($item['product_id']);

            if ($item['quantity'] > $product->stock) {
                $productErrors[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'stock' => $product->stock
                ];
            }
        }

        if (!empty($productErrors)) {
            throw ValidationException::withMessages([
                'message' => 'Stok yetersiz',
                'items' => $productErrors
            ]);
        }
    }

}
