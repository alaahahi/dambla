<?php

namespace App\Http\Controllers\Card;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\CardRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Repositories\AuthRepository;

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

class CardController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * Card Repository class.
     *
     * @var CardRepository
     */
    public $cardRepository;

    public function __construct(CardRepository $cardRepository)
    {
        $this->middleware('auth:api', ['except' => ['indexAll']]);
        $this->cardRepository = $cardRepository;
    }

    /**
     * @OA\GET(
     *     path="/api/cards",
     *     tags={"Cards"},
     *     summary="Get Card List",
     *     description="Get Card List as Array",
     *     operationId="index",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200,description="Get Card List as Array"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $data = $this->cardRepository->getAll();
            return $this->responseSuccess($data, 'Card List Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/cards/view/all",
     *     tags={"Cards"},
     *     summary="All Cards - Publicly Accessible",
     *     description="All Cards - Publicly Accessible",
     *     operationId="indexAll",
     *     @OA\Parameter(name="perPage", description="perPage, eg; 20", example=20, in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="All Cards - Publicly Accessible" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function indexAll(Request $request): JsonResponse
    {
        try {
            $data = $this->cardRepository->getPaginatedData($request->perPage);
            return $this->responseSuccess($data, 'Card List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/cards/view/search",
     *     tags={"Cards"},
     *     summary="All Cards - Publicly Accessible",
     *     description="All Cards - Publicly Accessible",
     *     operationId="search",
     *     @OA\Parameter(name="perPage", description="perPage, eg; 20", example=20, in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="search", description="search, eg; Test", example="Test", in="query", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="All Cards - Publicly Accessible" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $data = $this->cardRepository->searchCard($request->search, $request->perPage);
            return $this->responseSuccess($data, 'Card List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\POST(
     *     path="/api/cards",
     *     tags={"Cards"},
     *     summary="Create New Card",
     *     description="Create New Card",
     *     operationId="store",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="title", type="string", example="Card 1"),
     *              @OA\Property(property="description", type="string", example="Description"),
     *              @OA\Property(property="price", type="integer", example=10120),
     *              @OA\Property(property="image", type="string", example=""),
     *          ),
     *      ),
     *      security={{"bearer":{}}},
     *      @OA\Response(response=200, description="Create New Card" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $card = $this->cardRepository->create($request->all());
            return $this->responseSuccess($card, 'New Card Created Successfully !');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/cards/{id}",
     *     tags={"Cards"},
     *     summary="Show Card Details",
     *     description="Show Card Details",
     *     operationId="show",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Show Card Details"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function show($id): JsonResponse
    {
        try {
            $data = $this->cardRepository->getByID($id);
            if (is_null($data)) {
                return $this->responseError(null, 'Card Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Card Details Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\PUT(
     *     path="/api/cards/{id}",
     *     tags={"Cards"},
     *     summary="Update Card",
     *     description="Update Card",
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="title", type="string", example="Card 1"),
     *              @OA\Property(property="description", type="string", example="Description"),
     *              @OA\Property(property="price", type="integer", example=10120),
     *              @OA\Property(property="image", type="string", example=""),
     *          ),
     *      ),
     *     operationId="update",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200, description="Update Card"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $data = $this->cardRepository->update($id, $request->all());
            if (is_null($data))
                return $this->responseError(null, 'Card Not Found', Response::HTTP_NOT_FOUND);

            return $this->responseSuccess($data, 'Card Updated Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function chargeCard(Request $request ): JsonResponse
    {
        try {
            $data = $this->cardRepository->chargeCard( $request->all());
            if (is_null($data))
                return $this->responseError(null, 'Card Not Found', Response::HTTP_NOT_FOUND);

            return $this->responseSuccess($data, 'Card Charged Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\DELETE(
     *     path="/api/cards/{id}",
     *     tags={"Cards"},
     *     summary="Delete Card",
     *     description="Delete Card",
     *     operationId="destroy",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Delete Card"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function destroy($id): JsonResponse
    {
        try {
            $card =  $this->cardRepository->getByID($id);
            if (empty($card)) {
                return $this->responseError(null, 'Card Not Found', Response::HTTP_NOT_FOUND);
            }

            $deleted = $this->cardRepository->delete($id);
            if (!$deleted) {
                return $this->responseError(null, 'Failed to delete the card.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->responseSuccess($card, 'Card Deleted Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
