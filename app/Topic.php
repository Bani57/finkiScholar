<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = array('name', 'parent_topic_id');

    public function parent_topic()
    {
        return $this->belongsTo('App\Topic');
    }

    public function subtopics()
    {
        return $this->hasMany('App\Topic', 'parent_topic_id');
    }

    public function papers()
    {
        return $this->hasMany('App\Paper');
    }
}
