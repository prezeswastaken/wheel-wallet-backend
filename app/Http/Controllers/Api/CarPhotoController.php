<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\CarPhoto;
use App\Models\Car;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CarPhotoController extends Controller
{
    public function store(Request $request, $id){

        $car = Car::find($id);

        if(Auth::user()->cannot('read', $car)){
            return response()->json([
                'status' => 403,
                'message' => 'You do not own this car'
            ]);
        }
        else{
            $validator = Validator::make($request->all(),[
                'image' => 'required',
            ]);

            if($validator->fails()){

                $data = [
                    'status' => 422,
                    'errors' => $validator->messages()
                ];

                return response()->json($data, 422);
            }
            else{

                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();
                $filename = $id . time() . '.' . $extension;
                $image->move('uploads/car_photos/', $filename);
                
                $photo = CarPhoto::create([
                    'car_id' => $id,
                    'content' => $filename
                ]);

                if($photo){

                    $data = [
                        'status' => 200,
                        'message' => 'Photo added successfully'
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

    public function show(Request $request, $id){

        $car = Car::find($id);

        if(Auth::user()->cannot('read', $car)){
            return response()->json([
                'status' => 403,
                'message' => 'You do not own this car'
            ]);
        }
        else{

            $photos = CarPhoto::all()->where('car_id', $id);

            $data = [
                'status' => 200,
                'photos' => $photos
            ];

            return response()->json($data, 200);
        }
    }
}
