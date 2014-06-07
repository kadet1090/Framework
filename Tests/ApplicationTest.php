<?php

namespace Framework\Tests;

use Application\Controller\Site;
use Framework\Application;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    private $_config;
    private $_bootstrap;
    private $_calledController;

    public function setUp()
    {
        $_GET['q'] = 'site/index'; // route we use.

        $this->_config = file_get_contents('Files/config.json', FILE_USE_INCLUDE_PATH);

        $this->_bootstrap = new Application(json_decode($this->_config, true));
        $this->_bootstrap->init();
    }

    public function testControllerCall()
    {
        $controller = 'site';
        $this->_calledController = $this->_bootstrap->callController($controller);

        $this->assertInstanceOf(
            'App\\Controller\\Site',
            $this->_calledController
        );
    }

    public function testControllerActionCall()
    {
        $action = 'actionIndex';
        $res = $this->_bootstrap->callControllerMethod(
            $this->_calledController,
            $action
        );
        $this->assertTrue($res);
    }
}