<?php
class Product {
  public static function all($pdo, $limit=12) {
    $stmt=$pdo->prepare("SELECT * FROM Prodotto WHERE attivo=1 ORDER BY created_at DESC LIMIT ?");
    $stmt->bindValue(1,(int)$limit,PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
  }
  public static function search($pdo, $q='', $categoria_id=null) {
    $sql = "SELECT p.* FROM Prodotto p WHERE p.attivo=1";
    $params = [];
    if ($q!=='') { $sql .= " AND p.nome LIKE ?"; $params[] = "%$q%"; }
    if ($categoria_id) { $sql .= " AND p.categoria_id=?"; $params[] = $categoria_id; }
    $sql .= " ORDER BY p.created_at DESC";
    $stmt=$pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
  }
  public static function find($pdo, $id) {
    $stmt=$pdo->prepare("SELECT p.*, c.nome AS categoria_nome FROM Prodotto p LEFT JOIN Categoria c ON c.id=p.categoria_id WHERE p.id=?");
    $stmt->execute([$id]);
    return $stmt->fetch();
  }
}