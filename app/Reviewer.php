<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reviewer extends Model
{
    protected $fillable = array('user_id', 'topic_id');

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function papers()
    {
        return $this->belongsToMany('App\Paper', 'reviews',
            'reviewer_id', 'paper_id', 'user_id')
            ->withPivot('score', 'comment', 'passed')->as('review');
    }
}
