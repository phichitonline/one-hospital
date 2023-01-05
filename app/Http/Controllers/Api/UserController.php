<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // fetch data
        $users = User::latest()->get();

        // return message and data
        return response()->json([
            'message' => 'User fetch successfully',
            'version' => '1',
            'last_update' => '2023-01-04',
            'data' => UserResource::collection($users)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check validator
        $validator = Validator::make($request->all(),[
            'fullname' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'tel' => 'required',
            'role' => 'required|integer'
        ]);

        // If validate fails
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        // Create data
        $user = User::create([
            'fullname' => $request->fullname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'tel' => $request->tel,
            'avatar' => $request->avatar,
            'role' => $request->role
        ]);

        // Return message and data
        return response()->json(['User created successfully', new UserResource($user)]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // Check validator
        $validator = Validator::make($request->all(),[
            'fullname' => 'required|string',
            'username' => 'required|string',
            // 'email' => 'required|string|unique:users,email',
            // 'password' => 'required|string|confirmed',
            'tel' => 'required',
            'role' => 'required|integer'
        ]);

        // If validate fails
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        // Set data
        $user->fullname = $request->fullname;
        $user->username = $request->username;
        // $user->email = $request->email;
        // $user->password = $request->password;
        $user->tel = $request->tel;
        if ($request->role) {
            $user->role = $request->role;
        }

        // Update data
        $user->save();

        // Return message and data
        return response()->json(['User updated successfully', new UserResource($user)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json('User deleted successfully');
    }
}
