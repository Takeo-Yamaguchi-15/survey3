<?php
// bm_update.php
// ※ header() を使うため、このファイルでは何も出力しないこと（BOM含む）
require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: bm_list_view.php?msg=error');
  exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$action = $_POST['action'] ?? '';

if (!$id || !in_array($action, ['update','delete'], true)) {
  header('Location: bm_list_view.php?msg=error');
  exit;
}

try {
  $pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);

  if ($action === 'delete') {
    $stmt = $pdo->prepare('DELETE FROM responses WHERE id = ?');
    $stmt->execute([$id]);
    header('Location: bm_list_view.php?msg=deleted');
    exit;
  }

  // 更新
  $name   = trim($_POST['name']  ?? '');
  $email  = trim($_POST['email'] ?? '');
  $phone  = trim($_POST['phone'] ?? '');
  $book   = trim($_POST['book']  ?? '');
  $reason = trim($_POST['reason']?? '');
  $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT, [
    'options' => ['min_range'=>1, 'max_range'=>5]
  ]);

  if ($name==='' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $book==='' || $rating===false || $reason==='') {
    header('Location: bm_update_view.php?id='.$id.'&err=validation');
    exit;
  }

  $stmt = $pdo->prepare('
    UPDATE responses
    SET name = ?, email = ?, phone = ?, book = ?, rating = ?, reason = ?
    WHERE id = ?
  ');
  $stmt->execute([$name, $email, $phone, $book, (int)$rating, $reason, $id]);

  header('Location: bm_list_view.php?msg=updated');
  exit;

} catch (PDOException $e) {
  header('Location: bm_list_view.php?msg=error');
  exit;
}
