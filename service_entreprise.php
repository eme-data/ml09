<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entreprises | ml09</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" href="css/service_entreprise.css">
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
    const API_URL_ENTETE = "https://ml09.org/ml09_wp/wp-json/wp/v2/entreprise_texte?embed&acf_format=standard";
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

        <h1 style="color: var(--bleu_cerulean);">La Mission Locale Jeune Ariège au service des entreprises</h1><br>

        <div id="texte_container"></div>

        <script>
            fetch('https://ml09.org/ml09_wp/wp-json/wp/v2/entreprise_texte?embed&acf_format=standard')
                .then(res => res.json())
                .then(data => {
                    const acf = data[0].acf;
                    document.getElementById('texte_container').innerHTML = `
            <div>
                <p>${acf.texte}</p>
                <img src="${acf.logo_charte.url}" alt="${acf.logo_charte.alt || ''}">
                <p>${acf.texte2}</p>
                <p>${acf.texte3}</p>
            </div>
        `;
                })
                .catch(err => console.error('Erreur lors du chargement du texte :', err));
        </script>

        <!-- <p>
            La Mission Locale accueille, informe, oriente et accompagne les jeunes entre 16 et 25 ans dans la
            construction de leur parcours professionnel, mais elle est également un interlocuteur privilégié pour les
            entreprises.<br><br>

            Ainsi, elle peut :<br>

            · Vous informer sur les mesures pour l’emploi, les contrats les plus adaptés à vos besoins<br>

            · Sélectionner des jeunes selon vos critères de recrutement et les mettre en relation avec votre
            entreprise<br>

            · Assurer le suivi et l’intégration d’un jeune dans votre entreprise et son maintien dans l’emploi<br>

            Des actions d’information et de découverte sur votre secteur et ses métiers peuvent être également
            organisées en direction des jeunes.<br><br>


            Nous restons à votre disposition pour vous renseigner et vous apporter toute information complémentaire.

        </p><br>

        <div class="btn_centre">
            <a class="bnt_jaune" href="contact.php">Contactez-nous</a>
        </div><br><br><br>

        <h2>Charte « Je m’engage pour la Jeunesse »</h2><br>

        <div class="logo_centre">
            <img src="img/je_m_engage.webp" alt="">
        </div><br><br>
        <p>
            Considérant les difficultés pour les jeunes d’accéder à l’emploi sur nos territoires et de l’enjeu que
            constitue l’entrée dans le marché du travail pour ce public.<br>

            Considérant l’impérieuse nécessité pour la Mission Locale Jeune Ariège de travailler en proximité des
            acteurs économiques (les entreprises, les associations et les employeurs publics)<br>

            La Mission Locale Jeune Ariège, chargée d’accompagner les jeunes âgés de 16 à 25 ans dans leur recherche
            d’emploi ou leur orientation professionnelle, décide de proposer à tous les employeurs désireux de
            promouvoir l’emploi des jeunes de se rassembler autour d’un label commun.
            « Je M’ENGAGE pour la JEUNESSE ».<br><br>


            Ce label identifie et accompagne la signature de cette charte partenariale dans une préoccupation connexe à
            la Responsabilité Sociétale des Entreprises afin :<br><br>

            · D’identifier visuellement, par un support visuel ("auto collant" visible sur les devantures et sur les
            réseaux sociaux), l’appui et l’investissement des acteurs économiques pour le succès de la mission de
            service public de la mission locale et valoriser auprès des clients / usagers leur apport à l’insertion
            professionnelle des jeunes.<br>

            · De créer un réseau d’acteurs économiques capable de favoriser l’accès à l’emploi et à la qualification des
            jeunes.<br><br>


            Nous sommes heureux d’être la 3ème Mission Locale d’Occitanie à lancer son label entreprise en 2024 avec
            cette Charte d’engagement qui rapproche les chefs d’entreprises et nos chargés de relations entreprises :
            MLOA Mission Locale Ouest Audois (Carcassonne, Castelnaudary, Lézignan et Limoux) en 2018 et la Mission
            Jeunes Tarn Nord (Albi, Carmaux et Gaillac) le 13 avril 2024.<br><br>

            Rappel des objectifs de la charte :<br><br>


            1. Améliorer l’accès à l’emploi et à la qualification des jeunes de notre territoire<br>


            2. Promouvoir les recrutements par l’alternance (contrat d’apprentissage et contrat de
            professionnalisation)<br>


            3. Donner une visibilité aux acteurs économiques investis pour l’emploi des jeunes<br>


            4. Développer ensemble les réponses qui, en amont de l’emploi, préparent les jeunes à

            la vie professionnelle : Périodes de Mise en Situation en Milieu Professionnel (PMSMP), visites
            d’entreprises, services civiques<br>

            5. Mutualiser les ressources Iors d’évènements en faveur de l’emploi des jeunes : forums, opérations de
            recrutement,<br>

            6. Soutenir l’activité de la Mission Locale Jeune Ariège<br><br>



            C’est un engagement à travailler ensemble de façon efficace et à trouver les meilleures solutions pour
            éviter de laisser des jeunes au bord du chemin.<br>

            La jeunesse d’aujourd’hui est la richesse des territoires de demain.

        </p><br><br><br>

        <h2>Engagez-vous auprès des jeunes de la Mission Locale Haute-Garonne en nous versant votre taxe d’apprentissage
        !</h2><br>

        <p>
            
            La taxe d’apprentissage récoltée permettra de renforcer nos actions en faveur des jeunes les plus en
            difficulté, et les accompagner personnellement et professionnellement afin de leur permettre de mieux
            appréhender les facteurs clés de réussite pour leur recherche d’emploi.<br><br>

            • Vous pouvez verser une fraction de votre taxe d’apprentissage à votre Mission Locale par l’intermédiaire
            de votre organisme collecteur.<br><br>

            • Les Missions Locales sont habilitées à percevoir une partie des fonds Hors Quota, selon l’article
            L.6241-10 du Code du travail et apparaît sur la liste des « Établissements admis à titre dérogatoire ».

        </p><br><br> -->

        <div class="btn_centre">
            <strong><a class="bnt_jaune" href="contact.php">Contactez-nous</a></strong>
        </div>




    </main>

    <?php
    include 'footer.php';
    ?>

</body>

</html>