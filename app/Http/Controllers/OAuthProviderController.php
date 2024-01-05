<?php

namespace App\Http\Controllers;

use App\Models\OAuthProvider;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\OAuthProviderEnum;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class OAuthProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(OAuthProviderEnum $provider)
    {
        return Socialite::driver($provider->value)->redirect();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OAuthProviderEnum $provider)
    {
        $socialite = Socialite::driver($provider->value)->user();

        $name = $socialite->getName();

        if ($name == null) {
            $name = $socialite->getNickname();
        }

        $user = User::firstOrCreate([
            'email' => $socialite->getEmail(),
        ], [
            'name' => $name
        ]);

        $user->providers()->updateOrCreate([
            'provider' => $provider,
            'provider_id' => $socialite->getId(),
        ]);

        Auth::login($user);

        return redirect(env('SOCIALITE_REDIRECT_TO_FRONTEND'));
        /*return session()->all();*/
        /*return Auth::user();*/
    }


    public function test()
    {
        return session()->all();
    }
    /**
     * Display the specified resource.
     */
    public function show(OAuthProvider $oAuthProvider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OAuthProvider $oAuthProvider)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OAuthProvider $oAuthProvider)
    {
        //
    }
}
