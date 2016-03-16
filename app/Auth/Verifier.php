<?php
/**
 * Author: Junior Fontenele <lcbfjr@gmail.com>
 * Date: 16/03/2016
 * Time: 17:36
 */

namespace CodeProject\Auth;


use Illuminate\Support\Facades\Auth;

class Verifier
{
    public function verify($username, $password)
    {
        $credentials = [
            'email'    => $username,
            'password' => $password,
        ];

        if (Auth::once($credentials)) {
            return Auth::user()->id;
        }

        return false;
    }
}