<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="CSS/login.css" />
    <title>Login Page</title>
</head>
<?php
    //Linking variables to the mainApp.php page 
    session_start();

    //Connecting to the database
    $conn = mysqli_connect("localhost", "root", "", "socialhub");

    //Checking if the submit button is pressed to submit the form
    if (isset($_POST["submit"])) {
        //Assignnig variables from the form input fields 
        $email = $_POST['email'];
        $password = $_POST['password'];

        //Query to find the user by email
        $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

        if (mysqli_num_rows($query) > 0) {
            //Fetch the user's data
            $user = mysqli_fetch_assoc($query);
            
            //Verify the password matches (consider using password_hash in the future)
            if ($user['password'] == $password) {
                //Setting session variables
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_id'] = $user['user_id'];  // Assuming the 'id' column is the user_id in the 'users' table
                
                //Redirect to the main application
                header("Location: mainApp.php");
                exit();
            } else {
                //JavaScript to display an alert
                echo "<script> alert('The password is incorrect!') </script>";
            }
        } else {
            echo "<script> alert('Email does not exist! Redirecting to the Registration page.') 
            window.location.href = 'register.php';
            </script>";
            exit();
        }
    }
?>

<body>
    <div class="container">
        <header class="header">
            <h1 id="title" class="text-center">SocialHub Login Page</h1>
            <p id="description" class="description text-center">
                A central place for lively discussions.
            </p>
        </header>
        <form id="registration-form" method="post">
    
            <div class="form-group">
                <label id="email-label" for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your Email" required />
            </div> 
            <div class="form-group">
                <label id="password" for="password">Password</label>
                <input type="password" name="password" id="emapasswordil" class="form-control" placeholder="Enter your password" required />
            </div>            

            
            <div class="form-group">
                <button type="submit" id="submit" name="submit" class="submit-button">
                    Submit
                </button>
            </div>

            <div class="form-group">
                <p>Don't have an account? <a href="register.php" class="hyperlink">Register</a></p>
                
            </div>
        </form>
    </div>
</body>
</html>