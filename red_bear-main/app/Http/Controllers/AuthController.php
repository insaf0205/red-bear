<?php

namespace App\Http\Controllers;

use App\Models\User;

class AuthController extends Controller
{

    public function register()
    {

        $validator = validator(request()->all(), [
            "email"                 => "required|unique:users,email",
            "password"              => "required|string|min:6|confirmed",
            "password_confirmation" => "required|string",
        ]);

        if ($validator->fails()) return back()->withErrors($validator->errors());

        $data = $validator->validated();

        unset($data['password_confirmation']);

        User::create($data);

        return redirect("/login");

    }

    public function login()
    {
        $validator = validator(request()->all(), [
            "email" => "required|exists:users,email",
            "password" => "required"
        ]);

        if ($validator->fails()) return back()->withErrors($validator->errors())->with(["message" => "Invalid fields"]);

        $data = $validator->validated();

        if (!auth()->attempt($data)) return back()->with(["message" => "Не верный логин или пароль"]);

        return redirect("/profile");
    }

    /**
     * Display the specified resource.
     */
    public function logout()
    {
        auth()->logout();

        return redirect('/');
    }
}
