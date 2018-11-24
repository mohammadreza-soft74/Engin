<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/24/18
 * Time: 9:55 AM
 */

namespace App\Clasess\Base\RequestValidate;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class RequestValidate
{
    /**
     * @brief validate create Request
     * @param Request $request
     * @return Request
     * @throws \Exception
     */
    public static function createValidator(Request $request)
    {
        $res = [];

        $validator = Validator::make($request->all(), [
            'key' => 'required|string'

        ]);
        if ($validator->fails())
            throw  new \Exception($validator->messages());

        $req['key'] = $request->key;

        return $req;

    }

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public static function pageloadValidator(Request $request)
    {
        $req = [];

        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
            'key' => 'required|string'
        ]);
        if ($validator->fails())
            throw  new \Exception($validator->messages());

        $req['key'] = $request->key;
        $req['path'] = $request->path;

        return $req;
    }
}