<?php
/**
 * Unnati PostBase — media library (the images in uploads/) · https://postbase.top
 * SPDX-License-Identifier: AGPL-3.0-or-later OR LicenseRef-PostBase-Commercial
 */
if (!defined('PB_ROOT')) { http_response_code(403); exit; }

define('PB_MEDIA_EXTS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

/** Every image in uploads/, newest first: [rel, url, name, size, mtime]. Optional filename filter. */
function pb_media_list($q = '') {
    $out = [];
    if (!is_dir(PB_UPLOAD_DIR)) return $out;
    $q = strtolower(trim((string) $q));
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(PB_UPLOAD_DIR, FilesystemIterator::SKIP_DOTS));
    foreach ($it as $f) {
        if (!$f->isFile() || !in_array(strtolower($f->getExtension()), PB_MEDIA_EXTS, true)) continue;
        $rel = str_replace('\\', '/', substr($f->getPathname(), strlen(PB_UPLOAD_DIR) + 1));
        if ($q !== '' && strpos(strtolower($rel), $q) === false) continue;
        $out[] = ['rel' => $rel, 'url' => PB_BASE_PATH . '/uploads/' . $rel, 'name' => $f->getFilename(),
                  'size' => $f->getSize(), 'mtime' => $f->getMTime()];
    }
    usort($out, function ($a, $b) { return $b['mtime'] - $a['mtime'] ?: strcmp($a['rel'], $b['rel']); });
    return $out;
}

/** Where each image URL is used: posts/pages (body or featured image) and settings (logo, share image…). */
function pb_media_usage(array $urls) {
    $use = array_fill_keys($urls, []);
    if (!$urls) return $use;
    $posts = pb_all('SELECT id, title, type, body, cover_image FROM posts');
    $settings = pb_all("SELECT key, value FROM settings WHERE value LIKE '%/uploads/%'");
    foreach ($urls as $u) {
        foreach ($posts as $p) {
            if (strpos($p['body'], $u) !== false || $p['cover_image'] === $u) {
                $use[$u][] = ['kind' => $p['type'], 'id' => (int) $p['id'], 'title' => $p['title']];
            }
        }
        foreach ($settings as $s) {
            if (strpos($s['value'], $u) !== false) $use[$u][] = ['kind' => 'setting', 'id' => 0, 'title' => $s['key']];
        }
    }
    return $use;
}

/** Resolve a relative uploads path to a real image file inside uploads/, or null. */
function pb_media_path($rel) {
    $rel = str_replace('\\', '/', (string) $rel);
    if ($rel === '' || strpos($rel, '..') !== false || $rel[0] === '/') return null;
    if (!in_array(strtolower(pathinfo($rel, PATHINFO_EXTENSION)), PB_MEDIA_EXTS, true)) return null;
    $base = realpath(PB_UPLOAD_DIR);
    $full = realpath(PB_UPLOAD_DIR . '/' . $rel);
    if (!$base || !$full || strpos($full, $base . DIRECTORY_SEPARATOR) !== 0 || !is_file($full)) return null;
    return $full;
}

/** Delete images by relative path. Returns [deleted count, skipped count]. */
function pb_media_delete(array $rels) {
    $deleted = 0; $skipped = 0;
    foreach (array_unique($rels) as $rel) {
        $full = pb_media_path($rel);
        if ($full && @unlink($full)) $deleted++; else $skipped++;
    }
    return [$deleted, $skipped];
}

function pb_human_size($bytes) {
    if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
    if ($bytes >= 1024) return round($bytes / 1024) . ' KB';
    return $bytes . ' B';
}
