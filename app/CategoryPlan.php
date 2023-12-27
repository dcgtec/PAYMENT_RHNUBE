<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryPlan extends Model
{
    protected $table = 'category_plan';

    protected $fillable = [
        'id','name', 'slug','totNumMonth','description', 'action',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
