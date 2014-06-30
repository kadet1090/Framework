<?php

namespace Framework\Tests;

use Framework\Application;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    private $_config;
    private $_bootstrap;
    private $_calledController;

    public function setUp()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['q'] = 'site/index'; // route we use.

        $this->_config = file_get_contents('Files/config.json', FILE_USE_INCLUDE_PATH);

        $this->_bootstrap = new Application(json_decode($this->_config, true));
        $this->_bootstrap->init();
    }

    public function testControllerCall()
    {
        $controller              = 'App\\Controller\\Site';
        $this->_calledController = $this->_bootstrap->controller($controller);

        $this->assertInstanceOf(
            'App\\Controller\\Site',
            $this->_calledController
        );
    }
}