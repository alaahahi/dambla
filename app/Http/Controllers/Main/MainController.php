<?php

namespace App\Http\Controllers\Main;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Repositories\WalletRepository;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use App\Models\User;
use App\Models\Board;
use App\Models\Game;

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
    $session = Session::with('game')->where('id',$gameId)->first();
    $user =  User::where('id',auth()->user()->id)->with('wallet')->first();
    $boards = Board::where('game_id', $gameId)->get();

    return view('game.boards', compact('boards','user','session'));
    }

    public function handleForm(Request $request)
    {
        $selectedItems = $request->input('select2');
        $game = Game::where('id',$request->game_id)->first();
        $price =  $game->group_price ?? 0;
        $totalPrice =  (int) $price  *count( $selectedItems ); 
        $this->walletRepository->decreaseWallet($totalPrice,'Book '.count( $selectedItems ).' ticket for '.$game->name);
        Board::whereIn('id', $selectedItems)->update(['user_id' => auth()->user()->id,'is_pay'=> 1 ,'pay_time'=>Carbon::now()]);
        return redirect('/dashboard')->with('success', 'Form submitted successfully!');
    }
}
