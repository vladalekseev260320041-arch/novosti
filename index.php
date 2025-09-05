<?php
require_once __DIR__ . '/news_repository.php';
require_once __DIR__ . '/config.php';

$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = NEWS_PER_PAGE;
$offset = ($page - 1) * $perPage;

$total = fetch_news_count();
$news = fetch_news_paginated($perPage, $offset);
$latest = fetch_latest_news();
$totalPages = max(1, (int)ceil($total / $perPage));

function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
?>
<!doctype html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Галактический вестник — Новости</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <?php if ($latest): ?>
    <section class="banner" style="background-image: url('image/<?= e($latest['image']) ?>')">
      <div class="container banner-inner">
        <h2 class="banner-title"><?= e($latest['title']) ?></h2>
        <p class="banner-subtitle"><?= e(strip_tags($latest['announce'])) ?></p>
      </div>
    </section>
    <?php endif; ?>

    <main class="container">
      <section class="news" id="news">
        <div class="news-header">
          <h3>Новости</h3>
        </div>
        <div class="news-grid">
          <?php foreach ($news as $item): ?>
            <article class="news-card">
              <div class="news-card-content">
                <div class="news-meta"><time datetime="<?= e($item['date']) ?>"><?= date('d.m.Y', strtotime($item['date'])) ?></time></div>
                <h4 class="news-title"><?= e($item['title']) ?></h4>
                <p class="news-excerpt"><?= e(strip_tags($item['announce'])) ?></p>
                <a class="news-more" href="news.php?id=<?= (int)$item['id'] ?>">Подробнее</a>
              </div>
            </article>
          <?php endforeach; ?>
        </div>

        <nav class="pagination" role="navigation" aria-label="Навигация по страницам">
          <?php
            $maxVisible = 3;
            $visiblePages = min($totalPages, $maxVisible);
            for ($i = 1; $i <= $visiblePages; $i++):
          ?>
            <a class="page<?= $i === $page ? ' is-active' : '' ?>" href="?page=<?= $i ?>#news"<?= $i === $page ? ' aria-current="page"' : '' ?>><?= $i ?></a>
          <?php endfor; ?>
          <?php if ($totalPages > $maxVisible && $page < $totalPages): ?>
            <a class="page page-arrow next" href="?page=<?= min($totalPages, $page + 1) ?>#news" aria-label="Следующая страница"></a>
          <?php endif; ?>
        </nav>
      </section>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
  </body>
  </html>


