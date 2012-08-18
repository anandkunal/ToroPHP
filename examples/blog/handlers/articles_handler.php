<?
class ArticlesHandler {
    function get() {
        $articles = get_articles();
        include("views/articles.php");
    }
}