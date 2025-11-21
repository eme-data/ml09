<nav>

   <div class="navbar">
    <a class="logo" href="index.php">
        <img id="logo_menu" src="" alt="Logo du site">
    </a>

<script>
    const API_URL = "http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/menu?embed&acf_format=standard";
    const CACHE_KEY = "logo_menu_cache";
    const CACHE_DURATION = 60 * 60 * 1000; // 1 heure

    function setLogo(logo) {
        const imgHeader = document.getElementById("logo_menu");
        imgHeader.src = logo.url;
        imgHeader.alt = logo.alt || "Logo";
        imgHeader.setAttribute("fetchpriority", "high");
    }

    // 1. Vérifier s'il y a un cache valide
    const cached = localStorage.getItem(CACHE_KEY);
    if (cached) {
        const parsed = JSON.parse(cached);
        if (Date.now() - parsed.timestamp < CACHE_DURATION) {
            setLogo(parsed.data);
        }
    }

    // 2. Toujours essayer de rafraîchir en arrière-plan
    fetch(API_URL)
        .then(res => res.json())
        .then(data => {
            if (data && data[0] && data[0].acf && data[0].acf.logo) {
                const logo = data[0].acf.logo;

                // Affiche le logo
                setLogo(logo);

                // Met en cache
                localStorage.setItem(CACHE_KEY, JSON.stringify({
                    data: logo,
                    timestamp: Date.now()
                }));
            }
        })
        .catch(err => console.error("Erreur lors du chargement du logo :", err));
</script>
   

    <ul class="link">
        <li>
            <a href="index.php">Accueil</a>
        </li>
        <li>
            <p style="color: var(--bleu_cerulean)">Jeune <i class="fa-solid fa-angle-down "></i></p>
            <div class="dropdown">
                <ul>
                    <li class="img_box">
                        <img src="img/jeune_menu.webp" alt="">
                    </li>
                    <li>
                        <h3>
                            <font color='black'>Etre accompagné</font color>
                        </h3><br>

                        <a href="premier_pas.php">Premier pas</a>
                        <a href="suivi_perso.php">Suivi personnalisé</a>
                    </li>
                    <li>
                        <h3>
                            <font color='black'>Aide du quotidien</font color>
                        </h3><br>

                        <a href="logement.php">Logement</a>
                        <a href="sante.php">Santé </a>
                        <a href="mobilite.php">Mobilité</a>
                    </li>
                    <li>
                        <br> <br>
                        <a href="formation.php">Trouver une formation</a>
                        <a href="emploi.php">Chercher un emploi</a>
                        <a href="atelier.php">Les ateliers</a>
                        <a href="evenement.php">Les événements</a>
                    </li>

                </ul>
            </div>
        </li>
        <li>
            <a href="service_entreprise.php">Entreprises </a>
        </li>
        <li>
            <p style="color: var(--bleu_cerulean)">La Mission Locale <i class="fa-solid fa-angle-down"></i></p>
            <div class="dropdown">
                <ul>
                    <li class="img_box">
                        <img src="img/jeune_menu.webp" alt="">
                    </li>
                    <li>
                        <a href="contact.php">
                            <h3>
                                <font color='black'>Contacts</font color>
                            </h3>
                            Contacts, adresses, horaires
                        </a>
                    </li>
                    <li> <a href="equipe.php">
                            <h3>
                                <font color='black'>L'équipe</font color>
                            </h3>
                            Equipe de la ML09
                        </a>
                    </li>
                    <li class="rapport-container"></li>
                </ul>
            </div>
        </li>
       
        <li>
            <a href="paej.php">PAEJ</a>
        </li>
        <li>
            <a class="bnt_jaune" href="contact.php">Contactez-nous</a>
        </li>
    </ul>

    <!---------------------------------------------------------------- MENU -------------------------------------------------------------->

    <div class="toggle_btn">
        <i class="fa-solid fa-bars pointer"></i>
    </div>

    <div class="dropdown_menu ">

        <ul>
            <li>
            <li>
                <a href="index.php">Accueil</a>
            </li>
            <li>
                <a href="">Jeune <i class="fa-solid fa-angle-down"></i></a>
                <div class="dropdown">
                    <ul>
                        <li>
                            <h3>
                                <font color='black'>Etre accompagner</font color>
                            </h3><br>

                            <a href="premier_pas.php">Premier pas</a>
                            <a href="suivi_perso.php">Suivi personnalisé</a>
                        </li>
                        <li>
                            <h3>
                                <font color='black'>Aide du quotidien</font color>
                            </h3><br>

                            <a href="logement.php">Logement</a>
                            <a href="sante.php">Santé </a>
                            <a href="mobilite.php">Mobilité</a>
                            <a href="formation.php">Trouver une formation</a>
                            <a href="emploi.php">Chercher un emploi</a>
                            <a href="atelier.php">Les Ateliers</a>
                            <a href="evenement.php">Les Evènements</a>
                        </li>

                    </ul>
                </div>
            </li>
                 <li>
            <a href="service_entreprise.php">Entreprises </a>
            </li>
            <li>
                <a href="">La Mission locale <i class="fa-solid fa-angle-down"></i></a>
                <div class="dropdown">
                    <ul>
                        <li>
                            <a href="contact.php">
                                <h3>
                                    <font color='black'>Contacts</font color>
                                </h3>
                                Contacts, adresses, horaires <br> des différentes antennes sur l'Ariège
                            </a>
                        </li>
                        <li> <a href="equipe.php">
                                <h3>
                                    <font color='black'>L'équipe</font color>
                                </h3>
                                Equipe des différentes <br>antennes sur l'Ariège
                            </a>
                        </li>
                        <li class="rapport-container"></li>
                    </ul>
                </div>
            </li>
       
            <li>
                <a href="paej.php">PAEJ</a>
            </li>
            <li>
                <a class="bnt_jaune" href="contact.php">Contactez-nous</a>
            </li>
        </ul>


    </div>

    </div>

