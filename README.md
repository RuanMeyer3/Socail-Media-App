# Social Media App

## Description
This is a simple social media app where users can register, log in, view a timeline of posts, like posts, view profiles, search for users, and send messages. The app is built using HTML, PHP, JavaScript, and CSS, and it runs on a WAMP server with SQL and phpMyAdmin for database management.

## Features

### User Registration and Login:
- Users begin by logging in with their email.
- If the email is not found in the database, the user is directed to a registration page.
- The registration page allows users to create an account by providing their personal details and uploading a profile picture.

### Timeline:
- Once logged in, users can view a timeline of all posts sorted by the most recent.
- Users can like posts on the timeline.

### Profiles and Messaging:
- Users can click on a profile picture on the timeline to view a profile page.
- Users can send messages to other users directly from their profiles.

## Technology Stack
- **Frontend**: HTML, CSS
- **Backend**: PHP
- **Database**: MySQL, managed through phpMyAdmin
- **Server**: WAMP (Windows, Apache, MySQL, PHP)

## Installation
1. Download and install **WAMP server**.
2. Clone or download the project files and place them in the `www` directory of your WAMP server.
3. Start the WAMP server and open **phpMyAdmin**.
4. Create a new database for the app and import the SQL script to set up the necessary tables (e.g., users, posts, likes).
5. Update the **database connection settings** in the PHP files to match your local environment.

# Database Structure

The database name is **socialhub**. Within the database, the following tables are present:

## `users` Table
- **user_id**: INT, PRIMARY KEY
- **name**: VARCHAR(25)
- **surname**: VARCHAR(25)
- **email**: VARCHAR(50)
- **password**: VARCHAR(50)
- **phone**: INT
- **profile**: VARCHAR(255)
- **username**: VARCHAR(50)
- **age**: INT

## `posts` Table
- **post_id**: INT, PRIMARY KEY
- **about**: VARCHAR(255)
- **image**: VARCHAR(255)
- **timestamp**: TIMESTAMP
- **username**: VARCHAR(255)
- **profile**: VARCHAR(255)

## `messages` Table
- **messages_id**: INT, PRIMARY KEY
- **sender_id**: INT, FOREIGN KEY
- **recipient_id**: INT, FOREIGN KEY
- **content**: TEXT
- **timestamp**: TIMESTAMP

## `likes` Table
- **id**: INT, PRIMARY KEY
- **post_id**: INT, FOREIGN KEY
- **user_id**: INT, FOREIGN KEY
- **liked**: TINYINT
- **timestamp**: TIMESTAMP
