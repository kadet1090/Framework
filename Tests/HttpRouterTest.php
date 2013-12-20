<?php
require_once '../System/Router/RouterInterface.php';
require_once '../System/Exceptions/RoutingException.php';
require_once '../System/Router/HttpRouter.php';

class HttpRouterTest extends PHPUnit_Framework_TestCase
{
    private $_router;

    public function __construct()
    {
        $this->_router = new \PipeCMS\System\Router\HttpRouter();
    }

    public function testSimpleRoute()
    {
        $this->_router->addRoute('simple', array(
            'parameters' => array(
                'controller' => 'string',
                'action' => 'string',
            ),
        ));

        $this->assertTrue($this->_router->match('simple', 'controller/action'));
        $this->assertFalse($this->_router->match('simple', '!@#$%/action'));
        $this->assertFalse($this->_router->match('simple', 'controller'));
    }

    public function testComplexRoute()
    {
        $this->_router->addRoute('complex', array(
            'parameters' => array(
                'controller' => 'string',
                'action' => 'string',
            ),
            'optional' => array(
                'param1' => 'int'
            )
        ));

        $this->assertTrue($this->_router->match('complex', 'controller/action/10'));
        $this->assertTrue($this->_router->match('complex', 'controller/action'));
        $this->assertFalse($this->_router->match('complex', 'controller/action/abc'));
    }

    public function testDependencyWithWrongParent()
    {
        $this->_router->addRoute('depended', array(
            'optional' => array(
                'param1' => 'int'
            ),
            'parent' => 'simple',
        ));
    }

    /**
     * @depends testSimpleRoute
     */
    public function testDependency()
    {
        $this->_router->addRoute('simple', array(
            'parameters' => array(
                'controller' => 'string',
                'action' => 'string',
            ),
        ));

        $this->_router->addRoute('depended', array(
            'optional' => array(
                'param1' => 'int'
            ),
            'parent' => 'simple',
        ));

        $this->assertTrue($this->_router->match('depended', 'controller/action/10'));
        $this->assertFalse($this->_router->match('depended', 'controller/action/test'));
        $this->assertTrue($this->_router->match('depended', 'controller/action'));
    }
}
