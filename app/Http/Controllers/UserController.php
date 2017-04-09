<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::all();
        // return $users;
        return view('users.index',[
          'title' => 'Manage Users',
          'users' => $users
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
        $this->validate($request,[
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required | email | unique:users',
                'password' => 'required',
                'phone' => 'required'
            ]);

        $input = $request->all();
        $input['active'] = 1;
        $user = User::create($input);
        return $user;
        // return redirect()->back()->with('status','New User Added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // return $user;
        return view('users.partials.edit',[
          'title' => 'Manage Users',
          'user' => $user
          ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // return $request->all();
        $input = $request->all();
        (!empty($input['active']))?: $input['active'] = 0;
        $user->update($input);
        // return $user;
        return redirect()->back()->with('status','User Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->update(['active' => 0]);
        $user->delete();
        return 'Success!';
        
    }
}
