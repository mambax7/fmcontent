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
use XoopsModules\Fmcontent\{Helper,
    TopicHandler
};

if (!isset($forMods)) {
    exit('Module not found');
}
include __DIR__ . '/admin_header.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

// Display Admin header
xoops_cp_header();
// Define default value
$op = Request::getString('op', '');
// Initialize content handler
$topicHandler = Helper::getInstance()->getHandler('Topic');
$pageHandler  = Helper::getInstance()->getHandler('Page');

// Define scripts
$xoTheme->addScript('browse.php?Frameworks/jquery/jquery.js');
$xoTheme->addScript('browse.php?Frameworks/jquery/plugins/jquery.ui.js');
$xoTheme->addScript('browse.php?modules/' . $forMods->getVar('dirname') . '/js/order.js');
$xoTheme->addScript('browse.php?modules/' . $forMods->getVar('dirname') . '/js/admin.js');

// Add module stylesheet
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $forMods->getVar('dirname') . '/css/admin.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/system/css/ui/' . xoops_getModuleOption('jquery_theme', 'system') . '/ui.all.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/system/css/admin.css');

switch ($op) {
    case 'new_content':
        //        $contentType = Request::getString('content_type', 'content');
        $contentType = Request::getString('content_type', 'content');
        $obj         = $pageHandler->create();
        $obj->getContentForm($forMods, $contentType);
        break;
    case 'edit_content':
        //        $contentId = Request::getInt('content_id', 0);
        $contentId = Request::getInt('content_id', 0);
        if ($contentId > 0) {
            $obj = $pageHandler->get($contentId);
            $obj->getContentForm($forMods);
        } else {
            fmcontent_Redirect('content.php', 1, _FMCONTENT_MSG_EDIT_ERROR);
        }
        break;
    case 'new_link':
        //        $contentType = Request::getString('content_type', 'link');
        $contentType = Request::getString('content_type', 'link');
        $obj         = $pageHandler->create();
        $obj->getMenuForm($forMods, $contentType);
        break;
    case 'edit_link':
        $contentId = Request::getInt('content_id', 0);
        if ($contentId > 0) {
            $obj = $pageHandler->get($contentId);
            $obj->getMenuForm($forMods);
        } else {
            fmcontent_Redirect('content.php', 1, _FMCONTENT_MSG_EDIT_ERROR);
        }
        break;
    case 'delete':
        $contentId = Request::getInt('content_id', 0);
        if ($contentId > 0) {
            $content = $pageHandler->get($contentId);
            // Prompt message
            fmcontent_Message('backend.php', sprintf(_FMCONTENT_MSG_DELETE, $content->getVar('content_type') . ': "' . $content->getVar('content_title') . '"'), $contentId, 'content');
            // Display Admin footer
            xoops_cp_footer();
        }
        break;
    case 'order':
        if (Request::hasVar('mod', 'POST')) {
            $i = 1;
            foreach ($_POST['mod'] as $order) {
                if ($order > 0) {
                    $contentorder = $pageHandler->get($order);
                    $contentorder->setVar('content_order', $i);
                    if (!$pageHandler->insert($contentorder)) {
                        $error = true;
                    }
                    ++$i;
                }
            }
        }
        exit;
        break;
    default:

        // get module configs
        $content_perpage = xoops_getModuleOption('admin_perpage', $forMods->getVar('dirname'));
        $content_order   = xoops_getModuleOption('admin_showorder', $forMods->getVar('dirname'));
        $content_sort    = xoops_getModuleOption('admin_showsort', $forMods->getVar('dirname'));

        if (Request::hasVar('user', 'REQUEST')) {
            $content_user = Request::getInt('user', 0);
        } else {
            $content_user = null;
        }

        // get limited information
        if (Request::hasVar('limit', 'REQUEST')) {
            $content_limit = Request::getInt('limit', 0);
        } else {
            $content_limit = $content_perpage;
        }

        // get start information
        if (Request::hasVar('start', 'REQUEST')) {
            $content_start = Request::getInt('start', 0);
        } else {
            $content_start = 0;
        }

        // get topic information
        if (Request::hasVar('topic', 'REQUEST')) {
            $content_topic = Request::getInt('topic', 0);
            if ($content_topic) {
                $topics      = $topicHandler->getAll($content_topic);
                $topic_title = TopicHandler::getTopicFromId($content_topic);
            } else {
                $topics = $topic_title = _FMCONTENT_STATICS;
            }
        } else {
            $content_topic = null;
            $topic_title   = null;
            // get all topic informations
            $topics = $topicHandler->getAll($content_topic);
        }

        $content_infos = [
            'topics'         => $topics,
            'content_limit'  => $content_limit,
            'content_topic'  => $content_topic,
            'content_user'   => $content_user,
            'content_start'  => $content_start,
            'content_order'  => $content_order,
            'content_sort'   => $content_sort,
            'content_status' => false,
            'content_static' => false,
            'admin_side'     => true,
        ];

        $contents        = $pageHandler->getContentList($forMods, $content_infos);
        $content_numrows = $pageHandler->getContentCount($forMods, $content_infos);

        if ($content_numrows > $content_limit) {
            if ($content_topic) {
                $content_pagenav = new \XoopsPageNav($content_numrows, $content_limit, $content_start, 'start', 'limit=' . $content_limit . '&topic=' . $content_topic);
            } else {
                $content_pagenav = new \XoopsPageNav($content_numrows, $content_limit, $content_start, 'start', 'limit=' . $content_limit);
            }
            $content_pagenav = $content_pagenav->renderNav(4);
        } else {
            $content_pagenav = '';
        }

        $xoopsTpl->assign('navigation', 'content');
        $xoopsTpl->assign('navtitle', _FMCONTENT_CONTENT);
        $xoopsTpl->assign('topic_title', $topic_title);
        $xoopsTpl->assign('contents', $contents);
        $xoopsTpl->assign('content_pagenav', $content_pagenav);
        $xoopsTpl->assign('xoops_dirname', $forMods->getVar('dirname'));

        $configHandler = xoops_getHandler('config');
        $moduleConfig  = $configHandler->getConfigsByCat(0, 1);

        if ($moduleConfig['usetips']) {
            $xoopsTpl->assign('fmcontent_tips', _FMCONTENT_CONTENT_TIPS);
        }
        // Call template file
        $xoopsTpl->display(XOOPS_ROOT_PATH . '/modules/' . $forMods->getVar('dirname') . '/templates/admin/fmcontent_content.tpl');

        break;
}

// Admin Footer
include __DIR__ . '/admin_footer.php';
