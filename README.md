# ToroPHP

Toro is a tiny framework for PHP that lets you prototype web applications quickly.

* [Toro Home Page](http://toroweb.org)

## The Primordial Application

The canonical "Hello, world" example:

    require_once('toro.php');
    
    class MainHandler extends ToroHandler {
      public function get() { 
        echo "Hello, world";
      }
    }
    
    $site = new ToroApplication(Array(
      Array('/', 'string', 'MainHandler')
    ));
    
    $site->serve();

## A Substantial Application

Here is a slightly more advanced application garnished with pseudocode:

    require_once('toro.php');

    class BlogHandler extends ToroHandler {
      public function get() { 
        echo "This the front page of the blog. Load all articles.";
      }

      public function get_mobile() {
        // _mobile => fires if iPhone/Android/webOS is detected
        echo "Load a subset of the articles.";
      }
    }

    class ArticleHandler extends ToroHandler {
      public function get($slug) {
        echo "Load an article that matches the slug: " . $slug;
      }
    }

    class CommentHandler extends ToroHandler {
      public function post($slug) {
        echo "Validate slug - redirect if not found.";
        echo "Peek into $_POST, save the comment, and redirect."
      }

      public function post_xhr($slug) {
        // _xhr => fires if XHR request is detected
        echo "Validate, save, and return a JSON blob.";
      }
    }

    class SyndicationHandler extends ToroHandler {
      public function get() {
        echo "Display some recent entries in RSS/Atom.";
      }
    }

    $site = new ToroApplication(Array(
      Array('/', 'string', 'BlogHandler'),
      Array('^\/article\/([a-zA-Z0-9_]+)\/?$', 'regex', 'ArticleHandler'),
      Array('^\/comment\/([a-zA-Z0-9_]+)\/?$', 'regex', 'CommentHandler'),
      Array('/feed', 'string', 'SyndicationHandler')
    ));

    $site->serve();

## Installation

Grab the source or packaged download. If you grab the download, you will need to do the following:

    tar xvzf torophp-0.1.tar.gz
    cd torophp-0.1
    cp toro.php /path/to/htdocs/

Couch the following in your Apache configuration or .htaccess:

    RewriteEngine on
    RewriteCond %{REQUEST_FILENAME} !^/index.php
    RewriteCond %{DOCUMENT_ROOT}/%{REQUEST_FILENAME} !-f
    RewriteRule ^/(.+)$ /index.php/$1

## Roadmap

The immediate plan is to complete the following:

* Support global and handler callbacks.
* Add a skeleton project.
* Improve documentation.
* Setup a mailing list/group.
* Brainstorm a plugin model. What are some good implementations?

Toro is intended to be a minimal framework to help you organize and prototype your next PHP application. As the project's maintainer, it's my goal to make sure the source stays lean and focused.
