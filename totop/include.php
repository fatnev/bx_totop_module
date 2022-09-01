<?php
/*
* Файл local/modules/totop/include.php
*
* Регистрируем класс
*
*/


Bitrix\Main\Loader::registerAutoloadClasses(
    'totop',
    array(
        'Totop\\Main' => 'lib/Main.php',
    )
);