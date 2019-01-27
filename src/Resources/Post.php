<?php

namespace DigitalCloud\NovaBlogTool\Resources;

use DigitalCloud\NovaBlogTool\Actions\PublishPost;
use DigitalCloud\NovaBlogTool\Actions\SaveDraftPost;
use DigitalCloud\NovaBlogTool\Resources\Resource;
use DC\ButtonField\ButtonField;
use DC\FlagField\FlagField;
use DC\HiddenField\HiddenField;
use DigitalCloud\NovaBlogTool\ToolServiceProvider;
use DigitalCloud\NovaBlogTool\FieldSet;
use DigitalCloud\NovaBlogTool\PostForm;
use DC\SubmitButtonField\SubmitButtonField;
use Digitalcloud\MultilingualNova\Multilingual;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Fourstacks\NovaCheckboxes\Checkboxes;
use Infinety\Filemanager\FilemanagerField;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Trix;
use DigitalCloud\NovaBlogTool\Fields\ImageUpload;
use DigitalCloud\NovaBlogTool\Metrics\Posts\NewPosts;
use DigitalCloud\NovaBlogTool\Metrics\Posts\PostsTrend;
use Laravel\Nova\Nova;
use Spatie\TagsField\Tags;
use Yassi\NovaCustomForm\CustomFormTrait;

class Post extends Resource
{
    /**
     * The model the resource corresponds to.
     * @var string
     */
    public static $model = 'DigitalCloud\NovaBlogTool\Models\Post';

    /**
     * The single value that should be used to represent the resource when being displayed.
     * @var string
     */
    public static $title = 'title';

    /**
     * Hide resource from Nova's standard menu.
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * Get the searchable columns for the resource.
     * @return array
     */
    public static function searchableColumns()
    {
        return config('nova-blog.resources.posts.search');
    }

    /**
     * Get the fields displayed by the resource.
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return array_merge([

            new FieldSet( 'hidden',

                [
                    Select::make('status')->options([
                        'draft' => 'Draft',
                        'published' => 'Published'
                    ])->withMeta(['type' => 'hidden'])->onlyOnForms(),

                    //Boolean::make('published')->onlyOnForms()
                ],
                'main', ''
            ),

            $this->mainFieldSet(),

            new FieldSet( 'Tags',
                [
                    Tags::make('Tags')->withMeta(['label' => false]),
                ],
                'side', 'Tags'
            ),
            new FieldSet( 'Category',
                [
                    Checkboxes::make('Category', 'category')->options(\DigitalCloud\NovaBlogTool\Models\Category::pluck('name', 'id')->toArray())->withMeta(['label' => false]),
                ],
                'side', 'Category'
            ),
            new FieldSet( 'Featured Image',
                [
                    FilemanagerField::make('featured_image')->displayAsImage()->withMeta(['label' => false])
                    //ImageUpload::make('Image', 'featured_image', 'local')->store(new StoreImage),
                ],
                'side', 'Featured Image'
            ),

            HasMany::make('Comments', 'comments', Comment::class)
                ->sortable()
                ->rules(['required']),
        ], $this->conditionalFields($request));
    }

    /**
     * Get the cards available for the request.
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            (new NewPosts)->width('1/2'),
            (new PostsTrend)->width('1/2'),
        ];
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
        return [
            new PublishPost(),
            new SaveDraftPost()
        ];
    }

    public static function form ($request) {
        return new PostForm();
    }

    private function mainFieldSet() {
        return new FieldSet( 'Main Info',
            [
                ID::make()->sortable(),
                Text::make('title')->rules('required')
                    ->withMeta(['extraAttributes' => ['class' => 'attr1val', 'fullWidthContent' => true]])
                    ->withMeta(['showLabel' => true]),
                Select::make('status')->options([
                    'draft' => 'Draft',
                    'published' => 'Published'
                ])->withMeta(['type' => 'hidden'])->exceptOnForms(),
                Boolean::make('Published')->withMeta(['type' => 'hidden']),

                Trix::make('content')->withFiles('public')->rules('required')->withMeta(['label' => false]),
                Multilingual::make('lang'),
            ],
            'main', 'Main Info'
        );
    }

    private function conditionalFields(Request $request) {
        return ToolServiceProvider::availableAdditionalFields($request);
//        $fields = [];
//        if(in_array('DigitalCloud\ServiceTool\Resources\Service', Nova::availableResources($request))) {
//            $fields[] = BelongsToMany::make('Services', 'service', \DigitalCloud\ServiceTool\Resources\Service::class);
//        }
//        return $fields;
    }
}
