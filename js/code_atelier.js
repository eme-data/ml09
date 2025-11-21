

// ------------------------------------ Derniers ateliers ------------------------------------

// fetch("../api/index_api.php?tuveuxquoi=atelier")
//     .then(response => response.json())
//     .then(data => {
//         const atelierList = document.querySelector('.derniere_actu');

//         // Trier par date du plus récent au plus ancien
//         const sortedData = data.sort((a, b) => new Date(b.date) - new Date(a.date));

//         // Ne garder que les 2 derniers ateliers
//         const latestAteliers = sortedData.slice(0, 2);

//         // Afficher les ateliers
//         latestAteliers.forEach(atelier => {
//             const atelierItem = document.createElement('div');
//             atelierItem.classList.add('atelier-card'); 

//             atelierItem.innerHTML = `
//                 <div class="actu">
//                     <p class="date_actu">
//                         <span style="text-transform: uppercase;">
//                             <b>${new Date(atelier.date).toLocaleDateString('fr-FR', { day: '2-digit', month: 'long' })}</b>
//                         </span>
//                     </p>
//                     <div class="type_actu">
//                         <h3>Atelier</h3>
//                         <p>${atelier.type}</p><br>
//                         <p>${atelier.antenne}</p>
//                     </div>
//                 </div>
//                 <div class="atelier-description">
//                     ${atelier.detail || "Aucune description disponible."}
//                 </div>
//             `;

//             // Gestion du clic pour afficher/masquer la description
//             atelierItem.querySelector('.actu').addEventListener('click', () => {
//                 const desc = atelierItem.querySelector('.atelier-description');
//                 desc.style.display = (desc.style.display === 'block') ? 'none' : 'block';
//             });

//             atelierList.appendChild(atelierItem);
//         });
//     })
//     .catch(error => console.error('Erreur lors du chargement des ateliers :', error));

fetch("http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/atelier?embed&acf_format=standard")
    .then(response => response.json())
    .then(data => {
        const atelierList = document.querySelector('.derniere_actu');

        // Fonction pour convertir DD/MM/YYYY en timestamp
        const parseDate = (dateStr) => {
            if (!dateStr) return 0;
            const parts = dateStr.split('/');
            if (parts.length !== 3) return 0;
            const [day, month, year] = parts.map(Number);
            return new Date(year, month - 1, day).getTime();
        };

        // Trier par date chronologique
        const sortedData = data.sort((a, b) => parseDate(a.acf.date) - parseDate(b.acf.date));

        // Ne garder que les 2 premiers ateliers
        const latestAteliers = sortedData.slice(0, 2);

        // Afficher les ateliers
        latestAteliers.forEach(atelier => {
            const atelierItem = document.createElement('div');
            atelierItem.classList.add('atelier-card');

            const displayDate = atelier.acf.date_atelier
                ? atelier.acf.date_atelier.split('/').reverse().join('-') // juste pour afficher ISO si besoin
                : "Date non précisée";

            atelierItem.innerHTML = `
                <div class="actu">
                    <p class="date_actu">
                        <span style="text-transform: uppercase;">
                            <b>${new Date(parseDate(atelier.acf.date_atelier)).toLocaleDateString('fr-FR', { day: '2-digit', month: 'long' })}</b>
                        </span>
                    </p>
                    <div class="type_actu">
                        <h3>${atelier.acf.nom}</h3><br>
                        <p style="color:#2597cb;"><strong>${atelier.acf.antenne}</strong></p>
                    </div>
                </div>
                <div class="atelier-description">
                    ${atelier.acf.detail || "Aucune description disponible."}
                </div>
            `;

            atelierItem.querySelector('.actu').addEventListener('click', () => {
                const desc = atelierItem.querySelector('.atelier-description');
                desc.style.display = (desc.style.display === 'block') ? 'none' : 'block';
            });

            atelierList.appendChild(atelierItem);
        });
    })
    .catch(error => console.error('Erreur lors du chargement des ateliers :', error));


// ------------------------------------ Liste des ateliers ------------------------------------


fetch("http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/atelier?embed&acf_format=standard")
    .then(response => response.json())
    .then(data => {
        const atelierList = document.querySelector('.box_actu');
        const filterAtelier = document.getElementById('atelier-filter');

        // Trier par date_atelier décroissante (du plus éloigné au plus proche)
        const sortedData = data.sort((a, b) => {
            const [yearA, monthA, dayA] = a.acf.date_atelier.split('-');
            const [yearB, monthB, dayB] = b.acf.date_atelier.split('-');
            const dateA = new Date(yearA, monthA - 1, dayA);
            const dateB = new Date(yearB, monthB - 1, dayB);
            return dateB - dateA; // la plus grande date en premier
        });

        // Fonction pour afficher les ateliers
        const displayAteliers = (ateliers) => {
            atelierList.innerHTML = '';
            ateliers.forEach(atelier => {
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
                            <h3>${atelier.acf.nom}</h3><br>
                            <p style="color:#2597cb;"><strong>${atelier.acf.antenne}</strong></p>
                        </div>
                    </div>
                    <div class="atelier-description" style="display:none;">
                        ${atelier.acf.detail || "Aucune description disponible."}
                    </div>
                `;

                // Ajouter l'effet d'accordéon
                atelierItem.querySelector('.actu').addEventListener('click', () => {
                    const desc = atelierItem.querySelector('.atelier-description');
                    desc.style.display = (desc.style.display === 'block') ? 'none' : 'block';
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
                return sortedData.filter(atelier => atelier.acf.antenne === filter);
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
                : sortedData.filter(atelier => atelier.acf.antenne === selectedAntenne);

            displayAteliers(filteredAteliers);
        });
    })
    .catch(error => console.error('Erreur lors du chargement des ateliers:', error));




// ------------------------------------ Détails d’un atelier ------------------------------------

// document.addEventListener('DOMContentLoaded', () => {
//     const atelierId = new URLSearchParams(window.location.search).get('id');

//     if (atelierId) {
//         fetch("../api/index_api.php?tuveuxquoi=atelier")
//             .then(response => {
//                 if (!response.ok) throw new Error("Erreur de requête");
//                 return response.json();
//             })
//             .then(data => {
//                 const atelier = data.find(a => a.id_atelier == atelierId);
//                 if (atelier) {
//                     const detail = document.querySelector('.atelier-detail');
//                     if (detail) {
//                         detail.innerHTML = `
                            
//                                 <h1>${atelier.nom}</h1>
//                                 <h2>${atelier.antenne}</h2><br>
//                                 <h3>Date : ${new Date(atelier.date).toLocaleDateString('fr-FR')}</h3><br>
//                                 <p class="description_atelier">${atelier.detail}</p>
                            
//                         `;
//                     }
//                 } else {
//                     console.warn("Atelier non trouvé");
//                 }
//             })
//             .catch(error => console.error("Erreur chargement détails atelier :", error));
//     }
// });

