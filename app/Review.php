<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = array('reviewer_id', 'paper_id', 'score', 'comment', 'passed');
}
