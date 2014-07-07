<?php

namespace Framework\Tests;

use Framework\Application;
use Framework\Http\HtmlView;

class HtmlViewTest extends \PHPUnit_Framework_TestCase
{
    private $_view;

    public function setUp()
    {
        // template loading tested here
        $this->_view = new HtmlView('site/index', Application::$app->config);
    }

    public function testLoading()
    {
        $view = new HtmlView('site/index', Application::$app->config);
        $view->render();
    }

    public function testCascadeLoading()
    {
        $view = new HtmlView('site/index', Application::$app->config, ['./Tests/App/Cascade']);
        $this->assertEquals(
            file_get_contents('Tests/App/Cascade/site/index.phtml'),
            $view->render()
        );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testNoTemplate()
    {
        $view = new HtmlView('oh/what/is/wrong/with/you', Application::$app->config);
        $view->render();
    }

    public function testRender()
    {
        $this->assertEquals(
            file_get_contents('Tests/App/View/site/index.phtml'),
            $this->_view->render()
        );
    }

    public function testToString()
    {
        $this->assertEquals(
            file_get_contents('Tests/App/View/site/index.phtml'),
            (string)$this->_view
        );
    }
}