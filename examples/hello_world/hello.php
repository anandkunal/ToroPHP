<?ph

require('../../src/toro.php');

class HelloHandler {
    function get() {
      echo "Hello, world";
    }
}

Toro::serve(array(
    "/" => "HelloHandler"
));