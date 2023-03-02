<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



use App\Repositories\DashboardRepository;
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

class DashboardController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * Product Repository class.
     * 
     * @var DashboardRepository tag
     */
    public $dashboardRepository;
    public $eventRepository;


    public function __construct(DashboardRepository $dashboardRepository,EventRepository $eventRepository)
    {
        $this->middleware('auth:api', ['except' => ['indexAll']]);
        $this->dashboardRepository = $dashboardRepository;
        $this->eventRepository = $eventRepository;

    }

    /**
     * @OA\GET(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Get Product List",
     *     description="Get Product List as Array",
     *     operationId="index",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200,description="Get Product List as Array"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $data = $this->dashboardRepository->getAll();
            return $this->responseSuccess($data, 'tag List Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function dashboardAllInOneRequest(): JsonResponse
    {
        try {
            $hunters =  $this->dashboardRepository->hunters();
            $totalHunters= $hunters->count();
            $activeHunters= $hunters->where('is_valid',1)->count();
            $uanVerificationHunters= $hunters->where('is_valid',0)->count();
            $banHunters= $hunters->where('is_band',1)->count();

            $brands =  $this->dashboardRepository->brands();
            $totalBrands= $brands->count();
            $activeBrands= $brands->where('is_valid',1)->count();
            $uanVerificationBrands= $brands->where('is_accepted',0)->count();
            $banBrands= $brands->where('is_band',1)->count();

            $events =  $this->dashboardRepository->events();
            $totalEvent= $events->count();
            $uanVerificationEvents= $events->where('is_accepted',0)->count();

            $featured = $this->eventRepository->eventFeatured()->count();
            $eventToday = $this->eventRepository->eventToday()->count();
            $eventTomorrow = $this->eventRepository->eventTomorrow()->count();
            $eventWeekend = $this->eventRepository->eventWeekend()->count();

            $tags =  $this->dashboardRepository->tags();
            $totalTags= $tags->count();
            
            $category =  $this->dashboardRepository->category();
            $totalCategories= $category->count();
            
                return $this->responseSuccess([
                    ['name'=>'Total Hunters','value'=>$totalHunters],
                    ['name'=>'Active Hunters','value'=>$activeHunters],
                    ['name'=>'Un Verification Hunters','value'=>$uanVerificationHunters],
                    ['name'=>'Ban Hunters','value'=> $banHunters],
                    ['name'=>'Total Brands','value'=>$totalBrands],
                    ['name'=>'Active Brands','value'=>$activeBrands],
                    ['name'=>'Uan Verification Brands','value'=>$uanVerificationBrands],
                    ['name'=>'Ban Brands','value'=> $banBrands],
                    ['name'=>'Total Event','value'=>$totalEvent],
                    ['name'=>'FeaturedvEvent','value'=> $featured],
                    ['name'=>'Today Event','value'=> $eventToday],
                    ['name'=>'Tomorrow Event','value'=> $eventTomorrow],
                    ['name'=>'Weekend Event','value'  => $eventWeekend],
                    ['name'=>'Un Verification Events','value'=>$uanVerificationEvents],
                    ['name'=>'Total Tags','value'=>$totalTags],
                    ['name'=>'Total Categories','value'=>$totalCategories],
                ], 'dashboard Details Fetch Successfully !');
        } catch (\Exception $e) {
                return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\GET(
     *     path="/api/products/view/all",
     *     tags={"Products"},
     *     summary="All Products - Publicly Accessible",
     *     description="All Products - Publicly Accessible",
     *     operationId="indexAll",
     *     @OA\Parameter(name="perPage", description="perPage, eg; 20", example=20, in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="All Products - Publicly Accessible" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
 
}
