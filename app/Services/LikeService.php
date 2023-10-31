<?php

namespace App\Services;

use App\Models\Like;
use App\Models\Product;

class LikeService
{
    public function toggleLike($data, $user)
    {
        $product = Product::findOrFail($data['product_id']);

        $like = Like::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($like) {
            $like->delete();
            return 'No te gusta!';
        } else {
            Like::create([
                'user_id' => $user->id,
                'product_id' => $data['product_id']
            ]);
            return 'Te gusta!';
        }
    }
}
