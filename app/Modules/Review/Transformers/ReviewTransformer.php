<?php

namespace App\Modules\Review\Transformers;


use App\Modules\Review\Models\Review;
use League\Fractal\TransformerAbstract;

class ReviewTransformer extends TransformerAbstract
{
    /**
     * @param Review $review
     * @return array
     */
    public function transform(Review $review): array
    {
        return [
            'id' => $review->id,
            'content' => $review->content,
            'rating' => $review->rating,
            'room' => [
                'id' => $review->room_id,
                'code' => $review->room->code,
            ],
            'user' => [
                'id' => $review->user_id,
                'name' => $review->user->name,
                'email' => $review->user->email,
            ],
            'created_at' => $review->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
