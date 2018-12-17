<?php

namespace DigitalCloud\NovaBlogTool\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Nova\Actions\Actionable;
use Spatie\Translatable\HasTranslations;

class Comment extends Model
{
    use SoftDeletes, Actionable, HasTranslations;

    public $translatable = ['body'];

    /**
     * Fillable properties.
     * @var array
     */
    protected $fillable = [
        'post_id',
        'user_id',
        'body',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'post_id' => 'integer',
        'user_id' => 'integer',
        'body' => 'string',
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(config('nova-blog.user_model'), 'user_id');
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->translatable) && ! is_array($value)) {
            return $this->setTranslation($key, app()->getLocale(), $value);
        }
        return parent::setAttribute($key, $value);
    }
}
