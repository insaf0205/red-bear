<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Review;
use App\Models\Training;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(User $user, StoreCommentRequest $request)
    {
        $data = $request->validated();

        if ($user->role !== 'admin') {
            return back()->with("toast", [
                "message" => "Нельзя оставить отзыв не на тренера",
                "type" => "error"
            ]);
        }

        $authUser = auth()->user();

        $user_visited_trainer = Training::query()
            ->where('trainer_id', $user->id)
            ->where('student_id', $authUser->id)
            ->where('end_at', '<=', now())
            ->exists();

        if (!$user_visited_trainer) {
            return back()->with('toast', [
                'type' => 'error',
                'message' => 'Вы можете оставить отзыв только после завершённой тренировки.',
            ]);
        }

        if (Review::query()
            ->where('user_id', $authUser->id)
            ->where('trainer_id', $user->id)
            ->exists()
        ) {
            return back()->with('toast', [
                'type' => 'error',
                'message' => 'Вы уже оставили отзыв этому тренеру.',
            ]);
        }

        $data["user_id"] = $authUser->id;
        $data["trainer_id"] = $user->id;

        Review::create($data);

        return back()->with("toast", [
            "message" => "Вы создали коммент"
        ]);

        // Здесь создание отзыва
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        //
    }
}
