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
     * @param string         $key
     * @param \stdClass $resource JSON API resource
     * @param \stdClass|null $additional
     * @return $this
     */
    public function addResource(
        string $key,
        $resource,
        \stdClass $additional = null
    ) {
        $object = clone $resource->attributes;
        $object->id = $resource->id;

        if ($additional !== null) {
            foreach ($additional as $k => $v) {
                $object->{$k} = $v;
            }
        }

        $this->dictionary[$key] = static::convertToArray($object);

        return $this;
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
