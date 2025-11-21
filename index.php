<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <link rel="stylesheet" href="css/accueil.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/menu.css">

</head>

<body>

    <?php
    include 'menu.php';
    ?>
    <header>

        <div class="img_header"></div>

        <script>
            fetch("http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/image_accueil?embed&acf_format=standard")
                .then(response => response.json())
                .then(data => {
                    const img_header = document.querySelector('.img_header');

                    // Prendre seulement le premier 
                    const nb_img = data.slice(0, 1);

                    // Afficher les ateliers
                    nb_img.forEach(img => {
                        img_header.innerHTML = `
                <img src="${img.acf.image.url}" alt="${img.acf.image.alt || ''}" width="1200" height="600" fetchpriority="high">
            `;
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des ateliers :', error));
        </script>

        <div class="slogan">
            <h2>Mission Locale Jeune Ariège</h2>
            <p>Un accompagnement avec et <br>pour les jeunes</p> <br>
            <a class="bnt_bleu" href="contact.php">Contactez-nous</a>
        </div>



    </header>

    <!---------------------------------------------------------------------- ACTUALITE --------------------------------------------------------------->

    <section class="actualite">

        <h2>Ateliers</h2>

        <div class="derniere_actu"></div>

        <script>
    fetch("http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/atelier?embed&acf_format=standard")
        .then(response => response.json())
        .then(data => {
            const atelierList = document.querySelector('.derniere_actu');

            // Trier par date_atelier décroissante (du plus récent au plus ancien)
            const sortedData = data.sort((a, b) => {
                const [yearA, monthA, dayA] = a.acf.date_atelier.split('-');
                const [yearB, monthB, dayB] = b.acf.date_atelier.split('-');
                const dateA = new Date(yearA, monthA - 1, dayA);
                const dateB = new Date(yearB, monthB - 1, dayB);
                return dateB - dateA; // plus récent en premier
            });

            // Ne garder que les 2 derniers ateliers
            const latestAteliers = sortedData.slice(0, 2);

            // Afficher les ateliers
            latestAteliers.forEach(atelier => {
                const atelierItem = document.createElement('div');
                atelierItem.classList.add('atelier-card');

                const [year, month, day] = atelier.acf.date_atelier.split('-');
                const formattedDate = new Date(year, month - 1, day);

                atelierItem.innerHTML = `
                    <div class="actu">
                        <p class="date_actu">
                            <span style="text-transform: uppercase;">
                                <b>${formattedDate.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long' })}</b>
                            </span>
                        </p>
                        <div class="type_actu">
                            <h3>Atelier</h3>
                            <p>${atelier.acf.nom}</p><br>
                            <p>${atelier.acf.antenne}</p>
                        </div>
                    </div>
                    <div class="atelier-description" style="display:none;">
                        ${atelier.acf.detail || "Aucune description disponible."}
                    </div>
                `;

                // Gestion du clic pour afficher/masquer la description
                atelierItem.querySelector('.actu').addEventListener('click', () => {
                    const desc = atelierItem.querySelector('.atelier-description');
                    desc.style.display = (desc.style.display === 'block') ? 'none' : 'block';
                });

                atelierList.appendChild(atelierItem);
            });
        })
        .catch(error => console.error('Erreur lors du chargement des ateliers :', error));
</script>



        <div class="toute_actu">
            <a class="bnt_bleu" href="atelier.php"> Tous les Ateliers</a>
        </div>

    </section>




    <!---------------------------------------------------------------------- TEMOIGNAGE --------------------------------------------------------------->

    <section class="temoignage">

        <h2>Témoignages jeunes</h2><br>

        <div class="box_temoignage" id="box_temoignage"></div>
        <!-- 
        <div class="box_temoignage">

            <div class="temoigne">
                <div class="img_jeune">
                    <img src="img/jeune_homme.webp" alt="">
                </div>
                <p>La mission locale m’a aidé à trouver un apprentissage.<br><br>Lucas</p>
            </div>

            <div class="temoigne">
                <p>La mission locale m’a accompagné pour trouver une formation adaptée à mes besoins.<br><br>Aurore</p>
                <div class="img_jeune">
                    <img src="img/jeune.webp" alt="">
                </div>
            </div>

        </div> -->

        <script>
            fetch('http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/temoignage?embed&acf_format=standard')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('box_temoignage');

                    data.forEach((temoignage, index) => {
                        const div = document.createElement('div');
                        div.className = 'temoigne';

                        // Structure de l'image
                        const imageHTML = `
                        <div class="img_jeune">
                        <img src="${temoignage.acf.image.url}" alt="${temoignage.acf.image.alt || ''}">
                        </div>
                        `;

                        // Structure du texte
                        const texteHTML = `
                        <p>${temoignage.acf.texte}<br><br>${temoignage.acf.prenom}</p>
                        `;

                        // Alternance image gauche / droite
                        div.innerHTML = (index % 2 === 0)
                            ? imageHTML + texteHTML
                            : texteHTML + imageHTML;

                        container.appendChild(div);
                    });
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des témoignages :', error);
                });
        </script>

    </section>


    <!---------------------------------------------------------------------- TROUVEZ --------------------------------------------------------------->

    <section class="trouve">

        <h2>Où nous trouver ?</h2>

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

            // Fonction pour récupérer tous les points avec pagination automatique
            async function recupererTousLesPoints() {
                let tousLesPoints = [];
                let page = 1;
                let totalPages = 1;

                try {
                    do {
                        const response = await fetch(`http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/point?embed&acf_format=standard&per_page=100&page=${page}`);

                        // Récupérer le nombre total de pages depuis les en-têtes HTTP
                        totalPages = parseInt(response.headers.get('X-WP-TotalPages')) || 1;

                        const data = await response.json();
                        tousLesPoints = tousLesPoints.concat(data);

                        page++;
                    } while (page <= totalPages);

                    // Afficher tous les points sur la carte
                    tousLesPoints.forEach(antenne => {
                        if (antenne.acf && antenne.acf.latitude && antenne.acf.longitude) {
                            const marker = L.marker([antenne.acf.latitude, antenne.acf.longitude]).addTo(map);

                            marker.bindPopup(`
                                <h3>${antenne.acf.nom}</h3>
                                <p>${antenne.acf.adresse}</p>
                                <p>${antenne.acf.horaire}</p>
                            `);
                        }
                    });

                    console.log(`${tousLesPoints.length} points chargés sur la carte d'accueil`);
                } catch (error) {
                    console.error('Erreur de chargement des antennes :', error);
                }
            }

            // Lancer le chargement des points
            recupererTousLesPoints();


        </script>

    </section>


    <?php
    include 'footer.php';
    ?>

</body>

</html>