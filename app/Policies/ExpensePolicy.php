<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExpensePolicy
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

    public function read(User $user, Expense $expense){
        if($user->id === $expense->user_id){
            return Response::allow();
        }
        else{
            return Response::deny('You do not own this expense');
        }
    }
}
