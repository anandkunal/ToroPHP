<h1>Blog</h1>
<? include("_article.php"); ?>

<h3>Comments (<?= count($comments); ?>)</h3>
<?
    foreach ($comments as $comment) {
        include("_comment.php");
    }
?>

<h4>Add Comment</h4>
<form method="post" action="/article/<?= $article['slug']; ?>/comment">
    <strong>Name:</strong><br/>
    <input type="text" name="name" /><br/>
    <strong>Message:</strong><br/>
    <textarea name="body"></textarea><br/>
    <input type="submit" />
</form>