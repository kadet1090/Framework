<?php

namespace Framework\Mvc;

use Framework\Application;

class HttpView extends View
{
    /**
     * @var string $defaultTemplateExtension Specifies template files extension
     */
    public $defaultTemplateExtension = '.phtml';
    private $_template;

    /**
     * @param string $template Template name, using '/' separator, eg. site/index
     * @throws \Exception When template file not found
     */
    public function __construct($template)
    {
        $path = $this->_findViewFile($template);
        try {
            $this->_template = include($path);
        } catch (\Exception $e) {
            throw new \Exception('Template file not found in ' . $path, 0, $e);
        }

    }

    public function __set($var, $name)
    {
        $this->$var = $name;
    }

    public function __get($var)
    {
        return $this->$var;
    }

    public function __toString()
    {
        return (string)$this->render();
    }

    /**
     * Renders view with layout
     */
    public function render()
    {
        return (string)$this->_template;
    }

    private function _findViewFile($template)
    {
        $viewsPath = Application::$app->getConfig('viewsPath');
        rtrim($viewsPath, '/');
        if (strpos($template, '/') !== 0) {
            $template = '/' . $template;
        }

        return $viewsPath . $template . $this->defaultTemplateExtension;
    }

} 