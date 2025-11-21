<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAEJ</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" href="css/paej.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/menu.css">
</head>

<body>

        <?php
    include 'menu.php';
    ?>

    <!------------------------------------------------------ HEADER DE LA PAGE ------------------------------------------------------->

    <header>
    <div class="img_header">
        <img id="img_entete" src="" alt="Image d'en-tête">
    </div>
</header>

<script>
    const API_URL_ENTETE = "http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/paej?embed&acf_format=standard";
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


    <section class="p_presentation" >
       
    
        <h2>Anonyme - Confidentiel - Gratuit</h2><br>
        <div id="p_presentation"></div>

        <script>
            fetch('http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/paej?embed&acf_format=standard')
            .then(res => res.json())
            .then(data => {
            document.getElementById('p_presentation').innerHTML = data[0].acf.presentation;
            })
            .catch(err => console.error('Erreur lors du chargement du texte :', err));
        </script>

    </section>

    <section class="vignette_box">

        <div class="haut_v">
            <div class="vignette bleu_claire">
                <p>Santé</p>
            </div>

            <div class="vignette bleu_noir">
                <p>Addiction</p>
            </div>

            <div class="vignette jaune">
                <p>Sexualité</p>
            </div>
        </div>

        <div class="bas_v">
            <div class="vignette bleu_gris">
                <p>Mal-être</p>
            </div>

            <div class="vignette bleu_ciel">
                <p>Avenir</p>
            </div>

            <div class="vignette bleu_noir">
                <p>Isolement</p>
            </div>
        </div>

    </section>


    <section class="Horaire" >
        <h2>Horaires</h2>
        <div id="horaire"></div>

        <script>
            fetch('http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/paej?embed&acf_format=standard')
            .then(res => res.json())
            .then(data => {
            document.getElementById('horaire').innerHTML = data[0].acf.horaire;
            })
            .catch(err => console.error('Erreur lors du chargement du texte :', err));
        </script>

    </section>

    <section class="contact" >
        <h2>Contactez le PAEJ</h2>

        <div id="contact"></div>

            <script>
            fetch('http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/paej?embed&acf_format=standard')
            .then(res => res.json())
            .then(data => {
            document.getElementById('contact').innerHTML = data[0].acf.contact;
            })
            .catch(err => console.error('Erreur lors du chargement du texte :', err));
        </script>

    </section>

    <div class="map">
        <div id="map"></div>

        <script src="js/carte_paej.js?v=<?php echo time(); ?>"></script>
    </div>

        <?php
    include 'footer.php';
    ?>

</body>

</html>