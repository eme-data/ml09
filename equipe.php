<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipe | ml09</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/equipe.css">
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
    const API_URL_ENTETE = "https://ml09.org/ml09_wp/wp-json/wp/v2/equipe_page?embed&acf_format=standard";
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

        <h1 style="color: var(--bleu_cerulean);">Notre équipe</h1><br>
        <div id="texte_container"></div>

        <script>
            fetch('https://ml09.org/ml09_wp/wp-json/wp/v2/equipe_page?embed&acf_format=standard')
            .then(res => res.json())
            .then(data => {
            document.getElementById('texte_container').innerHTML = data[0].acf.texte;
            })
            .catch(err => console.error('Erreur lors du chargement du texte :', err));
        </script>
        

        <!------------------------------------------------------- equipe -------------------------------------------------->


        <div class="box_equipe">

            <!-- <div class="equipe">
                <div class="equipe-top">
                    <p>Président</p>
                    <img src="" alt="">
                    <h2>Olivier Raton</h2>
                </div>

                <div class="equipe-bottom">
                    <p><i class="fa-solid fa-phone icon"></i>&nbspTelephone</p>
                    <p><i class="fa-solid fa-envelope icon"></i>&nbspmail</p>
                </div>
            </div>

            <div class="equipe">
                <div class="equipe-top">
                    <p>Président</p>
                    <img src="" alt="">
                    <h2>Olivier Raton</h2>
                </div>

                <div class="equipe-bottom">
                    <p><i class="fa-solid fa-phone icon"></i>&nbspTelephone</p>
                    <p><i class="fa-solid fa-envelope icon"></i>&nbspmail</p>
                </div>
            </div>

            <div class="equipe">
                <div class="equipe-top">
                    <p>Président</p>
                    <img src="" alt="">
                    <h2>Olivier Raton</h2>
                </div>

                <div class="equipe-bottom">
                    <p><i class="fa-solid fa-phone icon"></i>&nbspTelephone</p>
                    <p><i class="fa-solid fa-envelope icon"></i>&nbspmail</p>
                </div>
            </div>

            <div class="equipe">
                <div class="equipe-top">
                    <p>Président</p>
                    <img src="" alt="">
                    <h2>Olivier Raton</h2>
                </div>

                <div class="equipe-bottom">
                    <p><i class="fa-solid fa-phone icon"></i>&nbspTelephone</p>
                    <p><i class="fa-solid fa-envelope icon"></i>&nbspmail</p>
                </div>
            </div> -->

            <script src="js/equipe.js"></script>



        </div>

        <!------------------------------------------------------- Map -------------------------------------------------->



        <div class="map">
            <div id="map"></div>
        </div>

        <script src="js/carte_toute.js"></script>

    </main>




    <?php
    include 'footer.php';
    ?>


</body>

</html>