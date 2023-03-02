<?php

namespace App\Http\Controllers\Brands;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;

use App\Repositories\BrandRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * @OA\Info(
 *     description="API Documentation - Basic CRUD Laravel",
 *     version="1.0.0",
 *     title="Basic CRUD Laravel API Documentation",
 *     @OA\Contact(
 *         email="manirujjamanakash@gmail.com"
 *     ),
 *     @OA\License(
 *         name="GPL2",
 *         url="https://devsenv.com"
 *     )
 * )
 */

class BrandsController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * Brand Repository class.
     *
     * @var BrandRepository
     */
    public $brandRepository;

    public function __construct(BrandRepository $brandRepository)
    {
        $this->middleware('auth:api', ['except' => ['indexAll']]);
        $this->brandRepository = $brandRepository;
    }

    /**
     * @OA\GET(
     *     path="/api/brands",
     *     tags={"Brands"},
     *     summary="Get Brand List",
     *     description="Get Brand List as Array",
     *     operationId="index",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200,description="Get Brand List as Array"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->brandRepository->getAll();
            return $this->responseSuccess($data, 'Brand List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function topBrand(Request $request): JsonResponse
    {
        try {
            $data = $this->brandRepository->topBrand($request->perPage);
            return $this->responseSuccess($data, 'Brand List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\GET(
     *     path="/api/brands/view/all",
     *     tags={"Brands"},
     *     summary="All Brands - Publicly Accessible",
     *     description="All Brands - Publicly Accessible",
     *     operationId="indexAll",
     *     @OA\Parameter(name="perPage", description="perPage, eg; 20", example=20, in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="All Brands - Publicly Accessible" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function indexAll(Request $request): JsonResponse
    {
        try {
            $data = $this->brandRepository->getPaginatedData($request->perPage);
            return $this->responseSuccess($data, 'Brand List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/brands/view/search",
     *     tags={"Brands"},
     *     summary="All Brands - Publicly Accessible",
     *     description="All Brands - Publicly Accessible",
     *     operationId="search",
     *     @OA\Parameter(name="perPage", description="perPage, eg; 20", example=20, in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="search", description="search, eg; Test", example="Test", in="query", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="All Brands - Publicly Accessible" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */

    public function search(Request $request): JsonResponse
    {
        try {
            $data = $this->brandRepository->searchBrand($request->search,$request->tagId,$request->catId,$request->entry_feeLessThan, $request->perPage);
            return $this->responseSuccess($data, 'Brand List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\POST(
     *     path="/api/brands",
     *     tags={"Brands"},
     *     summary="Create New Brand",
     *     description="Create New Brand",
     *     operationId="store",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="title", type="string", example="Brand 1"),
     *              @OA\Property(property="description", type="string", example="Description"),
     *              @OA\Property(property="price", type="integer", example=10120),
     *              @OA\Property(property="image", type="string", example=""),
     *          ),
     *      ),
     *      security={{"bearer":{}}},
     *      @OA\Response(response=200, description="Create New Brand" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function store(BrandRequest $request): JsonResponse
    {
        try {
            $brand = $this->brandRepository->create($request->all());
            return $this->responseSuccess($brand, 'New Brand Created Successfully !');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function addBrand(BrandRequest $request): JsonResponse
    {
        try {
            $brand = $this->brandRepository->addBrand($request->all());
            return $this->responseSuccess($brand, 'Brand Created Successfully !');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function myBrand(Request $request): JsonResponse
    {
        try {
            $brand = $this->brandRepository->myBrand($request->perPage);
            return $this->responseSuccess($brand, 'Lsit my Brand Successfully !');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function brandSponsors(): JsonResponse
    {
        try {
            $brandSponsors = $this->brandRepository->brandSponsors();
            if (is_null($brandSponsors))
            {
                return $this->responseError(null, 'Brand Sponsors Not Found', Response::HTTP_NOT_FOUND);
            }
                return $this->responseSuccess($brandSponsors, 'Brand Sponsors Fetch Successfully !');
        } catch (\Exception $e) {
                return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/brands/{id}",
     *     tags={"Brands"},
     *     summary="Show Brand Details",
     *     description="Show Brand Details",
     *     operationId="show",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Show Brand Details"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function show($id): JsonResponse
    {
        try {
            $data = $this->brandRepository->getByID($id);
            if (is_null($data)) {
                return $this->responseError(null, 'Brand Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Brand Details Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\PUT(
     *     path="/api/brands/{id}",
     *     tags={"Brands"},
     *     summary="Update Brand",
     *     description="Update Brand",
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="title", type="string", example="Brand 1"),
     *              @OA\Property(property="description", type="string", example="Description"),
     *              @OA\Property(property="price", type="integer", example=10120),
     *              @OA\Property(property="image", type="string", example=""),
     *          ),
     *      ),
     *     operationId="update",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200, description="Update Brand"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function update(Request $request, $id): JsonResponse
    { 
        try {
            $data = $this->brandRepository->update($id, $request->all());
            if (is_null($data))
                return $this->responseError(null, 'Brand Not Found', Response::HTTP_NOT_FOUND);

            return $this->responseSuccess($data, 'Brand Updated Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function editBrand(Request $request, $id): JsonResponse
    { 
        try {
            $data = $this->brandRepository->update($id??$request->id, $request->all());
            if (is_null($data))
                return $this->responseError(null, 'Brand Not Found', Response::HTTP_NOT_FOUND);

            return $this->responseSuccess($data, 'Brand Updated Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\DELETE(
     *     path="/api/brands/{id}",
     *     tags={"Brands"},
     *     summary="Delete Brand",
     *     description="Delete Brand",
     *     operationId="destroy",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Delete Brand"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function destroy($id): JsonResponse
    {
        try {
            $brand =  $this->brandRepository->getByID($id);
            if (empty($brand)) {
                return $this->responseError(null, 'Brand Not Found', Response::HTTP_NOT_FOUND);
            }

            $deleted = $this->brandRepository->delete($id);
            if (!$deleted) {
                return $this->responseError(null, 'Failed to delete the brand.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->responseSuccess($brand, 'Brand Deleted Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
