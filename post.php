<?php // post.php // ファイル識別用コメント ?>
<!doctype html> <!-- HTML5宣言 -->
<html lang="ja"> <!-- 文書の言語指定 -->
<head> <!-- ヘッダ領域開始 -->
  <meta charset="UTF-8"> <!-- 文字エンコーディング指定 -->
  <title>ブックマークアプリ</title> <!-- ページタイトル -->
</head> <!-- ヘッダ領域終了 -->
<body> <!-- 本文開始 -->
  <h1>ブックマークアプリ</h1> <!-- 見出し表示 -->
  <form action="write.php" method="post"> <!-- 送信先とメソッドの指定 -->
    <label>名前 <input type="text" name="name" required></label><br> <!-- 名前入力欄 -->
    <label>Email <input type="email" name="email" required></label><br> <!-- Email入力欄 -->
    <label>電話番号 <input type="tel" name="phone"></label><br> <!-- 電話番号入力欄 -->
    <label>対象の書籍 <input type="text" name="book" required></label><br> <!-- 書籍名入力欄 -->
    <label>5段階評価 <input type="number" name="rating" min="1" max="5" required></label><br> <!-- 評価数値入力欄 -->
    <label>評価の理由 <textarea name="reason" required></textarea></label><br> <!-- 理由入力欄 -->
    <button type="submit">送信</button> <!-- 送信ボタン -->
    <a href="read.php">一覧を見る</a> <!-- 一覧ページへの導線 -->
  </form> <!-- フォーム終了 -->
</body> <!-- 本文終了 -->
</html> <!-- HTML終了 -->
