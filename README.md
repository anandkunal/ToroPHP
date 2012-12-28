# Toro

Toro is a PHP router for developing RESTful web applications and APIs. It is
designed for minimalists who want to get work done.

## Quick Links

- [Official Website](http://toroweb.org)
- [Changelog](https://github.com/anandkunal/ToroPHP/wiki/Changelog)
- [Design Goals](https://github.com/anandkunal/ToroPHP/wiki/Design-Goals)


## Features

- RESTful routing using strings, regular expressions, and defined types
  (`number`, `string`, `alpha`)
- Flexible error handling and callbacks via `ToroHook`
- Intuitive and self-documented core (`Toro.php`)
- Tested with PHP 5.3 and above


## "Hello, world"

The canonical "Hello, world" example:

```php
<?php

class HelloHandler {
    function get() {
        echo "Hello, world";
    }
}

Toro::serve(array(
    "/" => "HelloHandler",
));
```


## Routing Basics

Routing with Toro is simple:

```php
<?php

Toro::serve(array(
    "/" => "SplashHandler",
    "/catalog/page/:number" => "CatalogHandler",
    "/product/:alpha" => "ProductHandler",
    "/manufacturer/:string" => "ManufacturerHandler"
));
```

An application's route table is expressed as an associative array
(`route_pattern => handler`). This is closely modeled after
[Tornado](http://tornadoweb.org) (Python). Routes are not expressed as
anonymous functions to prevent unnecessary code duplication for RESTful
dispatching.

From the above example, route stubs, such as `:number`, `:string`, and
`:alpha` can be conveniently used instead of common regular expressions.
Of course, regular expressions are still welcome. The previous example could
also be expressed as:

```php
<?php

Toro::serve(array(
    "/" => "SplashHandler",
    "/catalog/page/([0-9]+)" => "CatalogHandler",
    "/product/([a-zA-Z0-9-_]+)" => "ProductHandler",
    "/manufacturer/([a-zA-Z]+)" => "ManufacturerHandler"
));
```

Pattern matches are passed in order as arguments to the handler's request
method. In the case of `ProductHandler` above:

```php
<?php

class ProductHandler {
    function get($name) {
        echo "You want to see product: $name";
    }
}
```


## RESTful Handlers

```php
<?php

class ExampleHandler {
    function get() {}
    function post() {}
    function get_xhr() {}
    function post_xhr() {}
}
```

From the above, you can see two emergent patterns.

1. Methods named after the HTTP request method (`GET`, `POST`, `PUT`,
   `DELETE`) are automatically called.

2. Appending `_xhr` to a handler method automatically matches
   JSON/`XMLHTTPRequest` requests. If the `_xhr` method is not implemented,
   then the given HTTP request method is called as a fallback.


## ToroHook (Callbacks)

As of v2.0.0, there are a total of five Toro-specific hooks (callbacks):

```php
<?php

// Fired for 404 errors; must be defined before Toro::serve() call
ToroHook::add("404",  function() {});

// Before/After callbacks in order
ToroHook::add("before_request", function() {});
ToroHook::add("before_handler", function() {});
ToroHook::add("after_handler", function() {});
ToroHook::add("after_request",  function() {});
```

`before_handler` and `after_handler` are defined within handler's constructor:

```php
<?php

class SomeHandler {
    function __construct() {
        ToroHook::add("before_handler", function() { echo "Before"; });
        ToroHook::add("after_handler", function() { echo "After"; });
    }

    function get() {
        echo "I am some handler.";
    }
}
```

Hooks can also be stacked. Adding a hook pushes the provided anonymous
function into an array. When a hook is fired, all of the functions are called
sequentially.


## Installation

Grab a copy of the repository and move `Toro.php` to your project root.

### Using Composer

Install composer in your project:

```sh
$ curl -s https://getcomposer.org/installer | php
```

Create a `composer.json` file in your project root:

```js
{
    "require": {
        "torophp/torophp": "dev-master"
    }
}
```

Install via composer:

```sh
$ php composer.phar install
```

### Server Configuration

You may need to add the following snippet in your Apache HTTP Server virtual
host configuration or `.htaccess`:

```apacheconf
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond $1 !^(index\.php)
RewriteRule ^(.*)$ /index.php/$1 [L]
```

## Contributions

- Toro was inspired by the [Tornado Web Server](http://www.tornadoweb.org)
  (FriendFeed/Facebook)
- [Berker Peksag](http://berkerpeksag.com),
  [Martin Bean](http://www.martinbean.co.uk),
  [Robbie Coleman](http://robbie.robnrob.com), and
  [John Kurkowski](http://about.me/john.kurkowski) for bug fixes and patches
- [Danillo César de O. Melo](https://github.com/danillos/fire_event/blob/master/Event.php) for `ToroHook`
- [Jason Mooberry](http://jasonmooberry.com) for code optimizations and feedback

Contributions to Toro are welcome via pull requests.


## License

ToroPHP was created by [Kunal Anand](http://kunalanand.com) and released under
the MIT License.
