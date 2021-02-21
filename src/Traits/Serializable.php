<?php

namespace Jetimob\Http\Traits;

trait Serializable
{
    protected array $hydrationData = [];

    public function reflectProperty(string $propertyName): ?\ReflectionProperty
    {
        $reflectionClass = new \ReflectionClass($this);

        try {
            return $reflectionClass->getProperty($propertyName);
        } catch (\ReflectionException $e) {
            return null;
        }
    }

    public function hydrateArrayProperty(string $propertyName, \ReflectionProperty $property, array $array): self
    {
        // We'll try to extract the typing from de docblock. For this to work, the @var typing MUST use the
        // complete namespace of the type, i.e.: \Typing\Full\Namespace\Type
        $docComment = $property->getDocComment();

        if (!$docComment) {
            return $this;
        }

        preg_match_all('/@var\s+([\w\\\]+)\[].*/', $docComment, $matches, PREG_PATTERN_ORDER);

        if (empty($matches) || empty($matches[1])) {
            return $this;
        }

        $type = $matches[1][0];

        // is a built-in type array
        if (in_array($type, ['bool', 'boolean', 'double', 'float', 'int', 'integer', 'string'])) {
            $this->{$propertyName} = $array;
            return $this;
        }

        if (!class_exists($type) || !method_exists($type, 'deserializeArray')) {
            return $this;
        }

        $this->{$propertyName} = $type::deserializeArray($array);
        return $this;
    }

    /**
     * @param array $dataObject
     * @return $this
     */
    public function hydrate(array $dataObject): self
    {
        $this->hydrationData = $dataObject;
        // check if is not an associative array
        // https://stackoverflow.com/a/173479/4292986
        if (array_keys($dataObject) === range(0, count($dataObject) - 1)) {
            if (!property_exists($this, 'container')) {
                return $this;
            }

            $prop = $this->reflectProperty('container');

            if (is_null($prop)) {
                return $this;
            }

            $this->hydrateArrayProperty('container', $prop, $dataObject);

            return $this;
        }

        foreach ($dataObject as $key => $value) {
            // if the property doesn't exist or is already set, continue
            if (!property_exists($this, $key) || !empty($this->{$key})) {
                continue;
            }

            $prop = $this->reflectProperty($key);

            if (is_null($prop)) {
                continue;
            }

            $type = $prop->getType();

            // its not a built-in php type
            if ($type && !$type->isBuiltin()) {
                $expectedClass = $type->getName();

                if (method_exists($expectedClass, 'deserialize')) {
                    // can throw \TypeError
                    $this->{$key} = $expectedClass::deserialize($value, $expectedClass);
                }
            } elseif (is_array($value)) {
                return $this->hydrateArrayProperty($key, $prop, $value);
            } else {
                $this->{$key} = $value;
            }
        }

        return $this;
    }

    /**
     * @param string|array $serialized
     * @param string|null $into
     * @return $this
     * @throws \ReflectionException
     * @throws \JsonException
     * @throws \TypeError
     */
    public static function deserialize($serialized, string $into = null): self
    {
        if (is_array($serialized)) {
            $data = $serialized;
        } elseif (is_string($serialized)) {
            $data = json_decode($serialized, true, 512, JSON_THROW_ON_ERROR);
        } else {
            $data = json_decode(json_encode($serialized, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
        }

        $className = $into ?? static::class;
        $instance = new $className();

        if (is_string($data)) {
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        }

        if (empty($data)) {
            return $instance;
        }

        if (method_exists($instance, 'hydrate')) {
            $instance->hydrate($data);
        }

        return $instance;
    }

    public static function deserializeArray(array $serializedArray): array
    {
        $items = [];

        foreach ($serializedArray as $item) {
            $items[] = self::deserialize($item);
        }

        return $items;
    }

    /**
     * @return array
     */
    public function getHydrationData(): array
    {
        return $this->hydrationData;
    }
}
