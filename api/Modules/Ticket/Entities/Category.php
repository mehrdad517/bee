<?php

namespace Modules\Ticket\Entities;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Modules\Tag\Entities\Tag;

class Category extends Model
{
    use NodeTrait;

    protected $primaryKey = 'id';

    protected $table = 'ticket_category';

    protected $guarded = [];

    public $timestamps = false;


    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'ticket_category_has_tags', 'category_id', 'tag_id');
    }

}
