<?php

namespace DigitalCloud\NovaBlogTool;

use Illuminate\Http\Resources\MergeValue;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Panel;

class FieldSet extends MergeValue implements \JsonSerializable
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'field-set';

    /**
     * The name of the panel.
     *
     * @var string
     */
    public $name;
    public $label;
    public $position;
    public $class;
    public $style;

    /**
     * The panel fields.
     *
     * @var array
     */
    public $data;

    public $panel;

    /**
     * Create a new panel instance.
     *
     * @param  string  $name
     * @param  \Closure|array  $fields
     * @return void
     */
    public function __construct($name, $fields = [], $position = '', $label = '', $class = '', $style='')
    {
        $this->name = $name;
        $this->label = $label;
        $this->position = $position;
        $this->class = $class;
        $this->style = $style;
        parent::__construct($this->prepareFields($fields));
    }

    /**
     * Prepare the panel for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'component' => $this->component,
            'name' => $this->name,
            'label' => $this->label,
            'position' => $this->position,
            'prefixComponent' => true,
            'panel' => $this->panel,
            'indexName' => $this->name,
            'fields' => $this->data,
            'class' => $this->class,
            'style' => $this->style
        ];
    }

    /**
     * Prepare the given fields.
     *
     * @param  \Closure|array  $fields
     * @return array
     */
    protected function prepareFields($fields)
    {
        return collect(is_callable($fields) ? $fields() : $fields)->each(function ($field) {
            if($field instanceof Field) {
                $field->withMeta(['fieldSet' => $this->name]);
            }
        })->all();
    }
}