</nav>

<script>
    const toggle_btn = document.querySelector('.toggle_btn');
    const toggle_btnicon = document.querySelector('.toggle_btn i');
    const dropdown_menu = document.querySelector('.dropdown_menu');

    toggle_btn.onclick = function () {
        dropdown_menu.classList.toggle('open');
        const isopen = dropdown_menu.classList.contains('open');

        toggle_btnicon.classList = isopen
            ? 'fa-solid fa-xmark pointer'
            : 'fa-solid fa-bars pointer';
    }

    const dropdownMenu = document.querySelector('.dropdown_menu');
    const dropdownLinks = dropdownMenu.querySelectorAll('li > a');

    dropdownLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            const dropdown = this.nextElementSibling;

            // On vérifie que le lien est suivi d'un .dropdown
            if (dropdown && dropdown.classList.contains('dropdown')) {
                e.preventDefault(); // Empêche le lien vide d'agir
                dropdown.classList.toggle('show');

                // Fermer les autres dropdowns
                dropdownMenu.querySelectorAll('.dropdown').forEach(d => {
                    if (d !== dropdown) {
                        d.classList.remove('show');
                    }
                });
            }
        });
    });

    fetch("http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/rapport_activite?acf_format=standard&per_page=1")
    .then(response => response.json())
    .then(data => {
        // data est un tableau => on prend le premier élément
        const post = data[0];
        if (!post || !post.acf) return;

        let rapportURL = "";
        let rapportTitre = "Rapport d'activité";

        if (typeof post.acf.rapport_activite === "string") {
            rapportURL = post.acf.rapport_activite;
        } else if (typeof post.acf.rapport_activite === "object") {
            rapportURL = post.acf.rapport_activite.url || "";
            rapportTitre = post.acf.rapport_activite.title || rapportTitre;
        }

        if (rapportURL) {
            document.querySelectorAll(".rapport-container").forEach(container => {
                container.innerHTML = `
                    <a href="${rapportURL}" download="Brochure-2025.pdf">
                        <h3><span style="color:black">Rapport d'activité</span></h3>
                        Télécharger le rapport d'activité
                    </a>
                `;
            });
        }
    })
    .catch(error => console.error("Erreur chargement rapport:", error));

</script>
</nav>