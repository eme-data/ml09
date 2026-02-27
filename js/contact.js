fetch("https://ml09.org/ml09_wp/wp-json/wp/v2/contact?embed&acf_format=standard")
    .then(response => response.json())
    .then(data => {
        let tout_contact = "";

        const couleurs = ["#f2bb18", "#2597cb", "#27708c", "#092d44"];

        data.forEach((contact, index) => {
            const couleur = couleurs[index % couleurs.length];

            tout_contact += `
                <div class="contact" style="border: 3px solid ${couleur};">
                    <h2>Antenne de ${contact.acf.nom_antenne}</h2><br><br>

                    <p><i class="fa-solid fa-location-dot icon" style="color: ${couleur};"></i>&nbsp;&nbsp;${contact.acf.adresse}</p>
                    <p><i class="fa-solid fa-phone icon" style="color: ${couleur};"></i>&nbsp;${contact.acf.telephone}</p>
                    <p><i class="fa-solid fa-envelope icon" style="color: ${couleur};"></i>&nbsp;${contact.acf.mail}</p><br><br>

                    <p>Horaire:<br>
                        ${contact.acf.horaire}
                    </p>
                </div>
            `;
        });

        document.querySelector(".box_contact").innerHTML = tout_contact;
    })
    .catch(error => {
        console.error('Erreur lors du chargement des infos:', error);
    });
