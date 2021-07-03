<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Board;
use App\Models\BoardList;
use Validator;

class BoardListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($boardId)
    {
        $list = Board::find($boardId)->boardLists;
        return $list;
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
    public function store(Request $request, $boardId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ],
        [
            'name.required' => 'name must be filled'
        ]);

        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }
        
        $lastList = BoardList::max('order');
        // dd($lastList);

        Board::find($boardId)->boardLists()->create([
            'name' => $request->name,
            'order' => ++$lastList
        ]);

        return response()->json([
            'status' => true,
            'message' => 'create list success'
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
    public function update(Request $request, $boardId, $listId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ],
        [
            'name.required' => 'name must be filled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        Board::find($boardId)->boardLists()->find($listId)->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'update list success'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($boardId, $listId)
    {
        Board::find($boardId)->boardLists()->find($listId)->delete();

        return response()->json([
            'status' => true,
            'message' => 'delete list success'
        ], 200);
    }

    public function moveRight(Request $request, $boardId, $listId)
    {
        $validator = Validator::make($request->all(), [
            'afterListId' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $board = Board::find($boardId);

        $currentList = $board->boardLists()->find($listId);

        $nextList = $board->boardLists()->find($request->afterListId);
        // dd($nextList->order);
        // dapatkan order next id
        $currentListId = $nextList->order;
        $nextListId = $currentList->order;
        // update order list id
        $currentList->update(['order' => $currentListId]);
        $nextList->update(['order' => $nextListId]);

        return response()->json([
            'status' => true,
            'message' => 'move right success'
        ], 200);
    }

    public function moveLeft(Request $request, $boardId, $listId)
    {
        $validator = Validator::make($request->all(), [
            'beforeListId' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $board = Board::find($boardId);

        $currentList = $board->boardLists()->find($listId);
        $beforeList = $board->boardLists()->find($request->beforeListId);

        $currentListId = $beforeList->order;
        $beforeListId = $currentList->order;

        $currentList->update(['order' => $currentListId]);
        $beforeList->update(['order' => $beforeListId]);

        return response()->json([
            'status' => true,
            'message' => 'move left success'
        ], 200);
        // $beforeListId = $
    }
}
