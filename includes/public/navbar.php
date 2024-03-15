<div class="navbar">
	<div class="logo_div">
		<h1>MyWebSite</h1>
	</div>
	<ul>
	  <li><a class="active" href="index.php">Home</a></li>
	  <li><a href="#news">News</a></li>
	  <li><a href="#contact">Contact</a></li>
	  <li><a href="#about">About</a></li>
	  <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == "Admin") {
		echo "<li><a href='admin/dashboard.php'>Admin</a></li>";
	  }

	  ?>
	</ul>
</div>