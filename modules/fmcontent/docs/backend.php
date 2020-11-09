<?php

/**
 * XOOPS feed creator
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @since           2.0.0
 */
require_once __DIR__ . '/mainfile.php';

require_once XOOPS_TRUST_PATH . '/modules/fmcontent/include/functions.php';
require_once XOOPS_TRUST_PATH . '/modules/fmcontent/class/perm.php';
// Load template class
require_once XOOPS_ROOT_PATH . '/class/template.php';

$modsDirname = basename(XOOPS_TRUST_PATH . '/modules/fmcontent');

/** @var \XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$forMods       = $moduleHandler->getByDirname($modsDirname);
require_once XOOPS_TRUST_PATH . '/modules/fmcontent/rss.php';
