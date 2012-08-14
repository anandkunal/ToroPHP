# ToroPHP

Toro is a tiny router that lets you develop PHP web applications quickly.

* [Official Website & Documentation](http://toroweb.org)


## The Primordial Application

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

## A Substantial Application

Here is a slightly more advanced application garnished with pseudocode:

```php
<?php

class BlogHandler extends ToroHandler {
    function get() {
        echo "This the front page of the blog. Load all articles.";
    }
}

class ArticleHandler extends ToroHandler {
    function get($slug) {
        echo "Load an article that matches the slug: $slug.";
    }
}

class CommentHandler extends ToroHandler {
    function post($slug) {
        echo "Peek into the POST, save the comment, and redirect.";
    }

    function post_xhr($slug) {
        echo "Validate, save, and return a JSON blob.";
    }
}

Toro::serve(array(
    "/" => "BlogHandler",
    "/article/:alpha", "ArticleHandler",
    "/article/:alpha/comment", "CommentHandler"),
));
```


## Toro Hooks

There are 5 possible hooks (callbacks).

```php
<?php

ToroHook::add("404",  function() {});

ToroHook::add("before_request", function() {});
ToroHook::add("before_handler", function() {});
ToroHook::add("after_handler",  function() {});
ToroHook::add("after_request",  function() {});
```

While you can hook before\_handler and after\_handler anywhere, like index.php, most people will probably want to use it in a handler's constructor:

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

Adding a hook pushes the function into an array. When a particular hook is fired, all of the functions are fired in the appropriate order.


## Installation

Grab the source and copy toro.php to your htdocs or lib directory.

Couch the following in your Apache configuration or .htaccess:

    RewriteEngine on
    RewriteCond $1 !^(index\.php)
    RewriteRule ^(.*)$ index.php/$1 [L]


## Credits

ToroHook was provided by [Danillo César de O. Melo](https://github.com/danillos/fire_event/blob/master/Event.php). ToroHook will be the foundation for the future plugin system.

Special thanks to [Jason Mooberry](http://jasonmooberry.com) for code optimizations.


## License

ToroPHP is open-source software licensed under the MIT License.