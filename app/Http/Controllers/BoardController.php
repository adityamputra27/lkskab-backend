<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\BoardMember;
use App\Models\Board;
use Validator;
use DB;

class BoardController extends Controller
{
    private $board;
    private $member;

    public function __construct(Board $board, BoardMember $member)
    {
        $this->board = $board;
        $this->member = $member;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $board = Auth::user()->boards;
        return response()->json([
            'board' => $board
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'invalid field'
            ], 422);
        }

        Auth::user()->boards()->create([
            'name' => $request->name
        ]);
        
        return response()->json([
            'status' => true,
            'message' => 'create board success'
        ], 200);

        // $member = new BoardMember;
        // $member->board_id = $board->id;
        // $member->user_id = Auth::user()->id;
        // $member->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($boardId)
    {
        // $board = Auth::user()->boards()->with(['members' => function ($query) {
        //     $query->select('board_members.id', 'board_members.board_id', 'users.first_name', 'users.last_name',
        //         DB::raw('concat(left(users.first_name, 1), left(users.last_name, 1)) AS initial'));
        // }])
        // ->get()
        // ->find($boardId)->load(['boardLists.cards' => function($query){
        //     $query->orderBy('order', 'ASC');
        // }]);
        $boards = Auth::user()->boards()->with('members')->find($boardId)->load(['boardLists.cards' => function ($query) {
            $query->orderBy('order', 'ASC')->get();
        }]);

        $members = $boards->members;

        $arr = [];
        // foreach ($boards as $key => $board) {
        // $arr[$board]['members'] = $this->member->where('board_id', '=', $boards->id)
        //                         ->join('users', 'user_id', 'users.id')
        //                         ->select('users.id', 'users.first_name', 'users.last_name',
        //                         DB::raw('concat(left(users.first_name, 1), left(users.last_name, 1)) AS initital'))
        //                         ->get();
        // }

        // foreach($members as $key => $value) {

        //     $arr['members'] = $this->member->where('board_id', '=', $boardId)
        //                      ->join('users', 'user_id', 'users.id')
        //                      ->select('users.id', 'users.first_name', 'users.last_name',
        //                      DB::raw('concat(left(users.first_name, 1), left(users.last_name, 1)) AS initital'))
        //                      ->get();

        // }

        return response()->json([
            'status' => true,
            'message' => 'success',
            'board' => $boards
        ]);
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
    public function update(Request $request, $boardId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'invalid field'
            ], 422);
        }

        $board = Board::findOrFail($boardId);

        $board->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'update board success'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($boardId)
    {
        $board = Board::findOrFail($boardId);

        if($board->delete()) {
            return response()->json([
                'status' => true,
                'message' => 'delete board success'
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'delete board failed'
        ], 200);
    }
}
