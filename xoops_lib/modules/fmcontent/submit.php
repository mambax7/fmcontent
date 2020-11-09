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
 * FmContent submit file
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Hossein Azizabadi (AKA Voltan)
 */

use XoopsModules\Fmcontent;
use XoopsModules\Fmcontent\Helper;

if (!isset($forMods)) {
    exit('Module not found');
}

require __DIR__ . '/header.php';

include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH . '/class/tree.php';

$op = Request::getString('op', '');

// include Xoops header
include XOOPS_ROOT_PATH . '/header.php';

$pageHandler = Helper::getInstance()->getHandler('Page');
$topicHandler   = Helper::getInstance()->getHandler('Topic');
$fileHandler    = Helper::getInstance()->getHandler('File');

// Include language file
xoops_loadLanguage('admin', $forMods->getVar('dirname', 'e'));

// Check the access permission
global $xoopsUser;
$perm_handler = Fmcontent\Permission::getHandler();
if (!$perm_handler->isAllowed($xoopsUser, 'fmcontent_ac', '8', $forMods)) {
    redirect_header('index.php', 3, _NOPERM);
    exit();
}

switch ($op) {
    case 'add':

        if (!isset($_POST ['post'])) {
            redirect_header('index.php', 3, _NOPERM);
            exit();
        }

        if ($_REQUEST ['content_modid'] != $forMods->getVar('mid')) {
            redirect_header('index.php', 3, _NOPERM);
            exit();
        }

        $groups = xoops_getModuleOption('groups', $forMods->getVar('dirname'));
        $groups = $groups ?? '';
        $groups = is_array($groups) ? implode(' ', $groups) : '';

        $obj = $pageHandler->create();
        $obj->setVars($_REQUEST);

        $obj->setVar('content_order', $pageHandler->setorder($forMods));
        $obj->setVar('content_next', $pageHandler->setNext($forMods, $_REQUEST ['content_topic']));
        $obj->setVar('content_prev', $pageHandler->setPrevious($forMods, $_REQUEST ['content_topic']));
        $obj->setVar('content_menu', fmcontent_AjaxFilter($_REQUEST ['content_title']));
        $obj->setVar('content_alias', fmcontent_Filter($_REQUEST ['content_title']));
        $obj->setVar('content_words', fmcontent_MetaFilter($_REQUEST ['content_title']));
        $obj->setVar('content_desc', fmcontent_AjaxFilter($_REQUEST ['content_title']));
        $obj->setVar('content_create', time());
        $obj->setVar('content_update', time());
        $obj->setVar('content_groups', $groups);

        //Form topic_img
        Fmcontent\Utils::uploadimg($forMods, 'content_img', $obj, $_REQUEST ['content_img']);

        if ($perm_handler->isAllowed($xoopsUser, 'fmcontent_ac', '16', $forMods)) {
            $obj->setVar('content_status', '1');
            $pageHandler->updateposts($_REQUEST ['content_uid'], '1', $content_action = 'add');
        }

        if (!$pageHandler->insert($obj)) {
            fmcontent_Redirect('onclick="javascript:history.go(-1);"', 1, _FMCONTENT_MSG_ERROR);
            include XOOPS_ROOT_PATH . '/footer.php';
            exit();
        }

        // Reset next content for previous content
        $pageHandler->resetNext($forMods, $_REQUEST ['content_topic'], $obj->getVar('content_id'));
        $pageHandler->resetPrevious($forMods, $_REQUEST ['content_topic'], $obj->getVar('content_id'));

        if (xoops_getModuleOption('usetag', $forMods->getVar('dirname')) && is_dir(XOOPS_ROOT_PATH . '/modules/tag')) {
            $tag_handler = xoops_getModuleHandler('tag', 'tag');
            $tag_handler->updateByItem($_POST ['item_tag'], $obj->getVar('content_id'), $forMods->getVar('dirname'), 0);
        }

        // file
        if ($_REQUEST ['file_name']) {
            $fileobj = $fileHandler->create();
            $fileobj->setVar('file_date', time());
            $fileobj->setVar('file_modid', $forMods->getVar('mid'));
            $fileobj->setVar('file_title', $_REQUEST ['content_title']);
            $fileobj->setVar('file_content', $obj->getVar('content_id'));
            $fileobj->setVar('file_status', 1);

            Fmcontent\Utils::uploadfile($forMods, 'file_name', $fileobj, $_REQUEST ['file_name']);
            $pageHandler->contentfile('add', $obj->getVar('content_id'));
            if (!$fileHandler->insert($fileobj)) {
                fmcontent_Redirect('onclick="javascript:history.go(-1);"', 1, _FMCONTENT_MSG_ERROR);
                xoops_cp_footer();
                exit();
            }
        }

        // Redirect page
        fmcontent_Redirect('index.php', 1, _FMCONTENT_MSG_WAIT);
        include XOOPS_ROOT_PATH . '/footer.php';
        exit();
        break;

    default:

        $contentType = Request::getString('content_type', 'content');
        $obj          = $pageHandler->create();
        $obj->getContentSimpleForm($forMods, $contentType);
        break;
}

// include Xoops footer
include XOOPS_ROOT_PATH . '/footer.php';
