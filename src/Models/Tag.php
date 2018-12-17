<?php

namespace DigitalCloud\NovaBlogTool\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class Tag extends \Spatie\Tags\Tag
{
    use SoftDeletes, Actionable;
    /**
     * Fillable properties.
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'tagged_count',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'tagged_count' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsToMany
     */

    public function posts(): MorphToMany
    {
        return $this->morphedByMany(
            Post::class,
            'taggable',
            'taggables',
            'tag_id',
            'taggable_id'
        );
    }
}
