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

    console.log(`${tousLesPoints.length} points chargés sur la carte`);
  } catch (error) {
    console.error('Erreur de chargement des antennes :', error);
  }
}

// Lancer le chargement des points
recupererTousLesPoints();

