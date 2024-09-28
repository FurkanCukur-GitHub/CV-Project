<?php
session_start();

include 'dbconfig.php';

$userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : 0;

if (!$userId) {
    die("User ID is not set in the session.");
}

$cvData = [];

$cvQuery = "SELECT * FROM CV WHERE UserID = " . intval($userId);
if ($cvResult = $conn->query($cvQuery)) {
    $cvData = $cvResult->fetch_assoc();
} else {
    die("Error retrieving CV: " . $conn->error);
}

$languagesQuery = "SELECT * FROM Languages WHERE CVId = " . $cvData['CVId'];
$languagesResult = $conn->query($languagesQuery);

$workExperiencesQuery = "SELECT * FROM WorkExperiences WHERE CVId = " . $cvData['CVId'];
$workExperiencesResult = $conn->query($workExperiencesQuery);

$educationsQuery = "SELECT * FROM Educations WHERE CVId = " . $cvData['CVId'];
$educationsResult = $conn->query($educationsQuery);

$skillsQuery = "SELECT * FROM Skills WHERE CVId = " . $cvData['CVId'];
$skillsResult = $conn->query($skillsQuery);

$interestsQuery = "SELECT * FROM Interests WHERE CVId = " . $cvData['CVId'];
$interestsResult = $conn->query($interestsQuery);

$hobbiesQuery = "SELECT * FROM Hobbies WHERE CVId = " . $cvData['CVId'];
$hobbiesResult = $conn->query($hobbiesQuery);

$referencesQuery = "SELECT * FROM `References` WHERE CVId = " . $cvData['CVId'];
$referencesResult = $conn->query($referencesQuery);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User's CV</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="updateCv.css">
</head>

