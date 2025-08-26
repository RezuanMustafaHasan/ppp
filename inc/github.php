<?php
require_once __DIR__ . '/config.php';

function github_repos($username, $limit = 6) {
  $cacheFile = __DIR__ . '/../cache/github_repos.json';
  $cacheTTL = 60 * 60 * 6; // 6 hours

  // Serve from cache if fresh
  if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTTL)) {
    $data = json_decode(file_get_contents($cacheFile), true);
    if ($data) return array_slice($data, 0, $limit);
  }

  $url = "https://api.github.com/users/{$username}/repos?sort=updated&per_page=100";
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_USERAGENT, "HasanPortfolio/1.0"); // GitHub requires a UA
  curl_setopt($ch, CURLOPT_TIMEOUT, 6);
  $response = curl_exec($ch);
  $err = curl_error($ch);
  $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  if ($err || $code >= 400 || !$response) {
    // Fallback to curated list from config
    global $PROJECT_FALLBACKS;
    file_put_contents($cacheFile, json_encode($PROJECT_FALLBACKS, JSON_PRETTY_PRINT));
    return array_slice($PROJECT_FALLBACKS, 0, $limit);
  }

  $repos = json_decode($response, true);
  if (!is_array($repos)) $repos = [];

  // Filter: no forks, has description OR language
  $repos = array_values(array_filter($repos, function($r) {
    return !$r["fork"] && (strlen(trim($r["description"] ?? "")) > 0 || !empty($r["language"]));
  }));

  // Map to smaller shape
  $mapped = array_map(function($r) {
    return [
      "name" => $r["name"],
      "html_url" => $r["html_url"],
      "description" => $r["description"],
      "language" => $r["language"],
      "stargazers_count" => $r["stargazers_count"],
      "updated_at" => $r["updated_at"]
    ];
  }, $repos);

  // Cache
  file_put_contents($cacheFile, json_encode($mapped, JSON_PRETTY_PRINT));

  return array_slice($mapped, 0, $limit);
}
