<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class Controller extends BaseController
{
    public function test(Request $request)
    {
        return Auth::user()->token();
    }
    /**
     * 
     * Metodo que permite remover el token cuando el usuario de desloguea
     * 
     */
    public function logout(Request $request)
    {
        DB::table('oauth_access_tokens')
            ->where('user_id', Auth::user()->id)
            ->update(['revoked' => true]);

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', Auth::user()->token()->id)
            ->update(['revoked' => true]);
    }
}
