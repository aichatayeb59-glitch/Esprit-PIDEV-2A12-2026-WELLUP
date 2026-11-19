
<?php
require_once "connexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quantite = intval($_POST["quantite"]);

    if ($quantite > 0) {
        $sql = "INSERT INTO consommations (quantite) VALUES (:quantite)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["quantite" => $quantite]);

        echo "Consommation ajoutée avec succès !";
    } else {
        echo "Veuillez saisir une quantité valide.";
    }
}
?>
