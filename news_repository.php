<?php
require_once __DIR__ . '/db.php';

function fetch_news_paginated(int $limit, int $offset): array {
    $sql = 'SELECT id, date, title, announce, image FROM news ORDER BY date DESC LIMIT :limit OFFSET :offset';
    $stmt = get_pdo()->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function fetch_news_count(): int {
    $sql = 'SELECT COUNT(*) AS cnt FROM news';
    $stmt = get_pdo()->query($sql);
    $row = $stmt->fetch();
    return (int)($row['cnt'] ?? 0);
}

function fetch_news_by_id(int $id): ?array {
    $sql = 'SELECT id, date, title, announce, content, image FROM news WHERE id = :id LIMIT 1';
    $stmt = get_pdo()->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row ?: null;
}

function fetch_latest_news(): ?array {
    $sql = 'SELECT id, date, title, announce, image FROM news ORDER BY date DESC LIMIT 1';
    $stmt = get_pdo()->query($sql);
    $row = $stmt->fetch();
    return $row ?: null;
}


