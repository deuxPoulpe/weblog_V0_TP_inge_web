<?php
function getPublishedPosts() {
    global $conn;
    $sql = "SELECT id, user_id, title, slug, views, image, published, created_at, updated_at FROM posts WHERE published = 1"; // query that retrieves all published posts
    $result = mysqli_query($conn, $sql);

    $posts = array();
    while ($row = mysqli_fetch_assoc($result)) {
        // Get the topic for this post
        $topic = getPostTopic($row['id']);
        // Add the topic to the post's data
        $row['topic'] = $topic;
        // Add the post to the posts array
        $posts[] = $row;
    }

    // return the array of posts
    return $posts;
}
function getPostTopic($post_id) {
    global $conn;
    $sql = "SELECT * FROM topics 
            JOIN post_topic ON topics.id = post_topic.topic_id 
            WHERE post_topic.post_id = '$post_id'" ; // query that retrieves the topic by its id
    $result = mysqli_query($conn, $sql);
    $topic = mysqli_fetch_assoc($result);
    return $topic;
}

function getAuthorById($id) {
    global $conn;
    $sql = "SELECT * FROM users WHERE id='$id'"; // query that retrieves the user by their id
    $result = mysqli_query($conn, $sql);
    $author = mysqli_fetch_assoc($result); // fetch the result as an associative array
    return $author;
}

function getPostBySlug($slug) {
    global $conn;
    $sql = "SELECT * FROM posts WHERE slug='$slug'"; // query that retrieves the post with the given slug
    $result = mysqli_query($conn, $sql);
    $post = mysqli_fetch_assoc($result);
    return $post;
}

function getAllTopics() {
    global $conn;
    $sql = "SELECT * FROM topics"; // query that retrieves all topics
    $result = mysqli_query($conn, $sql);

    $topics = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // return the array of topics
    return $topics;
}

function getPublishedPostsByTopic($topic_id) {
    global $conn;
    $sql = "SELECT * FROM posts JOIN post_topic ON posts.id = post_topic.post_id WHERE post_topic.topic_id='$topic_id'"; // query that retrieves all published posts with the given topic_id
    $result = mysqli_query($conn, $sql);

    // fetch all posts as an associative array called $posts
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $final_posts = array();
    foreach ($posts as $post) {
        $post['topic'] = getPostTopic($post['id']);
        array_push($final_posts, $post);
    }
    return $final_posts;
}


function getTopicById($id) {
    global $conn;
    $sql = "SELECT * FROM topics WHERE id='$id'"; // query that retrieves the topic by its id
    $result = mysqli_query($conn, $sql);
    $topic = mysqli_fetch_assoc($result);
    return $topic;
}

function printPost($post){

    echo "<div class='post' style='margin-left: 0px;'>";
    echo "<div class='category'>";
    echo "<h2>" . $post['topic']['name'] . "</h2>"; // topic title
    echo "</div>";
    
    echo "<img class='post_image' src=" . BASE_URL . "/static/images/" . $post['image'] . " alt=\"" . $post['image'] . "\">";

    echo "<div class='post_info'>";
    echo "<p>" . $post['title'] . "</p>"; // post title

    echo "<span>";
    echo "<p>Published on " . date('F j, Y', strtotime($post['created_at'])) . "</p>";
    echo "<a class='read_more' href='single_post.php?post-slug=" . $post['slug'] . "'>Read more...</a>";
    echo "</span>";
    echo "</div>";
    echo "<hr>";
    echo "</div>";
}
