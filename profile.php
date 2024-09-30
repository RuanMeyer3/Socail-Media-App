<?php
    //Connecting to the database 
    $conn = mysqli_connect("localhost", "root", "", "socialhub");

    //Error hanldling connection 
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //Get the username from the URL 
    $username = isset($_GET['username']) ? $_GET['username'] : '';

    if ($username) {
        //Prepare and execute the query
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            //Fetching the user data
            $row = $result->fetch_assoc();
            $name = htmlspecialchars($row['name']);
            $surname = htmlspecialchars($row['surname']);
            $profilePic = htmlspecialchars($row['profile']);
            $phone = htmlspecialchars($row['phone']);
            $age = htmlspecialchars($row['age']);

        } else {
            echo "User not found.";
            exit;
        }
    } else {
        echo "No username provided.";
        exit;
    }


    //Button to go back to main page 
    if (isset($_POST['back-btn'])) { 
        header("Location: mainApp.php");
        exit();
    }


    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="CSS/profile.css">
</head>
<body>
    <div class="profile-container">
        <?php
        //Displaying the users information 
            echo "<div class='profile-container'>";
                echo "<h1>@$username</h1>";
                echo "<img src='$profilePic' alt='Profile Picture' class='profile-pic'>";
                echo "<p>Name: $name</p>";
                echo "<p>Surname: $surname</p>";
                echo "<p>age: $age</p>";
                echo "<p>Phone: 0$phone</p>";
            echo "</div>";
            ?>
    </div>
    <br>
    <div class="button-container">
        <!-- Back button in a form container -->
        <form method="post">
            <input type="submit" name="back-btn" class="back-btn" value="Back">
        </form>
        <!-- Message button to send the user a message -->
        <form method='get' action='messages.php'>
            <input type='hidden' name='recipient' value='<?php echo $username; ?>'>
            <button type='submit' class="message-btn">Message</button>
       </form>
    </div>
    
</body>
</html>
