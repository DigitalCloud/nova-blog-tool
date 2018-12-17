<?php

namespace DigitalCloud\NovaBlogTool\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class Image extends Model
{
    use SoftDeletes, Actionable;
    /**
     * Fillable properties.
     * @var array
     */
    protected $fillable = [
        'title',
        'filename',
        'thumbnail',
        'size',
    ];

    /**
     * Get image's link.
     * @return string
     */
    public function getLinkAttribute()
    {
        return url('uploads/images/'.$this->filename);
    }

    /**
     * Get image thumbnail's link.
     * @return string
     */
    public function getThumbnailLinkAttribute()
    {
        return url('uploads/images/'.config('nova-blog.image_settings.disk').$this->thumbnail);
    }
}
