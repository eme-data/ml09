
// ------------------------------------ Derniers evenements ------------------------------------

fetch("../api/index_api.php?tuveuxquoi=atelier")
    .then(response => response.json())
    .then(data => {
        const atelierList = document.querySelector('.derniere_actu');

        // Trier par date du plus récent au plus ancien
        const sortedData = data.sort((a, b) => new Date(b.date) - new Date(a.date));

        // Ne garder que les 2 derniers ateliers
        const latestAteliers = sortedData.slice(0, 2);

        // Afficher les ateliers
        latestAteliers.forEach(atelier => {
            const atelierItem = document.createElement('div');
            atelierItem.classList.add('atelier-card'); 

            atelierItem.innerHTML = `
                <div class="actu">
                    <p class="date_actu">
                        <span style="text-transform: uppercase;">
                            <b>${new Date(atelier.date).toLocaleDateString('fr-FR', { day: '2-digit', month: 'long' })}</b>
                        </span>
                    </p>
                    <div class="type_actu">
                        <h3>Atelier</h3>
                        <p>${atelier.type}</p><br>
                        <p>${atelier.antenne}</p>
                    </div>
                </div>
                <div class="atelier-description">
                    ${atelier.detail || "Aucune description disponible."}
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



// ------------------------------------ Liste des evenements ------------------------------------


fetch("../api/index_api.php?tuveuxquoi=atelier")
    .then(response => response.json())
    .then(data => {
        const atelierList = document.querySelector('.box_actu');
        const filterAtelier = document.getElementById('atelier-filter');

        // Trier par date (du plus proche au plus éloigné)
        const sortedData = data.sort((a, b) => new Date(b.date) - new Date(a.date));

        // Fonction pour afficher les ateliers
        const displayAteliers = (ateliers) => {
            atelierList.innerHTML = '';
            ateliers.forEach(atelier => {
                const atelierItem = document.createElement('div'); // Remplacer <a> par <div>
                atelierItem.classList.add('atelier-card');

                atelierItem.innerHTML = `
                    <div class="actu">
                        <p class="date_actu">
                            <span style="text-transform: uppercase;">
                                <b>${new Date(atelier.date).toLocaleDateString('fr-FR', { day: '2-digit', month: 'long' })}</b>
                            </span>
                        </p>
                        <div class="type_actu">
                            <h3>Atelier</h3>
                            <p>${atelier.type}</p><br>
                            <p>${atelier.antenne}</p>
                        </div>
                    </div>
                    <div class="atelier-description">
                        ${atelier.detail || "Aucune description disponible."}
                    </div>
                `;

                // Ajouter l'effet d'accordéon
                atelierItem.querySelector('.actu').addEventListener('click', () => {
                    const desc = atelierItem.querySelector('.atelier-description');
                    const isVisible = desc.style.display === 'block';
                    desc.style.display = isVisible ? 'none' : 'block';
                });

                atelierList.appendChild(atelierItem);
            });
        };

        // Mettre à jour l'URL avec le filtre
        const updateURL = (filter) => {
            const url = new URL(window.location);
            url.searchParams.set('filter', filter);
            window.history.pushState({}, '', url);
        };

        // Appliquer le filtre depuis l'URL
        const applyFilterFromURL = () => {
            const urlParams = new URLSearchParams(window.location.search);
            const filter = urlParams.get('filter');

            if (filter && filter !== 'all') {
                filterAtelier.value = filter;
                return sortedData.filter(atelier => atelier.antenne === filter);
            }

            filterAtelier.value = 'all';
            return sortedData;
        };

        // Affichage initial
        displayAteliers(applyFilterFromURL());

        // Filtrage dynamique
        filterAtelier.addEventListener('change', (event) => {
            const selectedAntenne = event.target.value;
            updateURL(selectedAntenne);

            const filteredAteliers = selectedAntenne === 'all'
                ? sortedData
                : sortedData.filter(atelier => atelier.antenne === selectedAntenne);

            displayAteliers(filteredAteliers);
        });
    })
    .catch(error => console.error('Erreur lors du chargement des ateliers:', error));




