<?php

namespace DigitalCloud\NovaBlogTool\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;
use DigitalCloud\NovaBlogTool\Traits\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use Sluggable, HasTranslations, SoftDeletes, Actionable;

    public $translatable = ['name', 'description'];

    /**
     * Model fillable fields.
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * @return HasMany
     */
    public function posts() : BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_category', 'post_id', 'category_id');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->translatable) && ! is_array($value)) {
            return $this->setTranslation($key, app()->getLocale(), $value);
        }
        return parent::setAttribute($key, $value);
    }
}
