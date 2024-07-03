<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApiTestCase extends WebTestCase
{
    final protected const string API_KEY = '?api_key=00000000-0000-0000-0000-000000000000';

    /**
     * Asserts that the retrieved JSON Object is from the Class $class.
     *
     * @param class-string $class
     * @param string[]     $groups
     */
    protected function assertObjectMatchClass(object $obj, string $class, array $groups = []): void
    {
        // Extract the properties from the class $class
        $cls = new \ReflectionClass($class);
        // Extract the property name from the Reflection Class
        $properties = array_map(function ($property) use ($groups) {
            if (!empty($groups)) {
                // Check if the property is in our selected groups
                $annotationGroups = $property->getAttributes('Symfony\Component\Serializer\Annotation\Groups');
                $in_group = false;
                foreach ($annotationGroups as $aGroup) {
                    foreach ($aGroup->getArguments() as $arg) {
                        if (in_array($arg, $groups)) {
                            $in_group = true;
                        }
                    }
                }
                // If not, we do not want to assert that property
                if (!$in_group) {
                    return false;
                }
            }

            return $property->name;
        }, $cls->getProperties());
        // Remove false value
        $properties = array_filter($properties, function (string|false $e) {
            return false !== $e;
        });

        // Check if each class property is present in our JSON Object
        foreach ($properties as $property) {
            $this->assertObjectHasProperty($property, $obj);
        }
    }
}
