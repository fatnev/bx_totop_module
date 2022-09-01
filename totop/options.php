<?php
/*
* Файл local/modules/totop/options.php
* 
* Страница настроек в админке 
* 
* Включать/выключать модуль;
* Подключать библиотеку jQuery, если она еще не подключена;
* Изменять ширину/высоту/радиус и цвет кнопки;
* Менять положение кнопки и скорость анимации.
* 
*/

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

// получаем идентификатор модуля
$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialchars($request['mid'] != '' ? $request['mid'] : $request['id']);
// подключаем наш модуль
Loader::includeModule($module_id);

/*
 * Параметры модуля со значениями по умолчанию
 */
$aTabs = array(
    array(
        /*
         * Первая вкладка «Основные настройки»
         */
        'DIV'     => 'edit1',
        'TAB'     => Loc::getMessage('TOTOP_OPTIONS_TAB_GENERAL'),
        'TITLE'   => Loc::getMessage('TOTOP_OPTIONS_TAB_GENERAL'),
        'OPTIONS' => array(
            array(
                'switch_on',                                   // имя элемента формы
                Loc::getMessage('TOTOP_OPTIONS_SWITCH_ON'), // поясняющий текст — «Включить прокрутку»
                'Y',                                           // значение по умолчанию «да»
                array('checkbox')                              // тип элемента формы — checkbox
            ),
            array(
                'jquery_on',                                   // имя элемента формы
                Loc::getMessage('TOTOP_OPTIONS_JQUERY_ON'), // поясняющий текст — «Подключить jQuery»
                'N',                                           // значение по умолчанию «нет»
                array('checkbox')                              // тип элемента формы — checkbox
            ),
        )
    ),
    array(
        /*
         * Вторая вкладка «Дополнительные настройки»
         */
        'DIV'     => 'edit2',
        'TAB'     => Loc::getMessage('TOTOP_OPTIONS_TAB_ADDITIONAL'),
        'TITLE'   => Loc::getMessage('TOTOP_OPTIONS_TAB_ADDITIONAL'),
        'OPTIONS' => array(
            /*
             * секция «Внешний вид»
             */
            Loc::getMessage('TOTOP_OPTIONS_SECTION_VIEW'),
            array(
                'width',                                    // имя элемента формы
                Loc::getMessage('TOTOP_OPTIONS_WIDTH'),  // поясняющий текст — «Ширина (пикселей)»
                '50',                                       // значение по умолчанию 50px
                array('text', 5)                            // тип элемента формы — input type="text", ширина 5 симв.
            ),
            array(
                'height',                                   // имя элемента формы
                Loc::getMessage('TOTOP_OPTIONS_HEIGHT'), // поясняющий текст — «Высота (пикселей)»
                '50',                                       // значение по умолчанию 50px
                array('text', 5)                            // тип элемента формы — input type="text", ширина 5 симв.
            ),
            array(
                'radius',                                   // имя элемента формы
                Loc::getMessage('TOTOP_OPTIONS_RADIUS'), // поясняющий текст — «Радиус (пикселей)»
                '50',                                       // значение по умолчанию 50px
                array('text', 5)                            // тип элемента формы — input type="text", ширина 5 симв.
            ),
            array(
                'color',                                    // имя элемента формы
                Loc::getMessage('TOTOP_OPTIONS_COLOR'),  // поясняющий текст — «Цвет фона»
                '#bf3030',                                  // значение по умолчанию #bf3030
                array('text', 5)                            // тип элемента формы — input type="text", ширина 5 симв.
            ),
            /*
             * секция «Положение на странице»
             */
            Loc::getMessage('TOTOP_OPTIONS_SECTION_LAYOUT'),
            array(
                'side',                                       // имя элемента формы
                Loc::getMessage('TOTOP_OPTIONS_POSITION'), // поясняющий текст — «Положение кнопки»
                'left',                                       // значение по умолчанию «left»
                array(
                    'selectbox',                              // тип элемента формы — <select>
                    array(
                        'left'  => Loc::getMessage('TOTOP_OPTIONS_SIDE_LEFT'),
                        'right' => Loc::getMessage('TOTOP_OPTIONS_SIDE_RIGHT')
                    )
                )
            ),
            array(
                'indent_bottom',                                   // имя элемента формы
                Loc::getMessage('TOTOP_OPTIONS_INDENT_BOTTOM'), // поясняющий текст — «Отступ снизу (пикселей)»
                '10',                                              // значение по умолчанию 10px
                array('text', 5)                                   // тип элемента формы — input type="text"
            ),
            array(
                'indent_side',                                     // имя элемента формы
                Loc::getMessage('TOTOP_OPTIONS_INDENT_SIDE'),   // поясняющий текст — «Отступ сбоку (пикселей)»
                '10',                                              // значение по умолчанию 10px
                array('text', 5)                                   // тип элемента формы — input type="text"
            ),
            /*
             * секция «Поведение»
             */
            Loc::getMessage('TOTOP_OPTIONS_SECTION_ACTION'),
            array(
                'speed',                                   // имя элемента формы
                Loc::getMessage('TOTOP_OPTIONS_SPEED'), // поясняющий текст — «Скорость прокрутки»
                'normal',                                  // значение по умолчанию «normal»
                array(
                    'selectbox',                           // тип элемента формы — <select>
                    array(
                        'slow'   => Loc::getMessage('TOTOP_OPTIONS_SPEED_SLOW'),
                        'normal' => Loc::getMessage('TOTOP_OPTIONS_SPEED_NORMAL'),
                        'fast'   => Loc::getMessage('TOTOP_OPTIONS_SPEED_FAST')
                    )
                )
            )
        )
    )
);

