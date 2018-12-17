<?php

namespace DigitalCloud\NovaBlogTool\Models;

use DigitalCloud\NovaBlogTool\Scopes\PublishedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;
use DigitalCloud\NovaBlogTool\Traits\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Tags\HasTags;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use SoftDeletes, Sluggable, HasTags, HasTranslations, Actionable;

    public $translatable = ['title', 'content', 'category'];
    protected $guard_name = 'web';

    protected $queuedCategories = [];

    /**
     * Fillable properties.
     * @var array
     */
    protected $fillable = [
        'user_id',
        'featured_image',
        'title',
        'content',
        'scheduled_for',
        'featured',
    ];

    /**
     * Appended fields.
     * @var array
     */
    //protected $appends = ['published'];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'featured' => 'boolean',
        'scheduled_for' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = [
        'scheduled_for',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Published mutator.
     * @return bool
     */
//    public function getPublishedAttribute()
//    {
//        return now() > $this->scheduled_for;
//    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::updating(function($model){
//            if($model->published) {
//                $model->status = 'published';
//            }
//            if($model->status != 'published') {
//                $model->published = 0;
//            }
        });

        self::creating(function($model){
            if($model->published) {
                $model->status = 'published';
            }
        });

        self::created(function($model){
            $model->category()->sync($model->queuedCategories, true);
            $model->queuedCategories = [];
        });

        static::addGlobalScope(new PublishedScope());
    }

    function setCategoryAttribute($value){
        if (! $this->exists) {
            $this->queuedCategories = $value;
            return;
        }

        $this->category()->sync($value, true);
    }

    function getCategoryAttribute(){
        return implode(',', $this->category()->pluck('category_id')->toArray());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(config('nova-blog.resources.users.model'), 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
//    public function featured_image(): BelongsTo
//    {
//        return $this->belongsTo(Image::class, 'image_id');
//    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'post_category');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
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

    public function tags(): MorphToMany
    {
        return $this->morphToMany(
            Tag::class,
            'taggable',
            'taggables',
            'taggable_id',
            'tag_id'
        );
    }
}
