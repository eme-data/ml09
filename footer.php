

    <!-- <footer class="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <img src="img/logo_footer.webp" alt="Logo Mission Locale Jeune Ariège" class="footer-logo">
                <div class="footer-social">
                    <a href="https://www.instagram.com/missionlocale09/" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.facebook.com/ml.foix.79/" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.youtube.com/c/MissionLocaleJeuneAri%C3%A8ge" class="social-icon"><i class="fa-brands fa-youtube"></i></a>
                    <a href="https://www.tiktok.com/@missionlocale09" class="social-icon"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>

            <div class="footer-section contact_footer">
                <h3>Siège Social :</h3>
                <p>MCEF ARIEGE, 18 A RUE DE L'ESPINET, 09000 FOIX</p>
                <p>Téléphone : 05 34 09 32 09</p>
                <p>Email : mission.locale@ml09.org</p>
            </div>


            <div class="footer-section hours">
                <h3>Horaires</h3>
                <p>Lundi - Vendredi : 9h00 - 12h30, 13h30 - 17h00</p>
                <p>Samedi : Fermé</p>
                <p>Dimanche : Fermé</p>
            </div>


            <div class="footer-section links">
                <h3>Liens Utiles</h3>
                <ul>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="premier_pas.php">Premier pas </a></li>
                    <li><a href="evenement.php">Événements</a></li>
                    <li><a href="equipe.php">L'équipe</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
        </div>


        <div class="footer-bottom">
            <p><a href="mention_legale.php">Mentions légales</a></p>
            <p>2025 Mission Locale Ariège – Tous droits réservés.</p>
        </div>
    </footer> -->



 <footer class="footer"></footer>

<script>
fetch("https://ml09.org/ml09_wp/wp-json/wp/v2/footer?embed&acf_format=standard")
    .then(response => response.json())
    .then(data => {
        if (!data || !data[0] || !data[0].acf) return;
        let footer = "";

        // On récupère uniquement le premier élément du tableau
        let ligne = data[0];

        footer = `
        <div class="footer-container">
            <div class="footer-logo">
                <img src="${ligne.acf.logo_mission_locale ? ligne.acf.logo_mission_locale.url : 'img/logo_footer.webp'}"
     alt="Logo Mission Locale Jeune Ariège" class="footer-logo"
     onerror="this.onerror=null; this.src='img/logo_footer.webp';">
                <div class="footer-social">
                    <a href="${ligne.acf.instagram || '#'}" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="${ligne.acf.facebook || '#'}" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="${ligne.acf.youtube || '#'}" class="social-icon"><i class="fa-brands fa-youtube"></i></a>
                    <a href="${ligne.acf.tik_tok || '#'}" class="social-icon"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>

            <div class="footer-section contact_footer">
                <h3 style="color:#ffffff;">Siège Social</h3>
                <p>${ligne.acf.siege_social || ''}</p>
            </div>

            <div class="footer-section hours">
                <h3 style="color:#ffffff;">Horaires</h3>
                <p>${ligne.acf.horaire || ''}</p>
            </div>

        </div>

        <div class="footer-bottom">
            <p><a href="mention_legale.php">Mentions légales</a></p>
            <p>2025 Mission Locale Ariège – Tous droits réservés.</p>
        </div>
        `;

        // Insérer le contenu du footer
        document.querySelector(".footer").innerHTML = footer;
    })
    .catch(error => {
        console.error('Erreur lors du chargement du footer:', error);
    });
</script>
