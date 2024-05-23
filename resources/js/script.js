//Supprimme une activité sous un message form Windows (appel deleteActivity.php dans le controllers)
function confirmDelete(activityId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cette activité ?")) {
        // Utilisation de l'API Fetch pour envoyer une requête DELETE au serveur
        fetch('../../controllers/deleteActivity.php?id=' + activityId, {
            method: 'DELETE'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('La suppression de l\'activité a échoué.');
            }
            // Recharger la page une fois que l'activité est supprimée
            window.location.reload();
        })
        .catch(error => {
            console.error('Erreur lors de la suppression de l\'activité:', error);
            // Afficher un message d'erreur à l'utilisateur
            alert('Une erreur est survenue lors de la suppression de l\'activité.');
        });
    }
}