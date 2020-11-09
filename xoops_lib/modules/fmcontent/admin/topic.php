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

include __DIR__ . '/admin_header.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

// Display Admin header
xoops_cp_header();
// Define default value
$op = Request::getString('op', '');
// Initialize content handler
$topicHandler   = Helper::getInstance()->getHandler('Topic');
$pageHandler = Helper::getInstance()->getHandler('Page');
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
    case 'new_topic':
        $obj = $topicHandler->create();
        $obj->getForm($forMods);
        break;
    case 'edit_topic':
        $topic_id = Request::getInt('topic_id', 0);
        if ($topic_id > 0) {
            $obj = $topicHandler->get($topic_id);
            $obj->getForm($forMods);
        } else {
            fmcontent_Redirect('topic.php', 1, _FMCONTENT_MSG_EDIT_ERROR);
        }
        break;
    case 'delete_topic':
        $topic_id = Request::getInt('topic_id', 0);
        if ($topic_id > 0) {
            $topic = $topicHandler->get($topic_id);
            // Prompt message
            fmcontent_Message('backend.php', sprintf(_FMCONTENT_MSG_DELETE, '"' . $topic->getVar('topic_title') . '"'), $topic_id, 'topic');
            // Display Admin footer
            xoops_cp_footer();
        }
    // no break

    case 'order':
        if (Request::hasVar('mod', 'POST')) {
            $i = 1;
            foreach ($_POST['mod'] as $order) {
                if ($order > 0) {
                    $contentorder = $topicHandler->get($order);
                    $contentorder->setVar('topic_weight', $i);
                    if (!$topicHandler->insert($contentorder)) {
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
        $topic_perpage = xoops_getModuleOption('admin_perpage_topic', $forMods->getVar('dirname'));
        $topic_order   = xoops_getModuleOption('admin_showorder_topic', $forMods->getVar('dirname'));
        $topic_sort    = xoops_getModuleOption('admin_showsort_topic', $forMods->getVar('dirname'));

        // get limited information
        if (Request::hasVar('limit', 'REQUEST')) {
            $topic_limit = Request::getInt('limit', 0);
        } else {
            $topic_limit = $topic_perpage;
        }

        // get start information
        if (Request::hasVar('start', 'REQUEST')) {
            $topic_start = Request::getInt('start', 0);
        } else {
            $topic_start = 0;
        }

        $topics        = $topicHandler->getTopics($forMods, $topic_limit, $topic_start, $topic_order, $topic_sort, $topic_menu = null, $topic_online = null, $topic_parent = null);
        $topic_numrows = $topicHandler->getTopicCount($forMods);

        if ($topic_numrows > $topic_limit) {
            $topic_pagenav = new \XoopsPageNav($topic_numrows, $topic_limit, $topic_start, 'start', 'limit=' . $topic_limit);
            $topic_pagenav = $topic_pagenav->renderNav(4);
        } else {
            $topic_pagenav = '';
        }

        $xoopsTpl->assign('navigation', 'topic');
        $xoopsTpl->assign('navtitle', _FMCONTENT_TOPIC);
        $xoopsTpl->assign('topics', $topics);
        $xoopsTpl->assign('topic_pagenav', $topic_pagenav);
        $xoopsTpl->assign('xoops_dirname', $forMods->getVar('dirname'));

        $configHandler = xoops_getHandler('config');
        $moduleConfig  = $configHandler->getConfigsByCat(0, 1);

        if ($moduleConfig['usetips']) {
            $xoopsTpl->assign('fmcontent_tips', _FMCONTENT_TOPIC_TIPS);
        }

        // Call template file
        $xoopsTpl->display(XOOPS_ROOT_PATH . '/modules/' . $forMods->getVar('dirname') . '/templates/admin/fmcontent_topic.tpl');

        break;
}

// Admin Footer
include __DIR__ . '/admin_footer.php';
