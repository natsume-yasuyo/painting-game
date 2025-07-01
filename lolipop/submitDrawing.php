<?php
// CORS対応
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// DB接続情報
$host = 'mysql324.phy.lolipop.lan';
$dbname = 'LAA1648452-painting';
$user = 'LAA1648452';
$pass = 'LpmHwzDLnz67MYK';
$charset = 'utf8mb4';

header('Content-Type: application/json');

// JSON読み込み
$data = json_decode(file_get_contents("php://input"), true);
$roomId = $data['roomId'] ?? '';
$image = $data['image'] ?? '';
$userId = $data['userId'] ?? '';

if (!$roomId || !$image || !$userId) {
    echo json_encode(['success' => false, 'message' => 'データが足りません']);
    exit;
}

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    // 同じroom_idとuser_idの組み合わせを上書き（REPLACE）
    $stmt = $pdo->prepare("REPLACE INTO drawings (room_id, user_id, image) VALUES (?, ?, ?)");
    $stmt->execute([$roomId, $userId, $image]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'DBエラー: ' . $e->getMessage()]);
}
?>
