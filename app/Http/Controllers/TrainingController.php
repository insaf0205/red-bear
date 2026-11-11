<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditTrainingRequest;
use App\Http\Requests\StoreTrainingRequest;
use App\Models\Training;
use App\Models\User;
use App\Services\ImageService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TrainingController extends Controller
{

    public function __construct(
        private ImageService $imageService
    )
    {

    }

    public function book(Training $training)
    {
        if ($training->student_id) {
            return back()->with('toast', [
                'type' => 'error',
                'message' => 'На эту тренировку уже есть запись',
            ]);
        }

        $user = auth()->user();

        if ($user->free_sessions_with_coach > 0) {

            $user->decrement("free_sessions_with_coach");

        } else {

            if ($user->balance >= $training->cost) {
                $user->decrement("balance", $training->cost);

                $training->update([
                   "cost_last_user_paid" => $training->cost
                ]);

            } else {
                return back()->with("toast", [
                    "message" => "У вас недостаточно средств. Пожалуйста, пополните счет"
                ]);
            }

        }

        $training->update([
            "student_id" => $user->id
        ]);

        return redirect("profile");
    }

    public function unbook(Training $training)
    {
        if (!$training->student_id) {
            return back()->withErrors(["message" => "На эту тренировку и так никто не записан"]);
        }

        $user = auth()->user();

        if ($training->student_id !== $user->id) {
            return back()->withErrors(["message" => "У вас нет прав"]);
        }

        $user->increment("balance", $training->cost_last_user_paid);
        $training->update([
            "cost_last_user_paid" => null
        ]);

        $training->update([
            "student_id" => null
        ]);

        return back();
    }

    public function show_trainings()
    {
        $user = auth()->user();

        $trainings = Training::query()
            ->whereDoesntHave('student')
            ->where('end_at', '>', Carbon::now('UTC'))
            ->latest('end_at')
            ->paginate(5);

        return view("trainings", compact("user", "trainings"));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

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
   public function store(StoreTrainingRequest $request)
   {
       $data = $request->validated();

       $image = $data["image"] ?? null;

       $data["cost"] = $data["cost"] ?? 400;

       if ($image) {
           $data["image_url"] = $this->imageService->save_image($image);
           unset($data["image"]);
       }

        Training::create($data);

        return redirect('/profile');
   }

    /**
     * Display the specified resource.
     */
    public function show_edit_training(Training $training)
    {
        $user = auth()->user();

        $trainers = User::query()
            ->where("role", "admin")
            ->get();

        return view("edit_training", compact("training", "user", "trainers"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Training $training, EditTrainingRequest $request)
    {
        $data = $request->validated();

        $image = $data["image"] ?? null;

        $data["cost"] = $data["cost"] ?? 400;

        if ($image) {
            $this->imageService->remove_image($training->image_url);
            $data["image_url"] = $this->imageService->save_image($image);
            unset($data["image"]);
        }

        $training->update($data);

        return redirect('/profile');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Training $training)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Training $training)
    {
        $this->imageService->remove_image($training->image_url);

        $training->delete();

        return redirect("/profile");
    }

    public function show_create_training()
    {
        $user = auth()->user();

        $trainers = User::query()
            ->where("role", "admin")
            ->get();

        return view("create-train", compact("user", "trainers"));
    }
}
