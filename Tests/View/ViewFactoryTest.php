<?php

namespace Framework\Tests;

use Framework\View\ViewFactory;

class ViewFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $factory;

    public function setUp()
    {
        $this->factory = new ViewFactory('\\Framework\\Http\\HtmlView', [], []);
    }

    public function testNewObjectProduce()
    {
        $viewObject = $this->factory->create('site/index', ['./Tests/App/View']);
        $this->assertInstanceOf(
            '\\Framework\\Http\\HtmlView',
            $viewObject
        );
    }
}
 