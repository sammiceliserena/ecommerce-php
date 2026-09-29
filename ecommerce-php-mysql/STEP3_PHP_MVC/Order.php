<?php
class Order {
  public static function create($pdo, $utente_id, $items) {
    $pdo->beginTransaction();
    try {
      $totale = 0;
      foreach ($items as $it) { $totale += $it['prezzo_unitario'] * $it['quantita']; }
      $stmt = $pdo->prepare("INSERT INTO Ordine (utente_id, totale, stato) VALUES (?, ?, 'COMPLETATO')");
      $stmt->execute([$utente_id, $totale]);
      $ordine_id = $pdo->lastInsertId();
      $stmtItem = $pdo->prepare("INSERT INTO OrdineItem (ordine_id, prodotto_id, quantita, prezzo_unitario) VALUES (?,?,?,?)");
      foreach ($items as $it) { $stmtItem->execute([$ordine_id, $it['prodotto_id'], $it['quantita'], $it['prezzo_unitario']]); }
      $pdo->commit();
      return $ordine_id;
    } catch (Exception $e) {
      $pdo->rollBack();
      throw $e;
    }
  }
  public static function listByUser($pdo, $utente_id) {
    $stmt=$pdo->prepare("SELECT * FROM Ordine WHERE utente_id=? ORDER BY creato_il DESC");
    $stmt->execute([$utente_id]);
    return $stmt->fetchAll();
  }
  public static function items($pdo, $ordine_id) {
    $stmt=$pdo->prepare("SELECT oi.*, p.nome FROM OrdineItem oi JOIN Prodotto p ON p.id=oi.prodotto_id WHERE oi.ordine_id=?");
    $stmt->execute([$ordine_id]);
    return $stmt->fetchAll();
  }
}