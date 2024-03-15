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
            <form action ="admin/create_post.php" method="post">
                <div class="formpost">
                    <input type="text" id="title" name="title" placeholder="Title"/>
                    <input type="text" id = "slug" name="slug" placeholder="slug"/>
                    <input type="text" id="body" name="body" placeholder="content"/>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>
            </form>

			
		</div>
	

	</div>

</body>

</html>




<!-- <?php 
// function new_post() {
//     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//         $sql = "INSERT INTO posts VALUES ("
//     }
// } -->