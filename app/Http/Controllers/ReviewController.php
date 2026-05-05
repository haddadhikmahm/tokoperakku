<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $existing = Review::where('user_id', Auth::id())
                          ->where('produk_id', $request->produk_id)
                          ->first();

        if ($existing) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Anda sudah memberikan ulasan untuk produk ini.'], 422);
            }
            return back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        $review = Review::create([
            'user_id' => Auth::id(),
            'produk_id' => $request->produk_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ulasan berhasil dikirim.',
                'review' => [
                    'username' => Auth::user()->username,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'date' => $review->created_at->diffForHumans(),
                    'initials' => strtoupper(substr(Auth::user()->username, 0, 2))
                ]
            ]);
        }

        return back()->with('success', 'Ulasan berhasil dikirim.');
    }
}
