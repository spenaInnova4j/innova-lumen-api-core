<?php namespace App\Http\Middleware;

use Closure;
use Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CorsMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $config = config('cors');

        $response = $next($request);

        // if (!($response instanceof BinaryFileResponse))
        //     $response->withHeaders($config['headers']);

        if (isset($_SERVER['HTTP_ORIGIN']) && $config['credentials']
            && in_array(($origin = $_SERVER['HTTP_ORIGIN']), $config['origins'])) {

            $response
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Allow-Origin', $origin);
        }

        return $response;
    }

}