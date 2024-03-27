
<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/includes/admin/head_section.php'); ?>
<?php include(ROOT_PATH . '/admin/post_functions.php'); ?>


<?php

// Get all admin users from DB
$posts = getAllPosts(); // by admin roles i mean (Admin or Author), table users
?>

<title>Admin | Manage users</title>
</head>

<body>
	<!-- admin navbar -->
	<?php include(ROOT_PATH . '/includes/admin/header.php') ?>
	<div class="container content">
		<!-- Left side menu -->
		<?php include(ROOT_PATH . '/includes/admin/menu.php') ?>

		<!-- Display records from DB-->
		<div class="table-div">
		<h1 class="page-title">Manage Posts</h1>

		
			<!-- Display notification message -->
			<?php include(ROOT_PATH . '/includes/public/messages.php') ?>

			<?php if (empty($posts)) : ?>
				<h1>No Posts in the database.</h1>
			<?php else : ?>
				<table class="table">
					<thead>
						<th>ID</th>
						<th>Author</th>
						<th>Title</th>
						<th>Views</th>
						<th>Published</th>
						<th colspan="2">Action</th>
					</thead>
					<tbody>
						<?php foreach ($posts as $key => $post) : ?>
							<tr>
								<td><?php echo $post["id"]; ?></td>
								<td>
									<?php echo getPostAuthorById($post['user_id']) ?>
								</td>
								<td><a href="<?php echo BASE_URL . 'single_post.php?post-slug=' . $post['slug'] ?>">
									<?php echo $post['title']; ?></a></td>
								
								<td><?php echo $post['views']; ?></td>
								<td><?php if ($post['published'] == true): ?>
									<a class="fa fa-check btn unpublish" href="posts.php?unpublish=<?php echo $post['id'] ?>"></a>
								<?php else: ?>
									<a class="fa fa-times btn publish" href="posts.php?publish=<?php echo $post['id'] ?>"></a>
								<?php endif ?></td>
								</td>
								<td>
									<a class="fa fa-pencil btn edit" href="create_post.php?edit-post=<?php echo $post['id'] ?>">
									</a>
								</td>
								<td>
									<a class="fa fa-trash btn delete" href="posts.php?delete-post=<?php echo $post['id'] ?>">
									</a>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			<?php endif ?>
		</div>
		<!-- // Display records from DB -->

	</div>

</body>

</html>