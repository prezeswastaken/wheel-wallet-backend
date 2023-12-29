<?php

namespace App\Policies;

use App\Models\Log;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LogPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function before(User $user){
        if($user->is_admin){
            return true;
        }
    }
    public function index(User $user){
        return Response::deny('You do not have permission');
    }
}
