<?php

namespace App\Http\Controllers;

use App\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function getAllTopics()
    {
        $topics = Topic::all();
        return response(json_encode($topics), 200);
    }

    public function getRootTopics()
    {
        $topics = Topic::where('parent_topic_id', null)->get();
        return response(json_encode($topics), 200);
    }

    public function getSubTopics($id)
    {
        $topic = Topic::find($id);
        $subtopics = $topic->subtopics;
        return response(json_encode($subtopics), 200);
    }

    public function getAncestors($id)
    {
        $topic = Topic::find($id);
        $ancestors = array();
        $tmp = $topic;
        while (!is_null($tmp)) {
            array_push($ancestors, $tmp);
            $tmp = $tmp->parent_topic;
        }
        $ancestors = array_reverse($ancestors, false);
        return response(json_encode($ancestors), 200);
    }

    public function getPapers($id)
    {
        $topic = Topic::find($id);
        $papers = $topic->papers()->orderBy('created_at', 'desc')->get();
        return response(json_encode($papers), 200);
    }

    public function getAuthorsForPapers($id)
    {
        $topic = Topic::find($id);
        $papers = $topic->papers()->orderBy('created_at', 'desc')->get();
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

}
