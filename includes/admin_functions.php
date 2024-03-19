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

function getAdminUsers() {
    global $conn;
    $sql = "SELECT * FROM users WHERE role = 'Admin'"; // query that retrieves all admin users
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

