<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LineAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('line')->redirect();
    }

    public function callback()
    {
        return response()->json(['status' => 'ok'], 200);
        $lineUser = Socialite::driver('line')->user();


        $user = User::updateOrCreate(
            ['line_id' => $lineUser->getId()],
            [
                'name'   => $lineUser->getName(),
                'avatar' => $lineUser->getAvatar(),
                'email'  => $lineUser->getEmail() ?? $lineUser->getId() . '@line.local',
            ]
        );

        Auth::login($user);

        return redirect()->intended('/dashboard');
    }
}
