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
 * FmContent topic file
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Hossein Azizabadi (AKA Voltan)
 */

use Xmf\Request;
use XoopsModules\Fmcontent;
use XoopsModules\Fmcontent\Helper;

if (!isset($forMods)) {
    exit('Module not found');
}

require __DIR__ . '/header.php';

include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

$pageHandler = Helper::getInstance()->getHandler('Page');
$topicHandler   = Helper::getInstance()->getHandler('Topic');

// Include content template
$xoopsOption ['template_main'] = 'fmcontent_topic.tpl';

// include Xoops header
include XOOPS_ROOT_PATH . '/header.php';

// Add Stylesheet
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $forMods->getVar('dirname') . '/css/style.css');

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

$xoopsTpl->assign('topics', $topics);
$xoopsTpl->assign('topic_pagenav', $topic_pagenav);
$xoopsTpl->assign('xoops_dirname', $forMods->getVar('dirname'));
$xoopsTpl->assign('advertisement', xoops_getModuleOption('advertisement', $forMods->getVar('dirname')));
$xoopsTpl->assign('imgwidth', xoops_getModuleOption('imgwidth', $forMods->getVar('dirname')));
$xoopsTpl->assign('imgfloat', xoops_getModuleOption('imgfloat', $forMods->getVar('dirname')));

// include Xoops footer
include XOOPS_ROOT_PATH . '/footer.php';
