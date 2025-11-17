<?php

namespace App\Application\Orm;

class ObjectEnsembler {

    private ReflectionObject $reflectionObject;
    private $object;
    
    public function __construct(ReflectionObject $reflectionObject) {
        $this->reflectionObject = $reflectionObject;
        $this->object = $reflectionObject->getObject();
    }

    public function ensemble($rowData, $level=1) {
        $object = $this->reflectionObject->createObject();
        $entity = $this->getEntity();
        foreach ($this->reflectionObject->getProperties() as $property) {
            if ($property['collection']) {
                // pass
            } elseif ($property['builtin']) {
                $fieldName = $entity . $level .'__' . $property['name'];
                if ($property['primary'] && !isset($rowData[$fieldName])) {
                    return null;
                }
                if (array_key_exists($fieldName, $rowData) && !\is_null($rowData[$fieldName])) {
                    $object->attributes[$property['name']]['value'] = $rowData[$fieldName];
                }
            } else { //} if ($level < 2) {
                $reflectionObject = new ReflectionObject($property['type']);
                $objectEnsembler = new ObjectEnsembler($reflectionObject);
                $object->attributes[$property['name']]['value'] = $objectEnsembler->ensemble($rowData, $level+1);
            }
        }
        return $object;
    }

    private function getEntity(): string {
        return $this->object->getEntity();
    }

}
