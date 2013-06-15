<?php
/* download to the same dir phpunit.phar and run the tests */
require_once(__DIR__ . "/../src/Toro.php");
class ToroUtilTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $routes = array(
            "/" => "Handler1",
            "/hello" => "Handler2",
            "/hello/there" => "Handler3",
            "/client/:number" => "Handler4",
            "/client/:number-:alpha" => "Handler5",
            "/client/:number-:alpha-stuff-:string" => "Handler6",
            "/client/:number/:alpha/:string" => "Handler7"
        );
        ToroUtil::$routes = $routes;
    }

    public function testHandler1()
    {
        $this->assertEquals('/', ToroUtil::url_for('Handler1'));
    }

    public function testHandler2()
    {
        $this->assertEquals('/hello', ToroUtil::url_for('Handler2'));
    }

    public function testHandler3()
    {
        $this->assertEquals('/hello/there', ToroUtil::url_for('Handler3'));
    }

    public function testHandler4()
    {
        $this->assertEquals('/client/123', ToroUtil::url_for('Handler4', array(123)));
    }

    public function testHandler5()
    {
        $this->assertEquals('/client/100-product', ToroUtil::url_for('Handler5', array(100, "product")));
    }

    public function testHandler6()
    {
        $this->assertEquals('/client/200-st10-stuff-something', ToroUtil::url_for('Handler6', array(200, "st10", "something")));
    }

    public function testHandler7()
    {
        $this->assertEquals('/client/300/this10/that', ToroUtil::url_for('Handler7', array(300, "this10", "that")));
    }

}