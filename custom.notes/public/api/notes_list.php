<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Подключаем ядро Bitrix
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include.php';

use Bitrix\Main\Loader;
use Custom\Notes\NotesTable;

Loader::includeModule('custom.notes');
header('Content-Type: application/json');

$data = NotesTable::getList(['order' => ['ID' => 'DESC']])->fetchAll();
echo json_encode($data);
