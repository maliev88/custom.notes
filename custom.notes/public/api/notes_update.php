<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include.php';

use Bitrix\Main\Loader;
use Custom\Notes\NotesTable;

Loader::includeModule('custom.notes');
header('Content-Type: application/json');

$id = $_POST['ID'] ?? null;
$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';

if (!$id || !$title) {
    http_response_code(400);
    echo json_encode(['error' => 'ID and TITLE required']);
    exit;
}

NotesTable::update($id, ['TITLE' => $title, 'CONTENT' => $content]);
echo json_encode(['success' => true]);
