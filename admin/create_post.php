<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/includes/admin/head_section.php'); ?>
<?php include(ROOT_PATH . '/admin/post_functions.php'); ?>

<?php $topics = getAllTopics(); ?>
<title>Admin | Manage users</title>
</head>

<body>
	<!-- admin navbar -->
	<?php include(ROOT_PATH . '/includes/admin/header.php') ?>
	<div class="container content">
		<?php include(ROOT_PATH . '/includes/admin/menu.php') ?>

		<!-- Middle form - to create and edit  -->
		<div class="action">
			<h1 class="page-title">Create Post</h1>
            <?php include(ROOT_PATH . '/includes/public/messages.php'); ?>
            <?php include(ROOT_PATH . '/includes/public/errors.php'); ?>
            <form action = <?php echo BASE_URL . 'admin/create_post.php'; ?> method="post" enctype="multipart/form-data">
                <div class="formpost">
                    <input type="text" id="title" name="title" placeholder="Title"/>
                    <input type="text" id = "slug" name="slug" placeholder="slug"/>
                    <input type="text" id="body" name="body" placeholder="content"/>
                    <input type="file" id="image" name="image" accept="image/*">

                    <label for="topic-select">Topic</label>
                
                    <select name="topic-select" id="topic-select">
                        
                    </select>
                            
					<input type="submit" value="publish_post" name="publish_post"/>

                </div>
            </form>

			
		</div>
	

	</div>

</body>

</html>




 <?php 
