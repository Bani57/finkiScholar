<?php

namespace App\Http\Controllers;

use App\Reviewer;
use Illuminate\Http\Request;

class ReviewerController extends Controller
{
    public function getPendingReviews($id)
    {
        $reviewer = Reviewer::where('user_id', $id)->first();
        $papers = $reviewer->papers()->orderBy('created_at', 'desc')->get();
        $pendingPapers = array();
        foreach ($papers as $paper) {
            if ($paper->review->passed == 0)
                array_push($pendingPapers, $paper);
        }
        return response(json_encode($pendingPapers), 200);
    }

    public function getLeadAuthorsForPendingReviews($id)
    {
        $reviewer = Reviewer::where('user_id', $id)->first();
        $papers = $reviewer->papers()->orderBy('created_at', 'desc')->get();
        $authors = array();
        foreach ($papers as $paper) {
            if ($paper->review->passed == 0) {
                $lead_author = $paper->authors()->where('is_lead_author', 1)->first();
                array_push($authors, $lead_author->user);
            }
        }
        return response(json_encode($authors), 200);
    }
}
