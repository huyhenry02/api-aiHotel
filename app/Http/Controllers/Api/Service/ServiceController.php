<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\ApiController;
use App\Http\Requests\PaginationRequest;
use App\Modules\Service\Repositories\Interfaces\ServiceInterface;
use App\Modules\Service\Requests\CreateServiceRequest;
use App\Modules\Service\Requests\GetOneServiceRequest;
use App\Modules\Service\Requests\UpdateServiceRequest;
use App\Modules\Service\Transformers\ServiceTransformer;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ServiceController extends ApiController
{
    use AuthorizesRequests, ValidatesRequests;
    public ServiceInterface $serviceRepo;
    public function __construct(ServiceInterface $serviceRepo)
    {
        $this->serviceRepo = $serviceRepo;
    }
    public function createService(CreateServiceRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $service = $this->serviceRepo->create($data);
            DB::commit();
            $data = fractal($service, new ServiceTransformer())->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function getOneService(GetOneServiceRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $service = $this->serviceRepo->find($data['service_id']);
            if (!$service) {
                return $this->respondError(__('messages.not_found'));

            }
            $data = fractal($service, new ServiceTransformer())->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function updateService(UpdateServiceRequest $request): JsonResponse
    {
        $postData = $request->validated();
        try {
            DB::beginTransaction();
            $service = $this->serviceRepo->find($postData['service_id']);
            if (!$service) {
                return $this->respondError(__('messages.not_found'));
            }
            $service->fill($postData);
            $service->save();
            $data = fractal($service, new ServiceTransformer())->toArray();
            $response = $this->respondSuccess($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function deleteService(GetOneServiceRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $service = $this->serviceRepo->find($request['service_id']);
            if (!$service) {
                throw new Exception('Room type not found');
            }
            $service->delete();
            $service->hotels()->detach();
            DB::commit();
            $response = $this->respondSuccess('Room type deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    public function getServices(PaginationRequest $request): JsonResponse
    {
        try {
            $postData = $request->validated('per_page', '15');
            $services = $this->serviceRepo->getData(perPage: $postData);
            $data = fractal($services, new ServiceTransformer())->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }
}
