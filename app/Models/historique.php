
<?php
require_once "connexion.php";

$sql = "SELECT * FROM consommations ORDER BY date_consommation DESC";
$stmt = $pdo->query($sql);
$consommations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <h1>Historique des consommations 💧</h1>
</header>

<main>
    <section class="water-box">

        <h2>Mes consommations</h2>

        <?php if (count($consommations) > 0): ?>

            <table>
                <tr>
                    <th>Quantité (ml)</th>
                    <th>Date</th>
                </tr>

                <?php foreach ($consommations as $consommation): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($consommation['quantite']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($consommation['date_consommation']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </table>

        <?php else: ?>

            <p>Aucune consommation enregistrée.</p>

        <?php endif; ?>

        <br>

        <a href="index.html">
            <button type="button">Retour</button>
        </a>

    </section>
</main>

</body>
</html>
