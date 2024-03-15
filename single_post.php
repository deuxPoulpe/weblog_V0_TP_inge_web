<?php include('config.php'); ?>
<?php include ('includes/public/head_section.php'); ?>

<?php include ('includes/all_functions.php'); ?>

<?php 
    if (isset ($_GET['post-slug'])) {
    $post = getPostBySlug($_GET['post-slug']);
    $topic = getPostTopic($post);

}
?>

<?php if (isset($_SESSION['user']['username'])) { ?>
<title>
    <?php echo $post['title'] ?> | MyWebSite
</title>
</head>

<body>
    <div class="container">
        <!-- Navbar -->
        <?php include (ROOT_PATH . '/includes/public/navbar.php'); ?>
        
        <!-- // Navbar -->
        <div class="content">
            <!-- Page wrapper -->
            <div class="post-wrapper">
                <!-- full post div -->
                <div class="full-post-div">
                    <h2 class="post-title"> <?php echo $post['title']?> </h2>
                        <div class="post-body-div">
                            <?php echo $post['body']; ?>
                        </div>
                </div>
                <!-- // full post div -->
            </div>
            <!-- // Page wrapper -->
            <!-- post sidebar -->
            <div class="post-sidebar">
                <div class="card">
                    <div class="card-header">
                        <h2>Topics</h2>
                    </div>
                    <div class="card-content">
                        <?php 
                        $topics = getAllTopics();
                        
                        foreach ($topics as $topic) {
                            echo "<a href='" . BASE_URL . "/filtered_posts.php?topic=" . $topic['id'] . "'>" . $topic['name'] . "</a>";
                        }
                        
                        ?>
                    </div>
                </div>
            </div>
            <!-- // post sidebar -->
        </div>
    </div>
    <!-- // content -->
    <?php include (ROOT_PATH . '/includes/public/footer.php'); ?>";

    <?php } else { ?>


        <title>
    <?php echo $post['title'] ?> | MyWebSite
</title>
</head>

<body>
    <div class="container">
        <!-- Navbar -->
        <?php include (ROOT_PATH . '/includes/public/navbar.php'); ?>
        <!-- // Navbar -->
        <div class="content">
            <!-- Page wrapper -->
            <div class="post-wrapper">
                <p>Please <a href="login.php">login</a> to view the full content.</p>
            </div>
            
        </div>
    </div>
    <!-- // content -->
    <?php include (ROOT_PATH . '/includes/public/footer.php'); ?>";

<?php } ?>