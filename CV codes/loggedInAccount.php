<?php

session_start();

include 'dbconfig.php';

$userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : 0;
if (!$userId) {
    die("User ID is not set in the session.");
}

$hasCV = false;
$cvCheckSql = "SELECT CVId FROM CV WHERE UserID = '$userId'";
$cvCheckResult = $conn->query($cvCheckSql);
if ($cvCheckResult->num_rows > 0) {
    $hasCV = true;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Profiles</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="loggedInAccount.css">
</head>

<body>
    <div class="row top">

        <div class="col-lg-4 col-md-4">
            <form id="searchForm">
                <input type="text" id="searchText" placeholder="Search CVs...">
                <button type="submit" name="search" class="button">Search</button>
            </form>
        </div>


        <div class="col-lg-2 col-md-2">
            <?php if ($hasCV) : ?>
                <h2 id="myCV"><a href="myCv.php">My CV</a></h2>
            <?php else : ?>

            <?php endif; ?>

        </div>

        <div class="col-lg-2 col-md-2">
            <?php if ($hasCV) : ?>
                <h2 id="updateCV"><a href="updateCv.php">Update My CV</a></h2>
            <?php else : ?>

            <?php endif; ?>
        </div>

        <div class="col-lg-2 col-md-2">
            <?php if ($hasCV) : ?>

            <?php else : ?>
                <h2 id="addCV"><a href="addCv.html">Add CV</a></h2>
            <?php endif; ?>
        </div>


        <div class="col-lg-2 col-md-2">
            <form action="logout.php" method="post">
                <button type="submit" class="button">Log Out</button>
            </form>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-4 col-md-4"></div>
        <div class="col-lg-4 col-md-4">
            <br>
            <header>Professional Profiles</header>
            <br>
        </div>
        <div class="col-lg-4 col-md-4"></div>

    </div>

    <div class="row">

        <div class="col-lg-1 col-md-1"></div>

        <div class="col-lg-10 col-md-10">

            <div id="profile-cards-container"></div>

        </div>

        <div class="col-lg-1 col-md-1"></div>

    </div>

    <script src="loadSearchProfiles.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>