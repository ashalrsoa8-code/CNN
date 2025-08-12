d"><?php
require 'db.php';
// search and category filter
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$cat = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
$params = [];
$sql = "SELECT p.id,p.title,p.excerpt,p.slug,p.published_at,u.name as author,c.name as category
        FROM posts p
        LEFT JOIN users u ON p.author_id = u.id
        LEFT JOIN categories c ON p.category_id = c.id";
$where = [];
if ($q !== '') {
    $where[] = "(p.title LIKE :q OR p.excerpt LIKE :q OR p.content LIKE :q)";
    $params[':q'] = "%$q%";
}
if ($cat) {
    $where[] = 'p.category_id = :cat';
    $params[':cat'] = $cat;
}
if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
$sql .= ' ORDER BY p.published_at DESC LIMIT 20';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll();
// categories for sidebar
$cats = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
?>
 
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>MiniBlogger — Home</title>
  <style>
    /* INTERNAL CSS - nice, modern, responsive */
    :root{--accent:#2b6cb0;--muted:#666}
    *{box-sizing:border-box}
    body{font-family:Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; margin:0; background:#f5f7fb; color:#111}
    header{background:linear-gradient(135deg,#fff 0%,#f0f6ff 100%);padding:28px 20px;border-bottom:1px solid #e6eefc}
    .container{max-width:1100px;margin:18px auto;padding:0 16px}
    .brand{display:flex;gap:12px;align-items:center}
    .logo{height:48px;width:48px;border-radius:10px;background:var(--accent);display:flex;align-items:center;justify-content:center;color:white;font-weight:700}
    h1{margin:0;font-size:20px}
    .top-actions{margin-left:auto}
    .searchbar{display:flex;gap:8px;align-items:center;margin:14px 0}
    input[type=text]{flex:1;padding:10px 12px;border-radius:8px;border:1px solid #d6e4ff}
    button{background:var(--accent);color:#fff;border:0;padding:10px 14px;border-radius:8px;cursor:pointer}
    main{display:grid;grid-template-columns:1fr 320px;gap:24px}
    .card{background:white;padding:18px;border-radius:12px;box-shadow:0 6px 18px rgba(31,45,61,0.06);border:1px solid #edf4ff}
    .post{margin-bottom:14px}
    .post h2{margin:0 0 6px;font-size:18px}
    .meta{color:var(--muted);font-size:13px;margin-bottom:8px}
    .excerpt{color:#222;line-height:1.5}
    .category-pill{display:inline-block;background:#eef6ff;color:var(--accent);padding:6px 10px;border-radius:999px;font-size:12px;margin-left:8px}
    aside .cat{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px dashed #eef4ff}
    .new-btn{background:linear-gradient(90deg,#2763c1,#3ea0ff);padding:10px 14px;border-radius:10px;color:white;border:none}
    footer{padding:24px;text-align:center;color:var(--muted);font-size:14px}
    @media(max-width:900px){main{grid-template-columns:1fr}aside{order:2}}
  </style>
</head>
<body>
  <header>
    <div class="container" style="display:flex;align-items:center;">
      <div class="brand">
        <div class="logo">MB</div>
        <div>
          <h1>MiniBlogger</h1>
          <div style="color:var(--muted);font-size:13px">A small, beautiful blogging platform</div>
        </div>
      </div>
      <div class="top-actions" style="margin-left:auto">
        <button class="new-btn" onclick="location.href='create_post.php'">+ New Post</button>
      </div>
    </div>
  </header>
 
  <div class="container">
    <div class="searchbar">
      <form style="display:flex;flex:1;width:100%" onsubmit="event.preventDefault();document.getElementById('q').value=document.getElementById('q').value.trim();location.href='?q='+encodeURIComponent(document.getElementById('q').value)+'&cat='+encodeURIComponent(document.getElementById('cat').value);">
        <input id="q" type="text" placeholder="Search posts, keywords..." value="<?=htmlspecialchars($q)?>">
        <select id="cat" style="padding:10px;border-radius:8px;border:1px solid #d6e4ff">
          <option value="0">All categories</option>
          <?php foreach($cats as $c): ?>
            <option value="<?=$c['id']?>" <?=($cat==$c['id'])?'selected':''?>><?=$c['name']?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit">Search</button>
      </form>
    </div>
 
    <main>
      <section>
        <div class="card">
          <?php if (count($posts)===0): ?>
            <p style="color:var(--muted)">No posts yet. Click "New Post" to start.</p>
          <?php endif; ?>
          <?php foreach($posts as $p): ?>
            <article class="post">
              <h2><a href="view_post.php?slug=<?=urlencode($p['slug'])?>" style="color:inherit;text-decoration:none"><?=htmlspecialchars($p['title'])?></a></h2>
              <div class="meta">By <?=htmlspecialchars($p['author']?:'Unknown')?> • <?=date('M j, Y', strtotime($p['published_at']))?><?php if($p['category']): ?> <span class="category-pill"><?=htmlspecialchars($p['category'])?></span><?php endif; ?></div>
              <div class="excerpt"><?=nl2br(htmlspecialchars(substr($p['excerpt']?:strip_tags($p['content']),0,280)))?> ...</div>
            </article>
          <?php endforeach; ?>
        </div>
      </section>
 
      <aside>
        <div class="car
          <h3 style="margin-top:0">Categories</h3>
          <?php foreach($cats as $c): ?>
            <div class="cat"><a href="?cat=<?=$c['id']?>" style="text-decoration:none;color:inherit"><?=$c['name']?></a><span style="color:var(--muted)"><?=
              $pdo->prepare('SELECT COUNT(*) FROM posts WHERE category_id=?')->execute([$c['id']]) ? '' : ''?></span></div>
          <?php endforeach; ?>
        </div>
        <div style="height:18px"></div>
        <div class="card">
          <h4 style="margin:0 0 8px 0">Recent</h4>
          <?php
            $recent = $pdo->query('SELECT id,title,slug FROM posts ORDER BY published_at DESC LIMIT 5')->fetchAll();
            foreach($recent as $r) echo '<div style="padding:8px 0"><a href="view_post.php?slug='.urlencode($r['slug']).'">'.htmlspecialchars($r['title']).'</a></div>';
          ?>
        </div>
      </aside>
    </main>
 
    <footer>
      &copy; <?=date('Y')?> MiniBlogger — Built with PHP and internal styles
    </footer>
  </div>
</body>
</html>
