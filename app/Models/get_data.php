
<?php
require_once "connexion.php";

header("Content-Type: application/json; charset=UTF-8");

$sql = "SELECT SUM(quantite) AS total
        FROM consommations
        WHERE DATE(date_consommation) = CURDATE()";

$stmt = $pdo->query($sql);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$total = $result["total"] ?? 0;

$sql = "SELECT id, quantite, date_consommation
        FROM consommations
        WHERE DATE(date_consommation) = CURDATE()
        ORDER BY date_consommation DESC";

$stmt = $pdo->query($sql);
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "total" => (int)$total,
    "history" => $history
]);
?>
