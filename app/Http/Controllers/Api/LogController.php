<?php

namespace App\Http\Controllers\Api;

use App\Models\Log;
use App\Models\Car;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{

    public function read($id)
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

}
