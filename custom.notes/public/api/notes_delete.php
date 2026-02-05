<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include.php';

use Bitrix\Main\Loader;
use Custom\Notes\NotesTable;

Loader::includeModule('custom.notes');
header('Content-Type: application/json');

$id = $_POST['ID'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'ID required']);
    exit;
}

NotesTable::delete($id);
echo json_encode(['success' => true]);
