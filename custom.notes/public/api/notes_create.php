<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include.php';

use Bitrix\Main\Loader;
use Custom\Notes\NotesTable;

Loader::includeModule('custom.notes');
header('Content-Type: application/json');

$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';

if (!$title) {
    http_response_code(400);
    echo json_encode(['error' => 'TITLE required']);
    exit;
}

$res = NotesTable::add(['TITLE' => $title, 'CONTENT' => $content]);
echo json_encode(['ID' => $res->getId()]);
