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

