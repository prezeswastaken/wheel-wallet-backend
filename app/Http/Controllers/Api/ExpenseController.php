<?php

namespace App\Http\Controllers\Api;

use App\Models\Car;
use App\Models\User;
use App\Models\Expense;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'user_id' => '',
            'car_id' => 'required',
            'name' => 'required|string|max:50',
            'cost' => 'numeric', // 999.99
            'date'=> 'required', // RRRR-MM-DD
            'planned' => 'required|boolean' // 1 - true , 0 - false
        ]);

        if($validator->fails()){

            $data = [
                'status' => 422,
                'errors' => $validator->messages()
            ];

            return response()->json($data, 422);
        }
        else{
            $car = Car::find($request->car_id);

            if(Auth::user()->cannot('read', $car)){
                return response()->json([
                    'status' => 403,
                    'message' => 'You do not own this car'
                ]);
            }
            else{
                $fixed_cost = $request->cost*100;

                $expense = Expense::create([
                    'user_id' => Auth::user()->id,
                    'car_id' => $request->car_id,
                    'name' => $request->name,
                    'cost' => $fixed_cost,
                    'date'=> $request->date,
                    'planned' => $request->planned,
                ]);

                if($expense){

                    $data = [
                        'status' => 200,
                        'message' => 'Expense created successfully'
                    ];
        
                    return response()->json($data, 200);
                }
                else{

                    $data = [
                        'status' => 500,
                        'message' => 'Something went wrong'
                    ];
        
                    return response()->json($data, 500);
                }
            }
        }
    }

    public function edit(){
        //
    }

    public function delete(){
        //
    }

    public function userexpenses(){
        //
    }

    public function carexpenses(){
        //
    }
}
