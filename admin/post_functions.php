<?php


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

if (isset($_POST['create_post'])) {
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
                echo "L'image a été téléchargée avec succès.";
            } 
            else {
                echo "Not uploaded because of error #".$_FILES["file"]["error"];
            }
        } else {
            echo 'Upload directory is not writable, or does not exist.';
        }
    } 
    else {
        echo "Aucune image téléchargée.";
    }
    
    // Get values from form
    $username = mysqli_real_escape_string($conn, $_SESSION['user']['username']);
    $title = mysqli_real_escape_string($conn, $_POST["title"]);
    $slug = mysqli_real_escape_string($conn, $_POST['slug']);
    $body = mysqli_real_escape_string($conn, $_POST['body']);

    // Ensure that the form is correctly filled
    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($slug)) {
        array_push($errors, "Slug is required");
    }

    // Fetch the user id
    $sql_userid = "SELECT id FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql_userid);

    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $userid = $row['id'];

        // Insert the post into the database
        $sql = "INSERT INTO posts (user_id, title, slug, views, image, body, published, created_at, updated_at) VALUES ('$userid', '$title', '$slug', 0, '$image_name', '$body', 1, '" . date("Y/m/d") . "', '" . date("Y/m/d") . "')";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            array_push($errors, "Error when adding post");
        }
        else {
            $_SESSION['message'] = "Post created";
            header("location: create_post.php");                         
        }
    }
    else {
        array_push($errors, "Error: User not found");
    }
}

function edit_post($post_id, $title, $content, $category, $published) {
    global $conn;
    $query = "UPDATE posts SET title = '$title', content = '$content', category = '$category', published = '$published' WHERE post_id = '$post_id'";
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Post successfully updated";
        header("Location: posts.php");
        exit(0);
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