<?php

require_once dirname(__FILE__).'/toro.php';

class MainHandler extends ToroHandler {
    public function get() { 
        echo 'Hello, world';
    }
}

class TestHandler extends ToroHandler {
    public function get() { 
        echo 'Test.';
    }
}

class ArticleHandler extends ToroHandler {
    public function get($slug) {
        echo sprintf('Load an article that matches the slug: %s', $slug);
    }
}

$site = new ToroApplication(array(
    array('/', 'MainHandler'),
    array('test', 'TestHandler'),
    array("article/([a-zA-Z0-9_]+)", 'ArticleHandler')
));

$site->serve();
