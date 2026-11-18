
class Objectif {
    constructor(id, titre, description, dateFin, progression = 0) {
        this.id = id;
        this.titre = titre;
        this.description = description;
        this.dateFin = dateFin;
        this.progression = progression;
    }

    static getAll() {
        const donnees = JSON.parse(localStorage.getItem("objectifs")) || [];

        return donnees.map(obj => new Objectif(
            obj.id,
            obj.titre,
            obj.description,
            obj.dateFin,
            obj.progression
        ));
    }

    static sauvegarder(objectifs) {
        localStorage.setItem("objectifs", JSON.stringify(objectifs));
    }
}
