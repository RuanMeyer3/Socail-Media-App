<?php
    //Connecting to the database 
    $conn = mysqli_connect("localhost", "root", "", "socialhub");

    //Error handling the connection 
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //Get the search query from the URL
    $query = isset($_GET['search-query']) ? $_GET['search-query'] : '';

    if ($query) {
        //Prepare and execute the search query
        $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE ? OR name LIKE ? OR surname LIKE ?");
        $searchTerm = "%" . $query . "%";
        $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            //Display the search results
            while ($row = $result->fetch_assoc()) {
                $username = htmlspecialchars($row['username']);
                $name = htmlspecialchars($row['name']);
                $surname = htmlspecialchars($row['surname']);
                $profile = htmlspecialchars($row['profile']);
                echo "<div class='user-profile'>";
                echo "<h2> <img src='$profile' alt='Profile Image' class='profile-icon'>  @$username</h2>";
                echo "<p>Name: $name</p>";
                echo "<p>Surname: $surname</p>";
                echo "<form method='get' action='messages.php'>";
                echo "<input type='hidden' name='recipient' value='$username'>";
                echo "<button type='submit' class='msg-btn'>Message</button>";
                echo "<a href='profile.php?username=" . urlencode($username) . "' class='button'>Profile</a>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "No users found.";
        }
    } else {
        echo "Please enter a search query.";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" href="CSS/profile.css">
</head>
<body>
    
</body>
</html>
