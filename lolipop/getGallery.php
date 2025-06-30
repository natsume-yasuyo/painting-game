<?php
// データベース接続情報
$host = 'mysql324.phy.lolipop.lan';
$dbname = 'LAA1648452-painting';
$user = 'LAA1648452';
$pass = 'LpmHwzDLnz67MYK';
$charset = 'utf8mb4';

header('Content-Type: application/json');

$roomId = $_GET['roomId'] ?? '';

if (!$roomId) {
    echo json_encode(['success' => false, 'message' => 'ルームIDが必要です']);
    exit;
}

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // 最新順に画像を取得（最大20件）
    $stmt = $pdo->prepare("SELECT image FROM drawings WHERE room_id = ? ORDER BY created_at DESC LIMIT 20");
    $stmt->execute([$roomId]);

    $images = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode(['success' => true, 'images' => $images]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'DBエラー: ' . $e->getMessage()]);
}
?>
