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
  let continuer = true;

  try {
    while (continuer) {
      console.log(`Chargement de la page ${page}...`);
      const response = await fetch(`http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/point?embed&acf_format=standard&per_page=100&page=${page}`);

      if (!response.ok) {
        console.log(`Arrêt du chargement : page ${page} non disponible`);
        break;
      }

      const data = await response.json();

      // Si la page ne contient aucun résultat, on arrête
      if (!data || data.length === 0) {
        console.log(`Arrêt du chargement : aucun résultat à la page ${page}`);
        break;
      }

      tousLesPoints = tousLesPoints.concat(data);
      console.log(`Page ${page} chargée : ${data.length} points`);

      // Si on a moins de 100 résultats, c'est la dernière page
      if (data.length < 100) {
        continuer = false;
      }

      page++;

      // Sécurité : limite à 10 pages maximum (1000 points)
      if (page > 10) {
        console.log('Limite de 10 pages atteinte');
        break;
      }
    }

    console.log(`Total : ${tousLesPoints.length} points récupérés`);

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

    console.log(`${tousLesPoints.length} points affichés sur la carte`);
  } catch (error) {
    console.error('Erreur de chargement des antennes :', error);
  }
}

// Lancer le chargement des points
recupererTousLesPoints();

