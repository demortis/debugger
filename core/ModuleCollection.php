<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 20.09.18
 * Time: 10:39
 */

namespace debugger\core;

use debugger\core\exceptions\EmptyModuleCollectionException;
use debugger\core\exceptions\ModuleNotExistsException;
use debugger\core\interfaces\Module;

class ModuleCollection implements \Iterator
{
    private $collection = [];

    private $exceptions = [];

    private $parent;

    public function __construct(array $modules = [])
    {
        if (!empty($modules)) {
            $this->addModules($modules);
        }
    }

    public function addModule(Module $module, string $name = null): void
    {
        $moduleName = $name ?? $module->getName();

        $this->collection[$moduleName] = $module;
    }

    public function addModules(array $modules): void
    {
        foreach ($modules as $name => $module) {
            if (!is_object($module)) {
                $module = DebugObject::create($module['class']);
            }

            $this->addModule($module, $name);
        }
    }

    public function init(): void
    {
        if (empty($this->collection)) {
            throw new EmptyModuleCollectionException();
        }

        foreach ($this as $module) {
            try {
                $module->init();
            } catch (\Throwable $e) {
                $this->addException($module, $e);
            }
        }
    }


    private function addException(Module $module, \Throwable $exception): void
    {
        $this->exceptions[$module->getName()] = $exception;
    }

    public function getModuleByName(string $name): ?Module
    {
        if (!key_exists($name, $this->collection)) {
            throw new ModuleNotExistsException($name);
        }

        return $this->collection[$name];
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function current()
    {
        return current($this->collection);
    }

    public function next()
    {
        next($this->collection);
    }

    public function rewind()
    {
        reset($this->collection);
    }

    public function key()
    {
        return key($this->collection);
    }

    public function valid()
    {
        return key($this->collection) !== null;
    }

}