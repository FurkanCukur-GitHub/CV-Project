<?php
session_start();
include 'dbconfig.php';

$userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : 0;

$sqlCV = "SELECT * FROM CV WHERE UserID = '$userId'";
$cvResult = $conn->query($sqlCV);
$cvId = 0;

if ($cvResult && $cvResult->num_rows > 0) {
    $row = $cvResult->fetch_assoc();
    $cvId = $row['CVId'];
} else {
    echo "No CV found for the user or error fetching CV";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['deleteCv'])) {
        $conn->query("DELETE FROM Languages WHERE CVId IN (SELECT CVId FROM CV WHERE UserID = " . intval($userId) . ")");

        $conn->query("DELETE FROM WorkExperiences WHERE CVId IN (SELECT CVId FROM CV WHERE UserID = " . intval($userId) . ")");

        $conn->query("DELETE FROM Educations WHERE CVId IN (SELECT CVId FROM CV WHERE UserID = " . intval($userId) . ")");

        $conn->query("DELETE FROM Skills WHERE CVId IN (SELECT CVId FROM CV WHERE UserID = " . intval($userId) . ")");

        $conn->query("DELETE FROM Interests WHERE CVId IN (SELECT CVId FROM CV WHERE UserID = " . intval($userId) . ")");

        $conn->query("DELETE FROM Hobbies WHERE CVId IN (SELECT CVId FROM CV WHERE UserID = " . intval($userId) . ")");

        $conn->query("DELETE FROM `References` WHERE CVId IN (SELECT CVId FROM CV WHERE UserID = " . intval($userId) . ")");

        $conn->query("DELETE FROM CV WHERE UserID = " . intval($userId));

        header("Location: loggedInAccount.php");
        exit();
    }

    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $newWidth = 150;
        $newHeight = 180;

        $image = imagecreatefromstring(file_get_contents($_FILES['picture']['tmp_name']));
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($image), imagesy($image));
        ob_start();
        imagejpeg($resizedImage);
        $imageData = ob_get_clean();
        $imageDataEscaped = mysqli_real_escape_string($conn, $imageData);
        $updatePictureSql = "UPDATE CV SET Picture = '$imageDataEscaped' WHERE UserID=" . $userId;
        $conn->query($updatePictureSql);
        imagedestroy($image);
        imagedestroy($resizedImage);
    }

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $profile = $_POST['profile'];
    $address = $_POST['address'];
    $postCode = $_POST['postCode'];
    $city = $_POST['city'];
    $linkedInURL = $_POST['linkedInURL'];
    $updatePersonalSql = "UPDATE CV SET FirstName = '$firstName', LastName = '$lastName', Email = '$email', PhoneNumber = '$phoneNumber', `Profile` = '$profile', `Address` = '$address', PostCode = '$postCode', City = '$city', LinkedInURL = '$linkedInURL' WHERE UserID=" . intval($userId);
    $conn->query($updatePersonalSql);

    if (isset($_POST['LanguageNames'])) {
        $languageNames = $_POST['LanguageNames'];
        $languageLevels = $_POST['LanguageLevels'];
        $deleteLanguagesSql = "DELETE FROM Languages WHERE CVId = '$cvId'";
        $conn->query($deleteLanguagesSql);
        for ($i = 0; $i < count($languageNames); $i++) {
            $sqlInsertLanguage = "INSERT INTO Languages (CVId, LanguageName, ProficiencyLevel) VALUES ('$cvId', '{$languageNames[$i]}', '{$languageLevels[$i]}')";
            $conn->query($sqlInsertLanguage);
        }
    } else {
        $deleteAllLanguagesSql = "DELETE FROM Languages WHERE CVId = '$cvId'";
        $conn->query($deleteAllLanguagesSql);
    }

    if (isset($_POST['WorkExperienceNames'])) {
        $experienceNames = $_POST['WorkExperienceNames'];
        $roles = $_POST['WorkExperienceRoles'];
        $startDates = $_POST['WorkExperienceStartDates'];
        $endDates = $_POST['WorkExperienceEndDates'];
        $deleteWorkExperiencesSql = "DELETE FROM WorkExperiences WHERE CVId = '$cvId'";
        $conn->query($deleteWorkExperiencesSql);
        for ($i = 0; $i < count($experienceNames); $i++) {
            $sqlInsertWorkExperience = "INSERT INTO WorkExperiences (CVId, CompanyName, `Role`, StartDate, EndDate) VALUES ('$cvId', '{$experienceNames[$i]}', '{$roles[$i]}', '{$startDates[$i]}', '{$endDates[$i]}')";
            $conn->query($sqlInsertWorkExperience);
        }
    } else {
        $deleteAllWorkExperiencesSql = "DELETE FROM WorkExperiences WHERE CVId = '$cvId'";
        $conn->query($deleteAllWorkExperiencesSql);
    }

    if (isset($_POST['EducationNames'])) {
        $educationNames = $_POST['EducationNames'];
        $degrees = $_POST['EducationDegrees'];
        $startYears = $_POST['EducationStartYears'];
        $endYears = $_POST['EducationEndYears'];
        $deleteEducationsSql = "DELETE FROM Educations WHERE CVId = '$cvId'";
        $conn->query($deleteEducationsSql);
        for ($i = 0; $i < count($educationNames); $i++) {
            $sqlInsertEducation = "INSERT INTO Educations (CVId, InstitutionName, Degree, StartYear, EndYear) VALUES ('$cvId', '{$educationNames[$i]}', '{$degrees[$i]}', '{$startYears[$i]}', '{$endYears[$i]}')";
            $conn->query($sqlInsertEducation);
        }
    } else {
        $deleteAllEducationsSql = "DELETE FROM Educations WHERE CVId = '$cvId'";
        $conn->query($deleteAllEducationsSql);
    }

    if (isset($_POST['SkillNames'])) {
        $skillNames = $_POST['SkillNames'];
        $proficiencyLevels = $_POST['SkillLevels'];
        $deleteSkillsSql = "DELETE FROM Skills WHERE CVId = '$cvId'";
        $conn->query($deleteSkillsSql);
        for ($i = 0; $i < count($skillNames); $i++) {
            $sqlInsertSkill = "INSERT INTO Skills (CVId, SkillName, ProficiencyLevel) VALUES ('$cvId', '{$skillNames[$i]}', '{$proficiencyLevels[$i]}')";
            $conn->query($sqlInsertSkill);
        }
    } else {
        $deleteAllSkillsSql = "DELETE FROM Skills WHERE CVId = '$cvId'";
        $conn->query($deleteAllSkillsSql);
    }

    if (isset($_POST['InterestNames'])) {
        $interests = $_POST['InterestNames'];
        $deleteInterestsSql = "DELETE FROM Interests WHERE CVId = '$cvId'";
        $conn->query($deleteInterestsSql);
        foreach ($interests as $interest) {
            $sqlInsertInterest = "INSERT INTO Interests (CVId, Interest) VALUES ('$cvId', '$interest')";
            $conn->query($sqlInsertInterest);
        }
    } else {
        $deleteAllInterestsSql = "DELETE FROM Interests WHERE CVId = '$cvId'";
        $conn->query($deleteAllInterestsSql);
    }

    if (isset($_POST['HobbyNames'])) {
        $hobbies = $_POST['HobbyNames'];
        $deleteHobbiesSql = "DELETE FROM Hobbies WHERE CVId = '$cvId'";
        $conn->query($deleteHobbiesSql);
        foreach ($hobbies as $hobby) {
            $sqlInsertHobby = "INSERT INTO Hobbies (CVId, Hobby) VALUES ('$cvId', '$hobby')";
            $conn->query($sqlInsertHobby);
        }
    } else {
        $deleteAllHobbiesSql = "DELETE FROM Hobbies WHERE CVId = '$cvId'";
        $conn->query($deleteAllHobbiesSql);
    }

    if (isset($_POST['ReferenceNames'])) {
        $referenceNames = $_POST['ReferenceNames'];
        $positions = $_POST['ReferencePositions'];
        $contactInfos = $_POST['ReferenceContactInformations'];
        $deleteReferencesSql = "DELETE FROM `References` WHERE CVId = '$cvId'";
        $conn->query($deleteReferencesSql);
        for ($i = 0; $i < count($referenceNames); $i++) {
            $sqlInsertReference = "INSERT INTO `References` (CVId, ReferenceName, Position, ContactInformation) VALUES ('$cvId', '{$referenceNames[$i]}', '{$positions[$i]}', '{$contactInfos[$i]}')";
            $conn->query($sqlInsertReference);
        }
    } else {
        $deleteAllReferencesSql = "DELETE FROM `References` WHERE CVId = '$cvId'";
        $conn->query($deleteAllReferencesSql);
    }

    header("Location: loggedInAccount.php");
} else {
    echo "No form data received.";
}

$conn->close();
