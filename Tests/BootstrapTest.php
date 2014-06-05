<?php

namespace Framework\Tests;

class BootstrapTest extends \PHPUnit_Framework_TestCase
{
    private $_config;
    private $_bootstrap;
    private $_calledController;

    public function setUp()
    {
        $_GET['q'] = 'site/index'; // route we use.
        $this->_config = file_get_contents('Files/config.json', FILE_USE_INCLUDE_PATH);
        $this->_bootstrap = new \Framework\Bootstrap(json_decode($this->_config, true));
        $this->_bootstrap->init();
    }

    public function testControllerCall()
    {
        $controller = 'site';
        $this->_calledController = $this->_bootstrap->callController($controller);
        $this->assertTrue(
             $this->_calledController
             instanceof \Application\Controller\Site
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