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
 * FmContent header file
 * Manage content page
 *
 * @copyright   {@link https://xoops.org/ XOOPS Project}
 * @license     {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @author      Andricq Nicolas (AKA MusS)
 * @package     forcontent
 */

use  XoopsModules\Fmcontent;
use XoopsModules\Fmcontent\Helper;

require dirname(__DIR__, 2) . '/mainfile.php';

require XOOPS_TRUST_PATH . '/modules/fmcontent/preloads/autoloader.php';

require_once XOOPS_TRUST_PATH . '/modules/fmcontent/include/functions.php';
//require_once XOOPS_TRUST_PATH . '/modules/fmcontent/class/perm.php';
//require_once XOOPS_TRUST_PATH . '/modules/fmcontent/class/utils.php';
// Load template class
require_once XOOPS_ROOT_PATH . '/class/template.php';

$moduleDirName = basename(__DIR__);
$helper = Helper::getInstance();

/** @var \XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$forMods       = $moduleHandler->getByDirname($moduleDirName);
//$forMods =  $helper->getHandler($moduleDirName);
