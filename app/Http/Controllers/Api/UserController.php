<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {

        if(Auth::user()->cannot('index' , User::class)){
            return response()->json([
                'status' => 403,
                'message' => 'You do not have permission'
            ]);
        }
        else{
            $users = User::all();

            if($users->count() > 0) {

                $data = [
                    'status' => 200,
                    'users' => $users
                ];

                return response()->json($data, 200);
            } else {

                $data = [
                    'status' => 404,
                    'users' => 'No records found'
                ];

                return response()->json($data, 404);
            }
        }
    }

    public function delete($id)
    {
        $user = User::find($id);

        if($user) {
            if(Auth::user()->cannot('delete', $user)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'You do not have permission'
                ]);
            } else {
                $user->delete();
                $data = [
                    'status' => 200,
                    'message' => 'Account deleted successfully'
                ];
                return response()->json($data, 200);
            }
        } else {
            $data = [
                'status' => 404,
                'message' => 'No such user found'
            ];

            return response()->json($data, 404);
        }
    }
}
