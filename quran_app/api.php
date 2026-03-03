<?php
// Simple proxy to Quran.com API to avoid CORS issues from browser.
// Usage:
//  - /quran_app/api.php?endpoint=chapters
//  - /quran_app/api.php?endpoint=verses&chapter=1

$endpoint = $_GET['endpoint'] ?? '';
$base = 'https://api.quran.com/api/v4';


if ($endpoint === 'chapters') {
    $url = "$base/chapters";
} elseif ($endpoint === 'verses' && isset($_GET['chapter'])) {
    $chapter = intval($_GET['chapter']);
    // request verses by chapter, only text
    $url = "$base/verses/by_chapter/" . $chapter . "?language=en&per_page=1000&fields=text_uthmani";
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid endpoint']);
    exit;
}


$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'QuranApp/1.0');
$response = curl_exec($ch);
$err = curl_error($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

header('Content-Type: application/json');
if ($err) {
    echo json_encode(['error' => $err]);
} else {
    http_response_code($http_code);
    echo $response;
}
