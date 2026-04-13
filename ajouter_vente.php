<?php
require_once 'Includes/authentification.php';
require_once 'Configuration/database.php';

$message = '';
$messageType = '';

$stmtClients = $pdo->query("SELECT id, nom FROM clients ORDER BY nom ASC");
$clients = $stmtClients->fetchAll(PDO::FETCH_ASSOC);

$stmtProduits = $pdo->query("SELECT id, nom, prix, stock FROM produits ORDER BY nom ASC");
$produits = $stmtProduits->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientId = $_POST['client_id'] ?? '';
    $quantites = $_POST['quantites'] ?? [];

    $clientId = ($clientId === '') ? null : (int) $clientId;

    $lignesVente = [];
    $totalGeneral = 0;

    if (!is_array($quantites)) {
        $quantites = [];
    }

    foreach ($produits as $produit) {
        $produitId = (int) $produit['id'];
        $quantite = isset($quantites[$produitId]) ? (int) $quantites[$produitId] : 0;

        if ($quantite < 0) {
            $message = "Les quantités ne peuvent pas être négatives.";
            $messageType = 'error';
            break;
        }

        if ($quantite > 0) {
            $stockDisponible = (int) $produit['stock'];

            if ($quantite > $stockDisponible) {
                $message = "Stock insuffisant pour le produit : " . $produit['nom'];
                $messageType = 'error';
                break;
            }

            $prixUnitaire = (float) $produit['prix'];
            $sousTotal = $prixUnitaire * $quantite;
            $totalGeneral += $sousTotal;

            $lignesVente[] = [
                'produit_id' => $produitId,
                'nom_produit' => $produit['nom'],
                'prix_unitaire' => $prixUnitaire,
                'quantite' => $quantite,
                'sous_total' => $sousTotal
            ];
        }
    }

    if (empty($message) && empty($lignesVente)) {
        $message = "Tu dois saisir au moins une quantité supérieure à 0.";
        $messageType = 'error';
    }

    if (empty($message)) {
        try {
            $pdo->beginTransaction();

            $stmtVente = $pdo->prepare("
                INSERT INTO ventes (client_id, user_id, total)
                VALUES (?, ?, ?)
            ");
            $stmtVente->execute([
                $clientId,
                $_SESSION['user_id'],
                $totalGeneral
            ]);

            $venteId = $pdo->lastInsertId();

            $stmtLigne = $pdo->prepare("
                INSERT INTO vente_produits (
                    vente_id,
                    produit_id,
                    nom_produit,
                    prix_unitaire,
                    quantite,
                    sous_total
                )
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stmtStock = $pdo->prepare("
                UPDATE produits
                SET stock = stock - ?
                WHERE id = ?
            ");

            foreach ($lignesVente as $ligne) {
                $stmtLigne->execute([
                    $venteId,
                    $ligne['produit_id'],
                    $ligne['nom_produit'],
                    $ligne['prix_unitaire'],
                    $ligne['quantite'],
                    $ligne['sous_total']
                ]);

                $stmtStock->execute([
                    $ligne['quantite'],
                    $ligne['produit_id']
                ]);
            }

            $pdo->commit();

            $_SESSION['message'] = "La vente a été enregistrée avec succès.";
            header('Location: details_vente.php?id=' . $venteId);
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "Une erreur est survenue lors de l'enregistrement de la vente.";
            $messageType = 'error';
        }
    }
}

include 'Includes/header.php';
?>

<h1>Enregistrer une vente</h1>

<?php if (!empty($message)): ?>
    <p class="<?= htmlspecialchars($messageType) ?>">
        <?= htmlspecialchars($message) ?>
    </p>
<?php endif; ?>

<form method="POST" action="">
    <label for="client_id">Client :</label>
    <select id="client_id" name="client_id">
        <option value="">-- Vente sans client --</option>
        <?php foreach ($clients as $client): ?>
            <option
                value="<?= (int) $client['id'] ?>"
                <?= (isset($_POST['client_id']) && $_POST['client_id'] == $client['id']) ? 'selected' : '' ?>
            >
                <?= htmlspecialchars($client['nom']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <h2>Produits à vendre</h2>

    <?php if (!empty($produits)): ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix unitaire (€)</th>
                        <th>Stock disponible</th>
                        <th>Quantité vendue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produits as $produit): ?>
                        <tr>
                            <td><?= htmlspecialchars($produit['nom']) ?></td>
                            <td><?= number_format((float) $produit['prix'], 2, ',', ' ') ?></td>
                            <td>
                                <?php if ((int) $produit['stock'] > 0): ?>
                                    <?= (int) $produit['stock'] ?>
                                <?php else: ?>
                                    <span class="stock-zero">Rupture</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <input
                                    type="number"
                                    name="quantites[<?= (int) $produit['id'] ?>]"
                                    min="0"
                                    max="<?= (int) $produit['stock'] ?>"
                                    value="<?= htmlspecialchars($_POST['quantites'][$produit['id']] ?? '0') ?>"
                                >
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <button type="submit">Valider la vente</button>
    <?php else: ?>
        <p>Aucun produit disponible. Ajoute d'abord des produits.</p>
    <?php endif; ?>
</form>

<p><a href="ventes.php">← Retour à la liste des ventes</a></p>

<?php include 'Includes/footer.php'; ?> 