<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardMember;
use App\Models\Board;
use App\Models\User;
use Validator;
use Auth;

class BoardMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'username' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'invalid field'
            ], 422);
        }

        $board = Board::find($boardId);
        // dd($board);

        $user = User::where('username', $request->username)->first();
        // dd($user->first_name);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'user did not exist'
            ], 422);
        }

        $board->members()->attach($user->id);
        // dd($board->members());

        return response()->json([
            'status' => true,
            'message' => 'add member success'
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $boardId, $userId)
    {
        $board = Board::find($boardId);

        $board->members()->detach($userId);

        return response()->json([
            'status' => true,
            'message' => 'remove member success'
        ], 200);
    }
}
