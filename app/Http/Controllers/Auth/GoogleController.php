<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Auth;
use Illuminate\Support\Facades\Hash;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        try {

            $user = Socialite::driver('google')->user();

            $current_user = User::where('google_id', $user->id)->first();

            if($current_user){

                Auth::login($current_user);

                return redirect()->intended(env('REDIRECT_URL'));

            }else{
                $newUser = User::updateOrCreate(['email' => $user->email],[
                    'name' => $user->name,
                    'google_id'=> $user->id,
                    'password' => Hash::make($user->password),
                ]);

                Auth::login($newUser);

                return redirect()->intended(env('REDIRECT_URL'));
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}