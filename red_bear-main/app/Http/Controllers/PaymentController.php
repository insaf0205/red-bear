<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
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

    public function show_payment_page()
    {
        $user = auth()->user();

        return view("topup", compact("user"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $validator = validator()->make($request->all(), [
            "sum" => "required|numeric|min:100"
        ]);

        if($validator->fails()) return back()->withErrors(["message" => "Минимальная сумма пополнения - 100 рублей"]);

        try{
            $response = Http::post('http://payment:8000/api/payments', [
                "sum" => $request["sum"],
                "webhook_url" => "http://web/api/payment-webhook"
            ]);

            $sum = $request['sum'];

            Payment::create([
                "user_id" => $user->id,
                "sum" => $sum,
                "payment_id" => $response['payment_id'],
            ]);

            return redirect($response['pay_url']);

        } catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function updateStatus(Request $request)
    {
        $payment = Payment::query()->where('payment_id', $request->payment_id)->firstOrFail();

        $payment->update(["status" => $request->status]);

        if($request->status === "success")
        {
            $user = User::find($payment->user_id);
            $user->increment('balance', $payment->sum);
        }

        return response()->noContent();
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
