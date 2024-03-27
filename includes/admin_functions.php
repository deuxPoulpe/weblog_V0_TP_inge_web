<?php

// Admin user variables
$admin_id = 0;
$isEditingUser = false;
$username = "";
$email = "";
$roles = getRoles();

// Topics variables
$topic_id = 0;
$isEditingTopic = false;
$topic_name = "";

// general variables
$errors = array();

/* - - - - - - - - - -
-
- Admin users actions
-
- - - - - - - - - - -*/

// if user clicks the create admin button
if (isset($_POST['create_admin'])) {
    createAdmin($_POST);
}

// if user clicks the delete admin button
if (isset($_GET['delete-admin'])) {
    $admin_id = $_GET['delete-admin'];
    delUsers($admin_id);
}

if (isset($_GET['edit-admin'])) {
    $admin_id = $_GET['edit-admin'];
    editAdmin($admin_id);
}

if (isset($_POST['update_admin'])) {
    updateAdmin($_POST);
}

function getAdminUsers() {
    global $conn;
    $sql = "SELECT * FROM users"; // query that retrieves all admin users
    $result = mysqli_query($conn, $sql);

    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // return the array of users
    return $users;
}

function createAdmin($request_values) {
    global $conn;
    global $errors;
    
    $role_id = $request_values['role_id'];
    if (empty($role_id)) {
        array_push($errors, "Role is required");
    }
    else {
        $sql = "SELECT name FROM roles WHERE id=$role_id";
        $result = mysqli_query($conn, $sql);
        $role = mysqli_fetch_assoc($result)['name'];
    }
        
    // get values from form
    $username = $request_values['username'];
    $email = $request_values['email'];
    $password = md5($request_values['password']);
    $passwordConfirmation = md5($request_values['passwordConfirmation']);

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $emails = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // if email is already in use
    if ($emails) {
        array_push($errors, "Email already exists");
    }

    // Ensure that the form is correctly filled
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
    if (empty($passwordConfirmation)) {
        array_push($errors, "Password confirmation is required");
    }
    if ($password != $passwordConfirmation) {
        array_push($errors, "The two passwords do not match");
    }

    if (empty($errors)) {
        // create new admin user
        $sql = "INSERT INTO users (username, password, role, email) VALUES ('$username', '$password', '$role', '$email')";
        mysqli_query($conn, $sql);

        // display success message
        $_SESSION['message'] = "User created";
    }
    // return all admin users
    return getAdminUsers();
}

function isAdmin() {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != "Admin") {
        header('location: ' . BASE_URL . '/index.php');
    }
}

function getRoles() {
    global $conn;
    $sql = "SELECT * FROM roles"; // query that retrieves all admin users
    $result = mysqli_query($conn, $sql);

    $roles = mysqli_fetch_all($result, MYSQLI_ASSOC);
    // return the array of users
    return $roles;
}

/* * * * * * * * * * * * * * * * * * * * *
* - Takes admin id as parameter
* - Fetches the admin from database
* - sets admin fields on form for editing
* * * * * * * * * * * * * * * * * * * * * */
function editAdmin($adminUser_id){
    global $conn, $username, $isEditingUser, $admin_id, $email;
    $sql = "SELECT * FROM users WHERE id=$adminUser_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $admin = mysqli_fetch_assoc($result);
    // set form values ($username and $email) on the form to be updated
    $admin_id = (int)$admin['id'];
    $username = $admin['username'];
    $email = $admin['email'];
    $isEditingUser = true;
}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * - Receives admin request from form and updates in database
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    
function updateAdmin($request_values){
    global $conn, $errors, $username, $isEditingUser, $admin_id, $email;

    // Solve the role of the user
    $role_id = $request_values['role_id'];
    if (empty($role_id)) {
        array_push($errors, "Role is required");
    }
    else {
        $sql = "SELECT name FROM roles WHERE id=$role_id";
        $result = mysqli_query($conn, $sql);
        $role = mysqli_fetch_assoc($result)['name'];
    }

    // Get values from form
    $username = $request_values['username'];
    $email = $request_values['email'];
    $password = md5($request_values['password']);
    $passwordConfirmation = md5($request_values['passwordConfirmation']);

    $sql = "SELECT * FROM users WHERE email='$request_values[email]' AND id != $request_values[admin_id]";
    $result = mysqli_query($conn, $sql);
    $users_with_same_email = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // if email is already in use
    if (count($users_with_same_email) > 0) {
        array_push($errors, "Email already in use");
    }

    // Ensure that the form is correctly filled
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
    if (empty($passwordConfirmation)) {
        array_push($errors, "Password confirmation is required");
    }
    if ($password != $passwordConfirmation) {
        array_push($errors, "The two passwords do not match");
    }

    // Modify the admin in the database
    if (count($errors) == 0) {
        $sql = "UPDATE users SET username='$request_values[username]', email='$request_values[email]', role='$role', password='$password' WHERE id=$request_values[admin_id]";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Admin user updated successfully";
            header('location: users.php');
            exit(0);
        }
    }
    $errors[] = "Failed to update admin user";
    return $errors;
}

function delUsers($user_id) {
    global $conn;
    $sql = "DELETE FROM users WHERE id=$user_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "User deleted successfully";
        header("location: users.php");
        exit(0);
    }
}

