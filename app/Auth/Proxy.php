<?php

namespace App\Auth;

use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App;
use DB;

class Proxy {

    protected $user;

    public function attemptLogin($credentials = array())
    {
  
        $this->getUser($credentials);

        if($this->user == null){
          return new Response('Usario o contraseña incorrectas', 404);
        }

        return $this->proxy('password', $credentials);
    }

    public function attemptRefresh($refresh  = array())
    {
        return $this->proxy('refresh_token', $refresh);
    }

    private function proxy($grantType, $data = array())
    {
        try {

            $oauth_clients = DB::table('oauth_clients')
            ->where('id','=','2')
            ->get()
            ->first();

            if($grantType == 'password'){

                $data = array_merge([
                    'client_id'     => $oauth_clients->id,
                    'client_secret' => $oauth_clients->secret,
                    'grant_type'    => $grantType
                ], $data);
    
            }else{
                $data = array_merge([
                    'client_id'     => $oauth_clients->id,
                    'client_secret' => $oauth_clients->secret,
                    'grant_type'    => $grantType,
                    'scope'         => ''
                ], $data);
            }
        
            $client = new Client();

            $guzzleResponse = $client->post(sprintf('%s/oauth/token', env('APP_URL')), [
                'form_params' => $data
            ]);
        } catch(\GuzzleHttp\Exception\BadResponseException $e) {
            $guzzleResponse = $e->getResponse();
        }
        
        $response = json_decode($guzzleResponse->getBody());
        if (property_exists($response, "access_token") && $grantType == 'password') {
            $response = [
                'accessToken'            => $response->access_token,
                'accessTokenExpiration'  => $response->expires_in,
                'refreshToken' => $response->refresh_token,
                'users' => $this->user
            ];
        }else{
            $response = [
                'accessToken'            => $response->access_token,
                'accessTokenExpiration'  => $response->expires_in,
                'refreshToken' => $response->refresh_token,
            ];
        }
        return response()->json($response,$guzzleResponse->getStatusCode());
    }

    public function getUser($credentials) {
        
        $this->user = DB::table('users')
        ->where('email','=',$credentials['username'])
        ->get()
        ->first();
        
        if ($this->user) {
            if (!app()->make('hash')->check($credentials['password'], $this->user->password)) {
                $this->user = null;
            }            
        }
    }

}
