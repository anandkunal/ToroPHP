<?
class ArticleHandler {
    function get($slug) {
        $article = get_article_by_slug($slug);
        $comments = get_article_comments($article['id']);
        include("views/article.php");
    }
}