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
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => '',
            'car_id' => 'required',
            'name' => 'required|string|max:50',
            'cost' => 'numeric', // 999.99
            'date'=> 'required', // RRRR-MM-DD
            'planned' => 'required|boolean' // 1 - true , 0 - false
        ]);

        if($validator->fails()) {

            $data = [
                'status' => 422,
                'errors' => $validator->messages()
            ];

            return response()->json($data, 422);
        } else {
            $car = Car::find($request->car_id);

            if(Auth::user()->cannot('read', $car)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'You do not own this car'
                ]);
            } else {
                $fixed_cost = $request->cost*100;

                $expense = Expense::create([
                    'user_id' => Auth::user()->id,
                    'car_id' => $request->car_id,
                    'name' => $request->name,
                    'cost' => $fixed_cost,
                    'date'=> $request->date,
                    'planned' => $request->planned,
                ]);

                if($expense) {

                    $data = [
                        'status' => 200,
                        'message' => 'Expense created successfully'
                    ];

                    return response()->json($data, 200);
                } else {

                    $data = [
                        'status' => 500,
                        'message' => 'Something went wrong'
                    ];

                    return response()->json($data, 500);
                }
            }
        }
    }

    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'cost' => 'numeric', // 999.99
            'date'=> 'required', // RRRR-MM-DD
            'planned' => 'required|boolean' // 1 - true , 0 - false
        ]);

        if($validator->fails()) {

            $data = [
                'status' => 422,
                'errors' => $validator->messages()
            ];

            return response()->json($data, 422);
        } else {
            $expense = Expense::find($id);

            if(Auth::user()->cannot('read', $expense)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'You do not own this expense'
                ]);
            } else {
                $fixed_cost = $request->cost*100;

                $expense->update([
                    'name' => $request->name,
                    'cost' => $fixed_cost,
                    'date'=> $request->date,
                    'planned' => $request->planned,
                ]);

                if($expense) {

                    $data = [
                        'status' => 200,
                        'message' => 'Expense updated successfully'
                    ];

                    return response()->json($data, 200);
                } else {

                    $data = [
                        'status' => 500,
                        'message' => 'Something went wrong'
                    ];

                    return response()->json($data, 500);
                }
            }
        }
    }

    public function delete(Request $request, $id)
    {
        $expense = Expense::find($id);

        if($expense) {
            if(Auth::user()->cannot('read', $expense)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'You do not own this expense'
                ]);
            } else {
                $expense->delete();
                $data = [
                    'status' => 200,
                    'message' => 'Expense deleted successfully'
                ];
                return response()->json($data, 200);
            }
        } else {
            $data = [
                'status' => 404,
                'message' => 'No such expense found'
            ];
            return response()->json($data, 404);
        }

    }

    public function userexpenses($id)
    {
        $user = User::find($id);

        if (!$user) {
            $data = [
                'status' => 404,
                'message' => 'No such user found'
            ];

            return response()->json($data, 404);
        } else {
            $expenses = Expense::where('user_id', $user->id)->get();
            foreach ($expenses as $expense) {
                $expense->car_model = Car::find($expense->car_id)->model;
            }
        }
        if($expenses!=null) {
            foreach ($expenses as $expense) {
                if(Auth::user()->cannot('read', $expense)) {
                    return response()->json([
                        'status' => 403,
                        'message' => 'You do not own this expense'
                    ]);
                }
            }
            return response()->json($expenses);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No expenses or found'
            ];

            return response()->json($data, 404);
        }
    }

    public function carexpenses($id)
    {
        $car = Car::find($id);
        $expenses = Expense::where('car_id', $car->id)->get();
        ;
        if($car) {
            if($expenses!=null) {
                if(Auth::user()->cannot('read', $car)) {
                    return response()->json([
                        'status' => 403,
                        'message' => 'You do not own this car'
                    ]);
                } else {

                    return response()->json($expenses, 200);
                }
            } else {
                $data = [
                    'status' => 404,
                    'message' => 'No expenses found'
                ];

                return response()->json($data, 404);
            }
        } else {
            $data = [
                'status' => 404,
                'message' => 'No such car found'
            ];

            return response()->json($data, 404);
        }
    }
}
