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

$data = json_decode(file_get_contents("php://input"), true);
$roomId = $data['roomId'] ?? '';
$topic = $data['topic'] ?? '';

if (!$roomId || !$topic) {
    echo json_encode(['success' => false, 'message' => 'roomIdまたはtopicがありません']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=...;dbname=...;charset=utf8mb4", "...", "...");
    $stmt = $pdo->prepare("UPDATE rooms SET start_time = NOW(), topic = ? WHERE room_id = ?");
    $stmt->execute([$topic, $roomId]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>