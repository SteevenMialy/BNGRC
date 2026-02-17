var delivrer = document.getElementById("delivrer");
var reinitialiser = document.getElementById("reinitialiser");

delivrer.addEventListener("click", function() {
    fetch(BASE_URL + '/besoin/delivrer', {
        method: 'GET'
    })    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
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

reinitialiser.addEventListener("click", function() {
    if (confirm("Êtes-vous sûr de vouloir réinitialiser les données ?")) {
        fetch(BASE_URL + '/data/reinitialize', {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload(); // Recharger la page pour mettre à jour la liste
            } else {
                alert(data.error || 'Erreur lors de la réinitialisation des données.');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la requête.');
        });
    }
});