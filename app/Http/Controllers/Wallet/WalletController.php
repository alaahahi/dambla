<?php

namespace App\Http\Controllers\Wallet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\WalletRepository;
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

class WalletController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * Wallet Repository class.
     *
     * @var WalletRepository
     */
    public $walletRepository;

    public function __construct(WalletRepository $walletRepository)
    {
        $this->middleware('auth:api', ['except' => ['indexAll']]);
        $this->walletRepository = $walletRepository;
    }

    /**
     * @OA\GET(
     *     path="/api/wallets",
     *     tags={"Wallets"},
     *     summary="Get Wallet List",
     *     description="Get Wallet List as Array",
     *     operationId="index",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200,description="Get Wallet List as Array"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $data = $this->walletRepository->getAll();
            return $this->responseSuccess($data, 'Wallet List Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/wallets/view/all",
     *     tags={"Wallets"},
     *     summary="All Wallets - Publicly Accessible",
     *     description="All Wallets - Publicly Accessible",
     *     operationId="indexAll",
     *     @OA\Parameter(name="perPage", description="perPage, eg; 20", example=20, in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="All Wallets - Publicly Accessible" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function indexAll(Request $request): JsonResponse
    {
        try {
            $data = $this->walletRepository->getPaginatedData($request->perPage);
            return $this->responseSuccess($data, 'Wallet List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/wallets/view/search",
     *     tags={"Wallets"},
     *     summary="All Wallets - Publicly Accessible",
     *     description="All Wallets - Publicly Accessible",
     *     operationId="search",
     *     @OA\Parameter(name="perPage", description="perPage, eg; 20", example=20, in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="search", description="search, eg; Test", example="Test", in="query", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="All Wallets - Publicly Accessible" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $data = $this->walletRepository->searchWallet($request->search, $request->perPage);
            return $this->responseSuccess($data, 'Wallet List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\POST(
     *     path="/api/wallets",
     *     tags={"Wallets"},
     *     summary="Create New Wallet",
     *     description="Create New Wallet",
     *     operationId="store",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="title", type="string", example="Wallet 1"),
     *              @OA\Property(property="description", type="string", example="Description"),
     *              @OA\Property(property="price", type="integer", example=10120),
     *              @OA\Property(property="image", type="string", example=""),
     *          ),
     *      ),
     *      security={{"bearer":{}}},
     *      @OA\Response(response=200, description="Create New Wallet" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $wallet = $this->walletRepository->create($request->all());
            return $this->responseSuccess($wallet, 'New Wallet Created Successfully !');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/wallets/{id}",
     *     tags={"Wallets"},
     *     summary="Show Wallet Details",
     *     description="Show Wallet Details",
     *     operationId="show",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Show Wallet Details"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function show($id): JsonResponse
    {
        try {
            $data = $this->walletRepository->getByID($id);
            if (is_null($data)) {
                return $this->responseError(null, 'Wallet Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Wallet Details Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function infoWallet(): JsonResponse
    {
        try {
            $data = $this->walletRepository->infoWallet();
            if (is_null($data)) {
                return $this->responseError(null, 'Wallet Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Wallet Details Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\PUT(
     *     path="/api/wallets/{id}",
     *     tags={"Wallets"},
     *     summary="Update Wallet",
     *     description="Update Wallet",
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="title", type="string", example="Wallet 1"),
     *              @OA\Property(property="description", type="string", example="Description"),
     *              @OA\Property(property="price", type="integer", example=10120),
     *              @OA\Property(property="image", type="string", example=""),
     *          ),
     *      ),
     *     operationId="update",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200, description="Update Wallet"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $data = $this->walletRepository->update($id, $request->all());
            if (is_null($data))
                return $this->responseError(null, 'Wallet Not Found', Response::HTTP_NOT_FOUND);

            return $this->responseSuccess($data, 'Wallet Updated Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\DELETE(
     *     path="/api/wallets/{id}",
     *     tags={"Wallets"},
     *     summary="Delete Wallet",
     *     description="Delete Wallet",
     *     operationId="destroy",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Delete Wallet"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function destroy($id): JsonResponse
    {
        try {
            $wallet =  $this->walletRepository->getByID($id);
            if (empty($wallet)) {
                return $this->responseError(null, 'Wallet Not Found', Response::HTTP_NOT_FOUND);
            }

            $deleted = $this->walletRepository->delete($id);
            if (!$deleted) {
                return $this->responseError(null, 'Failed to delete the wallet.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->responseSuccess($wallet, 'Wallet Deleted Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
