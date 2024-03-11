<?php
function getPublishedPosts() {
    global $conn;
    $sql = "SELECT * FROM posts WHERE published = 1"; // query that retrieves all published posts
    $result = mysqli_query($conn, $sql);
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC); // fetch all results as an associative array
    return $posts;
}