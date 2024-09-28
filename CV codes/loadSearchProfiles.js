function loadProfileCards() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'getSearchProfiles.php?searchText=' + encodeURIComponent(document.getElementById('searchText').value), true);
    xhr.onload = function () {
        if (this.status == 200) {
            var profiles = JSON.parse(this.responseText);
            var output = '';
            for (var i in profiles) {
                output += '<div class="profile-card" onclick="location.href=\'cv.php?cvid=' + profiles[i].CVId + '\'">' +
                    '<img class="profile-image" src="data:image/jpeg;base64,' + profiles[i].Picture + '"/>' +
                    '<p>' + profiles[i].FirstName + ' ' + profiles[i].LastName + '</p>' +
                    '</div>';
            }
            document.getElementById('profile-cards-container').innerHTML = output;
        }
    }
    xhr.send();
}

window.onload = function() {
    loadProfileCards();
};

document.getElementById('searchForm').addEventListener('submit', function (e) {
    e.preventDefault();
    loadProfileCards();
});