<body>

    <h1></h1>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <h1><strong>Update CV</strong></h1>
            <br><br>
        </div>
    </div>

    <div class="row updateCv">

        <div class="col-lg-10 col-md-10">

            <form method="post" action="updateCvData.php" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <h5>Picture</h5>
                        <input type="file" name="picture" accept="image/*"><br><br>

                        <h5>First Name</h5>
                        <input type="text" name="firstName" value="<?php echo ($cvData['FirstName']); ?>" required><br><br>

                        <h5>Last Name</h5>
                        <input type="text" name="lastName" value="<?php echo ($cvData['LastName']); ?>" required><br><br>

                        <h5>Email</h5>
                        <input type="email" name="email" value="<?php echo ($cvData['Email']); ?>"><br><br>

                        <h5>Phone Number</h5>
                        <input type="text" name="phoneNumber" value="<?php echo ($cvData['PhoneNumber']); ?>"><br><br>

                        <h5>Profile</h5>
                        <textarea name="profile"><?php echo ($cvData['Profile']); ?></textarea><br><br>

                        <h5>Address</h5>
                        <input type="text" name="address" value="<?php echo ($cvData['Address']); ?>"><br><br>

                        <h5>Post Code</h5>
                        <input type="text" name="postCode" value="<?php echo ($cvData['PostCode']); ?>"><br><br>

                        <h5>City</h5>
                        <input type="text" name="city" value="<?php echo ($cvData['City']); ?>"><br><br>

                        <h5>LinkedIn URL</h5>
                        <input type="url" name="linkedInURL" value="<?php echo ($cvData['LinkedInURL']); ?>"><br><br>

                    </div>


                    <div class="col-lg-9 col-md-9 col-sm-12">
                        <div>
                            <h5>Languages</h5>
                            <p><button type="button" onclick="addElement('languagesContainer', 'Language', [{ type: 'text', nameSuffix: 'Levels[]' }])">Add Language</button>
                                <button type="button" onclick="deleteElement('languagesContainer')">Delete Language</button>
                            </p>
                            <div id="languagesContainer">
                                <?php
                                while ($language = $languagesResult->fetch_assoc()) : ?>
                                    <div>
                                        <span>Language :</span>
                                        <input type="text" name="LanguageNames[]" value="<?php echo htmlspecialchars($language['LanguageName']); ?>">
                                        <span>Level :</span>
                                        <input type="text" name="LanguageLevels[]" value="<?php echo htmlspecialchars($language['ProficiencyLevel']); ?>">
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>

                        <br>

                        <div>
                            <h5>Work Experiences</h5>
                            <p><button type="button" onclick="addElement('workExperiencesContainer', 'WorkExperience', [{ type: 'text', nameSuffix: 'Roles[]' }, { type: 'date', nameSuffix: 'StartDates[]' }, { type: 'date', nameSuffix: 'EndDates[]' }])">Add
                                    Work Experience</button>
                                <button type="button" onclick="deleteElement('workExperiencesContainer')">Delete Work Experience</button>
                            </p>
                            <div id="workExperiencesContainer">
                                <?php while ($workExperience = $workExperiencesResult->fetch_assoc()) : ?>
                                    <div>
                                        <span>WorkExperience :</span>
                                        <input type="text" name="WorkExperienceNames[]" value="<?php echo htmlspecialchars($workExperience['CompanyName']); ?>">
                                        <span>Role :</span>
                                        <input type="text" name="WorkExperienceRoles[]" value="<?php echo htmlspecialchars($workExperience['Role']); ?>">
                                        <span>StartDate :</span>
                                        <input type="date" name="WorkExperienceStartDates[]" value="<?php echo htmlspecialchars($workExperience['StartDate']); ?>">
                                        <span>EndDate :</span>
                                        <input type="date" name="WorkExperienceEndDates[]" value="<?php echo htmlspecialchars($workExperience['EndDate']); ?>">
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>

                        <br>

                        <div>
                            <h5>Educations</h5>
                            <p><button type="button" onclick="addElement('educationsContainer', 'Education', [{ type: 'text', nameSuffix: 'Degrees[]' }, { type: 'number', nameSuffix: 'StartYears[]' }, { type: 'number', nameSuffix: 'EndYears[]' }])">Add Education</button>
                                <button type="button" onclick="deleteElement('educationsContainer')">Delete Education</button>
                            </p>
                            <div id="educationsContainer">
                                <?php while ($education = $educationsResult->fetch_assoc()) : ?>
                                    <div>
                                        <span>Education :</span>
                                        <input type="text" name="EducationNames[]" value="<?php echo htmlspecialchars($education['InstitutionName']); ?>">
                                        <span>Degree :</span>
                                        <input type="text" name="EducationDegrees[]" value="<?php echo htmlspecialchars($education['Degree']); ?>">
                                        <span>StartYear :</span>
                                        <input type="year" name="EducationStartYears[]" value="<?php echo htmlspecialchars($education['StartYear']); ?>">
                                        <span>EndYear :</span>
                                        <input type="year" name="EducationEndYears[]" value="<?php echo htmlspecialchars($education['EndYear']); ?>">
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>

                        <br>

                        <div>
                            <h5>Skills</h5>
                            <p><button type="button" onclick="addElement('skillsContainer', 'Skill', [{ type: 'text', nameSuffix: 'Levels[]' }])">Add Skill</button>
                                <button type="button" onclick="deleteElement('skillsContainer')">Delete Skill</button>
                            </p>
                            <div id="skillsContainer">
                                <?php while ($skill = $skillsResult->fetch_assoc()) : ?>
                                    <div>
                                        <span>Skill :</span>
                                        <input type="text" name="SkillNames[]" value="<?php echo htmlspecialchars($skill['SkillName']); ?>">
                                        <span>Level :</span>
                                        <input type="text" name="SkillLevels[]" value="<?php echo htmlspecialchars($skill['ProficiencyLevel']); ?>">
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>

                        <br>

                        <div>
                            <h5>Interests</h5>
                            <p><button type="button" onclick="addElement('interestsContainer', 'Interest')">Add Interest</button>
                                <button type="button" onclick="deleteElement('interestsContainer')">Delete Interest</button>
                            </p>
                            <div id="interestsContainer">
                                <?php while ($interest = $interestsResult->fetch_assoc()) : ?>
                                    <div>
                                        <span>Interest :</span>
                                        <input type="text" name="InterestNames[]" value="<?php echo htmlspecialchars($interest['Interest']); ?>">
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>

                        <br>

                        <div>
                            <h5>Hobbies</h5>
                            <p><button type="button" onclick="addElement('hobbiesContainer', 'Hobby')">Add Hobby</button>
                                <button type="button" onclick="deleteElement('hobbiesContainer')">Delete Hobby</button>
                            </p>
                            <div id="hobbiesContainer">
                                <?php while ($hobby = $hobbiesResult->fetch_assoc()) : ?>
                                    <div>
                                        <span>Hobby :</span>
                                        <input type="text" name="HobbyNames[]" value="<?php echo htmlspecialchars($hobby['Hobby']); ?>">
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>

                        <br>

                        <div>
                            <h5>References</h5>
                            <p><button type="button" onclick="addElement('referencesContainer', 'Reference', [{ type: 'text', nameSuffix: 'Positions[]' }, { type: 'text', nameSuffix: 'ContactInformations[]' }])">Add Reference</button>
                                <button type="button" onclick="deleteElement('referencesContainer')">Delete Reference</button>
                            </p>
                            <div id="referencesContainer">
                                <?php while ($reference = $referencesResult->fetch_assoc()) : ?>
                                    <div>
                                        <span>Reference :</span>
                                        <input type="text" name="ReferenceNames[]" value="<?php echo htmlspecialchars($reference['ReferenceName']); ?>">
                                        <span>Position :</span>
                                        <input type="text" name="ReferencePositions[]" value="<?php echo htmlspecialchars($reference['Position']); ?>">
                                        <span>ContactInformation :</span>
                                        <input type="text" name="ReferenceContactInformations[]" value="<?php echo htmlspecialchars($reference['ContactInformation']); ?>">
                                    </div>
                                <?php endwhile; ?>
                            </div>
                            <br><br>
                        </div>
                    </div>

                    <br>

                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-4"></div>
                    <div class="col-lg-4 col-md-4">
                        <input type="submit" value="Update CV">
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <input type="submit" name="deleteCv" value="Delete CV">
                    </div>
                </div>
                <br><br>
            </form>

            <script src="addDeleteButton.js"></script>

            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.9.2/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>