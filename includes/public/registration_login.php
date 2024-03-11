<?php
// variable declaration
$username = "";
$email = "";
$errors = array();

// LOG USER IN
if (isset($_POST['login_btn'])) {
    $username = esc($_POST['username']);
    $password = esc($_POST['password']);
    if (empty($username)) {
        array_push($errors, "Username required");
    }
    if (empty($password)) {
        array_push($errors, "Password required");
    }
    if (empty($errors)) {
        $password = md5($password); // encrypt password
        $sql = "SELECT * FROM users WHERE username='$username' and password='$password' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            // get id of created user
            $reg_user_id = mysqli_fetch_assoc($result)['id'];
            //var_dump(getUserById($reg_user_id)); die();
// put logged in user into session array
            $_SESSION['user'] = getUserById($reg_user_id);
            // if user is admin, redirect to admin area
            if (in_array($_SESSION['user']['role'], ["Admin", "Author"])) {
                $_SESSION['message'] = "You are now logged in";
                // redirect to admin area
                header('location: ' . BASE_URL . '/admin/dashboard.php');
                exit(0);
            } else {
                $_SESSION['message'] = "You are now logged in";
                // redirect to public area
                header('location: index.php');
                exit(0);
            }
        } else {
            array_push($errors, 'Wrong credentials');
        }
    }
}
elseif (isset($_POST['registration_btn'])) {
    $username = esc($_POST['username']);
    $email = esc($_POST['email']);
    $password = esc($_POST['password']);
    $password_conf = esc($_POST['password_conf']);
    if (empty($username)) {
        array_push($errors, "Username required");
    }
    if (empty($email)) {
        array_push($errors, "Email required");
    }
    if (empty($password)) {
        array_push($errors, "Password required");
    }
    if ($password != $password_conf) {
        array_push($errors, "Password do not match");
    }
    $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    $result = mysqli_query($conn, $user_check_query);
    $user = mysqli_fetch_assoc($result);
    if ($user) { // if user exists
        if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
        }
        if ($user['email'] === $email) {
            array_push($errors, "Email already exists");
        }
    }
    if (empty($errors)) {
        $password = md5($password); //encrypt password
        $last_id = mysqli_insert_id($conn);
        $id = $last_id + 1;
        $query = "INSERT INTO users (id, username, email, role, password, created_at, updated_at)
                        VALUES('$id', '$username', '$email', 'Author', '$password', now(), now())";
        mysqli_query($conn, $query);
        // get id of created user
        $reg_user_id = mysqli_insert_id($conn);
        // put logged in user into session array
        $_SESSION['user'] = getUserById($reg_user_id);
        // if user is admin, redirect to admin area
        var_dump($query);
        if (in_array($_SESSION['user']['role'], ["Admin", "Author"])) {
            $_SESSION['message'] = "You are now logged in";
            // redirect to admin area
            header('location: ' . BASE_URL . '/admin/dashboard.php');
            exit(0);
        } else {
            $_SESSION['message'] = "You are now logged in";
            // redirect to public area
            header('location: index.php');
            exit(0);
        }
    }
    

}

// Get user info from user id
function getUserById($id)
{
    global $conn; //rendre disponible, à cette fonction, la variable de connexion $conn
    $sql = "SELECT * FROM users WHERE id='$id'"; // replace with your SQL query
    $result = mysqli_query($conn, $sql); // execute the query
    $user = mysqli_fetch_assoc($result); // fetch the result as an associative array
    return $user;
}
// escape value from form
function esc(string $value)
{
    // bring the global db connect object into function
    global $conn;
    $val = trim($value); // remove empty space sorrounding string
    $val = mysqli_real_escape_string($conn, $value);
    return $val;
}