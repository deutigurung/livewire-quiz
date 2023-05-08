<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Providers\RouteServiceProvider;
class SocialiteController extends Controller
{
    public function loginSocial(Request $request , string $provider) {
        $this->validateProvider($request);
        return Socialite::driver($provider)->redirect();
    }

    public function callbackSocial(Request $request,string $provider) {
        $this->validateProvider($request);
        $response = Socialite::driver($provider)->user();
 
        $user = User::firstOrCreate([
            'email' => $response->getEmail(),
            'password' => '',
        ]);
        $data = [$provider.'_id' => $response->getId()];
        $user->update($data);
        Auth::login($user);
     
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    protected function validateProvider(Request $request)
    {
        return $this->getValidationFactory()
        ->make(
            $request->route()->parameters(),
            ['provider' => 'in:facebook,github,google']
        )->validate();
    }
}
