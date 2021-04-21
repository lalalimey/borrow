<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function agree(Request $request)
    {
        if ($request->agreeToTerms && $request->dataConsent) {
            $user = Auth::user();
            $user->agreetotermsandconditions = 1;
            $user->dataconsent = 1;
            $user->save();
        }
    }
}
