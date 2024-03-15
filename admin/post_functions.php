<?php

function edit_post($post_id, $title, $content, $category, $published, $post_id) {
    global $db;
    $query = "UPDATE posts SET title = '$title', content = '$content', category = '$category', published = '$published' WHERE post_id = '$post_id'";
    $db->exec($query);
}

function delete_post($post_id) {
    global $db;
    $query = "DELETE FROM posts WHERE post_id = '$post_id'";
    $db->exec($query);
}
