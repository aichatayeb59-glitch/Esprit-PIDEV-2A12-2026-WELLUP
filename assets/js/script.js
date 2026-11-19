
// Objectif quotidien
let objectif = 2000;

// Charger les données depuis MySQL
async function loadData() {
    try {
        const response = await fetch("get_data.php");
        const data = await response.json();

        updateProgress(data.total);
        displayHistory(data.history);

    } catch (error) {
        console.error("Erreur :", error);
    }
}


// Ajouter une consommation
async function addWater() {

    const input = document.getElementById("waterAmount");
    const amount = Number(input.value);

    if (amount <= 0) {
        document.getElementById("message").textContent =
            "Veuillez entrer une quantité valide.";
        return;
    }

    try {
        const response = await fetch("ajouter.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "quantite=" + encodeURIComponent(amount)
        });

        const result = await response.text();

        document.getElementById("message").textContent = result;

        input.value = "";

        loadData();

    } catch (error) {
        document.getElementById("message").textContent =
            "Erreur lors de l'ajout.";
    }
}


// Modifier l'objectif
function changeGoal() {

    const input = document.getElementById("goalAmount");
    const newGoal = Number(input.value);

    if (newGoal > 0) {

        objectif = newGoal;

        document.getElementById("goalText").textContent =
            "Objectif quotidien : " + objectif + " ml";

        document.getElementById("goalMessage").textContent =
            "Objectif modifié avec succès !";

        input.value = "";

        loadData();

    } else {

        document.getElementById("goalMessage").textContent =
            "Veuillez entrer un objectif valide.";
    }
}


// Actualiser le suivi quotidien
function updateProgress(total) {

    const pourcentage = (total / objectif) * 100;

    document.getElementById("consumption").textContent =
        "Quantité consommée : " + total + " ml";

    document.getElementById("goalText").textContent =
        "Objectif quotidien : " + objectif + " ml";

    document.getElementById("progressBar").style.width =
        Math.min(pourcentage, 100) + "%";

    document.getElementById("percentage").textContent =
        Math.round(pourcentage) + "% atteint";
}


// Afficher l'historique
function displayHistory(historique) {

    const historyList = document.getElementById("historyList");

    historyList.innerHTML = "";

    if (historique.length === 0) {
        historyList.innerHTML =
            "<li>Aucune consommation enregistrée.</li>";
        return;
    }

    historique.forEach(function(item) {

        const li = document.createElement("li");

        const date = new Date(item.date_consommation);

        const time = date.toLocaleTimeString("fr-FR", {
            hour: "2-digit",
            minute: "2-digit"
        });

        li.textContent = item.quantite + " ml - " + time;

        const button = document.createElement("button");

        button.textContent = "Supprimer";

        button.onclick = function() {
            deleteHistory(item.id);
        };

        li.appendChild(button);

        historyList.appendChild(li);
    });
}


// Supprimer une consommation
async function deleteHistory(id) {

    if (!confirm("Voulez-vous supprimer cette consommation ?")) {
        return;
    }

    try {
        const response = await fetch("supprimer.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "id=" + encodeURIComponent(id)
        });

        const result = await response.text();

        alert(result);

        loadData();

    } catch (error) {
        alert("Erreur lors de la suppression.");
    }
}


// Réinitialiser la journée
async function resetDay() {

    if (!confirm("Voulez-vous vraiment réinitialiser votre journée ?")) {
        return;
    }

    try {
        const response = await fetch("reset.php", {
            method: "POST"
        });

        const result = await response.text();

        document.getElementById("message").textContent = result;

        loadData();

    } catch (error) {
        alert("Erreur lors de la réinitialisation.");
    }
}


// Charger les données au démarrage
loadData();






