<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Access
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {


        foreach ($request->all() as $key => $value) { // sql injection

            if (!preg_match('/{.*}/', $value)) {
                if (preg_match('/\'|\"|\<|\>|select|union|select|insert|drop|delete|update|cast|create|convert|alter|declare|order|script|md5|benchmark|encode/', strtolower($value))) {
                    $msg = "invalid request for input $value";
                    return response(['status' => false, 'msg' => $msg], 403);
                }
            }
        }

        // openssl decrypt
        $key = hex2bin("c0ff70cc197a07dff1fb709688170426");
        $iv =  hex2bin("f8b4e45085a1045902c3c69c80e67a7c");

        $decrypted = openssl_decrypt($request->get('request'), 'AES-128-CBC', $key, OPENSSL_ZERO_PADDING, $iv);

        $decrypted = trim($decrypted); // you have to trim it according to https://stackoverflow.com/a/29511152

        foreach (explode(';', $decrypted) as $item) {

            if (preg_match('/.*:.*/', $item)) {

                $input = explode(':', $item);

                if (!preg_match('/{.*}/', trim($input[0]))) {
                    if (preg_match('/\'|\"|\<|\>|select|union|select|insert|drop|delete|update|cast|create|convert|alter|declare|order|script|md5|benchmark|encode/', strtolower(trim($input[0])))) {
                        $msg = "invalid request for input $input[0]";
                        return response(['status' => false, 'msg' => $msg], 403);
                    }
                }

                if (!preg_match('/{.*}/', trim($input[1]))) {
                    if (preg_match('/\'|\"|\<|\>|select|union|select|insert|drop|delete|update|cast|create|convert|alter|declare|order|script|md5|benchmark|encode/', strtolower(trim($input[1])))) {
                        $msg = "invalid request for input $input[0] with value $input[1]";
                        return response(['status' => false, 'msg' => $msg], 403);
                    }
                }

                $request->request->add([trim($input[0])  => trim($input[1])]);
            }
        }


        if ($request->get('origin') != 'localhost') {
            $msg = 'invalid request';
            return response(['status' => false, 'msg' => $msg], 400);
        }




        return $next($request);
    }
}
