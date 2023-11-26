<?php

namespace App\Http\Controllers\Api;

use App\Models\Car;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    public function index(){

        $cars = Car::all();

        if($cars->count() > 0){

            $data = [
                'status' => 200,
                'cars' => $cars
            ];

            return response()->json($data, 200);
        }
        else{

            $data = [
                'status' => 404,
                'cars' => 'No records found'
            ];

            return response()->json($data, 404);
        }
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'model' => 'required|string|max:50',
            'owner_id' => '',
            'coowner_id' => '',
            'status' => 'string|max:100'
        ]);

        if($validator->fails()){

            $data = [
                'status' => 422,
                'errors' => $validator->messages()
            ];

            return response()->json($data, 422);
        }
        else{

            $car = Car::create([
                'model' => $request->model,
                'owner_id' => Auth::user()->id,
                'coowner_id' => null,
                'status' => $request->status,
                'code' => strval($request->owner_id.substr(trim($request->model), 0, 3).time())
            ]);

            if($car){

                $data = [
                    'status' => 200,
                    'message' => 'Car created successfully'
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

    public function show($id){
        $car = Car::find($id);
        
        if($car){
            if(Auth::user()->cannot('read', $car)){
                return response()->json([
                    'status' => 403,
                    'message' => 'You do not own this car'
                ]);
            }
            else{
                $data = [
                    'status' => 200,
                    'car' => $car
                ];

                return response()->json($data, 200);
            }
        }
        else{

            $data = [
                    'status' => 404,
                    'message' => 'No such car found'
                ];
    
                return response()->json($data, 404);
        }
    }

    public function read($id){
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }else{
        $cars = Car::where('owner_id', $user->id)->get();
        }
        
        if($cars!=null){
            foreach ($cars as $car){
                if(Auth::user()->cannot('read', $car)){
                    return response()->json([
                        'status' => 403,
                        'message' => 'You do not own this car'
                    ]);
                }
            }
        }
    
        return response()->json($cars);
    }
    public function edit(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'model' => 'required|string|max:50',
            'owner_id' => 'required',
            'coowner_id' => '',
            'status' => 'string|max:100'
        ]);

        if($validator->fails()){

            $data = [
                'status' => 422,
                'errors' => $validator->messages()
            ];

            return response()->json($data, 422);
        }
        else{

            $car = Car::find($id);

            if(Auth::user()->cannot('read', $car)){
                return response()->json([
                    'status' => 403,
                    'message' => 'You do not own this car'
                ]);
            }
            else{
            if($car){
                
                $car->update([
                    'model' => $request->model,
                    'owner_id' => $request->owner_id,
                    'coowner_id' => null,
                    'status' => $request->status,
                    'code' => strval($request->owner_id.substr(trim($request->model), 0, 3).time())
                ]);

                $data = [
                    'status' => 200,
                    'message' => 'Car updated successfully'
                ];
    
                return response()->json($data, 200);
            }
            else{

                $data = [
                    'status' => 404,
                    'message' => 'No such car found'
                ];
    
                return response()->json($data, 404);
                }
            }
        }
    }
    public function delete($id){
        $car = Car::find($id);

        if($car){
            if(Auth::user()->cannot('read', $car)){
                return response()->json([
                    'status' => 403,
                    'message' => 'You do not own this car'
                ]);
            }
            else{
            $car->delete();
            $data = [
                'status' => 200,
                'message' => 'Car deleted successfully'
            ];
            return response()->json($data, 200);
        }
        }else{
            $data = [
                'status' => 404,
                'message' => 'No such car found'
            ];

            return response()->json($data, 404);
        }
    }

    public function join(Request $request){
        $car = Car::where('code', $request->code)->first();
        if($car){
            $car->update([
                'coowner_id' => Auth::user()->id
            ]);

            $data = [
                'status' => 200,
                'message' => 'Joined car as co-owner successfully'
            ];
            return response()->json($data, 200);
        }
        else{
            $data = [
                'status' => 404,
                'message' => 'No car with such code found'
            ];

            return response()->json($data, 404);
        }

    }
}