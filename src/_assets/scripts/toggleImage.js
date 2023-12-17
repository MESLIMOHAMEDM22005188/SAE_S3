// toggleImage.js
document.addEventListener("DOMContentLoaded", function() {
    const imageButton = document.getElementById('imageButton');
    imageButton.addEventListener('click', toggleButton);

    let isHomeVisible = true;

    function toggleButton() {
        fetch('/path-to-controller/HomeController.php')
            .then(response => response.json())
            .then(data => {
                const homeButton = document.getElementById('homeButton');
                homeButton.src = data.newImageSrc;
            });
    }
});
