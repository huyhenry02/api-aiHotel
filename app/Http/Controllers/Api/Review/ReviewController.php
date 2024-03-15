<?php

namespace App\Http\Controllers\Api\Review;

use App\Http\Controllers\ApiController;
use App\Http\Requests\PaginationRequest;
use App\Modules\Review\Repositories\Interfaces\ReviewInterface;
use App\Modules\Review\Requests\CreateReviewRequest;
use App\Modules\Review\Requests\FilterReviewsRequest;
use App\Modules\Review\Requests\GetOneReviewRequest;
use App\Modules\Review\Transformers\ReviewTransformer;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ReviewController extends ApiController
{
    /**
     * @var ReviewInterface
     */
    protected ReviewInterface $reviewRepo;

    /**
     * @param ReviewInterface $review
     */
    public function __construct(ReviewInterface $reviewRepo)
    {
        $this->reviewRepo = $reviewRepo;
    }

    /**
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function getListReview(PaginationRequest $request): JsonResponse
    {
        try {
            $postData = $request->validated('per_page', 15);
            $reviews = $this->reviewRepo->getData(perPage: $postData);
            $data = fractal($reviews, new ReviewTransformer())->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }
    public function filterReviews(FilterReviewsRequest $request): JsonResponse
    {
        try {
            $postData = $request->validated();
            $reviews = $this->reviewRepo->filterReviews($postData);
            $data = fractal($reviews, new ReviewTransformer())->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    /**
     * @param GetOneReviewRequest $request
     * @return JsonResponse
     */
    public function getOneReview(GetOneReviewRequest $request): JsonResponse
    {
        try {
            $postData = $request->validated();
            $review = $this->reviewRepo->find($postData['review_id']);
            if (!$review) {
                throw new Exception(__('messages.not_found'));
            }
            $data = fractal($review, new ReviewTransformer())->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    /**
     * @param CreateReviewRequest $request
     * @return JsonResponse
     */
    public function createReview(CreateReviewRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $postData = $request->validated();
            $postData['user_id'] = auth()->id();
            $review = $this->reviewRepo->create($postData);
            $data = fractal($review, new ReviewTransformer())->toArray();
            $response = $this->respondSuccess($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    /**
     * @param GetOneReviewRequest $request
     * @return JsonResponse
     */
    public function deleteReview(GetOneReviewRequest $request): JsonResponse
    {
        try {
            $postData = $request->validated();
            $review = $this->reviewRepo->find($postData['review_id']);
            if (!$review) {
                throw new Exception(__('messages.not_found'));
            }
            $review->delete();
            $response = $this->respondSuccess(__('messages.deleted'));
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }
}
