<?php

session_start();

include 'dbconfig.php';

$userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : 0;

$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$phoneNumber = $_POST['phoneNumber'];
$profile = $_POST['profile'];
$address = $_POST['address'];
$postCode = $_POST['postCode'];
$city = $_POST['city'];
$linkedInURL = $_POST['linkedInURL'];


if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
    
    $originalPicture = imagecreatefromstring(file_get_contents($_FILES['picture']['tmp_name']));
    $newWidth = 150;
    $newHeight = 180;

    $pictureResized = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($pictureResized, $originalPicture, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($originalPicture), imagesy($originalPicture));
    ob_start();
    imagejpeg($pictureResized); 
    $pictureContent = ob_get_clean();
    $pictureContentEscaped = mysqli_real_escape_string($conn, $pictureContent);
    imagedestroy($originalPicture);
    imagedestroy($pictureResized);

    $sql = "INSERT INTO CV (UserID, Picture, FirstName, LastName, Email, PhoneNumber, `Profile`, `Address`, PostCode, City, LinkedInURL) VALUES ('$userId', '$pictureContentEscaped', '$firstName', '$lastName', '$email', '$phoneNumber', '$profile', '$address', '$postCode', '$city', '$linkedInURL')";
} else {
    $sql = "INSERT INTO CV (UserID, FirstName, LastName, Email, PhoneNumber, `Profile`, `Address`, PostCode, City, LinkedInURL) VALUES ('$userId', '$firstName', '$lastName', '$email', '$phoneNumber', '$profile', '$address', '$postCode', '$city', '$linkedInURL')";
}
$conn->query($sql);
$cvId = $conn->insert_id;

if (isset($_POST['LanguageNames'])) {
    $languageNames = $_POST['LanguageNames'];
    $languageLevels = $_POST['LanguageLevels'];
    for ($i = 0; $i < count($languageNames); $i++) {
        $sql = "INSERT INTO Languages (CVId, LanguageName, ProficiencyLevel) VALUES ('$cvId', '{$languageNames[$i]}', '{$languageLevels[$i]}')";
        $conn->query($sql);
    }
}

if (isset($_POST['WorkExperienceNames'])) {
    $experienceNames = $_POST['WorkExperienceNames'];
    $roles = $_POST['WorkExperienceRoles'];
    $startDates = $_POST['WorkExperienceStartDates'];
    $endDates = $_POST['WorkExperienceEndDates'];
    
    for ($i = 0; $i < count($experienceNames); $i++) {
        $sql = "INSERT INTO WorkExperiences (CVId, CompanyName, `Role`, StartDate, EndDate) VALUES ('$cvId', '{$experienceNames[$i]}', '{$roles[$i]}', '{$startDates[$i]}', '{$endDates[$i]}')";
        $conn->query($sql);
    }
}

if (isset($_POST['EducationNames'])) {
    $educationNames = $_POST['EducationNames'];
    $degrees = $_POST['EducationDegrees'];
    $startYears = $_POST['EducationStartYears'];
    $endYears = $_POST['EducationEndYears'];
    for ($i = 0; $i < count($educationNames); $i++) {
        $sql = "INSERT INTO Educations (CVId, InstitutionName, Degree, StartYear, EndYear) VALUES ('$cvId', '{$educationNames[$i]}', '{$degrees[$i]}', '{$startYears[$i]}', '{$endYears[$i]}')";
        $conn->query($sql);
    }
}

if (isset($_POST['SkillNames'])) {
    $skillNames = $_POST['SkillNames'];
    $skillLevels = $_POST['SkillLevels'];
    for ($i = 0; $i < count($skillNames); $i++) {
        $sql = "INSERT INTO Skills (CVId, SkillName, ProficiencyLevel) VALUES ('$cvId', '{$skillNames[$i]}', '{$skillLevels[$i]}')";
        $conn->query($sql);
    }
}

if (isset($_POST['InterestNames'])) {
    $interestNames = $_POST['InterestNames'];
    for ($i = 0; $i < count($interestNames); $i++) {
        $sql = "INSERT INTO Interests (CVId, Interest) VALUES ('$cvId', '{$interestNames[$i]}')";
        $conn->query($sql);
    }
}

if (isset($_POST['HobbyNames'])) {
    $hobbyNames = $_POST['HobbyNames'];
    for ($i = 0; $i < count($hobbyNames); $i++) {
        $sql = "INSERT INTO Hobbies (CVId, Hobby) VALUES ('$cvId', '{$hobbyNames[$i]}')";
        $conn->query($sql);
    }
}

if (isset($_POST['ReferenceNames'])) {
    $referenceNames = $_POST['ReferenceNames'];
    $positions = $_POST['ReferencePositions'];
    $contactInfos = $_POST['ReferenceContactInformations'];
    for ($i = 0; $i < count($referenceNames); $i++) {
        $sql = "INSERT INTO `References` (CVId, ReferenceName, Position, ContactInformation) VALUES ('$cvId', '{$referenceNames[$i]}', '{$positions[$i]}', '{$contactInfos[$i]}')";
        $conn->query($sql);
    }
}

$conn->close();

header("Location: loggedInAccount.php");

?>
