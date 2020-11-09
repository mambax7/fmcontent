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

//use XoopsModules\Fmcontent;

require dirname(__DIR__, 3) . '/include/cp_header.php';

require XOOPS_TRUST_PATH . '/modules/fmcontent/preloads/autoloader.php';

//require XOOPS_TRUST_PATH . '/modules/fmcontent/preloads/autoloader.php';

//require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';


require_once $GLOBALS['xoops']->path('include/cp_header.php');
require_once $GLOBALS['xoops']->path('class/tree.php');
//require_once $GLOBALS['xoops']->path('modules/fmcontent/class/folder.php');

require_once XOOPS_TRUST_PATH . '/modules/fmcontent/include/functions.php';
//require_once XOOPS_TRUST_PATH . '/modules/fmcontent/class/utils.php';

xoops_load('xoopsformloader');

/** @var \XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$forMods       = $moduleHandler->getByDirname(basename(dirname(__DIR__)));
