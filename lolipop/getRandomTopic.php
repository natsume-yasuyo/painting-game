<?php
// CORS対応（必須）
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(204);
  exit;
}

// DB接続情報（あなたの環境にあわせて修正）
$host = 'mysql324.phy.lolipop.lan';
$dbname = 'LAA1648452-painting';
$user = 'LAA1648452';
$pass = 'LpmHwzDLnz67MYK';
$charset = 'utf8mb4';

$category = $_GET['category'] ?? '';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if ($category) {
    $stmt = $pdo->prepare("SELECT text FROM topics WHERE category = ? ORDER BY RAND() LIMIT 1");
    $stmt->execute([$category]);
  } else {
    $stmt = $pdo->query("SELECT text FROM topics ORDER BY RAND() LIMIT 1");
  }

  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    echo json_encode(['topic' => $row['text']]);
  } else {
    echo json_encode(['topic' => '（お題が見つかりませんでした）']);
  }

} catch (Exception $e) {
  echo json_encode(['error' => 'DBエラー: ' . $e->getMessage()]);
}