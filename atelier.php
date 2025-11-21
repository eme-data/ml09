<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atelier</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/atelier.css">
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
    const API_URL_ENTETE = "http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/atelier_texte?embed&acf_format=standard";
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
        <section class="texte_atelier">

        <h1 style="color: var(--bleu_cerulean);">Les ateliers de la Mission Locale Jeune Ariège</h1><br>

        <div id="texte_container"></div>

        <script>
            fetch('http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/atelier_texte?embed&acf_format=standard')
            .then(res => res.json())
            .then(data => {
            document.getElementById('texte_container').innerHTML = data[0].acf.texte;
            })
            .catch(err => console.error('Erreur lors du chargement du texte :', err));
        </script>

        <a class="bnt_jaune" href="contact.php">Contactez-nous</a>

        </section>

        <section class="actualite">

        <h2>Les ateliers !</h2><br>

        <select id="atelier-filter">
            <option value="all">Toutes les antennes</option>
            <option value="Pamiers">Pamiers</option>
            <option value="Foix">Foix</option>
            <option value="Saint-Girons">Saint-Girons</option>
            <option value="Lavelanet">Lavelanet</option>
        </select>

        <div class="box_actu"></div>
        </section>

        <script src="js/code_atelier.js"></script>



    </main>

    <?php
    include 'footer.php';
    ?>


</body>

</html>