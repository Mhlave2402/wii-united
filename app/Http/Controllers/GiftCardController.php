<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GiftCard;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use DB;
use Illuminate\Support\Facades\Storage; // Import Storage facade


class GiftCardController extends Controller
{
    public function generate(Request $request)
    {
        $validatedData = $request->validate([
            'value' => 'required|numeric|min:1',
            'quantity' => 'required|integer|min:1',
            'expiry_days' => 'required|integer|min:1',
        ], [
            'value.required' => 'Value is required.',
            'value.numeric' => 'Value must be a number.',
            'value.min' => 'Value must be at least 1.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be an integer.',
            'quantity.min' => 'Quantity must be at least 1.',
            'expiry_days.required' => 'Expiry days is required.',
            'expiry_days.integer' => 'Expiry days must be an integer.',
            'expiry_days.min' => 'Expiry days must be at least 1.',
        ]);

        $giftCards = [];
        for ($i = 0; $i < $validatedData['quantity']; $i++) {
            do {
                $code = Str::random(16);
            } while (GiftCard::where('code', $code)->exists());

            $expiryDate = Carbon::now()->addDays($validatedData['expiry_days']);
            $giftCards[] = [
                'code' => $code,
                'value' => $validatedData['value'],
                'expiry_date' => $expiryDate,
            ];
        }

        try {
            GiftCard::insert($giftCards);
            return redirect()->back()->with('success', 'Gift cards generated successfully.');
        } catch (\Exception $e) {
            Log::error('Gift card generation failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gift card generation failed. Please try again later.');
        }
    }


    public function redeem(Request $request)
    {
        $validatedData = $request->validate([
            'code' => ['required', 'string', 'max:255'], // Sanitize code input
            'user_id' => ['required', 'exists:users,id'],
        ], [
            'code.required' => 'Gift card code is required.',
            'code.max' => 'Gift card code cannot exceed 255 characters.',
            'user_id.required' => 'User ID is required.',
            'user_id.exists' => 'Invalid User ID.',
        ]);

        $giftCard = GiftCard::where('code', $validatedData['code'])->first();
        $user = User::find($validatedData['user_id']);

        if (!$giftCard || $giftCard->status != 'active' || $giftCard->expiry_date->isPast()) {
            return redirect()->back()->with('error', 'Gift card is not active or has expired.');
        }

        try {
            DB::beginTransaction();
            $user->increment('ewallet_balance', $giftCard->value);
            $giftCard->status = 'redeemed';
            $giftCard->save();
            DB::commit();
            return redirect()->back()->with('success', 'Gift card redeemed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gift card redemption failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gift card redemption failed. Please try again later.');
        }
    }
}
