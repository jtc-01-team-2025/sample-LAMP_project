<?php
// DB接続情報（docker-compose.ymlに合わせる）
$host = 'db';       // MySQLコンテナのサービス名
$user = 'myuser';
$pass = 'mypass';
$dbname = 'mydb';
// DB接続
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}
// テーブルがなければ作成
$conn->query("CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content VARCHAR(255) NOT NULL
)");
// フォーム送信された場合
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $message = $conn->real_escape_string($_POST['message']); // SQLインジェクション対策
    $conn->query("INSERT INTO messages (content) VALUES ('$message')");
}
// 保存されているメッセージを取得
$result = $conn->query("SELECT * FROM messages ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>メッセージ送信</title>
</head>

<body>
    <h1>メッセージ送信フォーム</h1>
    <form method="post" action="">
        <input type="text" name="message" placeholder="メッセージを入力" required>
        <button type="submit">送信</button>
    </form>
    <h2>保存されたメッセージ一覧</h2>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
        <li>
            <?= htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8') ?>
        </li>
        <?php endwhile; ?>
    </ul>
</body>

</html>
<?php
$conn->close();
?>