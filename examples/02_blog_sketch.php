<?php
require_once 'toro.php';

$blog_posts = array(
    array('title' => 'Evening Plans', 'body' => "I'm staying in to work on my law blog."),
    array('title' => "You don't need double talk; you need Bob Loblaw", 'body' => 'True.'),
    array('title' => "Why should you go to jail for a crime someone else noticed?", 'body' => "You shouldn't."),
);

function display_snippet($pos) {
    echo '<a href="article/"' . $blog_post[$pos]['id'] . '</a>' . $blog_post[$pos]['title'] . '</a><br/>';
}

function display_blog_post($pos) {
    echo '<h1>' . $blog_post[$pos]['title'] . '</h1><p>' . $blog_post[$pos]['body'] . '</p>';
}

function is_valid_post($pos) {
    $pos = intval($pos);
    return ($pos >= 0 && $pos < count($blog_posts));
}

class BlogHandler extends ToroHandler {
    public function get() { 
        // Splash page, display all entries.
        for ($i=0; $i<count($blog_posts); $i++) {
            display_snippet($i);
        }
    }

    public function get_mobile() {
        // Only display the last entry for mobile (Loblaw cares).
        display_snippet(0);
    }
}

class ArticleHandler extends ToroHandler {
    public function get($post_id) {
        // Display the post if it exists
        if (is_valid_post($post_id)) {
            display_blog_post($post);
        }
        else {
            header('HTTP/1.0 404 Not Found');
            echo 'Post does not exist';
            exit;
        }
    }
}

class CommentHandler extends ToroHandler {
    public function post_xhr($post_id) {
        // Bob Loblaw never got around to implementing this, here is pseudo PHP
        if (is_valid_post($post_id)) {
            // Assuming you save this comment, just return JSON if the post exists
            echo json_encode(array(
              'commentor' => $_POST['commentor'],
              'comment_body' => $_POST['comment_body']
            ));
        }
    }
}

$site = new ToroApplication(array(
    array('/', 'BlogHandler'),
    array('article/([a-zA-Z0-9_]+)', 'ArticleHandler'),
    array('comment/([a-zA-Z0-9_]+)', 'CommentHandler'),
));

$site->serve();