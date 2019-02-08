<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExternalCitation extends Model
{
    protected $fillable = array('title', 'authors', 'link', 'date_published', 'type', 'part', 'paper_id');

    public function paper()
    {
        return $this->belongsTo('App\Paper');
    }

}
