<?
require("handlers/article_handler.php");
require("handlers/articles_handler.php");
require("handlers/comment_handler.php");
require("lib/markdown.php");
require("lib/mysql.php");
require("lib/queries.php");
require("lib/toro.php");

ToroHook::add("404", function() {
    echo "Not found";
});

Toro::serve(array(
    "/" => "ArticlesHandler",
    "/article/:alpha" => "ArticleHandler",
    "/article/:alpha/comment" => "CommentHandler"
));
