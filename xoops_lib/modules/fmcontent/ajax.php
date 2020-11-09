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
 */

use XoopsModules\Fmcontent\Helper;

if (!isset($forMods)) {
    exit('Module not found');
}

require __DIR__ . '/header.php';

error_reporting(0);
$GLOBALS['xoopsLogger']->activated = false;

$contentId = Request::getString('id', '');
$content_text = Request::getString('value', '');

[$root, $id] = explode('_', $contentId);

if ((int)$id > 0) {
    // Initialize content handler
    $pageHandler = Helper::getInstance()->getHandler('Page');
    $content         = $pageHandler->get($id);
    $content->setVar('content_text', $content_text);
    if (!$pageHandler->insert($content)) {
        echo 'Error';
    } else {
        echo $content_text;
    }
}
