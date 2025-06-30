<?php
// データベース接続情報
$host = 'mysql324.phy.lolipop.lan';
$dbname = 'LAA1648452-painting';
$user = 'LAA1648452';
$pass = 'LpmHwzDLnz67MYK';
$charset = 'utf8mb4';

header('Content-Type: application/json');

// JSONを受け取る
$data = json_decode(file_get_contents("php://input"), true);
$roomId = $data['roomId'] ?? '';
$image = $data['image'] ?? '';

if (!$roomId || !$image) {
    echo json_encode(['success' => false, 'message' => 'データが足りません']);
    exit;
}

try {
    // DB接続
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    // データベースに保存
    $stmt = $pdo->prepare("INSERT INTO drawings (room_id, image) VALUES (?, ?)");
    $stmt->execute([$roomId, $image]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'DBエラー: ' . $e->getMessage()]);
}
?>