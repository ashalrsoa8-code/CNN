<?php
include 'data.php';
$id = $_GET['id'] ?? 1;
$article = null;
foreach ($news as $item) {
  if ($item['id'] == $id) {
    $article = $item;
    break;
  }
}
if (!$article) {
  echo "Article not found.";
  exit;
}
$related = array_filter($news, fn($n) => $n['category'] === $article['category'] && $n['id'] != $article['id']);
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $article['title'] ?></title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #fafafa; color: #333; }
    header { background: #cc0000; color: #fff; padding: 20px; text-align: center; }
    .container { max-width: 900px; margin: auto; padding: 20px; }
    h1 { color: #cc0000; margin-bottom: 10px; }
    img { width: 100%; max-height: 400px; object-fit: cover; border-radius: 10px; margin-bottom: 20px; }
    p { font-size: 16px; line-height: 1.6; }
    .related { margin-top: 40px; }
    .related h3 { color: #cc0000; }
    .related-item { margin-top: 15px; cursor: pointer; padding: 10px; background: #fff; border-left: 5px solid #cc0000; border-radius: 5px; transition: 0.3s; }
    .related-item:hover { background: #f0f0f0; }
    button { background: #cc0000; color: #fff; border: none; padding: 10px 15px; margin-bottom: 20px; cursor: pointer; border-radius: 5px; }
    button:hover { background: #a80000; }
  </style>
  <script>
    function goBack() {
      window.history.back();
    }
    function goToArticle(id) {
      window.location.href = 'article.php?id=' + id;
    }
  </script>
</head>
<body>
  <header>
    <h1><?= $article['title'] ?></h1>
  </header>
 
  <div class="container">
    <button onclick="goBack()">← Back</button>
    <img src="<?= $article['image'] ?>" alt="<?= $article['title'] ?>">
    <p><?= $article['content'] ?></p>
 
    <div class="related">
      <h3>Related News</h3>
      <?php foreach($related as $rel): ?>
        <div class="related-item" onclick="goToArticle(<?= $rel['id'] ?>)">
          <?= $rel['title'] ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>
