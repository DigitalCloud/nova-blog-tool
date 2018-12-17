<?php

namespace DigitalCloud\NovaBlogTool\Processors;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic;
use DigitalCloud\NovaBlogTool\Models\Image;

class StoreImage
{
    public function __invoke(Request $request, $model)
    {
        $image_file = $request->featured_image;

        $file_extension = $image_file->getClientOriginalExtension();

        $new_filename = str_random(8).'_'.time().''.str_random(32);

        $filename = $new_filename.'.'.$file_extension;

        $filename_thumb = $new_filename.'.'.$file_extension;

        Storage::disk(config('nova-blog.image_settings.disk'))
            ->put(
                config('nova-blog.image_settings.path').$filename,
                (string) file_get_contents($image_file)
            );
        $image_thumb = ImageManagerStatic::make($image_file)
            ->resize(
                config('nova-blog.image_thumb_settings.width'),
                config('nova-blog.image_thumb_settings.height')
            )->save($filename_thumb);
        Storage::disk(config('nova-blog.image_settings.disk'))
            ->put(
                config('nova-blog.image_settings.path_thumb').$filename_thumb,
                $image_thumb
            );

        //@todo Check is image uploaded with the post or alone in Image page.
//        $image_model = new Image;
//        $image_model->title = $image_file->getClientOriginalName();
//        $image_model->filename = $filename;
//        $image_model->thumbnail = $filename_thumb;
//        $image_model->size = number_format(
//                $image_file->getSize() / 1000000,
//                2
//            ).'MB';
//        $image_model->save();

        return [
            //'image_id' => $image_model->id,
            'featured_image' => $filename,
        ];
    }
}
