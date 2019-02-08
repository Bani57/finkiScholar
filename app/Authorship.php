<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Authorship extends Model
{
    protected $fillable = array('author_id', 'paper_id', 'is_lead_author', 'part');
}
