<?php
require_once __DIR__ . '/news_repository.php';
require_once __DIR__ . '/config.php';

$id = max(1, (int)($_GET['id'] ?? 0));
$item = fetch_news_by_id($id);
if (!$item) {
    http_response_code(404);
}

function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
?>
<!doctype html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $item ? 'Новость — ' . e($item['title']) : 'Новость не найдена' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="container">
      <?php if ($item): ?>
        <nav class="breadcrumb" aria-label="Хлебные крошки" style="margin-top: 24px;">
          <a href="index.php" class="breadcrumb-link">Главная</a>
          <span class="breadcrumb-sep">/</span>
          <span class="breadcrumb-current"><?= e($item['title']) ?></span>
        </nav>

        <article class="featured-card is-plain" style="margin-top: 12px;">
          <div class="article-layout">
            <div class="article-content">
              <div class="news-meta">
                <time datetime="<?= e($item['date']) ?>"><?= date('d.m.Y', strtotime($item['date'])) ?></time>
              </div>
              <h1 class="featured-title" style="margin-top: 12px;"><?= e($item['title']) ?></h1>
              <p class="featured-subtitle" style="margin-top: 8px;"><?= e(strip_tags($item['announce'])) ?></p>
              <div class="article-body">
                <?php
                  $plain = trim(strip_tags($item['content']));
                  $paragraphs = preg_split("/\R{2,}/", $plain);
                  foreach ($paragraphs as $para) {
                    $para = trim($para);
                    if ($para === '') { continue; }
                    echo '<p>' . e($para) . '</p>';
                  }
                ?>
              </div>
              <a class="news-more" href="index.php#news" style="margin-top: 20px;">Назад к новостям</a>
            </div>
            <aside class="article-aside">
              <?php if (!empty($item['image'])): ?>
                <img class="article-image" src="image/<?= e($item['image']) ?>" alt="<?= e($item['title']) ?>" loading="lazy">
              <?php endif; ?>
            </aside>
          </div>
        </article>
      <?php else: ?>
        <p>Новость не найдена.</p>
      <?php endif; ?>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
  </body>
  </html>


