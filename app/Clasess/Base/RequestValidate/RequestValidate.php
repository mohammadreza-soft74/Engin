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

        $validator = Validator::make($request->all(), [
            'key' => 'required|string'

        ]);
        if ($validator->fails())
            throw  new \Exception($validator->messages());

        $request['key'] = $request->keys;

        return $request;

    }
}