<?php

namespace App\Http\Controllers\Events;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;

use App\Repositories\EventRepository;

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

class EventsController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * Event Repository class.
     *
     * @var EventRepository
     */
    public $eventRepository;
    
    public function __construct(EventRepository $eventRepository)
    {
        $this->middleware('auth:api', ['except' => ['indexAll']]);
        $this->eventRepository = $eventRepository;
    }

    /**
     * @OA\GET(
     *     path="/api/events",
     *     tags={"Events"},
     *     summary="Get Event List",
     *     description="Get Event List as Array",
     *     operationId="index",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200,description="Get Event List as Array"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->eventRepository->getAll($request->perPage);
            return $this->responseSuccess($data, 'Event List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/events/view/all",
     *     tags={"Events"},
     *     summary="All Events - Publicly Accessible",
     *     description="All Events - Publicly Accessible",
     *     operationId="indexAll",
     *     @OA\Parameter(name="perPage", description="perPage, eg; 20", example=20, in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="All Events - Publicly Accessible" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function indexAll(Request $request): JsonResponse
    {
        try {
            $data = $this->eventRepository->getPaginatedData($request->perPage);
            return $this->responseSuccess($data, 'Event List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function myEvent(Request $request): JsonResponse
    {
        try {
            $data = $this->eventRepository->myEvent($request->perPage);
            return $this->responseSuccess($data, 'Event List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function myTicket(Request $request): JsonResponse
    {
        try {
            $data = $this->eventRepository->myTicket($request->perPage);
            return $this->responseSuccess($data, 'Ticket List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    
    public function eventTicket(Request $request,$id): JsonResponse
    {
        try {
            $data = $this->eventRepository->eventTicket($request->perPage,$id);
            return $this->responseSuccess($data, 'Event ticket List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\GET(
     *     path="/api/events/view/search",
     *     tags={"Events"},
     *     summary="All Events - Publicly Accessible",
     *     description="All Events - Publicly Accessible",
     *     operationId="search",
     *     @OA\Parameter(name="perPage", description="perPage, eg; 20", example=20, in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="search", description="search, eg; Test", example="Test", in="query", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="All Events - Publicly Accessible" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $data = $this->eventRepository->searchEvent($request->search,$request->tagId,$request->entry_feeLessThan,$request->startDate,$request->sort, $request->perPage);
            return $this->responseSuccess($data, 'Event List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\POST(
     *     path="/api/events",
     *     tags={"Events"},
     *     summary="Create New Event",
     *     description="Create New Event",
     *     operationId="store",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="title", type="string", example="Event 1"),
     *              @OA\Property(property="description", type="string", example="Description"),
     *              @OA\Property(property="price", type="integer", example=10120),
     *              @OA\Property(property="image", type="string", example=""),
     *          ),
     *      ),
     *      security={{"bearer":{}}},
     *      @OA\Response(response=200, description="Create New Event" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function store(EventRequest $request): JsonResponse
    {
        try {
            $event = $this->eventRepository->create($request->all());
            return $this->responseSuccess($event, 'New Event Created Successfully !');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function addEvent(EventRequest $request): JsonResponse
    {
        try {
            $event = $this->eventRepository->addEvent($request->all());
            return $this->responseSuccess($event, 'New Event Created Successfully !');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/events/{id}",
     *     tags={"Events"},
     *     summary="Show Event Details",
     *     description="Show Event Details",
     *     operationId="show",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Show Event Details"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function show($id): JsonResponse
    {
        try {
            $data = $this->eventRepository->getByID($id);
            if (is_null($data)) {
                return $this->responseError(null, 'Event Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Event Details Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function EventAllOneRequest(): JsonResponse
    {
        try {
            $featured = $this->eventRepository->eventFeatured();
            $eventToday = $this->eventRepository->eventToday();
            $eventTomorrow = $this->eventRepository->eventTomorrow();
            $eventWeekend = $this->eventRepository->eventWeekend();
            if (is_null($featured))
            {
                return $this->responseError(null, 'Event Not Found', Response::HTTP_NOT_FOUND);
            }
                return $this->responseSuccess([
                    'featuredEvent' => $featured,
                    'todayEvent' => $eventToday,
                    'tomorrowEvent' => $eventTomorrow,
                    'WeekendEvent'  => $eventWeekend
                ], 'Event Details Fetch Successfully !');
        } catch (\Exception $e) {
                return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

    /**
     * @OA\PUT(
     *     path="/api/events/{id}",
     *     tags={"Events"},
     *     summary="Update Event",
     *     description="Update Event",
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="title", type="string", example="Event 1"),
     *              @OA\Property(property="description", type="string", example="Description"),
     *              @OA\Property(property="price", type="integer", example=10120),
     *              @OA\Property(property="image", type="string", example=""),
     *          ),
     *      ),
     *     operationId="update",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200, description="Update Event"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $data = $this->eventRepository->update($id ?? $request->id, $request->all());
            if (is_null($data))
                return $this->responseError(null, 'Event Not Found', Response::HTTP_NOT_FOUND);

            return $this->responseSuccess($data, 'Event Updated Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function bookingEvent(Request $request): JsonResponse
    {
        try {
            $data = $this->eventRepository->bookingEvent( $request->all());
            if (is_null($data))
                return $this->responseError(null, 'Booking Event Falid', Response::HTTP_NOT_FOUND);

            return $this->responseSuccess($data, 'Booking Event Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function editEvent(Request $request, $id): JsonResponse
    {
        try {
            $data = $this->eventRepository->update($id, $request->all());
            if (is_null($data))
                return $this->responseError(null, 'Event Not Found', Response::HTTP_NOT_FOUND);

            return $this->responseSuccess($data, 'Event Updated Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @OA\DELETE(
     *     path="/api/events/{id}",
     *     tags={"Events"},
     *     summary="Delete Event",
     *     description="Delete Event",
     *     operationId="destroy",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Delete Event"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function destroy($id): JsonResponse
    {
        try {
            $event =  $this->eventRepository->getByID($id);
            if (empty($event)) {
                return $this->responseError(null, 'Event Not Found', Response::HTTP_NOT_FOUND);
            }

            $deleted = $this->eventRepository->delete($id);
            if (!$deleted) {
                return $this->responseError(null, 'Failed to delete the event.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->responseSuccess($event, 'Event Deleted Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
