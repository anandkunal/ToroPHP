<?
class CommentHandler {
    function post($slug) {
        $article = get_article_by_slug($slug);
        if (isset($_POST['name']) && isset($_POST['body']) && 
            strlen(trim($_POST['name'])) > 0 && strlen(trim($_POST['body'])) > 0) {
            save_comment($article['id'], trim($_POST['name']), trim($_POST['body']));
        }
        header("Location: /article/$slug");
    }
}