<?php
// bm_update_view.php
require __DIR__ . '/config.php';

function h($v){ return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { http_response_code(400); echo '<meta charset="UTF-8">不正なアクセス（id不正）'; exit; }

try {
  $pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
  $stmt = $pdo->prepare('SELECT * FROM responses WHERE id = ?');
  $stmt->execute([$id]);
  $row = $stmt->fetch();
  if (!$row) { http_response_code(404); echo '<meta charset="UTF-8">データが見つかりません'; exit; }
} catch (PDOException $e) {
  http_response_code(500);
  echo '<meta charset="UTF-8">DBエラー: '.h($e->getMessage());
  exit;
}
?>
<!doctype html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>アンケート編集 #<?= (int)$row['id'] ?></title>
</head>
<body>
  <h1>アンケート編集 #<?= (int)$row['id'] ?></h1>

  <?php if (isset($_GET['err']) && $_GET['err']==='validation'): ?>
    <p style="color:#c00">入力に不備があります。</p>
  <?php endif; ?>

  <form action="bm_update.php" method="post" accept-charset="UTF-8">
    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">

    <p>
      <label>名前<br>
        <input type="text" name="name" value="<?= h($row['name']) ?>" required>
      </label>
    </p>

    <p>
      <label>Email<br>
        <input type="email" name="email" value="<?= h($row['email']) ?>" required>
      </label>
    </p>

    <p>
      <label>電話番号<br>
        <input type="tel" name="phone" value="<?= h($row['phone']) ?>">
      </label>
    </p>

    <p>
      <label>対象の書籍<br>
        <input type="text" name="book" value="<?= h($row['book']) ?>" required>
      </label>
    </p>

    <p>
      <label>5段階評価（1〜5）<br>
        <input type="number" name="rating" min="1" max="5" step="1" value="<?= (int)$row['rating'] ?>" required>
      </label>
    </p>

    <p>
      <label>評価の理由<br>
        <textarea name="reason" rows="6" required><?= h($row['reason']) ?></textarea>
      </label>
    </p>

    <p>
      <button type="submit" name="action" value="update">更新</button>
      <button type="submit" name="action" value="delete" onclick="return confirm('削除します。よろしいですか？');">削除</button>
      <a href="bm_list_view.php">一覧に戻る</a>
    </p>
  </form>
</body>
</html>
