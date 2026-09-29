<?php
class User {
  public static function create($pdo, $nome, $cognome, $email, $password) {
    $stmt = $pdo->prepare("INSERT INTO Utenti (nome, cognome, email, password_hash) VALUES (?,?,?,?)");
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt->execute([$nome, $cognome, $email, $hash]);
    return $pdo->lastInsertId();
  }
  public static function findByEmail($pdo, $email) {
    $stmt = $pdo->prepare("SELECT * FROM Utenti WHERE email=?");
    $stmt->execute([$email]);
    return $stmt->fetch();
  }
  public static function findById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM Utenti WHERE id=?");
    $stmt->execute([$id]);
    return $stmt->fetch();
  }
}