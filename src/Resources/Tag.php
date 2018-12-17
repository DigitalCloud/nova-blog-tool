<?php

namespace DigitalCloud\NovaBlogTool\Resources;

use App\Nova\Resource;
use Digitalcloud\MultilingualNova\Multilingual;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\BelongsToMany;

class Tag extends Resource
{
    /**
     * The model the resource corresponds to.
     * @var string
     */
    public static $model = 'DigitalCloud\NovaBlogTool\Models\Tag';

    /**
     * The single value that should be used to represent the resource when being displayed.
     * @var string
     */
    public static $title = 'name';

    /**
     * Hide resource from Nova's standard menu.
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * Get the searchable columns for the resource.
     * @return array
     */
//    public static function searchableColumns()
//    {
//        return config('nova-blog.resources.tags.search');
//    }

    /**
     * Get the fields displayed by the resource.
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        $locale = $locale ?? app()->getLocale();
        return [
            ID::make()->sortable(),

            Text::make('name')
                ->sortable()
                ->rules(['required', 'string', 'max:255'])
                ->creationRules(['unique:tags,name->' . $locale])
                ->updateRules(['unique:tags,name->' . $locale . ',{{resourceId}}']),
            Multilingual::make('lang'),
            MorphToMany::make('Posts', 'posts', Post::class),
        ];
    }

    /**
     * Get the cards available for the request.
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
