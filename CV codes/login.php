<?php
session_start();

include 'dbconfig.php';

$user_username = mysqli_real_escape_string($conn, $_POST['username']);
$user_password = mysqli_real_escape_string($conn, $_POST['password']);

$sql = "SELECT * FROM Users WHERE Username = '$user_username' AND `Password` = SHA('$user_password')";
$result = $conn->query($sql);

if ($result->num_rows == TRUE) {
    $row = $result->fetch_assoc();
    $_SESSION['userId'] = $row['UserID'];

    header("Location: loggedInAccount.php");
} else {
    echo "Wrong username or password!";
    header("Refresh:2; url=login.html");
}

$conn->close();
?>

