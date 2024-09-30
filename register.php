<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="CSS/login.css" />
    <title>Register Page</title>
</head>

<?php
    //Connecting to the database
    $conn = mysqli_connect("localhost", "root", "", "socialhub");

    if (isset($_POST["submit"])) {
        //Assigning variables from the form input fields 
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $age = $_POST['age'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $profile = '';
        $username = $name . $surname;


        //Check if the file is uploaded correctly
        if (isset($_FILES['profile']) && $_FILES['profile']['error'] == 0) {
            $profile_name = $_FILES['profile']['name'];
            $profile_tmp = $_FILES['profile']['tmp_name'];
            $profile_path = "profiles/" . $profile_name;

            move_uploaded_file($profile_tmp, $profile_path);

            //Store the image path to save in the database
            $profile = $profile_path;
        }

        //Query to extarct data from the database 
        $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

        //Checking the rows in the database for matching values
        if (mysqli_num_rows($query) > 0) {
            //If email exists the page will redirect you to the login page 
            echo "<script> alert('Email Already Exists. Redirecting to the login page.') 
            window.location.href = 'login.php';
            </script>";
            exit();
        //Inserting the new user details if email is not in the database 
        } else {
            $query = "INSERT INTO users(name, surname, email, password, phone, profile, username, age) 
                      VALUES('$name', '$surname', '$email', '$password', '$phone', '$profile', '$username', '$age')";
            mysqli_query($conn, $query);

            echo "<script> alert('Data Inserted Successfully') </script>";
        }
    }
?>

<body>
    <div class="container">
        <header class="header">
            <h1 id="title" class="text-center">SocialHub Registration Page</h1>
            <p id="description" class="description text-center">
                A central place for lively discussions.
            </p>
        </header>
        <form id="registration-form" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label id="name-label" for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter your name" required />
            </div>
            <div class="form-group">
                <label id="surname-label" for="surname">Surname</label>
                <input type="text" name="surname" id="surname" class="form-control" placeholder="Enter your Surname" required />
            </div>
            <div class="form-group">
                <label id="number-label" for="number">Age<span class="clue">(optional)</span></label>
                <input type="number" name="age" id="number" min="10" max="99" class="form-control" placeholder="Age" />
            </div>
            <div class="form-group">
                <label id="email-label" for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your Email" required />
            </div>
            <div class="form-group">
                <label id="phone-label" for="phone">Phone Number</label>
                <input type="number" name="phone" id="phone" class="form-control" placeholder="Enter your Phone Number" required />
            </div>
            <div class="form-group">
                <label id="password" for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Create your password" required />
            </div>
            <div class="form-group">
                <label id="profile-picture" for="profile-picture">Profile Picture</label>
                <input type="file" name="profile" id="profile-picture" class="form-control" accept="image/*" required />
            </div>
            <div class="form-group">
                <button type="submit" id="submit" name="submit" class="submit-button">Submit</button>
            </div>
            <div class="form-group">
                <p>Already have an account? <a href="login.php" class="hyperlink">Login</a></p>
            </div>
        </form>
    </div>
</body>

</html>
