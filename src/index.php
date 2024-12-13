<?php declare(strict_types=1);

/**
 * Casts an array of key-value pairs or objects to an instance of a specified class.
 *
 * @template T of object
 * @param class-string<T> $to The class name to cast to.
 * @param object|array<string, mixed> ...$froms A collection of objects or arrays of key-value pairs to cast in the provided order.
 * @return T An instance of the class `$to`.
 */
function cast(string $to, ...$froms): object
{
    try {
        $toReflection = new \ReflectionClass($to);
        $newInstance = $toReflection->newInstanceWithoutConstructor();

        foreach ($froms as $from) {
            if (is_object($from)) {
                $fromReflection = new \ReflectionObject($from);
                foreach ($fromReflection->getProperties() as $property) {
                    $property->setAccessible(true);
                    if ($toReflection->hasProperty($property->getName())) {
                        $targetProperty = $toReflection->getProperty($property->getName());
                        $targetProperty->setAccessible(true);
                        $targetProperty->setValue($newInstance, $property->getValue($from));
                    }
                }
            } else {
                foreach ($from as $prop => $val) {
                    if ($toReflection->hasProperty($prop)) {
                        $targetProperty = $toReflection->getProperty($prop);
                        $targetProperty->setAccessible(true);
                        $targetProperty->setValue($newInstance, $val);
                    }
                }
            }
        }

        return $newInstance;
    } catch (\Throwable $prev) {
        throw new \InvalidArgumentException(
            \sprintf('Cannot cast "%s" to "%s"', get_class($from), $to),
            0,
            $prev
        );
    }
}
