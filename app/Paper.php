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
        return $this->belongsToMany('App\Author', 'authorships');
    }

    public function citations()
    {
        return $this->belongsToMany('App\Paper', 'citations',
            'cited_paper_id', 'citing_paper_id');
    }

    public function bibliography()
    {
        return $this->belongsToMany('App\Paper', 'citations',
            'citing_paper_id', 'cited_paper_id');
    }

    public function bibliography_external()
    {
        return $this->hasMany('App\ExternalCitation');
    }

    public function reviewers()
    {
        return $this->belongsToMany('App\Reviewer', 'reviews');
    }

    public function reviews()
    {
        return $this->hasMany('App\Review');
    }
}
