
class ObjectifController {

    static getObjectifs() {
        return Objectif.getAll();
    }

    static ajouter(titre, description, dateFin) {
        const objectifs = Objectif.getAll();

        const nouvelObjectif = new Objectif(
            Date.now(),
            titre,
            description,
            dateFin,
            0
        );

        objectifs.push(nouvelObjectif);
        Objectif.sauvegarder(objectifs);

        return nouvelObjectif;
    }

    static modifier(id, titre, description, dateFin) {
        const objectifs = Objectif.getAll();

        const objectif = objectifs.find(obj => obj.id === id);

        if (!objectif) return false;

        objectif.titre = titre;
        objectif.description = description;
        objectif.dateFin = dateFin;

        Objectif.sauvegarder(objectifs);
        return true;
    }

    static supprimer(id) {
        const objectifs = Objectif.getAll();
        const nouveauxObjectifs = objectifs.filter(obj => obj.id !== id);

        Objectif.sauvegarder(nouveauxObjectifs);
    }

    static modifierProgression(id, progression) {
        const objectifs = Objectif.getAll();

        const objectif = objectifs.find(obj => obj.id === id);

        if (!objectif) return;

        objectif.progression = progression;
        Objectif.sauvegarder(objectifs);
    }
}
