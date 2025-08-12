<?php
include 'data.php';
 
$category = $_GET['category'] ?? 'World';
$filtered = array_filter($news, fn($item) => $item['category'] === $category);
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $category ?> News</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f0f0f0; color: #333; }
    header { background: #cc0000; padding: 20px; text-align: center; color: #fff; }
    .container { max-width: 1100px; margin: auto; padding: 20px; }
    h2 { color: #cc0000; margin-bottom: 10px; }
    .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
    .card { background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); cursor: pointer; transition: 0.3s; }
    .card:hover { transform: scale(1.02); }
    .card img { width: 100%; height: 150px; object-fit: cover; border-radius: 5px; }
    .card h3 { margin: 10px 0 5px; }
    .card p { font-size: 14px; color: #666; }
    button { background: #cc0000; color: #fff; border: none; padding: 10px 15px; margin-bottom: 20px; cursor: pointer; border-radius: 5px; }
    button:hover { background: #a80000; }
  </style>
  <script>
    function goBack() { window.location.href = 'index.php'; }
    function goToArticle(id) {
      window.location.href = 'article.php?id=' + id;
    }
  </script>
</head>
<body>
  <header>
    <h1><?= $category ?> News</h1>
  </header>
 
  <div class="container">
    <button onclick="goBack()">← Back to Home</button>
 
    <div class="grid">
      <?php foreach($filtered as $item): ?>
        <div class="card" onclick="goToArticle(<?= $item['id'] ?>)">
          <img src="<?= $item['image'] ?>" alt="<?= $item['title'] ?>">
          <h3><?= $item['title'] ?></h3>
          <p><?= $item['description'] ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>
