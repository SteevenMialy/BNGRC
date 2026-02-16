var delivrer = document.getElementById("delivrer");

delivrer.addEventListener("click", function() {
    fetch(BASE_URL + '/besoin/delivrer', {
        method: 'GET'
    })    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Besoins marqués comme délivrés avec succès !');
            location.reload(); // Recharger la page pour mettre à jour la liste
        } else {
            alert('Erreur lors de la mise à jour des besoins.');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de la requête.');
    });

});