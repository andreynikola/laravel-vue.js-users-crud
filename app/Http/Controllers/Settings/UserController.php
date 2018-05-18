<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Html\HtmlBuilder;
use Illuminate\Support\Facades\DB;
use Validator, Input, Redirect, Hash, Session;

use App\Users;
use App\Platforms;
use App\Roles;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Users::all();
        $platforms = Platforms::all();
        $roles = Roles::all();

        return view('settings.users.index',[
                                            'users' => $users,
                                            'platforms' => $platforms,
                                            'roles' => $roles
                                            ]
                    );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = array(
            'surname' => 'required|string|min:4|max:100',
            'name'  => 'required|string|min:4|max:100',
            'father_name' => 'required|string|min:4|max:100',
            'email' => 'required|email',
            'phone' => 'required',
            'platform' => 'required|string|max:200',
            'role' => 'required|string|max:20'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json([ 'Action' => 'Bad request' ], 500); 
        } else {

            $user = new Users;
            $user->name           = Input::get('name');
            $user->email          = Input::get('email');
            $user->role          = Input::get('role');
            $user->surname        = Input::get('surname');
            $user->father_name    = Input::get('father_name');
            $user->phone          = Input::get('phone');
            $user->platform       = Input::get('platform');

            $password       = str_random(8);
            $user->password = Hash::make($password);

            $user->save();

            Session::flash('message', 'Successfully created nerd');
            return response()->json([ 'Action' => 'Successfully created nerd' ]); 

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = DB::table('users')
                ->where('id', '=', $id)
                ->select('id','name','email','surname','father_name','phone','company','platform','role')
                ->get();

        return response()->json($user);  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Users::find($id);
        // return view('settings.users.edit',['user' => $user]);  
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

        $rules = array(
            'name'  => 'required|string|min:4|max:100',
            'email' => 'required|email',
            'role' => 'required|string|max:20',
            'phone' => 'required|numeric',
            'surname' => 'required|string|min:4|max:100',
            'father_name' => 'required|string|min:4|max:100',
            'platform' => 'required|string|max:200'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json([ 'Action' => 'Bad request' ], 400); 
        } else {

            $user = Users::find($id);
            $user->name           = Input::get('name');
            $user->email          = Input::get('email');
            $user->role           = Input::get('role');
            $user->surname        = Input::get('surname');
            $user->father_name    = Input::get('father_name');
            $user->phone          = Input::get('phone');
            $user->platform       = Input::get('platform');

            $user->save();

            return response()->json([ 'Action' => 'Good request' ], 200); 

            Session::flash('message', 'Successfully created nerd with password ' );

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Users::whereIn('id', explode(',', $id));
        $user->delete();
        Session::flash('message', 'Successfully deleted the user!');
    }

}