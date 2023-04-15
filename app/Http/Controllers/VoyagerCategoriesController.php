<?php

namespace App\Http\Controllers;

use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Database\Schema\SchemaManager;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Events\BreadDataAdded;
use App\Models\Board;
use App\Models\Game;
use App\Models\Hazko;
use App\Models\Bingo;

class VoyagerCategoriesController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    public function store(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->addRows)->validate();
        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());
        $this->createBoard($data->id,$data->game_id);
        event(new BreadDataAdded($dataType, $data));

        if (!$request->has('_tagging')) {
            if (auth()->user()->can('browse', $data)) {
                $redirect = redirect()->route("voyager.{$dataType->slug}.index");
            } else {
                $redirect = redirect()->back();
            }

            return $redirect->with([
                'message'    => __('voyager::generic.successfully_added_new')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                'alert-type' => 'success',
            ]);
        } else {
            return response()->json(['success' => true, 'data' => $data]);
        }
    }

    public function createBoard($sessionsId,$gameId)
    {
        $lastCardNumber = Board::max('card_number');
        $game = Game::find($gameId);
        $game_type =$game->game_type;
        if($game_type==1){
          $cards =  Hazko::all();
        
          foreach ($cards as $card) {
            $boardData = [
                'row_1_col_1' => $card['row_1_col_1'],
                'row_1_col_2' => $card['row_1_col_2'],
                'row_1_col_3' => $card['row_1_col_3'],
                'row_1_col_4' => $card['row_1_col_4'],
                'row_1_col_5' => $card['row_1_col_5'],
                'row_2_col_1' => $card['row_2_col_1'],
                'row_2_col_2' => $card['row_2_col_2'],
                'row_2_col_3' => $card['row_2_col_3'],
                'row_2_col_4' => $card['row_2_col_4'],
                'row_2_col_5' => $card['row_2_col_5'],
                'row_3_col_1' => $card['row_3_col_1'],
                'row_3_col_2' => $card['row_3_col_2'],
                'row_3_col_3' => $card['row_3_col_3'],
                'row_3_col_4' => $card['row_3_col_4'],
                'row_3_col_5' => $card['row_3_col_5'],
                'row_4_col_1' => $card['row_4_col_1'],
                'row_4_col_2' => $card['row_4_col_2'],
                'row_4_col_3' => $card['row_4_col_3'],
                'row_4_col_4' => $card['row_4_col_4'],
                'row_4_col_5' => $card['row_4_col_5'],
            ];
        
            $board = Board::create([
                'user_id' =>  Auth::user()->id,
                'sessions_id' => $sessionsId,
                'card_number' => $card['card_number'],
                'board_data' => json_encode($boardData),
            ]);
        }
        }
        if($game_type==2){
            $cards =  Bingo::all();
          
            foreach ($cards as $card) {
              $boardData = [
                  'row_1_col_1' => $card['row_1_col_1'],
                  'row_1_col_2' => $card['row_1_col_2'],
                  'row_1_col_3' => $card['row_1_col_3'],
                  'row_1_col_4' => $card['row_1_col_4'],
                  'row_1_col_5' => $card['row_1_col_5'],
                  'row_2_col_1' => $card['row_2_col_1'],
                  'row_2_col_2' => $card['row_2_col_2'],
                  'row_2_col_3' => $card['row_2_col_3'],
                  'row_2_col_4' => $card['row_2_col_4'],
                  'row_2_col_5' => $card['row_2_col_5'],
                  'row_3_col_1' => $card['row_3_col_1'],
                  'row_3_col_2' => $card['row_3_col_2'],
                  'row_3_col_3' => $card['row_3_col_3'],
                  'row_3_col_4' => $card['row_3_col_4'],
                  'row_3_col_5' => $card['row_3_col_5'],
                  'row_4_col_1' => $card['row_4_col_1'],
                  'row_4_col_2' => $card['row_4_col_2'],
                  'row_4_col_3' => $card['row_4_col_3'],
                  'row_4_col_4' => $card['row_4_col_4'],
                  'row_4_col_5' => $card['row_4_col_5'],
                  'row_5_col_1' => $card['row_5_col_1'],
                  'row_5_col_2' => $card['row_5_col_2'],
                  'row_5_col_3' => $card['row_5_col_3'],
                  'row_5_col_4' => $card['row_5_col_4'],
                  'row_5_col_5' => $card['row_5_col_5'],
              ];
          
              $board = Board::create([
                'user_id' =>  Auth::user()->id,
                'sessions_id' => $sessionsId,
                'card_number' => $card['card_number'],
                'board_data' => json_encode($boardData),
              ]);
          }

          }

        return 1;
    }
}