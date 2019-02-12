<?php

namespace App\Http\Controllers;

use App\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function updateReview(Request $request)
    {
        $reviewer_id = $request->json('reviewer_id');
        $paper_id = $request->json('paper_id');
        $review = Review::where('reviewer_id', $reviewer_id)->where('paper_id', $paper_id)->first();
        $review->score = $request->json('score');
        $review->comment = $request->json('comment');
        $review->passed = $request->json('passed');
        $review->save();
        return response(json_encode('Review updated successfully.'), 200);
    }
}
