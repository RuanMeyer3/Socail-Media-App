<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="CSS/mainApp.css">
</head>


<?php
    session_start();

    //Connecting to the database 
    $conn = mysqli_connect("localhost", "root", "", "socialhub");

    //Error handling connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //Get current user ID from session
    $currentUserId = $_SESSION['user_id'];

    //Get recipient username and user_id from URL
    $recipient = isset($_GET['recipient']) ? $_GET['recipient'] : '';

    if ($recipient) {
        //Query to get the recipient's user ID
        $recipientQuery = "SELECT user_id FROM users WHERE username = '$recipient'";
        $recipientResult = mysqli_query($conn, $recipientQuery);

        if (mysqli_num_rows($recipientResult) > 0) {
            $recipientData = mysqli_fetch_assoc($recipientResult);
            $recipientId = $recipientData['user_id'];

            echo "<div class='window'>";
            echo "<h1>Messages with @$recipient</h1>";

            //Handle message sending
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
                $message = mysqli_real_escape_string($conn, $_POST['message']);

                if (!empty($message)) {
                    //Inserting the message to a message database 
                    $insertMessage = "INSERT INTO messages (sender_id, recipient_id, content, timestamp) 
                                      VALUES ('$currentUserId', '$recipientId', '$message', NOW())";
                    if (mysqli_query($conn, $insertMessage)) {
                        echo "<p>Message sent!</p>";
                    } else {
                        echo "<p>Error sending message.</p>";
                    }
                } else {
                    echo "<p>Message cannot be empty.</p>";
                }
            }

            //Fetch and display messages between current user and recipient
            $messageQuery = "SELECT * FROM messages 
                             WHERE (sender_id = '$currentUserId' AND recipient_id = '$recipientId') 
                             OR (sender_id = '$recipientId' AND recipient_id = '$currentUserId') 
                             ORDER BY timestamp ASC";

            $messageResult = mysqli_query($conn, $messageQuery);

            echo "<div class='messages-container'>";
            if (mysqli_num_rows($messageResult) > 0) {
                while ($messageRow = mysqli_fetch_assoc($messageResult)) {
                    $sender = ($messageRow['sender_id'] == $currentUserId) ? 'You' : "@$recipient";
                    echo "<div class='message'><strong>$sender:</strong> <br>" . $messageRow['content'] . " 
                    <br><em>(" . $messageRow['timestamp'] . ")</em></div><br>";
                }
            } else {
                echo "<p>No messages yet.</p>";
            }
            echo "</div>";

            //Message sending form
            echo "
            <form method='POST' action=''>
                <textarea name='message' rows='3' placeholder='Type your message here...'></textarea>
                <button type='submit'>Send</button>
            </form>";
        } else {
            echo "<p>Recipient not found.</p>";
        }
    } else {
        //If no recipient is specified, display a list of users the current user has messaged
        echo "<div class='window'>";
        echo "<h1>Select a conversation</h1>";

        //Query to get the list of previous message recipients
        $recipientsQuery = "SELECT DISTINCT u.username FROM users u
                            JOIN messages m ON (u.user_id = m.sender_id OR u.user_id = m.recipient_id)
                            WHERE (m.sender_id = '$currentUserId' OR m.recipient_id = '$currentUserId') 
                            AND u.user_id != '$currentUserId'";
        $recipientsResult = mysqli_query($conn, $recipientsQuery);

        if (mysqli_num_rows($recipientsResult) > 0) {
            echo "<ul>";
            while ($row = mysqli_fetch_assoc($recipientsResult)) {
                $username = htmlspecialchars($row['username']);
                echo "<li><a href='messages.php?recipient=$username'>@$username</a></li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No previous conversations found.</p>";
        }

        echo "</div>";
    }

    //Close the connection
    mysqli_close($conn);
?>


<body>
    
</body>
</html>