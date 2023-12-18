function changeText() {
    const displayText = document.getElementById('displayText');
    const homeButton = document.getElementById('homeButton');
    const returnImage = document.getElementById('returnImage');

    // Effacer le texte actuel
    displayText.textContent = '';

    // Cacher l'image 'Home_Button'
    homeButton.style.display = 'none';

    // Afficher l'image 'retour'
    returnImage.style.display = 'block';
}

function returnToInitialState() {
    const displayText = document.getElementById('displayText');
    const homeButton = document.getElementById('homeButton');
    const returnImage = document.getElementById('returnImage');

    // Restaurer le texte initial
    displayText.textContent = 'Bienvenue au département Informatique';

    // Afficher l'image 'Home_Button'
    homeButton.style.display = 'block';

    // Cacher l'image 'retour'
    returnImage.style.display = 'none';
}