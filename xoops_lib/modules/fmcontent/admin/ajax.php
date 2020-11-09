<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * FmContent edit in place file
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Andricq Nicolas (AKA MusS)
 * @author      Hossein Azizabadi (AKA Voltan)
 */

use XoopsModules\Fmcontent;

if (!isset($forMods)) {
    exit('Module not found');
}

error_reporting(0);
$GLOBALS['xoopsLogger']->activated = false;

$ajax_type = Request::getString('type', '');

switch ($ajax_type) {
    case 'filter':
        $value = $func = Request::getString('value', '');
        echo fmcontent_Filter($value);
        break;

    case 'menu':
        $value = $func = Request::getString('value', '');
        echo fmcontent_AjaxFilter($value);
        break;

    case 'words':
        $value = $func = Request::getString('value', '');
        echo fmcontent_MetaFilter($value);
        break;

    case 'desc':
        $value = $func = Request::getString('value', '');
        echo fmcontent_AjaxFilter($value);
        break;
}
