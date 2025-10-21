<?php
// MySQL接続情報（docker-compose.yml の設定に合わせる）
$host = 'db';       // MySQLコンテナのサービス名
$user = 'myuser';   // 環境変数 MYSQL_USER
$pass = 'mypass';   // 環境変数 MYSQL_PASSWORD
$dbname = 'mydb';   // 環境変数 MYSQL_DATABASE
// MySQLへ接続
$conn = new mysqli($host, $user, $pass, $dbname);
// 接続エラーの確認
if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}
echo "<h1>MySQL接続成功！</h1>";
// テスト用のテーブル作成
$conn->query("CREATE TABLE IF NOT EXISTS test (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message VARCHAR(255) NOT NULL
)");
// データ挿入
$conn->query("INSERT INTO test (message) VALUES ('Hello from PHP & MySQL')");
// データ取得
$result = $conn->query("SELECT * FROM test");
echo "<h2>testテーブルの内容:</h2>";
echo "<ul>";
while ($row = $result->fetch_assoc()) {
    echo "<li>ID: {$row['id']} - Message: {$row['message']}</li>";
}
echo "</ul>";
// 接続終了
$conn->close();
?>