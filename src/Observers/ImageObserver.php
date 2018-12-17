<?php

namespace DigitalCloud\NovaBlogTool\Observers;

use Illuminate\Support\Facades\Storage;
use DigitalCloud\NovaBlogTool\Models\Image;

class ImageObserver
{
    /**
     * Handle the image "deleting" event.
     * @param Image $image
     * @return void
     */
    public function deleting(Image $image)
    {
        Storage::disk(config('nova-blog.image_settings.disk'))->delete($image->filename);
        Storage::disk(config('nova-blog.image_settings.disk'))->delete(config('nova-blog.image_settings.path_thumb').$image->thumbnail);
    }
}
