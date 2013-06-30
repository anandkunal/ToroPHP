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

	function myBeerAction($num){
		echo "got my beer ".$num;
	}
}

Toro::serve(array(
    "/" => "HelloHandler",
    "/beers" => "BeerHandler",
    "/beers/:number" => array("BeerHandler"=>"myBeerAction")
));