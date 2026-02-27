// Lieu de la carte 
var map = L.map('map').setView([43.02345030435656, 1.6188815119039786], 10);

// Visuel de la carte 
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19,
  attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);


// ------------------------------------ point ------------------------------------

fetch('https://ml09.org/ml09_wp/wp-json/wp/v2/point_paej?embed&acf_format=standard&per_page=100')
  .then(response => response.json())
  .then(data => {
    console.log(`${data.length} points PAEJ chargés`);
    data.forEach(antenne => {
      const marker = L.marker([antenne.acf.latitude, antenne.acf.longitude]).addTo(map);

      marker.bindPopup(`
        <h3>${antenne.acf.nom}</h3>
        <p>${antenne.acf.adresse}</p>
        <p>${antenne.acf.horaire}</p>
      `);
    });
  })
  .catch(error => console.error('Erreur de chargement des antennes :', error));



