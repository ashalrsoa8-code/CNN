<?php
require 'db.php';
// fetch categories and authors
$cats = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$authors = $pdo->query('SELECT * FROM users ORDER BY name')->fetchAll();
?>
 
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Create Post — MiniBlogger</title>
  <style>
    /* internal CSS for editor */
    body{font-family:Inter, system-ui, -apple-system, 'Segoe UI', Roboto; background:#f7fafc;margin:0}
    .wrap{max-width:900px;margin:26px auto;padding:0 16px}
    .card{background:#fff;padding:18px;border-radius:12px;border:1px solid #e9f2ff}
    input, select{width:100%;padding:10px;border-radius:8px;border:1px solid #d6e4ff;margin-bottom:12px}
    .toolbar{display:flex;gap:8px;margin-bottom:8px}
    .toolbar button{padding:8px;border-radius:8px;border:1px solid #e0eefc;background:white;cursor:pointer}
    #editor{min-height:300px;border-radius:8px;padding:12px;border:1px solid #eef6ff;background:#fcfdff}
    .actions{display:flex;gap:8px;justify-content:flex-end;margin-top:12px}
    @media(max-width:640px){.toolbar{flex-wrap:wrap}}
  </style>
</head>
<body>
  <div class="wrap">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
      <h2>Create New Post</h2>
      <button onclick="location.href='index.php'">Back</button>
    </div>
 
    <div class="card">
      <input id="title" placeholder="Post title">
      <input id="slug" placeholder="slug (optional, auto-generated if empty)">
      <input id="excerpt" placeholder="Short excerpt / summary">
      <div style="display:flex;gap:10px;margin-bottom:12px">
        <select id="author" style
