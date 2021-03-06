<?php

/**
 * Builds (read: constructor injection) the object.
 *
 */

class Pd_Make_Constructor extends Pd_Make_Abstract {

    /**
     * Creates the object
     *
     */
    public function constructObject() {

        $this->loadMap();

        if ($this->_map->has('constructor')) {

            $constructWith = array();

            foreach($this->_map->itemsFor('constructor') as $item) {
                $constructWith[] = $this->getDependencyForItem($item);
            }

            $reflector = new ReflectionClass($this->_className);
            $this->_object = $reflector->newInstanceArgs($constructWith);

        } else {

            $reflector = new ReflectionClass($this->_className);
            if ($reflector->isInstantiable()) {
                $this->_object = new $this->_className();
            } else {
                $this->_object = null;
            }

        }


    }

    /**
     * Creates the object and sets all the dependencies required
     * for construction.
     *
     * @param string $className
     * @param string $containerName
     * @return mixed object
     */
    public static function construct($className, $containerName = 'main') {

        $constructor = new self();
        $constructor->setClassName($className);
        $constructor->setContainer(Pd_Container::get($containerName));
        $constructor->constructObject();
        return $constructor->object();

    }

}


