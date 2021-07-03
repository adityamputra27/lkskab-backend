<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Board;
use App\Models\Card;
use App\Models\BoardList;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($boardId, $listId)
    {
        $card = Board::find($boardId)->boardLists()->find($listId)->cards;
        return response()->json([
            'cards' => $card
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $boardId, $listId)
    {
        $validator = Validator::make($request->all(), [
            'task' => 'required'
        ],
        [
            'task.required' => 'Task must be filled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'invalid field'
            ], 422);
        }

        $lastOrder = Card::where('list_id', $listId)->max('order');

        Board::find($boardId)->boardLists()->find($listId)->cards()->create([
            'task' => $request->task,
            'order' => ++$lastOrder
        ]);

        return response()->json([
            'status' => true,
            'message' => 'create card success'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $boardId, $listId, $cardId)
    {
        $validator = Validator::make($request->all(), [
            'task' => 'required'
        ],
        [
            'task.required' => 'Task must be filled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'invalid field'
            ], 422);
        }

        Board::find($boardId)->boardLists()->find($listId)->cards()->find($cardId)->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'update card success'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($boardId, $listId, $cardId)
    {
        Board::find($boardId)->boardLists()->find($listId)->cards()->find($cardId)->delete();

        return response()->json([
            'status' => true,
            'message' => 'delete card success'
        ], 200);
    }

    public function moveUp(Request $request, $cardId)
    {
        $validator = Validator::make($request->all(), [
            'afterCardId' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $currentCard = Card::find($cardId);
        $nextCard = Card::find($request->afterCardId);

        $currentCardId = $nextCard->order;
        $nextCardId = $currentCard->order;

        $currentCard->update(['order' => $currentCardId]);
        $nextCard->update(['order' => $nextCardId]);

        return response()->json([
            'status' => true,
            'message' => 'move success'
        ], 200);
    }

    public function moveDown(Request $request, $cardId)
    {
        $validator = Validator::make($request->all(), [
            'beforeCardId' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $currentCard = Card::find($cardId);
        $beforeCard = Card::find($request->beforeCardId);

        $currentCardId = $beforeCard->order;
        $beforeCardId = $currentCard->order;

        $currentCard->update(['order' => $currentCardId]);
        $beforeCard->update(['order' => $beforeCardId]);

        return response()->json([
            'status' => true,
            'message' => 'move success'
        ], 200);
    }

    public function moveToAnotherList(Request $request, $cardId, $listId)
    {
        $card = Card::find($cardId);

        $maxOrder = $card->where('list_id', $listId)->max('order');

        $card->update(['list_id' => $listId, 'order' => ++$maxOrder]);

        return 'success';
    }
}
