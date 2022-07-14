<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterFormRequest;
use App\Http\Resources\PrivateUserResource;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function action(RegisterFormRequest $request)
    {

        $user = User::create($request->safe()->only(['email','name','password']));

        return new PrivateUserResource($user);
    }
}
