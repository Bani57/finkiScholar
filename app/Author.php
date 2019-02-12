<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = array('user_id', 'organization_name', 'organization_type', 'organization_country',
        'organization_address', 'organization_email', 'organization_telephone');

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function papers()
    {
        return $this->belongsToMany('App\Paper', 'authorships',
            'author_id', 'paper_id', 'user_id')
            ->withPivot('is_lead_author', 'part')->as('authorship');
    }
}
