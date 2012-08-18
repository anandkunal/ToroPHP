<?
function get_articles() {
    $query = MySQL::getInstance()->query("SELECT * FROM articles ORDER BY published DESC");
    return $query->fetchAll();
}

function get_article_by_slug($slug) {
    $query = MySQL::getInstance()->prepare("SELECT * FROM articles WHERE slug=:slug");
    $query->bindValue(':slug', $slug, PDO::PARAM_STR);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

function get_article_comments($article_id) {
    $query = MySQL::getInstance()->prepare("SELECT * FROM comments WHERE article_id=:article_id ORDER BY posted ASC");
    $query->bindValue(':article_id', $article_id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function save_comment($article_id, $name, $body) {
    $query = MySQL::getInstance()->prepare("INSERT INTO comments (article_id, name, body) VALUES (:article_id, :name, :body)");
    $query->bindValue(':article_id', $article_id, PDO::PARAM_INT);
    $query->bindValue(':name', $name, PDO::PARAM_STR);
    $query->bindValue(':body', $body, PDO::PARAM_STR);
    $query->execute();
}