<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mission Locale Ariège | ML09 | PAEJ de l’Ariège</title>
    <meta name="description" content="Accompagnement des jeunes de 16 à 25 ans. Emploi. Formation. Santé. Mobilité. Logement. Mission Locale de l'Ariège | ML09 | Foix, Pamiers, Lavelanet, St-Girons"/>

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

        <div class="img_header" style="min-height: 400px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center;">
            <p style="color: #999;">Chargement...</p>
        </div>

        <script>
            fetch("https://ml09.org/ml09_wp/wp-json/wp/v2/image_accueil?embed&acf_format=standard")
                .then(response => response.json())
                .then(data => {
                    const img_header = document.querySelector('.img_header');

                    // Vérifier que data existe et contient des éléments
                    if (!data || data.length === 0) {
                        img_header.style.display = 'none';
                        return;
                    }

                    // Prendre seulement le premier avec vérification
                    const firstImage = data[0];
                    if (firstImage && firstImage.acf && firstImage.acf.image && firstImage.acf.image.url) {
                        img_header.innerHTML = `
                            <img src="${firstImage.acf.image.url}"
                                 alt="${firstImage.acf.image.alt || 'Mission Locale Jeune Ariège'}"
                                 width="1200"
                                 height="600"
                                 fetchpriority="high">
                        `;
                        // Retirer le style de chargement
                        img_header.style.minHeight = '';
                        img_header.style.backgroundColor = '';
                        img_header.style.display = '';
                        img_header.style.alignItems = '';
                        img_header.style.justifyContent = '';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement de l\'image d\'en-tête :', error);
                    const img_header = document.querySelector('.img_header');
                    img_header.style.display = 'none';
                });
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

        <div class="derniere_actu">
            <p style="text-align: center; padding: 20px;">Chargement des ateliers...</p>
        </div>

        <script>
    fetch("https://ml09.org/ml09_wp/wp-json/wp/v2/atelier?embed&acf_format=standard")
        .then(response => response.json())
        .then(data => {
            const atelierList = document.querySelector('.derniere_actu');

            // Vider le message de chargement
            atelierList.innerHTML = '';

            // Vérifier que data existe et contient des éléments
            if (!data || data.length === 0) {
                atelierList.innerHTML = '<p style="text-align: center;">Aucun atelier disponible pour le moment.</p>';
                return;
            }

            // Filtrer les ateliers qui ont toutes les données nécessaires
            const validAteliers = data.filter(atelier =>
                atelier &&
                atelier.acf &&
                atelier.acf.date_atelier &&
                atelier.acf.nom
            );

            // Trier par date_atelier décroissante (du plus récent au plus ancien)
            const sortedData = validAteliers.sort((a, b) => {
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

                // Récupération de la date avec fallback
                const rawDate = atelier.acf.date_atelier || atelier.acf.date || atelier.date;
                let formattedDateStr = "Date non définie";
                
                if (rawDate) {
                    const dateParts = rawDate.split(/[-/]/);
                    let dateObj;
                    if (dateParts.length === 3) {
                        // Gère YYYY-MM-DD
                        dateObj = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
                    } else {
                        dateObj = new Date(rawDate);
                    }
                    
                    if (!isNaN(dateObj.getTime())) {
                        formattedDateStr = dateObj.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long' });
                    }
                }

                atelierItem.innerHTML = `
                    <div class="actu">
                        <p class="date_actu">
                            <span style="text-transform: uppercase;">
                                <b>${formattedDateStr}</b>
                            </span>
                        </p>
                        <div class="type_actu">
                            <h3>Atelier</h3>
                            <p>${atelier.acf.nom || atelier.title.rendered}</p><br>
                            <p>${atelier.acf.antenne || ''}</p>
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
        .catch(error => {
            console.error('Erreur lors du chargement des ateliers :', error);
            const atelierList = document.querySelector('.derniere_actu');
            atelierList.innerHTML = '<p style="text-align: center; color: red;">Erreur lors du chargement des ateliers.</p>';
        });
</script>



        <div class="toute_actu">
            <a class="bnt_bleu" href="atelier.php"> Tous les Ateliers</a>
        </div>

    </section>




    <!---------------------------------------------------------------------- TEMOIGNAGE --------------------------------------------------------------->

    <section class="temoignage">

        <h2>Témoignages jeunes</h2><br>

        <div class="box_temoignage" id="box_temoignage">
            <p style="text-align: center; padding: 20px;">Chargement des témoignages...</p>
        </div>
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
            fetch('https://ml09.org/ml09_wp/wp-json/wp/v2/temoignage?embed&acf_format=standard')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('box_temoignage');

                    // Vider le message de chargement
                    container.innerHTML = '';

                    // Vérifier que data existe et contient des éléments
                    if (!data || data.length === 0) {
                        container.innerHTML = '<p style="text-align: center;">Aucun témoignage disponible pour le moment.</p>';
                        return;
                    }

                    // Filtrer les témoignages qui ont toutes les données nécessaires
                    const validTemoignages = data.filter(temoignage =>
                        temoignage &&
                        temoignage.acf &&
                        temoignage.acf.prenom &&
                        temoignage.acf.texte &&
                        temoignage.acf.image &&
                        temoignage.acf.image.url
                    );

                    validTemoignages.forEach((temoignage, index) => {
                        const div = document.createElement('div');
                        div.className = 'temoigne';

                        // Structure de l'image avec fallback
                        const imageUrl = (temoignage.acf && temoignage.acf.image) ? (temoignage.acf.image.url || temoignage.acf.image) : '';
                        const imageHTML = `
                        <div class="img_jeune">
                        <img src="${imageUrl}" alt="" loading="lazy">
                        </div>
                        `;

                        // Structure du texte avec fallback
                        const texte = (temoignage.acf && temoignage.acf.texte) || temoignage.content?.rendered || '';
                        const prenom = (temoignage.acf && temoignage.acf.prenom) || temoignage.title?.rendered || '';
                        const texteHTML = `
                        <p>${texte}<br><br>${prenom}</p>
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
                    const container = document.getElementById('box_temoignage');
                    container.innerHTML = '<p style="text-align: center; color: red;">Erreur lors du chargement des témoignages.</p>';
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

            fetch('https://ml09.org/ml09_wp/wp-json/wp/v2/point?embed&acf_format=standard&per_page=100')
                .then(response => response.json())
                .then(data => {
                    console.log(`${data.length} points chargés sur la page d'accueil`);
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

    </section>


    <?php
    include 'footer.php';
    ?>

</body>

</html>