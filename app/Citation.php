<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Citation extends Model
{
    protected $fillable = array('citing_paper_id', 'cited_paper_id', 'part');

}
