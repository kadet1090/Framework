<?php

namespace Framework\View;

class ViewFactory
{
    private $_className;
    private $_config;

    /**
     * Constructor
     *
     * @param string $class View class name to create, eg. HttpView
     * @param mixed $config Configuration for new views
     * @throws \Exception When {$class} is not a View class
     */
    public function __construct($class, array $config)
    {
        if (!is_string($class)) {
            throw new \Exception('Class name to produce must be a string');
        }
        if (!is_subclass_of($class, '\\Framework\\Mvc\\View')) {
            throw new \Exception('Class ' . $class . ' does not extend View class');
        }
        $this->_className = $class;
        $this->_config = $config;
    }

    /**
     * Returns View object.
     *
     * @param string $template Template for new View
     * @param array $dirs Cascade directories
     * @return mixed View object
     */
    public function create($template, $dirs = [])
    {
        $className = $this->_className;
        return new $className($template, $this->_config, $dirs);
    }
} 