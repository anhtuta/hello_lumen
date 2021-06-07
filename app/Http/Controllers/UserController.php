<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * The user repository instance.
     */
    protected $users;

    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    // public function __construct(UserRepository $users)
    public function __construct()
    {
        //$this->users = $users;
        // $this->middleware('auth');

        // $this->middleware('log', ['only' => [
        //     'fooAction',
        //     'barAction',
        // ]]);

        // $this->middleware('subscribed', ['except' => [
        //     'fooAction',
        //     'barAction',
        // ]]);
    }

    /**
     * Retrieve the user for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
    public function getUserById($id)
    {
        // return User::findOrFail($id);
        return "getUserById: ". $id;
    }

    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function createUser(Request $request)
    {
        $name = $request->input('name');
        return "createUser";
    }

    /**
     * Update the specified user.
     *
     * @param  Request  $request
     * @param  string  $id
     * @return Response
     */
    public function updateUser(Request $request, $id)
    {
        return "updateUser: " . $id;
    }
}
