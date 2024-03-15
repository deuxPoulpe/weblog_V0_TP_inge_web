<?php

// Admin user variables
$admin_id = 0;
$isEditingUser = false;
$username = "";
$email = "";

// Topics variables
$topic_id = 0;
$isEditingTopic = false;
$topic_name = "";

// general variables
$errors = [];

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
    // get values from form
    $username = $request_values['username'];
    $password = password_hash($request_values['password'], PASSWORD_DEFAULT); // hash the password before storing for security

    // create new admin user
    $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'Admin')";
    mysqli_query($conn, $sql);

    // return all admin users
    return getAdminUsers();
}

function isAdmin() {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != "Admin") {
        header('location: ' . BASE_URL . '/index.php');
    }
}