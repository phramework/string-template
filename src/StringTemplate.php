<?php

namespace Phramework\StringTemplate;

use Phramework\JSONAPI\Resource;
use StringTemplate\Engine;

/**
 * @since 2.55.0
 */
class StringTemplate
{
    /**
     * @var array
     */
    protected $dictionary = [];

    /**
     * @var Engine
     */
    protected $engine;

    public function __construct()
    {
        $this->engine = new Engine();
    }

    /**
     * @param string     $key
     * @param \stdClass $attributes
     * @return $this
     * @since 0.1.0
     */
    public function add(
        string $key,
        \stdClass $attributes
    ) {
        $this->dictionary[$key] = static::convertToArray(
            $attributes
        );

        return $this;
    }

    /**
     * @param string         $key
     * @param \stdClass      $resource JSON API resource
     * @param \stdClass|null $additional
     * @return $this
     * @todo define JSON API resource structure
     */
    public function addResource(
        string $key,
        \stdClass $resource,
        \stdClass $additional = null
    ) {
        $object = new \stdClass;

        //Safe, if attributes exist, base object on resource's attributes
        if (property_exists($resource, 'attributes')) {
            $object = clone $resource->attributes;
        }

        //Safely, inject id if exists
        if (property_exists($resource, 'id')) {
            $object->id = $resource->id;
        }

        if ($additional !== null) {
            //Inject additional attributes
            foreach ($additional as $k => $v) {
                $object->{$k} = $v;
            }
        }

        return $this->add($key, $object);
    }

    /**
     * @param string $template
     * @return string
     */
    public function render(string $template) : string
    {
        return $this->engine->render(
            $template,
            $this->dictionary
        );
    }

    /**
     * @param \stdClass $object
     * @return array
     */
    public static function convertToArray(\stdClass $object) : array
    {
        foreach ($object as $k => &$v) {
            if (is_object($v)) {
                $v = static::convertToArray($v);
            }
        }

        return (array) $object;
    }
}
