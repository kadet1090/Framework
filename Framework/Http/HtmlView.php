<?php

namespace Framework\Http;

use Framework\View\View;
use Winek\FileResolver;

class HtmlView extends View
{
    /**
     * @var string $defaultTemplateExtension Specifies template files extension
     */
    private $extension = '.phtml';
    private $directory = '../themes/';

    private $config;

    private $file;

    private $vars = [];

    /**
     * @param string $template Template name, using '/' separator, eg. site/index
     * @param array $config View config
     * @param array $dirs Cascade directories
     * @throws \RuntimeException When template file not found
     */
    public function __construct($template, $config, $dirs = [])
    {
        if (!isset($config['view'])) {
            $config['view'] = [];
        }
        if (!isset($config['view']['html'])) {
            $config['view']['html'] = [];
        }

        $this->config = array_merge($config['view'], $config['view']['html']);

        $this->extension = isset($this->config['extension']) ? $this->config['extension'] : '.phtml';
        $this->directory = isset($this->config['directory']) ? $this->config['directory'] : '../themes/';

        $dirs[] = $this->directory;

        $this->file = $this->_findViewFile($template, $dirs);
    }

    public function __set($name, $value)
    {
        $this->vars[$name] = $value;
    }

    public function __get($name)
    {
        return $this->vars[$name];
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
        ob_start();
        extract($this->vars);
        $globals = self::$_globals;

        $includePath = set_include_path(get_include_path() . PATH_SEPARATOR . dirname($this->file));

        include $this->file;

        return ob_get_clean();

    }

    private function _findViewFile($template, $dirs)
    {
        $resolver = new FileResolver();

        foreach ($dirs as $dir) {
            $resolver->add($dir);
        }

        return $resolver->resolve($template . $this->extension);
    }

} 