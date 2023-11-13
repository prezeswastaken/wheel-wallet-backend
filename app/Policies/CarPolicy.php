<?php

namespace App\Policies;

use App\Models\Car;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CarPolicy
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

    public function read(User $user, Car $car){
        if($user->id === $car->owner_id || $user->id === $car->coowner_id){
            return Response::allow();
        }
        else{
            return Response::deny('You do not own this car');
        }
    }
}
