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

$roomId = $_GET['roomId'] ?? '';
if (!$roomId) {
    echo json_encode(['startTime' => null]);
    exit;
}

try {
    $pdo = new PDO("mysql:host=...;dbname=...;charset=utf8mb4", "...", "...");
    $stmt = $pdo->prepare("SELECT start_time, topic FROM rooms WHERE room_id = ?");
    $stmt->execute([$roomId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['start_time']) {
        echo json_encode([
            'startTime' => $row['start_time'],
            'topic' => $row['topic']
        ]);
    } else {
        echo json_encode(['startTime' => null]);
    }

} catch (PDOException $e) {
    echo json_encode(['startTime' => null, 'error' => $e->getMessage()]);
}
?>