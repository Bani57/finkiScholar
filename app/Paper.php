<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    protected $fillable = array('title', 'file', 'abstract', 'status', 'date_submitted', 'topic_id');

    public function topic()
    {
        return $this->belongsTo('App\Topic');
    }

    public function authors()
    {
        return $this->belongsToMany('App\Author', 'authorships',
            'paper_id', 'author_id', 'id', 'user_id')
            ->withPivot('part')->as('authorship');
    }

    public function citations()
    {
        return $this->belongsToMany('App\Paper', 'citations',
            'cited_paper_id', 'citing_paper_id')
            ->withPivot('part')->as('citation');
    }

    public function bibliography()
    {
        return $this->belongsToMany('App\Paper', 'citations',
            'citing_paper_id', 'cited_paper_id')
            ->withPivot('part')->as('citation');
    }

    public function reviewers()
    {
        return $this->belongsToMany('App\Reviewer', 'reviews',
            'paper_id', 'reviewer_id', 'id', 'user_id')
            ->withPivot('score', 'comment', 'passed')->as('review');
    }
}
