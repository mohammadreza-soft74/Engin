<?php

namespace App\Http\Middleware;

use Closure;
use Config;

class IpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $allowed_ips = Config::get("network.request_allowed_ips");

        if (in_array($request->ip(),$allowed_ips))
            return $next($request);

        else
        {
            $response = $next($request);
            $response->setContent(json_encode(["Message"=>"*Attention* sorry you are not allowed to communicate with this server!" , "ip"=>$request->ip()]));
            return $response;
        }
    }
}
