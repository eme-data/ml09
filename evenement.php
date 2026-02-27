<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evénements | ml09</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/evenement.css">
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
    const API_URL_ENTETE = "https://ml09.org/ml09_wp/wp-json/wp/v2/evenement_texte?embed&acf_format=standard";
    const CACHE_KEY_ENTETE = "img_entete_evenement";
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
        
        <section class="texte_evenement">

        <h1 style="color: var(--bleu_cerulean);">Les événements de la Mission Locale et de ses partenaires</h1><br>

        <div id="texte_container"></div>

        <script>
            fetch('https://ml09.org/ml09_wp/wp-json/wp/v2/evenement_texte?embed&acf_format=standard')
            .then(res => res.json())
            .then(data => {
            if (data && data[0] && data[0].acf) {
                document.getElementById('texte_container').innerHTML = data[0].acf.texte;
            }
            })
            .catch(err => console.error('Erreur lors du chargement du texte :', err));
        </script>

        
        </section>

        <section class="event">

        <h2>Les événements !</h2><br>

        <div class="box_evenement"></div>
        </section>

        <script>

            // ------------------------------------ Liste des evenements ------------------------------------

    function parseDateACF(dateStr) {
        if (!dateStr) return null;
        if (dateStr.includes('/')) {
            const [day, month, year] = dateStr.split('/').map(Number);
            return new Date(year, month - 1, day);
        }
        if (dateStr.includes('-')) {
            const [year, month, day] = dateStr.split('-').map(Number);
            return new Date(year, month - 1, day);
        }
        if (dateStr.length === 8 && !isNaN(dateStr)) {
            return new Date(parseInt(dateStr.substring(0, 4)), parseInt(dateStr.substring(4, 6)) - 1, parseInt(dateStr.substring(6, 8)));
        }
        return null;
    }

fetch("https://ml09.org/ml09_wp/wp-json/wp/v2/evenement?embed&acf_format=standard&per_page=100")
    .then(response => response.json())
    .then(data => {
        const evenementList = document.querySelector('.box_evenement');

        // Trier par date (du plus proche au plus éloigné)
        const sortedData = data.sort((a, b) => {
            const dateA = parseDateACF(a.acf.date);
            const dateB = parseDateACF(b.acf.date);
            return (dateB || 0) - (dateA || 0);
        });

        // Fonction pour afficher les evenements
        const displayevenements = (evenements) => {
            evenementList.innerHTML = '';
            evenements.forEach(evenement => {
                const dateObj = parseDateACF(evenement.acf.date);
                const formattedDate = (dateObj && !isNaN(dateObj.getTime()))
                    ? dateObj.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long' })
                    : "Date non définie";

                const evenementItem = document.createElement('a');
                evenementItem.href = 'detail_evenement.php?id=' + evenement.id;
                evenementItem.classList.add('evenement-card');
                evenementItem.innerHTML = `

                    <div class="evenement">
                        <p class="date_evenement">
                            <span style="text-transform: uppercase;">
                                <b>${formattedDate}</b>
                            </span>
                        </p>
                        <div class="type_evenement">
                            <p style="font-weight: bold;">${evenement.acf.nom || ''}</p>
                            <p>${evenement.acf.ville || ''}</p>
                        </div>
                    </div>

                `;
                evenementList.appendChild(evenementItem);
            });
        };

        displayevenements(sortedData);

        // Mettre à jour l'URL avec le filtre
        const updateURL = (filter) => {
            const url = new URL(window.location);
            url.searchParams.set('filter', filter);
            window.history.pushState({}, '', url);
        };
    })
    .catch(error => console.error('Erreur lors du chargement des evenements:', error));

        </script>



    </main>

    <?php
    include 'footer.php';
    ?>


</body>

</html>