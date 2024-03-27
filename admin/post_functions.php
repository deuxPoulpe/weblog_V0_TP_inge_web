<?php

$isEditingPost = false;
$title = "";
$body = "";
$errors = array();



if (isset($_GET['edit-post'])) {
    $post_id = $_GET['edit-post'];
    edit_post($post_id);
}

if (isset($_GET['delete-post'])) {
    $post_id = $_GET['delete-post'];
    delete_post($post_id);
}

if (isset($_GET['unpublish'])) {
    $post_id = $_GET['unpublish'];
    unpublishPost($post_id);
}

if (isset($_GET['publish'])) {
    $post_id = $_GET['publish'];
    publishPost($post_id);
}

if (isset($_POST['create-post'])) {
    create_post($_POST);
}

function create_post($request_values) {
    global $conn, $errors;

    // If image is uploaded, process it
    if (isset($_FILES['featured_image'])) {
        $image_path = ROOT_PATH . "/static/images/";
        $image_name = $_FILES['featured_image']['name'];
        $image_tmp = $_FILES['featured_image']['tmp_name'];
        if (is_dir($image_path)) {
            if (move_uploaded_file($image_tmp, $image_path . $image_name)) {
                $_SESSION['message'] = "Image téléchargée avec succès";
            } 
            
            else {
                $_SESSION['message'] = "Aucune image téléchargée";
            }   
    } 
    else {
        array_push($errors, "error when trying to load image");
    }
    }
    
    // Get values from form
    $user_id = mysqli_real_escape_string($conn, $_SESSION['user']['id']);
    $title = mysqli_real_escape_string($conn, $_POST["title"]);
    $slug = str_replace(' ', '_', $title);
    $slug = strtolower($slug);
    $body = mysqli_real_escape_string($conn, $_POST['body']);
    $topic_id = mysqli_real_escape_string($conn, $_POST['topic_id']);

    // Ensure that the form is correctly filled
    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($slug)) {
        array_push($errors, "Slug is required");
    }

    // Insert the post into the database
    $sql = "INSERT INTO posts (user_id, title, slug, views, image, body, published, created_at, updated_at) VALUES ('$user_id', '$title', '$slug', 0, '$image_name', '$body', 1, '" . date("Y/m/d") . "', '" . date("Y/m/d") . "')";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        array_push($errors, "Error when adding post");
    }

    // Get the last inserted post id
    $post_id = mysqli_insert_id($conn);

    // Link the post to the topic
    $sql_post_topic = "INSERT INTO post_topic (post_id, topic_id) VALUES ('$post_id', '$topic_id')";
    $result = mysqli_query($conn, $sql_post_topic);
    
    if (!$result) {
        array_push($errors, "Error when linking post to topic");
    }
    
    if (count($errors) == 0) {
        $_SESSION['message'] = "Post created successfully";
        header('location: create_post.php');
        exit(0);
    }                
}

function edit_post($post_id) {
    global $conn, $title, $body, $isEditingPost, $post_id;
    
    $sql = "SELECT * FROM posts WHERE id=$post_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $post = mysqli_fetch_assoc($result);
    if (empty($post)) {
        array_push($errors, "No post found");
        header("location: posts.php");
        exit(0);
    } else {
        $title = $post['title'];
        $body = $post['body'];
        $isEditingPost = true;
    }
    
}

function delete_post($post_id) {
    global $conn;
    $query = "DELETE FROM posts WHERE id = '$post_id'";
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Post successfully deleted";
        header("Location: posts.php");
        exit(0);
    }
   
}


function getPostAuthorById($user_id){
    global $conn ;
    $sql = "SELECT username FROM users WHERE id=$user_id" ;
    $result = mysqli_query($conn, $sql) ;
    if ($result) {
    return mysqli_fetch_assoc($result)['username'] ;
    } else {
    return null ;
    }
}


function getAllPosts() {
    global $conn;
    $sql = "SELECT * FROM posts"; // query that retrieves all posts
    $result = mysqli_query($conn, $sql);

    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // return the array of posts
    
    return $posts;
}

function unpublishPost($post_id) {
    global $conn;
    $sql = "UPDATE posts SET published=0 WHERE id=$post_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Post successfully unpublished";
        header("location: posts.php");
        exit(0);
    }
}

function publishPost($post_id) {
    global $conn;
    $sql = "UPDATE posts SET published=1 WHERE id=$post_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Post successfully published";
        header("location: posts.php");
        exit(0);
    }
}

function getAllTopicsList()
{
    global $conn;
    $sql = "SELECT * FROM topics";
    $result = mysqli_query($conn, $sql);
    $topics = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $topics;
}

function updatePosts($request_values){
    global $conn, $errors;

    // Get values from form
    $title = $request_values['title'];
    $image = $request_values['image'];
    $body = $request_values['body'];
    $post_id = $request_values['post_id'];

    // Ensure that the form is correctly filled
    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($image)) {
        array_push($errors, "Image is required");
    }
    if (empty($body)) {
        array_push($errors, "Body is required");
    }

    // Modify the post in the database
    if (count($errors) == 0) {
        $sql = "UPDATE posts SET title='$title', image='$image', body='$body' WHERE id=$post_id";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Post updated successfully";
            header('location: posts.php');
            exit(0);
        }
    }
    $errors[] = "Failed to update post";
    return $errors;
}