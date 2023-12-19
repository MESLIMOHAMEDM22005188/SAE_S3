document.addEventListener('DOMContentLoaded', function() {
    const imageGB = document.getElementById('image_GB');
    if (imageGB) {
        imageGB.addEventListener('click', function() {
            loadTimetable('8398'); // Utilisez ici le code ADE approprié
        });
    }
});

function loadTimetable(groupCode)  {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=' + groupCode, true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText); // Log the ICS data or process it as needed
        }
    };

    xhr.send();
}
