
let objectifEnModification = null;

const formulaire = document.getElementById("objectifForm");
const listeObjectifs = document.getElementById("listeObjectifs");
const boutonSubmit = document.getElementById("submitBtn");

function afficherObjectifs() {
    listeObjectifs.innerHTML = "";

    const objectifs = ObjectifController.getObjectifs();

    objectifs.forEach(objectif => {
        const carte = document.createElement("div");
        carte.className = "objectif-card";

        const titre = document.createElement("h3");
        titre.textContent = objectif.titre;

        const description = document.createElement("p");
        description.textContent = objectif.description;

        const date = document.createElement("p");
        date.textContent = "Date limite : " + objectif.dateFin;

        const progression = document.createElement("p");
        progression.textContent = "Progression : " + objectif.progression + "%";

        const slider = document.createElement("input");
        slider.type = "range";
        slider.min = 0;
        slider.max = 100;
        slider.value = objectif.progression;

        slider.addEventListener("input", function () {
            ObjectifController.modifierProgression(
                objectif.id,
                Number(slider.value)
            );

            progression.textContent =
                "Progression : " + slider.value + "%";
        });

        const boutonModifier = document.createElement("button");
        boutonModifier.textContent = "Modifier";
        boutonModifier.className = "edit-btn";

        boutonModifier.addEventListener("click", function () {
            document.getElementById("titre").value = objectif.titre;
            document.getElementById("description").value = objectif.description;
            document.getElementById("dateFin").value = objectif.dateFin;

            objectifEnModification = objectif.id;
            boutonSubmit.textContent = "Enregistrer les modifications";
        });

        const boutonSupprimer = document.createElement("button");
        boutonSupprimer.textContent = "Supprimer";
        boutonSupprimer.className = "delete-btn";

        boutonSupprimer.addEventListener("click", function () {
            if (confirm("Voulez-vous supprimer cet objectif ?")) {
                ObjectifController.supprimer(objectif.id);
                afficherObjectifs();
            }
        });

        carte.append(
            titre,
            description,
            date,
            progression,
            slider,
            boutonModifier,
            boutonSupprimer
        );

        listeObjectifs.appendChild(carte);
    });
}

formulaire.addEventListener("submit", function (event) {
    event.preventDefault();

    const titre = document.getElementById("titre").value.trim();
    const description = document.getElementById("description").value.trim();
    const dateFin = document.getElementById("dateFin").value;

    if (!titre || !description || !dateFin) {
        alert("Veuillez remplir tous les champs.");
        return;
    }

    if (objectifEnModification !== null) {
        ObjectifController.modifier(
            objectifEnModification,
            titre,
            description,
            dateFin
        );

        objectifEnModification = null;
        boutonSubmit.textContent = "Ajouter l'objectif";
    } else {
        ObjectifController.ajouter(titre, description, dateFin);
    }

    formulaire.reset();
    afficherObjectifs();
});

afficherObjectifs();
