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

        if ($request->isMethod('OPTIONS')) {
            return response()->json(null, 200, $config['headers']);
        }

        $response = $next($request);

        if (!($response instanceof BinaryFileResponse)) {
            foreach ($config['headers'] as $header => $value) {
                $response->headers->set($header, $value);
            }
        }

        if (isset($_SERVER['HTTP_ORIGIN']) && $config['credentials']
            && in_array(($origin = $_SERVER['HTTP_ORIGIN']), $config['origins'])) {

            $response->header->set('Access-Control-Allow-Origin', $origin);
        }

        return $response;
    }

}