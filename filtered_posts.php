<?php include('config.php'); ?>
<?php include ('includes/public/head_section.php'); ?>

<?php include ('includes/all_functions.php'); ?>

<?php if (isset($_GET['topic']))     {
    $posts = getPublishedPostsByTopic($_GET['topic']);
    $topic = getTopicById($_GET['topic']);
}

?>
                    
<?php if (isset($_SESSION['user']['username'])) { ?>
<title>
    <?php echo $topic['name'] ?> | MyWebSite 
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

                <?php foreach($posts as $post) {
                    printPost($post);
                }
                ?>
            </div>
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
