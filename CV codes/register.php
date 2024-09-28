<?php
session_start();

include 'dbconfig.php';

$user_username = mysqli_real_escape_string($conn, $_POST['username']);
$user_email = mysqli_real_escape_string($conn, $_POST['email']);
$user_password = mysqli_real_escape_string($conn, $_POST['password']);

$sql_check = "SELECT * FROM Users WHERE Username = '$user_username' OR Email = '$user_email'";
$check_result = $conn->query($sql_check);

if ($check_result->num_rows > 0) {
    echo "This username or email address is already in use!";
    header("Refresh:2; url=register.html");
} else {
    $sql_insert = "INSERT INTO Users (Username, Email, Password) VALUES ('$user_username', '$user_email', SHA('$user_password'))";

    if ($conn->query($sql_insert) === TRUE) {
        $_SESSION['userId'] = $conn->insert_id;
    }
    
    header("Location: loggedInAccount.php");
}

$conn->close();
?>
