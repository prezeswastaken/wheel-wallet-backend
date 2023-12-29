<?php

namespace App\Http\Controllers\Api;

use App\Models\Log;
use App\Models\Car;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        if(Auth::user()->cannot('index', Log::class)) {
            return response()->json([
                'status' => 403,
                'message' => 'You do not have permission'
            ]);
        } else {
            $logs = Log::all();

            if($logs->count() > 0) {

                $data = [
                    'status' => 200,
                    'cars' => $logs
                ];

                return response()->json($data, 200);
            } else {

                $data = [
                    'status' => 404,
                    'logs' => 'No records found'
                ];

                return response()->json($data, 404);
            }
        }
    }

    public function readcar($id)
    {
        $car = Car::find($id);

        if(Auth::user()->cannot('read', $car)) {
            return response()->json([
                'status' => 403,
                'message' => 'You do not own this car'
            ]);
        } 
        else {
            if (!$car) {
                return response()->json(['message' => 'Car not found'], 404);
            } else {
                $logs = Log::where('car_id', $id)->get();

                return response()->json($logs);
            }
        }
    }

    public function readuser($id)
    {
        $cars = Car::where('owner_id', $id)->orWhere('coowner_id', $id)->get();
        $logs = collect();

        foreach($cars as $car){
            if(Auth::user()->cannot('read', $car)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'You do not own this car'
                ]);
            } 
            else {
                $this_car_logs = Log::where('car_id', $car->id)->get();
                $logs = $logs->merge($this_car_logs);
            }
        }

        return response()->json($logs);
    }
}
