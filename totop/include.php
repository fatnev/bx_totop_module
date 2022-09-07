<?php
/*
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