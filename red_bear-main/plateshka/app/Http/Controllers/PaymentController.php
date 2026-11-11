<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            "sum" => "required|numeric|min:10",
            "webhook_url" => "required|string|max:255",
        ]);

        if ($validator->fails()) return response()->json(["message" => "Validation error", "errors" => $validator->errors()], 422);

        $payment = Payment::create($validator->validated());

        return response()->json([
            "pay_url" => rtrim(config('app.url'), '/') . "/payments/{$payment->id}",
            "payment_id" => $payment->id,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        return view('payment', compact('payment'));
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
        if($payment->status !== "pending") return back()->withErrors(["error" => "Платеж уже обработан"]);

        $validator = validator($request->all(), [
            "card_number" => "required|string",
        ]);

        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();

        $card_number = Str::of($validator->validated()["card_number"])->replaceMatches('/\\s+/', '');;
        $status = "";

        if ($card_number == "8888000000004444") {
            $status = "success";
        } else if ($card_number == "8888000000001488") {
            $status = "failed";
        } else {
            return back()->withErrors(["error" => "Неверные реквизиты для оплаты"]);
        }

        try{
            Http::post($payment->webhook_url, [
                "payment_id" => $payment->id,
                "status" => $status,
            ]);

            $payment->update([
                "status" => $status,
            ]);

            return back();
        }catch (\Exception $exception)
        {
            return back()->withErrors(["error" => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
