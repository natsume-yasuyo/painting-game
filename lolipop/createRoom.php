<?php
// CORS対応（必須）
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content
    exit;
}

// データベース接続情報
$host = 'mysql324.phy.lolipop.lan';
$dbname = 'LAA1648452-painting';
$user = 'LAA1648452';
$pass = 'LpmHwzDLnz67MYK';
$charset = 'utf8mb4';

// ヘッダー設定
header('Content-Type: application/json');

// JSON入力を取得
$data = json_decode(file_get_contents("php://input"), true);
$roomId = $data['roomId'] ?? '';
$password = $data['password'] ?? '';

// バリデーション
if (!$roomId || !$password) {
    echo json_encode(['success' => false, 'message' => '入力が不足しています']);
    exit;
}

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // 既存のルームがあるか確認
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM rooms WHERE id = ?");
    $stmt->execute([$roomId]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'このルームIDは既に存在します']);
        exit;
    }

    // ルーム作成
    $stmt = $pdo->prepare("INSERT INTO rooms (id, password, topic) VALUES (?, ?, ?)");
    $stmt->execute([$roomId, password_hash($password, PASSWORD_DEFAULT), $topic]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'DBエラー: ' . $e->getMessage()]);
}
?>
