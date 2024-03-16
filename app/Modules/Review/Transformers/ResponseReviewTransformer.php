<?php

namespace App\Modules\Review\Transformers;


use App\Modules\Review\Models\ResponseReview;
use App\Modules\Review\Models\Review;
use League\Fractal\TransformerAbstract;

class ResponseReviewTransformer extends TransformerAbstract
{
    /**
     * @param ResponseReview $responseReview
     * @return array
     */
    public function transform(ResponseReview $responseReview): array
    {
        return [
            'id' => $responseReview->id,
            'content' => $responseReview->content,
            'review' => [
                'id' => $responseReview->review_id,
                'content' => $responseReview->review->content,
            ],
            'user' => [
                'id' => $responseReview->user_id,
                'name' => $responseReview->user->name,
                'email' => $responseReview->user->email,
            ],
            'created_at' => $responseReview->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
