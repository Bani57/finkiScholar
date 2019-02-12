<?php

namespace App\Http\Controllers;

use App\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{

    public function getAllAuthors()
    {
        $authors=Author::all();
        $users=array();
        foreach ($authors as $author)
            array_push($users, $author->user);
        return response(json_encode($users), 200);
    }

    public function getPapersByAuthor($id)
    {
        $author = Author::where('user_id', $id)->first();
        $papers = $author->papers()->orderBy('created_at', 'desc')->get();
        return response(json_encode($papers), 200);
    }

    public function getTopicsForPapers($id)
    {
        $author = Author::where('user_id', $id)->first();
        $papers = $author->papers()->orderBy('created_at', 'desc')->get();
        $topics = array();
        foreach ($papers as $paper) {
            $topic = $paper->topic;
            array_push($topics, $topic->name);
        }
        return response(json_encode($topics), 200);
    }

    public function getNumCitationsForPapers($id)
    {
        $author = Author::where('user_id', $id)->first();
        $papers = $author->papers()->orderBy('created_at', 'desc')->get();
        $citations = array();
        foreach ($papers as $paper) {
            $num_citations = count($paper->citations);
            array_push($citations, $num_citations);
        }
        return response(json_encode($citations), 200);
    }

    public function getCoAuthorsForPapers($id)
    {
        $author = Author::where('user_id', $id)->first();
        $papers = $author->papers()->orderBy('created_at', 'desc')->get();
        $authors = array();
        foreach ($papers as $paper) {
            $authors_names = array();
            foreach ($paper->authors as $author) {
                $user = $author->user;
                $name = $user->first_name . " " . $user->last_name;
                array_push($authors_names, $name);
            }
            $authors_string = implode(', ', $authors_names);
            array_push($authors, $authors_string);
        }
        return response(json_encode($authors), 200);
    }

    public function getReviewsForPapers($id)
    {
        $author = Author::where('user_id', $id)->first();
        $papers = $author->papers()->orderBy('created_at', 'desc')->get();
        $reviews=array();
        foreach ($papers as $paper){
            $review_data=array();
            foreach ($paper->reviewers as $reviewer)
                array_push($review_data, $reviewer->review);
            array_push($reviews, $review_data);
        }
        return response(json_encode($reviews), 200);
    }

    public function getReviewersForPapers($id)
    {
        $author = Author::where('user_id', $id)->first();
        $papers = $author->papers()->orderBy('created_at', 'desc')->get();
        $reviewers=array();
        foreach ($papers as $paper){
            $users=array();
            foreach ($paper->reviewers as $reviewer)
                array_push($users,$reviewer->user);
            array_push($reviewers, $users);
        }
        return response(json_encode($reviewers), 200);
    }
}
