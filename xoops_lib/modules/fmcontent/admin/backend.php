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

use Xmf\Request;
use XoopsModules\Fmcontent;
use XoopsModules\Fmcontent\Helper;

if (!isset($forMods)) {
    exit('Module not found');
}

// Define default value
$op = Request::getString('op', 'new');
// Admin header
xoops_cp_header();
// Redirect to content page
if (!isset($_POST ['post']) && isset($_POST ['content_type'])) {
    $contentType = $_POST ['content_type'];
    fmcontent_Redirect('navigation.php?content_type=' . $contentType, 0, _FMCONTENT_MSG_WAIT);
    // Include footer
    xoops_cp_footer();
    exit();
}

// Initialize content handler
$pageHandler = Helper::getInstance()->getHandler('Page');
$topicHandler   = Helper::getInstance()->getHandler('Topic');
$fileHandler    = Helper::getInstance()->getHandler('File');

switch ($op) {
    case 'add_topic':
        $obj = $topicHandler->create();
        $obj->setVars($_REQUEST);

        if ($topicHandler->existAlias($forMods, $_REQUEST)) {
            fmcontent_Redirect('javascript:history.go(-1)', 3, _FMCONTENT_MSG_ALIASERROR);
            xoops_cp_footer();
            exit();
        }

        $obj->setVar('topic_date_created', time());
        $obj->setVar('topic_date_update', time());
        $obj->setVar('topic_weight', $topicHandler->setorder($forMods));

        //image
        if (Request::hasVar('topic_img', 'REQUEST ')) {
            Fmcontent\Utils::uploadimg($forMods, 'topic_img', $obj, $_REQUEST ['topic_img']);
        }

        if (!$topicHandler->insert($obj)) {
            fmcontent_Redirect('onclick="javascript:history.go(-1);"', 1, _FMCONTENT_MSG_ERROR);
            xoops_cp_footer();
            exit();
        }

        $topic_id = $obj->db->getInsertId();

        //permission
        Fmcontent\Permission::setPermission($forMods, 'fmcontent_access', $_POST ['groups_view'], $topic_id, true);
        Fmcontent\Permission::setPermission($forMods, 'fmcontent_submit', $_POST ['groups_submit'], $topic_id, true);

        // Redirect page
        fmcontent_Redirect('topic.php', 1, _FMCONTENT_MSG_WAIT);
        xoops_cp_footer();
        exit();
        break;

    case 'edit_topic':
        $topic_id = Request::getInt('topic_id', 0);
        if ($topic_id > 0) {
            $obj = $topicHandler->get($topic_id);
            $obj->setVars($_POST);
            $obj->setVar('topic_date_update', time());

            if ($topicHandler->existAlias($forMods, $_REQUEST)) {
                fmcontent_Redirect('javascript:history.go(-1)', 3, _FMCONTENT_MSG_ALIASERROR);
                xoops_cp_footer();
                exit();
            }

            //image
            Fmcontent\Utils::uploadimg($forMods, 'topic_img', $obj, $_REQUEST ['topic_img']);
            if (Request::hasVar('deleteimage', 'POST ') && 1 == Request::getInt('deleteimage', 0, 'POST ')) {
                Fmcontent\Utils::deleteimg($forMods, 'topic_img', $obj);
            }
            //permission
            Fmcontent\Permission::setPermission($forMods, 'fmcontent_access', $_POST ['groups_view'], $topic_id, false);
            Fmcontent\Permission::setPermission($forMods, 'fmcontent_submit', $_POST ['groups_submit'], $topic_id, false);

            if (!$topicHandler->insert($obj)) {
                fmcontent_Redirect('onclick="javascript:history.go(-1);"', 1, _FMCONTENT_MSG_ERROR);
                xoops_cp_footer();
                exit();
            }
        }

        // Redirect page
        fmcontent_Redirect('topic.php', 1, _FMCONTENT_MSG_WAIT);
        xoops_cp_footer();
        exit();
        break;

    case 'add':
        $groups = Request::getArray('content_groups', [], 'POST ');
        $groups = is_array($groups) ? implode(' ', $groups) : '';

        $obj = $pageHandler->create();
        $obj->setVars($_REQUEST);


        if ('link' === $_REQUEST ['content_type']) {
            $obj->setVar('content_title', $_REQUEST ['content_menu']);
        }

        if ('link' !== $_REQUEST ['content_type'] && $pageHandler->existAlias($forMods, $_REQUEST)) {
            fmcontent_Redirect('javascript:history.go(-1)', 3, _FMCONTENT_MSG_ALIASERROR);
            xoops_cp_footer();
            exit();
        }

        if (!$_REQUEST ['content_default'] && 0 == $_REQUEST ['content_topic']) {
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));
            $criteria->add(new \Criteria('content_topic', 0));
            $criteria->add(new \Criteria('content_default', 1));
            if (!$pageHandler->getCount($criteria)) {
                $obj->setVar('content_default', '1');
            }
        }

        $obj->setVar('content_order', $pageHandler->setorder($forMods));
        $obj->setVar('content_next', $pageHandler->setNext($forMods, $_REQUEST ['content_topic']));
        $obj->setVar('content_prev', $pageHandler->setPrevious($forMods, $_REQUEST ['content_topic']));
        $obj->setVar('content_groups', $groups);
        $obj->setVar('content_create', time());
        $obj->setVar('content_update', time());

        //image
        if (Request::hasVar('content_img', 'REQUEST ')) {
            Fmcontent\Utils::uploadimg($forMods, 'content_img', $obj, $_REQUEST ['content_img']);
        }

        $pageHandler->updateposts($_REQUEST ['content_uid'], $_REQUEST ['content_status'], $content_action = 'add');

        if (!$pageHandler->insert($obj)) {
            fmcontent_Redirect('onclick="javascript:history.go(-1);"', 1, _FMCONTENT_MSG_ERROR);
            xoops_cp_footer();
            exit();
        }

        // Reset next and previous content
        $pageHandler->resetNext($forMods, $_REQUEST ['content_topic'], $obj->getVar('content_id'));
        $pageHandler->resetPrevious($forMods, $_REQUEST ['content_topic'], $obj->getVar('content_id'));

        // tag
        if (xoops_getModuleOption('usetag', $forMods->getVar('dirname')) && is_dir(XOOPS_ROOT_PATH . '/modules/tag')) {
            $tag_handler = xoops_getModuleHandler('tag', 'tag');
            $tag_handler->updateByItem($_POST ['item_tag'], $obj->getVar('content_id'), $forMods->getVar('dirname'), 0);
        }

        // file
        if (Request::hasVar('file_name', 'REQUEST ')) {
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
        fmcontent_Redirect('content.php', 1, _FMCONTENT_MSG_WAIT);
        xoops_cp_footer();
        exit();
        break;

    case 'edit':
        $contentId = Request::getInt('content_id', 0);
        if ($contentId > 0) {
            $groups = Request::getArray('content_groups', [], 'POST ');
            $groups = is_array($groups) ? implode(' ', $groups) : '';

            $obj = $pageHandler->get($contentId);
            $obj->setVars($_REQUEST);
            $obj->setVar('content_groups', $groups);
            $obj->setVar('content_update', time());

            if ($pageHandler->existAlias($forMods, $_REQUEST)) {
                fmcontent_Redirect('javascript:history.go(-1)', 3, _FMCONTENT_MSG_ALIASERROR);
                xoops_cp_footer();
                exit();
            }

            if ('link' === $_REQUEST ['content_type']) {
                $obj->setVar('content_title', $_REQUEST ['content_menu']);
            }

            if (!isset($_REQUEST ['content_titleview'])) {
                $obj->setVar('content_titleview', 0);
            }

            if (!isset($_REQUEST ['dohtml'])) {
                $obj->setVar('dohtml', 0);
            }

            if (!isset($_REQUEST ['dobr'])) {
                $obj->setVar('dobr', 0);
            }

            if (!isset($_REQUEST ['doimage'])) {
                $obj->setVar('doimage', 0);
            }

            if (!isset($_REQUEST ['dosmiley'])) {
                $obj->setVar('dosmiley', 0);
            }

            if (!isset($_REQUEST ['doxcode'])) {
                $obj->setVar('doxcode', 0);
            }

            //image
            Fmcontent\Utils::uploadimg($forMods, 'content_img', $obj, $_REQUEST ['content_img']);
            if (Request::hasVar('deleteimage', 'POST ') && 1 == Request::getInt('deleteimage', 0, 'POST ')) {
                Fmcontent\Utils::deleteimg($forMods, 'content_img', $obj);
            }

            if (!$pageHandler->insert($obj)) {
                fmcontent_Redirect('onclick="javascript:history.go(-1);"', 1, _FMCONTENT_MSG_ERROR);
                xoops_cp_footer();
                exit();
            }

            //tag
            if (xoops_getModuleOption('usetag', $forMods->getVar('dirname')) && is_dir(XOOPS_ROOT_PATH . '/modules/tag')) {
                $tag_handler = xoops_getModuleHandler('tag', 'tag');
                $tag_handler->updateByItem($_POST ['item_tag'], $contentId, $forMods->getVar('dirname'), $catid = 0);
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
        }

        // Redirect page
        fmcontent_Redirect('content.php', 1, _FMCONTENT_MSG_WAIT);
        xoops_cp_footer();
        exit();
        break;

    case 'add_file':

        $obj = $fileHandler->create();
        $obj->setVars($_REQUEST);
        $obj->setVar('file_date', time());

        Fmcontent\Utils::uploadfile($forMods, 'file_name', $obj, $_REQUEST ['file_name']);
        $pageHandler->contentfile('add', $_REQUEST['file_content']);
        if (!$fileHandler->insert($obj)) {
            fmcontent_Redirect('onclick="javascript:history.go(-1);"', 1, _FMCONTENT_MSG_ERROR);
            xoops_cp_footer();
            exit();
        }

        // Redirect page
        fmcontent_Redirect('file.php', 1, _FMCONTENT_MSG_WAIT);
        xoops_cp_footer();
        exit();
        break;

    case 'edit_file':
        $file_id = Request::getInt('file_id', 0);
        if ($file_id > 0) {
            $obj = $fileHandler->get($file_id);
            $obj->setVars($_REQUEST);

            if ($_REQUEST['file_content'] != $_REQUEST['file_previous']) {
                $pageHandler->contentfile('add', $_REQUEST['file_content']);
                $pageHandler->contentfile('delete', $_REQUEST['file_previous']);
            }

            if (!$fileHandler->insert($obj)) {
                fmcontent_Redirect('onclick="javascript:history.go(-1);"', 1, _FMCONTENT_MSG_ERROR);
                xoops_cp_footer();
                exit();
            }
        }
        // Redirect page
        fmcontent_Redirect('file.php', 1, _FMCONTENT_MSG_WAIT);
        xoops_cp_footer();
        exit();
        break;

    case 'status':
        $contentId = Request::getInt('content_id', 0);
        if ($contentId > 0) {
            $obj = $pageHandler->get($contentId);
            $old = $obj->getVar('content_status');
            $pageHandler->updateposts($obj->getVar('content_uid'), $obj->getVar('content_status'), $content_action = 'status');
            $obj->setVar('content_status', !$old);
            if ($pageHandler->insert($obj)) {
                exit();
            }
            echo $obj->getHtmlErrors();
        }
        break;

    case 'default':
        $contentId = Request::getInt('content_id', 0);
        $topic_id   = Request::getInt('topic_id', 0);
        if ($contentId > 0) {
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('content_topic', $topic_id));
            $pageHandler->updateAll('content_default', 0, $criteria);
            $obj = $pageHandler->get($contentId);
            $obj->setVar('content_default', 1);
            if ($pageHandler->insert($obj)) {
                exit();
            }
            echo $obj->getHtmlErrors();
        }
        break;

    case 'display':
        $contentId = Request::getInt('content_id', 0);
        if ($contentId > 0) {
            $obj = $pageHandler->get($contentId);
            $old = $obj->getVar('content_display');
            $obj->setVar('content_display', !$old);
            if ($pageHandler->insert($obj)) {
                exit();
            }
            echo $obj->getHtmlErrors();
        }
        break;

    case 'topic_asmenu':
        $topic_id = Request::getInt('topic_id', 0);
        if ($topic_id > 0) {
            $obj = $topicHandler->get($topic_id);
            $old = $obj->getVar('topic_asmenu');
            $obj->setVar('topic_asmenu', !$old);
            if ($topicHandler->insert($obj)) {
                exit();
            }
            echo $obj->getHtmlErrors();
        }
        break;

    case 'topic_online':
        $topic_id = Request::getInt('topic_id', 0);
        if ($topic_id > 0) {
            $obj = $topicHandler->get($topic_id);
            $old = $obj->getVar('topic_online');
            $obj->setVar('topic_online', !$old);
            if ($topicHandler->insert($obj)) {
                exit();
            }
            echo $obj->getHtmlErrors();
        }
        break;

    case 'topic_show':
        $topic_id = Request::getInt('topic_id', 0);
        if ($topic_id > 0) {
            $obj = $topicHandler->get($topic_id);
            $old = $obj->getVar('topic_show');
            $obj->setVar('topic_show', !$old);
            if ($topicHandler->insert($obj)) {
                exit();
            }
            echo $obj->getHtmlErrors();
        }
        break;

    case 'file_status':
        $file_id = Request::getInt('file_id', 0);
        if ($file_id > 0) {
            $obj = $fileHandler->get($file_id);
            $old = $obj->getVar('file_status');
            $obj->setVar('file_status', !$old);
            if ($fileHandler->insert($obj)) {
                exit();
            }
            echo $obj->getHtmlErrors();
        }
        break;

    case 'delete':
        //print_r($_POST);
        $id      = Request::getInt('id', 0);
        $handler = Request::getString('handler', 0);
        if ($id > 0 && $handler) {
            switch ($handler) {
                case 'content':
                    $obj = $pageHandler->get($id);
                    $url = 'content.php';
                    $pageHandler->updateposts($obj->getVar('content_uid'), $obj->getVar('content_status'), $content_action = 'delete');
                    if (!$pageHandler->delete($obj)) {
                        echo $obj->getHtmlErrors();
                    }
                    break;
                case 'topic':
                    $obj = $topicHandler->get($id);
                    $url = 'topic.php';
                    if (!$topicHandler->delete($obj)) {
                        echo $obj->getHtmlErrors();
                    }
                    break;
                case 'file':
                    $obj = $fileHandler->get($id);
                    $url = 'file.php';
                    $pageHandler->contentfile('delete', $obj->getVar('file_content'));
                    if (!$fileHandler->delete($obj)) {
                        echo $obj->getHtmlErrors();
                    }
                    break;
            }
        }

        // Redirect page
        fmcontent_Redirect($url, 1, _FMCONTENT_MSG_WAIT);
        xoops_cp_footer();
        exit();
        break;
}

// Redirect page
fmcontent_Redirect('index.php', 1, _FMCONTENT_MSG_WAIT);
// Include footer
xoops_cp_footer();
