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
    // Fonction pour parser une date ACF (format DD/MM/YYYY ou YYYY-MM-DD)
    function parseDateACF(dateStr) {
        if (!dateStr) return null;
        // Format DD/MM/YYYY
        if (dateStr.includes('/')) {
            const [day, month, year] = dateStr.split('/').map(Number);
            return new Date(year, month - 1, day);
        }
        // Format YYYY-MM-DD
        if (dateStr.includes('-')) {
            const [year, month, day] = dateStr.split('-').map(Number);
            return new Date(year, month - 1, day);
        }
        // Format YYYYMMDD
        if (dateStr.length === 8 && !isNaN(dateStr)) {
            const year = parseInt(dateStr.substring(0, 4));
            const month = parseInt(dateStr.substring(4, 6));
            const day = parseInt(dateStr.substring(6, 8));
            return new Date(year, month - 1, day);
        }
        return null;
    }

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

            // Trier par date décroissante (du plus récent au plus ancien)
            const sortedData = validAteliers.sort((a, b) => {
                const dateA = parseDateACF(a.acf.date_atelier);
                const dateB = parseDateACF(b.acf.date_atelier);
                return (dateB || 0) - (dateA || 0);
            });

            // Ne garder que les 2 derniers ateliers
            const latestAteliers = sortedData.slice(0, 2);

            // Afficher les ateliers
            latestAteliers.forEach(atelier => {
                const atelierItem = document.createElement('div');
                atelierItem.classList.add('atelier-card');

                // Récupération et formatage de la date
                const dateObj = parseDateACF(atelier.acf.date_atelier);
                const formattedDateStr = (dateObj && !isNaN(dateObj.getTime()))
                    ? dateObj.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long' })
                    : "Date non définie";

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

                    // Vérifier que data est un tableau et contient des éléments
                    if (!Array.isArray(data) || data.length === 0) {
                        container.innerHTML = '<p style="text-align: center;">Aucun témoignage disponible pour le moment.</p>';
                        return;
                    }

                    data.forEach((temoignage, index) => {
                        if (!temoignage || !temoignage.acf) return;

                        const acf = temoignage.acf;

                        // Récupérer le texte et le prénom avec vérifications strictes
                        const texte = (typeof acf.texte === 'string' && acf.texte)
                            || (temoignage.content && typeof temoignage.content.rendered === 'string' && temoignage.content.rendered)
                            || '';
                        const prenom = (typeof acf.prenom === 'string' && acf.prenom)
                            || (temoignage.title && typeof temoignage.title.rendered === 'string' && temoignage.title.rendered)
                            || '';

                        // Ne pas afficher si texte ET prénom sont vides
                        if (!texte && !prenom) return;

                        // Récupérer l'URL de l'image
                        let imageUrl = '';
                        if (acf.image) {
                            if (typeof acf.image === 'string') {
                                imageUrl = acf.image;
                            } else if (acf.image.url) {
                                imageUrl = acf.image.url;
                            }
                        }

                        const div = document.createElement('div');
                        div.className = 'temoigne';

                        const imageHTML = imageUrl
                            ? `<div class="img_jeune"><img src="${imageUrl}" alt="${prenom}" loading="lazy"></div>`
                            : '';

                        const texteHTML = `<p>${texte}${prenom ? '<br><br>' + prenom : ''}</p>`;

                        // Alternance image gauche / droite
                        div.innerHTML = (index % 2 === 0)
                            ? imageHTML + texteHTML
                            : texteHTML + imageHTML;

                        container.appendChild(div);
                    });

                    // Si aucun témoignage n'a été affiché
                    if (container.children.length === 0) {
                        container.innerHTML = '<p style="text-align: center;">Aucun témoignage disponible pour le moment.</p>';
                    }
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
                .then(response => {
                    if (!response.ok) throw new Error(`Erreur HTTP ${response.status}`);
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('La réponse API n\'est pas du JSON (vérifier show_in_rest dans CPTUI)');
                    }
                    return response.json();
                })
                .then(data => {
                    if (!Array.isArray(data)) {
                        console.error('Réponse API inattendue pour les points :', data);
                        return;
                    }
                    data.forEach(antenne => {
                        if (!antenne.acf || !antenne.acf.latitude || !antenne.acf.longitude) return;
                        const marker = L.marker([antenne.acf.latitude, antenne.acf.longitude]).addTo(map);

                        marker.bindPopup(`
                            <h3>${antenne.acf.nom || ''}</h3>
                            <p>${antenne.acf.adresse || ''}</p>
                            <p>${antenne.acf.horaire || ''}</p>
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