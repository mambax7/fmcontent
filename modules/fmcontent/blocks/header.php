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
 *
 * @copyright   {@link https://xoops.org/ XOOPS Project}
 * @license     {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @author      Andricq Nicolas (AKA MusS)
 * @package     forcontent
 */



require_once $GLOBALS['xoops']->path('class/tree.php');

require_once XOOPS_TRUST_PATH . '/modules/fmcontent/include/functions.php';

/** @var \XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$forMods       = $moduleHandler->getByDirname(basename(dirname(__DIR__)));
