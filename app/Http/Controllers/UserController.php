<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function home()
    {
        $user = auth()->user();

        $trainings = null;

        $trainings_past = null;

        if ($user) {
            $trainings = Training::query()
                ->whereDoesntHave('student')
                ->where('end_at', '>', Carbon::now('UTC'))
                ->latest('end_at')
                ->paginate(5);
        }


        return view("home", compact("user", "trainings", "trainings_past"));
    }

    public function show_me()
    {
        $user = auth()->user();

        $trainings = Training::query()
            ->whereDoesntHave('student')
            ->where('end_at', '>', Carbon::now('UTC'))
            ->latest('end_at')
            ->paginate(5);

        $past_trainings = null;

        if ($user->role === "user") {
            $past_trainings = Training::query()
                ->where('student_id', $user->id)
                ->where('end_at', '<', now())
                ->latest('end_at')
                ->paginate(5);
        }

        return view("profile", compact("user", "trainings", "past_trainings"));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
//        dd(\request()->all());

        $user = auth()->user();

        $validator = validator(request()->all(), [
            "name" => "nullable|string|max:100",
            "surname" => "nullable|string|max:100",
            "phone" => "nullable|string|max:20",
        ]);

        if ($validator->fails()) return back()->withErrors($validator->errors())->with(["message" => "Invalid fields"]);

        $data = $validator->validated();

        $user->update($data);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
