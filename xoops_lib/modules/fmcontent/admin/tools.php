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
 * FmContent Admin page
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Andricq Nicolas (AKA MusS)
 * @author      Hossein Azizabadi (AKA Voltan)
 */

use XoopsModules\Fmcontent;
use XoopsModules\Fmcontent\Helper;

if (!isset($forMods)) {
    exit('Module not found');
}
include __DIR__ . '/admin_header.php';
// Display Admin header
xoops_cp_header();
// Define default value
$op = Request::getString('op', 'display');
// Add module stylesheet
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $forMods->getVar('dirname') . '/css/admin.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/system/css/admin.css');
// Initialize content handler
$topicHandler   = Helper::getInstance()->getHandler('Topic');
$pageHandler = Helper::getInstance()->getHandler('Page');

switch ($op) {
    case 'display':
    default:

        // rebuild alias
        $form = new \XoopsThemeForm(_FMCONTENT_ALIAS_TITLE, 'tools', 'tools.php', 'post', true);
        $form->addElement(new \XoopsFormRadioYN(_FMCONTENT_ALIAS_CONTENT, 'topic', '1'));
        $form->addElement(new \XoopsFormRadioYN(_FMCONTENT_ALIAS_TOPIC, 'content', '1'));
        $form->addElement(new \XoopsFormHidden('op', 'alias'));
        $form->addElement(new \XoopsFormButton('', 'post', _SUBMIT, 'submit'));
        $xoopsTpl->assign('alias', $form->render());

        // meta alias
        $form = new \XoopsThemeForm(_FMCONTENT_META_TITLE, 'tools', 'tools.php', 'post', true);
        $form->addElement(new \XoopsFormRadioYN(_FMCONTENT_META_KEYWORD, 'keyword', '1'));
        $form->addElement(new \XoopsFormRadioYN(_FMCONTENT_META_DESCRIPTION, 'description', '1'));
        $form->addElement(new \XoopsFormHidden('op', 'meta'));
        $form->addElement(new \XoopsFormButton('', 'post', _SUBMIT, 'submit'));
        $xoopsTpl->assign('meta', $form->render());

        // Add clone
        $form = new \XoopsThemeForm(_FMCONTENT_FORMFOLDER_TITLE, 'tools', 'tools.php', 'post', true);
        $form->addElement(new \XoopsFormText(_FMCONTENT_FORMFOLDER_NAME, 'folder_name', 50, 255, ''), true);
        $form->addElement(new \XoopsFormHidden('op', 'clone'));
        $button_tray = new \XoopsFormElementTray('', '');
        $submit_btn  = new \XoopsFormButton('', 'post', _SUBMIT, 'submit');
        $button_tray->addElement($submit_btn);
        $form->addElement($button_tray);
        $xoopsTpl->assign('folder', $form->render());

        // remove contents form
        $module_handler = xoops_getHandler('module');
        $result         = $GLOBALS['xoopsDB']->query('SELECT DISTINCT(content_modid) FROM ' . $GLOBALS['xoopsDB']->prefix('fmcontent_content'));
        $form           = new \XoopsThemeForm(_FMCONTENT_FORMPURGE_TITLE, 'tools', 'tools.php', 'post', true);
        $form->addElement(new \XoopsFormHidden('op', 'purge'));
        $clone = [];
        while (false !== ($myrow = $GLOBALS['xoopsDB']->fetchArray($result))) {
            if ($myrow['content_modid'] != $forMods->getVar('mid')) {
                if (!$module_handler->get($myrow['content_modid'])) {
                    $clone[] = $myrow['content_modid'];
                    $form->addElement(new \XoopsFormHidden('modid[]', $myrow['content_modid']));
                }
            }
        }
        $button_tray = new \XoopsFormElementTray('', '');
        $submit_btn  = new \XoopsFormButton('', 'post', _SUBMIT, 'submit');
        $button_tray->addElement($submit_btn);
        $form->addElement($button_tray);
        if (count($clone)) {
            $xoopsTpl->assign('purge', $form->render());
        }

        // other options
        $xoopsTpl->assign('header', true);
        break;
    case 'clone':
        $folder = Request::getString('folder_name', '');
        if (!is_dir(XOOPS_ROOT_PATH . '/modules/' . $folder)) {
            $folder_handler = new \FolderHandler(XOOPS_ROOT_PATH . '/modules/' . $folder);
            $optn           = [
                'to'   => XOOPS_ROOT_PATH . '/modules/' . $folder,
                'from' => XOOPS_ROOT_PATH . '/modules/fmcontent',
            ];
            $folder_handler->copy($optn);
            if (is_array($folder_handler->messages)) {
                $xoopsTpl->assign('messages', $folder_handler->messages);
            } else {
                $xoopsTpl->assign('messages', $folder_handler->erros);
            }
        } else {
            fmcontent_Redirect('tools.php', 1, _FMCONTENT_MSG_CLONE_ERROR);
        }
        break;
    case 'purge':
        $modid = $_REQUEST['modid'];
        foreach ($modid as $id) {
            $pageHandler->deleteAll(new \Criteria('content_modid', $id));
            $topicHandler->deleteAll(new \Criteria('topic_modid', $id));
        }
        fmcontent_Redirect('tools.php', 1, _FMCONTENT_MSG_WAIT);
        break;
    case 'alias':

        if ($_POST['topic']) {
            $criteria = new \CriteriaCompo();
            $criteria->setSort('topic_id');
            $criteria->setOrder('DESC');
            $criteria->setLimit(1);
            $last = $topicHandler->getObjects($criteria);
            foreach ($last as $item) {
                $last_id = $item->getVar('topic_id');
            }
            $topic_id = '1';
            while ($topic_id <= $last_id) {
                $obj = $topicHandler->get($topic_id);
                if ($obj) {
                    $obj->setVar('topic_alias', fmcontent_Filter($obj->getVar('topic_title', 'e')));
                    $topicHandler->insert($obj);
                }
                $topic_id += 1;
            }
        }

        if ($_POST['content']) {
            $criteria = new \CriteriaCompo();
            $criteria->setSort('content_id');
            $criteria->setOrder('DESC');
            $criteria->setLimit(1);
            $last = $pageHandler->getObjects($criteria);
            foreach ($last as $item) {
                $last_id = $item->getVar('content_id');
            }
            $contentId = '1';
            while ($contentId <= $last_id) {
                $obj = $pageHandler->get($contentId);
                if ($obj) {
                    $obj->setVar('content_alias', fmcontent_Filter($obj->getVar('content_title', 'e')));
                    $pageHandler->insert($obj);
                }
                $contentId += 1;
            }
        }
        fmcontent_Redirect('tools.php', 1, _FMCONTENT_MSG_WAIT);
        break;
    case 'meta':
        $criteria = new \CriteriaCompo();
        $criteria->setSort('content_id');
        $criteria->setOrder('DESC');
        $criteria->setLimit(1);
        $last = $pageHandler->getObjects($criteria);
        foreach ($last as $item) {
            $last_id = $item->getVar('content_id');
        }
        $contentId = '1';

        if ($_POST['keyword']) {
            while ($contentId <= $last_id) {
                $obj = $pageHandler->get($contentId);
                if ($obj) {
                    $obj->setVar('content_words', fmcontent_MetaFilter($obj->getVar('content_title', 'e')));
                    $pageHandler->insert($obj);
                }
                $contentId += 1;
            }
        }

        if ($_POST['description']) {
            while ($contentId <= $last_id) {
                $obj = $pageHandler->get($contentId);
                if ($obj) {
                    $obj->setVar('content_desc', fmcontent_AjaxFilter($obj->getVar('content_title', 'e')));
                    $pageHandler->insert($obj);
                }
                $contentId += 1;
            }
        }
        fmcontent_Redirect('tools.php', 1, _FMCONTENT_MSG_WAIT);
        break;
}

$xoopsTpl->assign('navigation', 'tools');
$xoopsTpl->assign('navtitle', _FMCONTENT_TOOLS);

// Call template file
$xoopsTpl->display(XOOPS_ROOT_PATH . '/modules/' . $forMods->getVar('dirname') . '/templates/admin/fmcontent_tools.tpl');

// Display Xoops footer
include __DIR__ . '/admin_footer.php';
