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
        return $this->belongsToMany('App\Paper', 'reviews');
    }

    public function reviews()
    {
        return $this->hasMany('App\Review');
    }
}
