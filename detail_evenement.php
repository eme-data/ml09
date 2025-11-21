<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details evenement</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" href="css/detail_evenement.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/menu.css">
</head>


<body>

 <?php
    include 'menu.php';
?>



<div class="evenement-detail">

        <script>
            // ------------------------------------ Détails d’un evenement ------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    const evenementId = new URLSearchParams(window.location.search).get('id');

    if (evenementId) {
        fetch("http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/evenement?embed&acf_format=standard")
            .then(response => {
                if (!response.ok) throw new Error("Erreur de requête");
                return response.json();
            })
            .then(data => {
                const evenement = data.find(a => a.id == evenementId);
                if (evenement) {
                    const detail = document.querySelector('.evenement-detail');
                    if (detail) {
                        // Vérifier si l'image existe et n'est pas false
                        let imageHTML = '';
                        if (evenement.acf.img && evenement.acf.img !== false && evenement.acf.img.url) {
                            imageHTML = `
                                <div class="image-wrapper">
                                    <img src="${evenement.acf.img.url}" alt="${evenement.acf.img.alt || ''}">
                                </div><br><br>
                            `;
                        }

                        detail.innerHTML = `
                            <a href=javascript:history.go(-1)>
                                <i class="fa-solid fa-circle-arrow-left" style="font-size: 30px;"></i>
                            </a><br><br>
                            ${imageHTML}
                            <h1>${evenement.acf.nom}</h1>
                            <h3>Date : ${new Date(evenement.acf.date).toLocaleDateString('fr-FR')}</h3>
                            <h3>${evenement.acf.ville}</h3><br>
                            <p class="description_evenement">${evenement.acf.detail}</p>
                        `;
                    }
                } else {
                    console.warn("evenement non trouvé");
                }
            })
            .catch(error => console.error("Erreur chargement détails evenement :", error));
    }
});

        </script>

</div>


<?php
    include 'footer.php';
?>
    
</body>

</html>