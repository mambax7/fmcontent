<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

use Xmf\Request;
use XoopsModules\Fmcontent;
use XoopsModules\Fmcontent\Helper;

require __DIR__ . '/header.php';

$com_itemid = Request::getInt('com_itemid', 0, 'GET');
if ($com_itemid > 0) {
    $pageHandler = Helper::getInstance()->getHandler('Page');
    $content         = $pageHandler->get($com_itemid);
    $com_replytitle  = $content->getVar('content_title');
    include_once XOOPS_ROOT_PATH . '/include/comment_new.php';
}
