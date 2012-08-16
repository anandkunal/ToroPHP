# Toro

Toro is a PHP router for developing RESTful web applications and APIs. Toro is for minimalists that want to rapidly prototype big ideas while writing beautiful code.

## Quick Links

- [Official Website](http://toroweb.org)
- [Changelog](https://github.com/anandkunal/ToroPHP/wiki/Changelog)
- [Design Goals](https://github.com/anandkunal/ToroPHP/wiki/Design-Goals)
- Recipes (auth, error handling, etc.) [SOON]

## Features

- RESTful routing using strings, regular expressions, and defined types (`number`, `string`, `alpha`)
- Flexible error handling and callbacks via `ToroHook`
- Intuitive and self-documented core (`toro.php`)
- PHPUnit coverage for core classes
- Tested with PHP 5.3 and above


## "Hello, world"

The canonical "Hello, world" example:

```php
<?php

class MainHandler extends ToroHandler {
    function get() {
        echo "Hello, world";
    }
}

Toro::serve(array(
    "/" => "MainHandler",
));
```


## Routing Basics

[Coming soon]


## RESTful Handlers

[Coming soon]


## ToroHook (Callbacks)

As of v2.0.0, there are a total of five Toro-specific hooks (callbacks):

```php
<?php

// Fired for 404 errors
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

class SomeHandler extends ToroHandler {
    function __construct() {
        ToroHook::add("before_handler", function() { echo "Before"; });
        ToroHook::add("after_handler", function() { echo "After"; });
    }

    function get() {
        echo "I am some handler.";
    }
}
```

Hooks can also be stacked. Adding a hook pushes the provided anonymous function into an array. When a hook is fired, all of the functions are called sequentially.


## Installation

Grab a copy of the repository and move `toro.php` to your htdocs or library directory. You may need to add the following snippet in your Apache virtual host configuration or `.htaccess`:

    RewriteEngine on
    RewriteCond $1 !^(index\.php)
    RewriteRule ^(.*)$ index.php/$1 [L]


## Contributions

- Toro was inspired by the [Tornado Web Server](http://www.tornadoweb.org) (FriendFeed/Facebook)
- [Berker Peksag](http://berkerpeksag.com), [Martin Bean](http://www.martinbean.co.uk), [Robbie Coleman](http://robbie.robnrob.com), and [John Kurkowski](http://about.me/john.kurkowski) for bug fixes and patches
- [Danillo César de O. Melo](https://github.com/danillos/fire_event/blob/master/Event.php) for `ToroHook`
- [Jason Mooberry](http://jasonmooberry.com) for code optimizations and feedback

Contributions to Toro are welcome via pull requests. Note, all requests will be reviewed carefully. Please try to add test coverage for new feature development.


## License

ToroPHP was created by [Kunal Anand](http://kunalanand.com) and is released under the MIT License.