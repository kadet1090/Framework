<?php
class HttpRouterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Framework\Http\HttpRouter
     */
    private $_router;

    public function setUp()
    {
        $this->_router = new \Framework\Http\HttpRouter();
    }

    public function testSimpleRoute()
    {
        $this->_router->addRoute(
            'simple',
            array(
                'parameters' => array(
                    'controller' => 'string',
                    'action'     => 'string',
                ),
            )
        );

        $this->assertTrue($this->_router->match('simple', 'controller/action'));
        $this->assertFalse($this->_router->match('simple', '!@#$%/action'));
        $this->assertFalse($this->_router->match('simple', 'controller'));
    }

    public function testComplexRoute()
    {
        $this->_router->addRoute(
            'complex',
            array(
                'parameters' => array(
                    'controller' => 'string',
                    'action'     => 'string',
                ),
                'optional'   => array(
                    'param1' => 'int'
                )
            )
        );

        $this->assertTrue($this->_router->match('complex', 'controller/action/10'));
        $this->assertTrue($this->_router->match('complex', 'controller/action'));
        $this->assertFalse($this->_router->match('complex', 'controller/action/abc'));
    }

    /**
     * @depends testSimpleRoute
     */
    public function testDependency()
    {
        $this->_router->addRoute(
            'simple',
            array(
                'parameters' => array(
                    'controller' => 'string',
                    'action'     => 'string',
                ),
            )
        );

        $this->_router->addRoute(
            'depended',
            array(
                'optional' => array(
                    'param1' => 'int'
                ),
                'parent'   => 'simple',
            )
        );

        $this->assertTrue($this->_router->match('depended', 'controller/action/10'));
        $this->assertFalse($this->_router->match('depended', 'controller/action/test'));
        $this->assertTrue($this->_router->match('depended', 'controller/action'));
    }

    public function testDispatch()
    {
        $this->_router->addRoute(
            'simple',
            array(
                'parameters' => array(
                    'controller' => 'string',
                    'action'     => 'string',
                ),
            )
        );

        $request = $this->_router->dispatch('post/add/test');

        $this->assertEquals('post', $request->controller);
        $this->assertEquals('add', $request->action);

        $this->assertEquals('post', $request[0]);
        $this->assertEquals('add', $request[1]);
    }
}
