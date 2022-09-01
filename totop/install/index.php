<?php
/*
 * Файл local/modules/totop/install/index.php
 */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

Loc::loadMessages(__FILE__);

class totop extends CModule {

    public function __construct() {
        if (is_file(__DIR__.'/version.php')) {
            include_once(__DIR__.'/version.php');
            $this->MODULE_ID           = get_class($this);
            $this->MODULE_VERSION      = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
            $this->MODULE_NAME         = Loc::getMessage('TOTOP_NAME');
            $this->MODULE_DESCRIPTION  = Loc::getMessage('TOTOP_DESCRIPTION');
        } else {
            CAdminMessage::showMessage(
                Loc::getMessage('TOTOP_FILE_NOT_FOUND').' version.php'
            );
        }
    }
    
    public function doInstall() {

        global $APPLICATION;

        // мы используем функционал нового ядра D7 — поддерживает ли его система?
        if (CheckVersion(ModuleManager::getVersion('main'), '14.00.00')) {
            // копируем файлы, необходимые для работы модуля
            $this->installFiles();
            // создаем таблицы БД, необходимые для работы модуля
            $this->installDB();
            // регистрируем модуль в системе
            ModuleManager::registerModule($this->MODULE_ID);
            // регистрируем обработчики событий
            $this->installEvents();
        } else {
            CAdminMessage::showMessage(
                Loc::getMessage('TOTOP_INSTALL_ERROR')
            );
            return;
        }

        $APPLICATION->includeAdminFile(
            Loc::getMessage('TOTOP_INSTALL_TITLE').' «'.Loc::getMessage('TOTOP_NAME').'»',
            __DIR__.'/step.php'
        );
    }
    
    public function installFiles() {
        // копируем js-файлы, необходимые для работы модуля
        CopyDirFiles(
            __DIR__.'/assets/scripts',
            Application::getDocumentRoot().'/bitrix/js/'.$this->MODULE_ID.'/',
            true,
            true
        );
        // копируем css-файлы, необходимые для работы модуля
        CopyDirFiles(
            __DIR__.'/assets/styles',
            Application::getDocumentRoot().'/bitrix/css/'.$this->MODULE_ID.'/',
            true,
            true
        );
    }
    
    public function installDB() {
        return;
    }

    public function installEvents() {
        // перед выводом буферизированного контента добавим свой HTML код,
        // в котором сохраним настройки для нашей кнопки прокрутки наверх
        EventManager::getInstance()->registerEventHandler(
            'main',
            'OnBeforeEndBufferContent',
            $this->MODULE_ID,
            'Totop\\Main',
            'appendJavaScriptAndCSS'
        );
    }

    public function doUninstall() {

        global $APPLICATION;

        $this->uninstallFiles();
        $this->uninstallDB();
        $this->uninstallEvents();

        ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->includeAdminFile(
            Loc::getMessage('TOTOP_UNINSTALL_TITLE').' «'.Loc::getMessage('TOTOP_NAME').'»',
            __DIR__.'/unstep.php'
        );

    }

    public function uninstallFiles() {
        // удаляем js-файлы
        Directory::deleteDirectory(
            Application::getDocumentRoot().'/bitrix/js/'.$this->MODULE_ID
        );
        // удаляем css-файлы
        Directory::deleteDirectory(
            Application::getDocumentRoot().'/bitrix/css/'.$this->MODULE_ID
        );
        // удаляем настройки нашего модуля
        Option::delete($this->MODULE_ID);
    }
    
    public function uninstallDB() {
        return;
    }
    
    public function uninstallEvents() {
        // удаляем наш обработчик события
        EventManager::getInstance()->unRegisterEventHandler(
            'main',
            'OnBeforeEndBufferContent',
            $this->MODULE_ID,
            'Totop\\Main',
            'appendJavaScriptAndCSS'
        );
    }

}
