<?php include('config.php'); ?>
<?php include('includes/public/registration_login.php'); ?>

<?php include('includes/public/head_section.php'); ?>


<title>MyWebSite | Sign in </title>
</head>

<body>

	<div class="container">

		<!-- Navbar -->
		<?php include(ROOT_PATH . '/includes/public/navbar.php'); ?>
		<!-- // Navbar -->


		<div style="width: 40%; margin: 20px auto;">
			<form method="post" action="register.php">
				<h2>Register on MyWebSite</h2>
				<?php include(ROOT_PATH . '/includes/public/errors.php') ?>
				<input type="text" name="username" placeholder="Username">

                <input type="email" name="email" placeholder="Email">

				<input type="password" name="password" value="" placeholder="Password">

                <input type="password" name="confirm_password" value="" placeholder="Confirm Password">

				<button type="submit" class="btn" name="Register_btn">Register</button>
				<p>
					Already a member? <a href="login.php">Sign in</a>
				</p>
			</form>
		</div>


	</div>