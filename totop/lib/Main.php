<?php
/*
* Файл local/modules/totop/lib/Main.php
*
* В файле local/modules/totop/install/install.php, 
* привязались к событию OnBeforeEndBufferContent и указали метод appendJavaScriptAndCSS() класса Main как обработчик. 
*
*/

namespace Totop;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Page\Asset;

class Main {

    public function appendJavaScriptAndCSS() {

        // прокрутка будет работать только в публичной части
        if (!defined('ADMIN_SECTION') && ADMIN_SECTION !== true) {
            $module_id = pathinfo(dirname(__DIR__))['basename'];
            $options = json_encode(
                array(
                    'switch_on'     => Option::get($module_id, 'switch_on', 'Y'),
                    'width'         => Option::get($module_id, 'width', '50'),
                    'height'        => Option::get($module_id, 'height', '50'),
                    'radius'        => Option::get($module_id, 'radius', '50'),
                    'color'         => Option::get($module_id, 'color', '#bf3030'),
                    'side'          => Option::get($module_id, 'side', 'left'),
                    'indent_bottom' => Option::get($module_id, 'indent_bottom', '10'),
                    'indent_side'   => Option::get($module_id, 'indent_side', '10'),
                    'speed'         => Option::get($module_id, 'speed', 'normal')
                )
            );

            Asset::getInstance()->addCss('/bitrix/css/'.$module_id.'/style.css');
            // подключить библиотеку jQuery?
            if (Option::get($module_id, 'jquery_on', 'N') == 'Y') {
                \CJSCore::init(array('jquery2'));
            }

            Asset::getInstance()->addString(
                "<script id='".$module_id."-params' data-params='".$options."'></script>",
                true
            );

            Asset::getInstance()->addJs('/bitrix/js/'.$module_id.'/script.js');
            
        }
    }

}