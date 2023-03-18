<?php

namespace App\Http\Controllers\Main;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Repositories\WalletRepository;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use App\Models\User;
use App\Models\Board;

use App\Models\Session;
use App\Traits\ResponseTrait;
use Carbon\Carbon;

class MainController extends Controller
{

    public function __construct(WalletRepository $walletRepository)
    {
        $this->walletRepository = $walletRepository;

    }
    use ResponseTrait;
    public function index(): Renderable
    {
        return view('index');
    }
    public function login(): Renderable
    {
        return view('login');
    }
    public function dashboard()
    {
        
        $user =  User::where('id',auth()->user()->id)->with('wallet')->first();
        $session = Session::with('game')->get();
        //return $this->responseSuccess($session, 'Event List Fetched Successfully !');
        return view('dashboard',compact('user','session'));
    }

    public function createBoard(Request $request)
    {
        
        $userId = $request->user_id;
        $gameId = $request->game_id;

        $lastCardNumber = Board::max('card_number');
        $nextCardNumber = $lastCardNumber + 1;
        for ($i=$nextCardNumber ; $i <= 150 ; $i++) { 
            $boardData = [[rand(1, 90),null, null, null, null],
                      [null ,rand(1, 90) , null,rand(1, 90),null],
                      [null, null, null, null,rand(1, 90)],
                      [null, null, null,rand(1, 90), null]];
        
        $board = Board::create([
            'user_id' => $userId,
            'game_id' => $gameId,
            'card_number' =>  $i,
            'board_data' => json_encode($boardData),
        ]);

        }

        return response()->json([
            'message' => 'Board created successfully',
            'board' => $board,
        ], 201);
    }

    public function game($gameId,Request $request)
    {
        $userId = 65;
        $gameId = 1;
    $session = Session::with('game')->where('id',$gameId)->first();
    $user =  User::where('id',auth()->user()->id)->with('wallet')->first();
    $boards = Board::where('user_id', $userId)
    ->where('game_id', $gameId)
    ->get();

    return view('game.boards', compact('boards','user','session'));
    }

    public function handleForm(Request $request)
    {
        $selectedItems = $request->input('select2');

        // $totalPrice =  (int)$event->entry_fee *(int)$data['ticket_number']  ; 
        // $this->walletRepository->decreaseWallet($totalPrice,'Book a party ticket for '.$event->name);
        // $event->decrement('ticket',$data['ticket_number']); 

        Board::whereIn('id', $selectedItems)->update(['user_id' => auth()->user()->id,'is_pay'=> 1 ,'pay_time'=>Carbon::now()]);
        // Do something with the selected items...
        
        return redirect('/dashboard')->with('success', 'Form submitted successfully!');
    }
}
