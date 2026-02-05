<?php

use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class custom_notes extends CModule
{
    var $MODULE_ID = "custom.notes";
    var $MODULE_NAME = "Notes Module";
    var $MODULE_VERSION = "1.0.0";
    var $MODULE_VERSION_DATE = "2026-02-04";
    var $MODULE_DESCRIPTION = "Мини-сервис заметок";
    var $MODULE_PREFIX = "V1";

    function __construct()
    {
        $arModuleVersion = array();
        include(dirname(__FILE__) . '/version.php');

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('CUSTOM_NOTES_' . $this->MODULE_PREFIX . '_INSTALL_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('CUSTOM_NOTES_' . $this->MODULE_PREFIX . '_INSTALL_DESCRIPTION');

        $this->PARTNER_NAME = Loc::getMessage('CUSTOM_NOTES_' . $this->MODULE_PREFIX . '_INSTALL_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('CUSTOM_NOTES_' . $this->MODULE_PREFIX . '_INSTALL_PARTNER_URI');
    }

    function DoInstall()
    {
        $this->InstallDB();
        $this->InstallFiles();

        ModuleManager::registerModule($this->MODULE_ID);
    }

    function DoUninstall()
    {

        $this->UnInstallDB();
        $this->UnInstallFiles();

        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    function InstallDB($install_wizard = true)
    {
        global $DB, $APPLICATION;
        $connection = \Bitrix\Main\Application::getConnection();
        $errors = null;

        if (!$DB->TableExists('b_bp_workflow_instance')) {
            $errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/local/modules/custom.notes/install/db/' . $connection->getType() . '/install.sql');
        }

        if (!empty($errors)) {
            $APPLICATION->ThrowException(implode("", $errors));
            return false;
        }

        return true;
    }

    function UnInstallDB($arParams = array())
    {
        global $DB, $APPLICATION;
        $connection = \Bitrix\Main\Application::getConnection();
        $errors = null;
        $errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"] . "/local/modules/custom.notes/install/db/" . $connection->getType() . "/uninstall.sql");

        if (!empty($errors)) {
            $APPLICATION->ThrowException(implode("", $errors));
            return false;
        }

        return true;
    }

    function InstallFiles()
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/local/modules/custom.notes/install/components/", $_SERVER["DOCUMENT_ROOT"] . "/local/components/", true, true);

        $moduleApiPath = $_SERVER["DOCUMENT_ROOT"] . "/local/modules/custom.notes/public/api/";
        $publicApiPath = $_SERVER["DOCUMENT_ROOT"] . "/api/";

        if (!is_dir($publicApiPath)) {
            mkdir($publicApiPath, 0755, true); // true = рекурсивно, но у нас только одна папка
        }

        $files = scandir($moduleApiPath);

        foreach ($files as $file) {
            if (in_array($file, ['.', '..'])) continue;

            $source = $moduleApiPath . $file;
            $dest = $publicApiPath . $file;

            if (is_file($source)) {
                copy($source, $dest); // копируем файл, перезаписывая если уже есть
            }
        }

        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFilesEx("/local/components/custom-notes/");

        $moduleApiPath = $_SERVER["DOCUMENT_ROOT"] . "/local/modules/custom.notes/public/api/";
        $publicApiPath = $_SERVER["DOCUMENT_ROOT"] . "/api/";

        // Удаляем файлы, которые были скопированы из модуля
        $files = scandir($moduleApiPath);

        foreach ($files as $file) {
            if (in_array($file, ['.', '..'])) continue;

            $targetFile = $publicApiPath . $file;

            if (file_exists($targetFile) && is_file($targetFile)) {
                unlink($targetFile); // удаляем файл
            }
        }

        return true;
    }

}
