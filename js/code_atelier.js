

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

// Fonction universelle pour parser une date ACF (DD/MM/YYYY, YYYY-MM-DD ou YYYYMMDD)
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

fetch("https://ml09.org/ml09_wp/wp-json/wp/v2/atelier?embed&acf_format=standard")
    .then(response => response.json())
    .then(data => {
        const atelierList = document.querySelector('.derniere_actu');
        if (!atelierList) return;

        // Trier par date chronologique
        const sortedData = data.sort((a, b) => {
            const dateA = parseDateACF(a.acf.date_atelier);
            const dateB = parseDateACF(b.acf.date_atelier);
            return (dateA || 0) - (dateB || 0);
        });

        // Ne garder que les 2 premiers ateliers
        const latestAteliers = sortedData.slice(0, 2);

        // Afficher les ateliers
        latestAteliers.forEach(atelier => {
            const atelierItem = document.createElement('div');
            atelierItem.classList.add('atelier-card');

            const dateObj = parseDateACF(atelier.acf.date_atelier);
            const formattedDate = (dateObj && !isNaN(dateObj.getTime()))
                ? dateObj.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long' })
                : "Date non précisée";

            atelierItem.innerHTML = `
                <div class="actu">
                    <p class="date_actu">
                        <span style="text-transform: uppercase;">
                            <b>${formattedDate}</b>
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


fetch("https://ml09.org/ml09_wp/wp-json/wp/v2/atelier?embed&acf_format=standard")
    .then(response => response.json())
    .then(data => {
        const atelierList = document.querySelector('.box_actu');
        const filterAtelier = document.getElementById('atelier-filter');

        // Trier par date_atelier décroissante (du plus récent au plus ancien)
        const sortedData = data.sort((a, b) => {
            const dateA = parseDateACF(a.acf.date_atelier);
            const dateB = parseDateACF(b.acf.date_atelier);
            return (dateB || 0) - (dateA || 0);
        });

        // Fonction pour afficher les ateliers
        const displayAteliers = (ateliers) => {
            atelierList.innerHTML = '';
            ateliers.forEach(atelier => {
                const atelierItem = document.createElement('div');
                atelierItem.classList.add('atelier-card');

                const formattedDateObj = parseDateACF(atelier.acf.date_atelier);
                const formattedDate = (formattedDateObj && !isNaN(formattedDateObj.getTime())) ? formattedDateObj : null;

                atelierItem.innerHTML = `
                    <div class="actu">
                        <p class="date_actu">
                            <span style="text-transform: uppercase;">
                                <b>${formattedDate ? formattedDate.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long' }) : 'Date non précisée'}</b>
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

