// ------------------------------------ Liste des evenements ------------------------------------

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

fetch("https://ml09.org/ml09_wp/wp-json/wp/v2/evenement?embed&acf_format=standard")
    .then(response => response.json())
    .then(data => {
        const evenementList = document.querySelector('.box_evenement');

        // Trier par date ACF (du plus proche au plus éloigné)
        const sortedData = data.sort((a, b) => {
            const dateA = parseDateACF(a.acf.date);
            const dateB = parseDateACF(b.acf.date);
            return (dateA || 0) - (dateB || 0);
        });

        // Fonction pour afficher les événements
        const displayEvenements = (evenements) => {
            evenementList.innerHTML = '';
            evenements.forEach(evenement => {
                const evenementItem = document.createElement('a');
                evenementItem.href = 'detail_evenement.php?id=' + evenement.id;
                evenementItem.classList.add('evenement-card');

                const dateObj = parseDateACF(evenement.acf.date);
                const formattedDate = (dateObj && !isNaN(dateObj.getTime()))
                    ? dateObj.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long' })
                    : 'Date non précisée';

                evenementItem.innerHTML = `
                    <div class="evenement">
                        <p class="date_evenement">
                            <span style="text-transform: uppercase;">
                                <b>${formattedDate}</b>
                            </span>
                        </p>
                        <div class="type_evenement">
                            <p style="font-weight: bold;">${evenement.acf.nom}</p>
                            <p>${evenement.acf.ville}</p>
                        </div>
                    </div>
                `;

                evenementList.appendChild(evenementItem);
            });
        };

        // Affichage initial
        displayEvenements(sortedData);

        // (optionnel) mise à jour de l'URL si tu ajoutes des filtres
        const updateURL = (filter) => {
            const url = new URL(window.location);
            url.searchParams.set('filter', filter);
            window.history.pushState({}, '', url);
        };
    })
    .catch(error => console.error('Erreur lors du chargement des evenements:', error));



// ------------------------------------ Détails d’un evenement ------------------------------------

document.addEventListener('DOMContentLoaded', () => {
    const evenementId = new URLSearchParams(window.location.search).get('id');

    if (evenementId) {
        fetch("https://ml09.org/ml09_wp/wp-json/wp/v2/evenement?embed&acf_format=standard")
            .then(response => {
                if (!response.ok) throw new Error("Erreur de requête");
                return response.json();
            })
            .then(data => {
                const evenement = data.find(a => a.id == evenementId);
                if (evenement) {
                    const detail = document.querySelector('.evenement-detail');
                    if (detail) {
                        const dateDetail = parseDateACF(evenement.acf.date);
                        const formattedDateDetail = (dateDetail && !isNaN(dateDetail.getTime()))
                            ? dateDetail.toLocaleDateString('fr-FR')
                            : 'Date non précisée';

                        detail.innerHTML = `

                                <a href="evenement.php"><i class="fa-solid fa-circle-arrow-left" style="font-size: 30px;"></i></a><br><br>

                                <div class="image-wrapper">
                                    <img src="${evenement.acf.img ? evenement.acf.img.url : ''}" alt="${evenement.acf.img ? (evenement.acf.img.alt || '') : ''}">
                                </div><br><br>
                                <h1>${evenement.acf.nom}</h1>
                                <h3>Date : ${formattedDateDetail}</h3>
                                <h3>${evenement.acf.ville}</h3><br>
                                <p class="description_evenement">${evenement.acf.detail}</p>

                        `;
                    }
                } else {
                    console.warn("evenement non trouvé");
                }
            })
            .catch(error => console.error("Erreur chargement détails evenement :", error));
    }
});

