<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/includes/admin/head_section.php'); ?>

<title>Admin | Manage users</title>
</head>

<body>
	<!-- admin navbar -->
	<?php include(ROOT_PATH . '/includes/admin/header.php') ?>
	<div class="container content">
		<!-- Left side menu -->
		<?php include(ROOT_PATH . '/includes/admin/menu.php') ?>

		<!-- Middle form - to create and edit  -->
		<div class="action">
			<h1 class="page-title">Create Post</h1>
            <form action = <?php echo BASE_URL . 'admin/create_post.php'; ?> method="post">
                <div class="formpost">
                    <input type="text" id="title" name="title" placeholder="Title"/>
                    <input type="text" id = "slug" name="slug" placeholder="slug"/>
                    <input type="text" id="body" name="body" placeholder="content"/>
                    <input type="file" id="image" name="image" accept="image/*">
					<input type="submit" value="Publish_post" name="publish_post"/>
                </div>
            </form>

			
		</div>
	

	</div>

</body>

</html>




 <?php 
if (isset($_POST['publish_post'])) {

    if (isset($_FILES['image'])){
        $image_path = BASE_URL.'includes/static/images/' . basename($_FILES['image']['name']);
        if (!move_uploaded_file($_FILES['image']['name'], $image_path)) {
        }
    } 
	else {
        $image_path = '';
    }

    global $conn;

    $username = mysqli_real_escape_string($conn, $_SESSION['user']['username']);
    $title = mysqli_real_escape_string($conn, $_POST["title"]);
    $slug = mysqli_real_escape_string($conn, $_POST['slug']);
    $body = mysqli_real_escape_string($conn, $_POST['body']);

    $sql_userid = "SELECT id FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql_userid);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $userid = $row['id'];

        $sql = "INSERT INTO posts (user_id, title, slug, views, image, body, published, created_at, updated_at) VALUES ('$userid', '$title', '$slug', 0, '$image_path', '$body', 1, '" . date("Y/m/d") . "', '" . date("Y/m/d") . "')";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            array_push($errors, "Error when adding post");
        }
    } 
	else {
        array_push($errors, "Error: User not found");
    }
}
