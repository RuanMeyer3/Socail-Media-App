<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Social Insta : Home Page </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/mainApp.css">
</head>

<?php
    session_start();

    //Connecting to the database
    $conn = mysqli_connect("localhost", "root", "", "socialhub");

    //Get logged-in user's email and user ID from session
    $email = $_SESSION['email'];
    $user_id = $_SESSION['user_id'];

    //Liking or unliking a post if the user has already liked the post 
    if (isset($_POST['like_post'])) {
        $post_id = $_POST['post_id'];

        //Check if the user has already liked this post
        $check_like = "SELECT liked FROM likes WHERE post_id = '$post_id' AND user_id = '$user_id'";
        $like_result = mysqli_query($conn, $check_like);

        if (mysqli_num_rows($like_result) > 0) {
            //User has already liked this post, toggle like/unlike
            $like_row = mysqli_fetch_assoc($like_result);
            $current_liked = $like_row['liked'];

            //Toggle like status
            $new_liked_status = $current_liked ? 0 : 1;
            $update_like = "UPDATE likes SET liked = '$new_liked_status' WHERE post_id = '$post_id' AND user_id = '$user_id'";
            mysqli_query($conn, $update_like);
        } else {
            //User has not liked this post yet, insert new like
            $insert_like = "INSERT INTO likes (post_id, user_id, liked) VALUES ('$post_id', '$user_id', 1)";
            mysqli_query($conn, $insert_like);
        }
    }

    //Fetch the profile picture of the logged-in user
    $query = "SELECT profile FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $profilePic = $row['profile'];
        $profilePicPath = $profilePic;
    } else {
        //Default profile image
        $profilePicPath = 'Images/defaultProfile.jpeg'; 
    }

    //Handle post creation
    if (isset($_POST['submit_post'])) { 
        $content = $_POST['content'];
        $image = '';
        $profile = $profilePicPath;

        $usernameQuery = "SELECT username FROM users WHERE email = '$email'";
        $usernameResult = mysqli_query($conn, $usernameQuery);

        if ($usernameResult && mysqli_num_rows($usernameResult) > 0) {
            $usernameRow = mysqli_fetch_assoc($usernameResult);
            $username = $usernameRow['username'];
        } else {
            $username = 'Unknown';
        }

        //Checking if the file is selected 
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image_name = $_FILES['image']['name'];
            $image_tmp = $_FILES['image']['tmp_name'];
            $image_path = "uploads/" . $image_name;

            move_uploaded_file($image_tmp, $image_path);

            //Store the image path in the database
            $image = $image_path;
        }

        //Inserting the post in a database table called posts 
        $query = "INSERT INTO posts (about, image, username, profile) VALUES ('$content', '$image', '$username', '$profile')";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Post added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding post');</script>";
        }
    }
?>

<body>
    <!-- navbar -->
    <nav class="navbar">
        <img src="Images/logo.png" class="logo" alt="">
        <form class="search-box" method="get" action="search.php">
            <input type="text" placeholder="Search" name="search-query" id="search-input">
            <button class="search-btn" type="submit" > <img src="Images/search.png" class="search-icon"> </button>
        </form>
        <div class="nav-links">
            <a href="messages.php"> <img src="Images/messages.jpeg" class="nav-icon user-profile" alt=""></a>
            <a href='profile.php?username=<?php 

                    //Fetching th username of the user 
                    $usernameQuery = "SELECT username FROM users WHERE email = '$email'";
                    $usernameResult = mysqli_query($conn, $usernameQuery);

                    if ($usernameResult && mysqli_num_rows($usernameResult) > 0) {
                        $usernameRow = mysqli_fetch_assoc($usernameResult);
                        $username = $usernameRow['username'];
                    } else {
                        $username = 'Unknown';
                    }

                echo urlencode($username); ?>'>
                <img src="<?php echo $profilePicPath; ?>" class="nav-icon user-profile" alt="">
            </a>
        </div>
    </nav>

    <!-- main section -->
    <section class="main">
        <button id="addPostBtn" class="add-post-button">Add Post</button>
        
            
        <form id="postForm" class="form-container" style="display: none" method="POST" enctype="multipart/form-data">
            <textarea name="content" placeholder="What's on your mind?" required></textarea>
            <input type="file" name="image" accept="image/*">
            <button type="submit" name="submit_post">Post</button>
        </form>

        <?php
        //Fetch posts from the database
        $query = "SELECT * FROM posts ORDER BY timestamp DESC";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='post'>";

            //Fetch and display the profile picture of the person who made the post
            $profilePicPath = $row['profile'];  
            
            $username = $row['username'];

            //Display the profile picture and username
            echo "<a href='profile.php?username=" . urlencode($username) . "'><img src='" . $profilePicPath . "' class='nav-icon user-profile' alt=''></a>";
            echo "<a href='profile.php?username=" . urlencode($username) . " ' style='text-decoration:none;'>  @" .$username ."</a>";  // Display the post creator's username

            //Display the post content
            echo "<p>" . $row['about'] . "</p>";

            //If the post has an image, display it
            if (!empty($row['image'])) {
                echo "<img src='" . $row['image'] . "' alt='Post image'>";
            }

            //Fetch the post_id for the current post
            $post_id = $row['post_id'];

            //Fetch and display the number of likes for the post
            $like_count_query = "SELECT COUNT(*) AS like_count FROM likes WHERE post_id = '$post_id' AND liked = 1";
            $like_count_result = mysqli_query($conn, $like_count_query);
            $like_count_row = mysqli_fetch_assoc($like_count_result);
            $like_count = $like_count_row['like_count'];

            //Check if the logged-in user has liked the post
            $user_id = $_SESSION['user_id'];
            $user_like_query = "SELECT liked FROM likes WHERE post_id = '$post_id' AND user_id = '$user_id'";
            $user_like_result = mysqli_query($conn, $user_like_query);
            $liked = (mysqli_num_rows($user_like_result) > 0 && mysqli_fetch_assoc($user_like_result)['liked'] == 1) ? true : false;

            //Apply background color based on the like status
            $button_style = $liked ? "background-color: red; color: white;" : "background-color: transparent; color: black;";

            echo "<form method='POST' action=''>";
            echo "<input type='hidden' name='post_id' value='$post_id'>";
            echo "<button type='submit' name='like_post' class='like-button' style='$button_style'>Like ($like_count)</button>";
            echo "</form>";

            echo "<p><small>Posted on " . $row['timestamp'] . "</small></p>";
            echo "</div>";
            echo "<hr>";
        }
?>

    </section>

    <script src="js/post.js"></script>
    <script>
        //JavaScript to toggle the post form visibility when "Add Post" is clicked
        document.getElementById("addPostBtn").onclick = function () {
            var postForm = document.getElementById("postForm");
            postForm.style.display = (postForm.style.display === "none") ? "inline-block" : "none";
        };
    </script>

</body>

</html>
