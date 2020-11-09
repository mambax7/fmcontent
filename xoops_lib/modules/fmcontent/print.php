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
 * FmContent print file
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

require __DIR__ . '/header.php';

// Initialize content handler
$pageHandler = Helper::getInstance()->getHandler('Page');
$topicHandler   = Helper::getInstance()->getHandler('Topic');

if (Request::hasVar('id', 'REQUEST')) {
    $contentId = Request::getInt('id', 0);
} else {
    $content_alias = Request::getString('page', 0);
    if ($content_alias) {
        $contentId = $pageHandler->getId($content_alias);
    }
}

// Initialize template
$xoopsTpl = new \XoopsTpl();
$page     = [];

$obj = $pageHandler->get($contentId);

// Get user right
$group  = is_object($xoopsUser) ? $xoopsUser->getGroups() : [XOOPS_GROUP_ANONYMOUS];
$groups = explode(' ', $obj->getVar('content_groups'));
if (count(array_intersect($group, $groups)) <= 0) {
    redirect_header('index.php', 2, _NOPERM);
    exit();
}
if (XOOPS_GROUP_ADMIN == $group[0]) {
    $xoopsTpl->assign('admin', 1);
}

$page          = $obj->toArray();
$content_topic = $obj->getVar('content_topic');

if (isset($content_topic) && $content_topic > 0) {
    $view_topic = $topicHandler->get($content_topic);

    if (!isset($view_topic)) {
        redirect_header('index.php', 3, _FMCONTENT_TOPIC_ERROR);
        exit();
    }

    if ($view_topic->getVar('topic_modid') != $forMods->getVar('mid')) {
        redirect_header('index.php', 3, _FMCONTENT_TOPIC_ERROR);
        exit();
    }

    if ('0' == $view_topic->getVar('topic_online')) {
        redirect_header('index.php', 3, _FMCONTENT_TOPIC_ERROR);
        exit();
    }

    // Check the access permission
    $perm_handler = Fmcontent\Permission::getHandler();
    if (!$perm_handler->isAllowed($xoopsUser, 'fmcontent_access', $view_topic->getVar('topic_id'), $forMods)) {
        redirect_header('index.php', 3, _NOPERM);
        exit;
    }

    if (xoops_getModuleOption('disp_option', $forMods->getVar('dirname'))
        && '0' == $view_topic->getVar('topic_showprint')) {
        redirect_header('index.php', 3, _NOPERM);
        exit;
    }

    if ('0' == xoops_getModuleOption('disp_printlink', $forMods->getVar('dirname'))) {
        redirect_header('index.php', 3, _NOPERM);
        exit;
    }
}

$page['title']  = $obj->getVar('content_title');
$page['alias']  = $obj->getVar('content_alias');
$page['short']  = $obj->getVar('content_short');
$page['text']   = $obj->getVar('content_text');
$page['img']    = $obj->getVar('content_img');
$page['imgurl'] = XOOPS_URL . xoops_getModuleOption('img_dir', $forMods->getVar('dirname')) . $obj->getVar('content_img');
$page['author'] = \XoopsUser::getUnameFromId($obj->getVar('content_uid'));
$page['date']   = formatTimestamp($obj->getVar('content_create'), _MEDIUMDATESTRING);
$page['link']   = fmcontent_Url($forMods->getVar('dirname'), $page);

$xoopsTpl->assign('content', $page);
$xoopsTpl->assign('module', $forMods->getVar('dirname'));
$xoopsTpl->assign('imgwidth', xoops_getModuleOption('imgwidth', $forMods->getVar('dirname')));
$xoopsTpl->assign('imgfloat', xoops_getModuleOption('imgfloat', $forMods->getVar('dirname')));

// Index Variable
$xoopsTpl->assign('xoops_sitename', $xoopsConfig['sitename']);
$xoopsTpl->assign('xoops_pagetitle', $page['title']);
$xoopsTpl->assign('meta_author', XoopsUser::getUnameFromId($obj->getVar('content_uid')));
$xoopsTpl->assign('meta_copyright', $xoopsConfig['sitename']);
$xoopsTpl->assign('meta_keywords', $obj->getVar('content_words'));
$xoopsTpl->assign('meta_description', $obj->getVar('content_desc'));

// Set xoops page title
$xoopsTpl->assign('xoops_pagetitle', $page['title'] . ' - ' . $forMods->getVar('name', 's'));

// Set local style
if (file_exists(XOOPS_ROOT_PATH . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css')) {
    $xoopsTpl->assign('localstyle', XOOPS_URL . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css');
} else {
    $xoopsTpl->assign('localstyle', XOOPS_URL . '/language/english/style.css');
}

// Print page config
$xoopsTpl->assign('print_logo', xoops_getModuleOption('print_logo', $forMods->getVar('dirname')));
$xoopsTpl->assign('print_logofloat', xoops_getModuleOption('print_logofloat', $forMods->getVar('dirname')));
$xoopsTpl->assign('print_logourl', XOOPS_URL . xoops_getModuleOption('print_logourl', $forMods->getVar('dirname')));
$xoopsTpl->assign('print_img', xoops_getModuleOption('print_img', $forMods->getVar('dirname')));
$xoopsTpl->assign('print_short', xoops_getModuleOption('print_short', $forMods->getVar('dirname')));
$xoopsTpl->assign('print_text', xoops_getModuleOption('print_text', $forMods->getVar('dirname')));
$xoopsTpl->assign('print_date', xoops_getModuleOption('print_date', $forMods->getVar('dirname')));
$xoopsTpl->assign('print_author', xoops_getModuleOption('print_author', $forMods->getVar('dirname')));
$xoopsTpl->assign('print_link', xoops_getModuleOption('print_link', $forMods->getVar('dirname')));
$xoopsTpl->assign('print_title', xoops_getModuleOption('print_title', $forMods->getVar('dirname')));
$xoopsTpl->assign('print_columns', xoops_getModuleOption('print_columns', $forMods->getVar('dirname')));

// Display print page
echo $xoopsTpl->fetch(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getInfo('dirname') . '/templates/fmcontent_print.tpl');
