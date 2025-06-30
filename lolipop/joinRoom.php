<?php
// データベース接続情報
$host = 'mysql324.phy.lolipop.lan';
$dbname = 'LAA1648452-painting';
$user = 'LAA1648452';
$pass = 'LpmHwzDLnz67MYK';
$charset = 'utf8mb4';

// ヘッダー設定
header('Content-Type: application/json');

// JSONで送られてきた値を取得
$data = json_decode(file_get_contents("php://input"), true);
$roomId = $data['roomId'] ?? '';
$password = $data['password'] ?? '';

if (!$roomId || !$password) {
    echo json_encode(['success' => false, 'message' => '入力が不足しています']);
    exit;
}

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // ルーム情報取得
    $stmt = $pdo->prepare("SELECT password, topic FROM rooms WHERE id = ?");
    $stmt->execute([$roomId]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        echo json_encode(['success' => false, 'message' => 'ルームが存在しません']);
        exit;
    }

    // パスワード照合
    if (!password_verify($password, $room['password'])) {
        echo json_encode(['success' => false, 'message' => 'パスワードが違います']);
        exit;
    }

    // 成功
    echo json_encode(['success' => true, 'topic' => $room['topic']]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'DBエラー: ' . $e->getMessage()]);
}
?>
