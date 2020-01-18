<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return view('users.index')->with(['users' => $users]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if($user) {
            User::destroy($id);
        }

        return redirect()->back();
    }

    public function changeRole($user, $role)
    {
        $userToUpdate = User::find($user);

        if ($userToUpdate) {
            $userToUpdate->role = $role;
            $userToUpdate->save();
        }

        return redirect()->back();
    }
}
