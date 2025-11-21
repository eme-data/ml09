<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premier pas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" href="css/premier_pas.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/menu.css">
</head>

<body>

    <?php
    include 'menu.php';
    ?>



    <header>
    <div class="img_header">
        <img id="img_entete" src="" alt="Image d'en-tête">
    </div>
</header>

<script>
    const API_URL_ENTETE = "http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/texte_premier_pas?acf_format=standard";
    const CACHE_KEY_ENTETE = "img_entete_cache";
    const CACHE_DURATION_ENTETE = 60 * 60 * 1000; // 1 heure

    function setImgEntete(img) {
        const imgHeader = document.getElementById("img_entete");
        imgHeader.src = img.url;
        imgHeader.alt = img.alt || "Image d'en-tête";
    }

    // 1. Vérifier si on a une image en cache
    const cachedEntete = localStorage.getItem(CACHE_KEY_ENTETE);
    if (cachedEntete) {
        const parsed = JSON.parse(cachedEntete);
        if (Date.now() - parsed.timestamp < CACHE_DURATION_ENTETE) {
            setImgEntete(parsed.data);
        }
    }

    // 2. Toujours essayer de rafraîchir en arrière-plan
    fetch(API_URL_ENTETE)
        .then(res => res.json())
        .then(data => {
            if (data && data[0] && data[0].acf && data[0].acf.img_entete) {
                const img = data[0].acf.img_entete;

                // Affiche l'image
                setImgEntete(img);

                // Met en cache
                localStorage.setItem(CACHE_KEY_ENTETE, JSON.stringify({
                    data: img,
                    timestamp: Date.now()
                }));
            }
        })
        .catch(err => console.error("Erreur lors du chargement de l'image header :", err));
</script>



    <main>

        <!------------------------------------------------------- Texte -------------------------------------------------->

        <h1 style="color: var(--bleu_cerulean);">Premier pas à la Mission Locale Jeune Ariège</h1><br>


        <div id="texte_container"></div>

        <script>
            fetch('http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/texte_premier_pas?acf_format=standard')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('texte_container').innerHTML = data[0].acf.texte;
                })
                .catch(err => console.error('Erreur lors du chargement du texte :', err));
        </script>

        <!------------------------------------------------------- Map -------------------------------------------------->

        <div class="map">
            <div id="map"></div>
        </div>

        <script>

            // Lieu de la carte 
            var map = L.map('map').setView([43.02345030435656, 1.6188815119039786], 10);

            // Visuel de la carte 
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);


            // ------------------------------------ point ------------------------------------

            fetch('http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/point?embed&acf_format=standard')
                .then(response => response.json())
                .then(data => {
                    data.forEach(antenne => {
                        const marker = L.marker([antenne.acf.latitude, antenne.acf.longitude]).addTo(map);

                        marker.bindPopup(`
        <h3>${antenne.acf.nom}</h3>
        <p>${antenne.acf.adresse}</p>
        <p>${antenne.acf.horaire}</p>
      `);
                    });
                })
                .catch(error => console.error('Erreur de chargement des antennes :', error));



        </script>

    </main>

    <?php
    include 'footer.php';
    ?>

</body>

</html>