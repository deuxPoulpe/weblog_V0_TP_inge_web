<?php
// variable declaration
$username = "";
$email = "";
$errors = array();

// REGISTER USER

if (isset($_POST['Register_btn'])) {
    // call these variables with the global keyword to make them available in function
    global $conn, $errors, $username, $email;
    // receive all input values from the form. Call the e() function
    // defined below to escape form values
    $username = esc($_POST['username']);
    $email = esc($_POST['email']);
    $password_1 = esc($_POST['password']);
    $password_2 = esc($_POST['password']);
    // form validation: ensure that the form is correctly filled
    if (empty($username)) {
        array_push($errors, "Username required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors,"Invalid email");
    }
    else if (empty($email)) {
        array_push($errors, "Email required");
    }
    if (empty($password_1)) {
        array_push($errors, "Password required");
    }
    if (empty($password_2)) {
        array_push($errors, "Password required");
    }
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }
    // register user if there are no errors in the form
    if (count($errors) == 0) {
        $conn->query("INSERT INTO users (username, email, password) VALUES('$username', '$email', '" . md5($password_1) . "')");
        // get id of created user
        $reg_user_id = $conn->insert_id;
        // put logged in user into session array
        $_SESSION['user'] = getUserById($reg_user_id);
        $_SESSION['message'] = "You are now registered";
        // redirect to public area
        header('location: index.php');
        exit(0);
    }
}

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
// Get user info from user id
function getUserById($id)
{
    global $conn; // make the connection variable $conn available to this function
    $sql = "SELECT * FROM users WHERE id = ?"; // query that retrieves the user and their role
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc(); // put $result in associative format
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