/*
 * Создаем форму для редактирвания параметров модуля
 */
$tabControl = new CAdminTabControl(
    'tabControl',
    $aTabs
);

$tabControl->begin();
?>
<form action="<?= $APPLICATION->getCurPage(); ?>?mid=<?=$module_id; ?>&lang=<?= LANGUAGE_ID; ?>" method="post">
    <?= bitrix_sessid_post(); ?>
    <?php
    foreach ($aTabs as $aTab) { // цикл по вкладкам
        if ($aTab['OPTIONS']) {
            $tabControl->beginNextTab();
            __AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
        }
    }
    $tabControl->buttons();
    ?>
    <input type="submit" name="apply" 
           value="<?= Loc::GetMessage('TOTOP_OPTIONS_INPUT_APPLY'); ?>" class="adm-btn-save" />
    <input type="submit" name="default"
           value="<?= Loc::GetMessage('TOTOP_OPTIONS_INPUT_DEFAULT'); ?>" />
</form>

<?php
$tabControl->end();

/*
 * Обрабатываем данные после отправки формы
 */
if ($request->isPost() && check_bitrix_sessid()) {

    foreach ($aTabs as $aTab) { // цикл по вкладкам
        foreach ($aTab['OPTIONS'] as $arOption) {
            if (!is_array($arOption)) { // если это название секции
                continue;
            }
            if ($arOption['note']) { // если это примечание
                continue;
            }
            if ($request['apply']) { // сохраняем введенные настройки
                $optionValue = $request->getPost($arOption[0]);
                if ($arOption[0] == 'switch_on') {
                    if ($optionValue == '') {
                        $optionValue = 'N';
                    }
                }
                if ($arOption[0] == 'jquery_on') {
                    if ($optionValue == '') {
                        $optionValue = 'N';
                    }
                }
                Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(',', $optionValue) : $optionValue);
            } elseif ($request['default']) { // устанавливаем по умолчанию
                Option::set($module_id, $arOption[0], $arOption[2]);
            }
        }
    }

    LocalRedirect($APPLICATION->getCurPage().'?mid='.$module_id.'&lang='.LANGUAGE_ID);

}
?>