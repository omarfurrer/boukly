<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "name"
    ];

    /**
     * A tag belongs to many bookmarks.
     *
     * @return BelongsToMany
     */
    public function bookmarks()
    {
        return $this->belongsToMany(Bookmark::class)->withPivot('user_id')->withTimestamps();
    }
}
