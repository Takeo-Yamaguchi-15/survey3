<?php // write.php // ファイル識別用コメント
require __DIR__ . '/config.php'; // 本番接続情報の読み込み
function h($v){ return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); } // HTMLエスケープ関数定義
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: post.php'); exit; } // POST以外アクセスのリダイレクト

$name   = trim($_POST['name']  ?? ''); // 名前の受け取り
$email  = trim($_POST['email'] ?? ''); // Emailの受け取り
$phone  = trim($_POST['phone'] ?? ''); // 電話番号の受け取り
$book   = trim($_POST['book']  ?? ''); // 書籍名の受け取り
$reason = trim($_POST['reason']?? ''); // 理由の受け取り
$rating = filter_input(INPUT_POST,'rating',FILTER_VALIDATE_INT,['options'=>['min_range'=>1,'max_range'=>5]]); // 評価の検証

$errors = []; // エラー配列の初期化
if ($name==='') { $errors[]='名前は必須'; } // 必須チェック（名前）
if (!filter_var($email,FILTER_VALIDATE_EMAIL)) { $errors[]='Email不正'; } // 形式チェック（Email）
if ($book==='') { $errors[]='書籍は必須'; } // 必須チェック（書籍）
if ($rating===false) { $errors[]='評価は1〜5'; } // 範囲チェック（評価）
if ($reason==='') { $errors[]='理由は必須'; } // 必須チェック（理由）

if ($errors) { echo '<meta charset="UTF-8"><ul>'; foreach($errors as $e){ echo '<li>'.h($e).'</li>'; } echo '</ul><a href="post.php">戻る</a>'; exit; } // エラー表示と戻り導線

try { // 例外処理開始
  $pdo = new PDO($dsn,$user,$pass,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]); // PDO接続確立
  $pdo->exec("CREATE TABLE IF NOT EXISTS responses (id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(100) NOT NULL,email VARCHAR(255) NOT NULL,phone VARCHAR(30),book VARCHAR(255) NOT NULL,rating TINYINT NOT NULL,reason TEXT NOT NULL,created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"); // テーブル自動作成
  $stmt = $pdo->prepare('INSERT INTO responses (name,email,phone,book,rating,reason) VALUES (?,?,?,?,?,?)'); // プリペアドステートメント生成
  $stmt->execute([$name,$email,$phone,$book,(int)$rating,$reason]); // パラメータバインド実行

  // ここを read.php → bm_list_view.php に変更したが、なぜかreadにとんてしまう
  echo '<meta charset="UTF-8">登録完了 <a href="bm_list_view.php">一覧へ</a> / <a href="post.php">続けて入力</a>'; // 完了メッセージ出力

} catch (PDOException $e) { // 例外捕捉
  http_response_code(500); // ステータスコード設定
  echo '<meta charset="UTF-8">DBエラー: '.h($e->getMessage()); // エラーメッセージ出力
} // 例外処理終了
