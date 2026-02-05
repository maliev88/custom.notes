<?php
use Bitrix\Main\Loader;

//\Bitrix\Main\Loader::includeModule("custom.notes");

// Подключаем D7
Loader::registerAutoLoadClasses(
    "custom.notes", // идентификатор модуля
    [
        "Custom\\Notes\\NotesTable" => "classes/NotesTable.php",
        "Custom\\Notes\\NotesApi"   => "classes/NotesApi.php",
        // здесь можно добавить другие классы модуля
    ]
);