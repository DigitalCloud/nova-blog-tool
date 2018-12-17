<?php

namespace DigitalCloud\NovaBlogTool\Resources;

use DigitalCloud\NovaBlogTool\FieldSet;
use Laravel\Nova\Resource as NovaResource;
use Laravel\Nova\Http\Requests\NovaRequest;
use Yassi\NestedForm\Traits\NestedFormTrait;
use Yassi\NovaCustomForm\CustomFormTrait;

abstract class Resource extends NovaResource
{
    use CustomFormTrait;
    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query;
    }

    /**
     * Build a Scout search query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Scout\Builder  $query
     * @return \Laravel\Scout\Builder
     */
    public static function scoutQuery(NovaRequest $request, $query)
    {
        return $query;
    }

    /**
     * Build a "detail" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function detailQuery(NovaRequest $request, $query)
    {
        return parent::detailQuery($request, $query);
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuery(NovaRequest $request, $query)
    {
        return parent::relatableQuery($request, $query);
    }



    ///////////////////////////////
    public function creationFields(NovaRequest $request)
    {
        $creationFields = parent::creationFields($request);
        if(!$request->isMethod('get')) {
            return $creationFields;
        }
        $indexedCreationFields = $creationFields->mapWithKeys(function ($item) {
            return [$item->attribute => $item];
        });

        return $this->availableFieldSet($request, $indexedCreationFields);
    }

    public function updateFields(NovaRequest $request) {
        $updateFields = parent::updateFields($request);
        if(!$request->isMethod('get')) {
            return $updateFields;
        }
        $indexedUpdateFields = $updateFields->mapWithKeys(function ($item) {
            return [$item->attribute => $item];
        });
        return $this->availableFieldSet($request, $indexedUpdateFields);
    }

    public function detailFields(NovaRequest $request)
    {
        $detailFields = parent::detailFields($request);
        if(!$request->isMethod('get')) {
            return $detailFields;
        }
        //$detailFields = $this->availableFieldSet($request, $detailFields);
        return $detailFields;

    }

    public function availableFieldSet(NovaRequest $request, $fields)
    {
        $fieldSets = collect(array_values($this->fields($request)))
            ->whereInstanceOf(FieldSet::class)->mapWithKeys(function ($fieldSet) {
                return [camel_case($fieldSet->name) => $fieldSet];
            });

        if(count($fieldSets) < 1) {
            return $fields;
        }
        $this->assignFieldsToFieldSet($request, $fields);

        foreach ($fieldSets as $fieldSet) {
            $setfields = $fields->filter(function ($field) use ($fieldSet){
                return $field->meta['fieldSet'] == $fieldSet->name;
            });
            $fieldSet->data = $setfields;
        }
        return $fieldSets;
    }

    protected function assignFieldsToFieldSet(NovaRequest $request, $fields)
    {
        foreach ($fields as $field) {
            $name = $field->meta['fieldSet'] ?? Panel::defaultNameFor($request->newResource());
            $field->meta['fieldSet'] = $name;
        }
        return $fields;
    }
}
