<?php

namespace Framework\Tests;

use Framework\Mvc\HttpView;

class HttpViewTest extends \PHPUnit_Framework_TestCase
{
    private $_view;

    public function setUp()
    {
        // template loading tested here
        $this->_view = new HttpView('site/index');
    }

    public function testRender()
    {
        $this->assertTrue((boolean)$this->_view->render());
        // __toString()
        $this->assertTrue((boolean)(print $this->_view));
    }
}