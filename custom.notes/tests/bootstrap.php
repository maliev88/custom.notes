<?php
$_SERVER['DOCUMENT_ROOT'] = realpath(__DIR__ . '/../../../..');

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include.php';

\Bitrix\Main\Loader::includeModule('custom.notes');
