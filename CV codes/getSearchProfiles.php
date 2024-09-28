<?php

include 'dbconfig.php';

$searchText = isset($_GET['searchText']) ? $conn->real_escape_string($_GET['searchText']) : '';

$query = "SELECT DISTINCT CV.CVId, CV.FirstName, CV.LastName, CV.Picture FROM CV
              LEFT JOIN Languages ON CV.CVId = Languages.CVId
              LEFT JOIN WorkExperiences ON CV.CVId = WorkExperiences.CVId
              LEFT JOIN Educations ON CV.CVId = Educations.CVId
              LEFT JOIN Skills ON CV.CVId = Skills.CVId
              LEFT JOIN Interests ON CV.CVId = Interests.CVId
              LEFT JOIN Hobbies ON CV.CVId = Hobbies.CVId
              LEFT JOIN `References` ON CV.CVId = `References`.CVId
              WHERE CV.Profile LIKE '%$searchText%'
              OR CV.FirstName LIKE '%$searchText%'
              OR CV.LastName LIKE '%$searchText%'
              OR CV.Email LIKE '%$searchText%'
              OR CV.PhoneNumber LIKE '%$searchText%'
              OR CV.Address LIKE '%$searchText%'
              OR CV.PostCode LIKE '%$searchText%'
              OR CV.City LIKE '%$searchText%'
              OR CV.LinkedInURL LIKE '%$searchText%'
              OR Languages.LanguageName LIKE '%$searchText%'
              OR Languages.ProficiencyLevel LIKE '%$searchText%'
              OR WorkExperiences.CompanyName LIKE '%$searchText%'
              OR WorkExperiences.Role LIKE '%$searchText%'
              OR Educations.InstitutionName LIKE '%$searchText%'
              OR Educations.Degree LIKE '%$searchText%'
              OR Skills.SkillName LIKE '%$searchText%'
              OR Skills.ProficiencyLevel LIKE '%$searchText%'
              OR Interests.Interest LIKE '%$searchText%'
              OR Hobbies.Hobby LIKE '%$searchText%'
              OR `References`.ReferenceName LIKE '%$searchText%'
              OR `References`.Position LIKE '%$searchText%'
              OR `References`.ContactInformation LIKE '%$searchText%'";

$result = $conn->query($query);

$profiles = array();

while ($row = $result->fetch_assoc()) {
    $profile = array(
        'CVId' => $row['CVId'],
        'FirstName' => $row['FirstName'],
        'LastName' => $row['LastName'],
        'Picture' => base64_encode($row['Picture'])
    );
    array_push($profiles, $profile);
}

header('Content-Type: application/json');
echo json_encode($profiles);

$conn->close();
