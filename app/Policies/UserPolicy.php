<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }

        public function before(User $user){
            if($user->is_admin){
                return true;
            }
        }

        public function delete(User $user, User $user2){
            if($user->id === $user2->id){
                return Response::allow();
            }
            else{
                return Response::deny('You do not have permission');
            }
        }

        public function index(User $user){
            return Response::deny('You do not have permission');
        }
    
}
