// fetch("../api/index_api.php?tuveuxquoi=equipe")
//     .then(response => response.json())
//     .then(data => {
//         let tout_equipe = "";

//         const couleurs = ["#f2bb18", "#2597cb", "#27708c", "#092d44"];

//         data.forEach((equipe, index) => {
//             const couleur = couleurs[index % couleurs.length];

//             // Vérifie si une image est définie et non vide
//             const imageHTML = equipe.photo && equipe.photo.trim() !== ""
//                 ? `<img src="${equipe.photo}" alt="">`
//                 : "";

//             tout_equipe += `
//                 <div class="equipe" style="border: 3px solid ${couleur};">
//                     <div class="equipe-top">
//                         <p>${equipe.fonction}</p>
//                         ${imageHTML}
//                         <h2>${equipe.nom_prénom}</h2>
//                     </div>

//                     <div class="equipe-bottom">
//                         <p><i class="fa-solid fa-phone icon" style="color: ${couleur};"></i>&nbsp;${equipe.telephone}</p>
//                         <p><i class="fa-solid fa-envelope icon" style="color: ${couleur};"></i>&nbsp;${equipe.mail}</p>
//                     </div>
//                 </div>
//             `;
//         });

//         document.querySelector(".box_equipe").innerHTML = tout_equipe;
//     })
//     .catch(error => {
//         console.error('Erreur lors du chargement des infos:', error);
//     });


fetch("https://ml09.org/ml09_wp/wp-json/wp/v2/equipe?acf_format=standard&per_page=100&orderby=menu_order&order=asc")
    .then(response => response.json())
    .then(data => {
        let html_global = "";

        const couleursParPole = {
            "Siège": "#F2BB18",
            "Département": "#092D44",
            "Foix": "#27708C",
            "Lavelanet": "#309FB8",
            "Saint-Girons": "#F2BB18",
            "Pamiers": "#092D44",
            "Autre": "#999999"
        };

        const ordrePoles = [
            "Siège",
            "Département",
            "Foix",
            "Lavelanet",
            "Saint-Girons",
            "Pamiers",
            "Autre"
        ];

        const groupes = {};

        // Regrouper par pôle
        data.forEach((equipe) => {
            const pole = equipe.acf?.pole || "Autre";
            if (!groupes[pole]) {
                groupes[pole] = [];
            }
            groupes[pole].push(equipe);
        });

        // Afficher dans l'ordre défini
        ordrePoles.forEach((pole) => {
            if (!groupes[pole]) return; // si aucun membre, on saute

            const couleur = couleursParPole[pole] || couleursParPole["Autre"];

            html_global += `<h2 class="titre_pole" style="color: ${couleur};">${pole}</h2>`;
            html_global += `<div class="groupe_pole">`;

            groupes[pole].forEach((equipe) => {
                // Gérer la photo (string URL ou objet)
                let imageURL = "";
                let imageAlt = equipe.acf?.nom_prenom || "";

                if (typeof equipe.acf?.photo === "string") {
                    imageURL = equipe.acf.photo;
                } else if (typeof equipe.acf?.photo === "object") {
                    imageURL = equipe.acf.photo.url || "";
                    imageAlt = equipe.acf.photo.alt || imageAlt;
                }

                const imageHTML = imageURL ? `<img src="${imageURL}" alt="${imageAlt}">` : "";

                html_global += `
                    <div class="equipe" style="border: 3px solid ${couleur};">
                        <div class="equipe-top">
                            <p>${equipe.acf?.fonction || ""}</p>
                            ${imageHTML}
                            <h2>${equipe.acf?.nom_prenom || ""}</h2>
                        </div>

                        <div class="equipe-bottom">
                            <p>&nbsp;${equipe.acf?.telephone || ""}</p>
                            <p>&nbsp;${equipe.acf?.mail || ""}</p>
                        </div>
                    </div>
                `;
            });

            html_global += `</div>`;
        });

        document.querySelector(".box_equipe").innerHTML = html_global;
    })
    .catch(error => {
        console.error("Erreur lors du chargement des infos:", error);
    });
