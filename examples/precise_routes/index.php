<?php

require("../../src/Toro.php");

class HelloHandler {
    function get() {
      echo "Hello, world";
    }
}

class BeerHandler {
	function get() {
		echo "All the Beers are belong to me.";
	}

	function action_get($num){
		echo "I got my beer with id ".$num;
	}

	function action_post($num){
		echo "Posted my beer with id ".$num;
	}

	function comment_post($num){
		echo "Posted by beer by comment with id ".$num;
	}
}

Toro::serve(array(
    "/" => "HelloHandler",
    "/beers" => "BeerHandler",
    "/beers/:number" => array("BeerHandler"=>"action"),
    "/beers/:number/comment" => array("BeerHandler"=>"comment")
));