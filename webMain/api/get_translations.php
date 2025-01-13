<?php
header('Content-Type: application/json');

require_once '../languages/translations.php';

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'uz';
$lang = in_array($lang, ['uz', 'ru', 'en']) ? $lang : 'uz';

echo json_encode($translations[$lang]); 