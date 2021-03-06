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
 * FmContent index file
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

include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

global $xoopsUser;

$pageHandler = Helper::getInstance()->getHandler('Page');
$topicHandler   = Helper::getInstance()->getHandler('Topic');

// Include content template
$template                      = xoops_getModuleOption('template', $forMods->getVar('dirname'));
$xoopsOption ['template_main'] = 'fmcontent_' . $template . '_index.tpl';

if (Request::hasVar('user', 'REQUEST ')) {
    $content_user = Request::getInt('user', 0);
} else {
    $content_user = null;
}

if (Request::hasVar('topic', 'REQUEST ')) {
    $content_topic = Request::getInt('topic', 0);
} elseif (Request::hasVar('page', 'REQUEST ')) {
    $topic_alias   = Request::getString('page', 0);
    $content_topic = $topicHandler->getId($topic_alias);
} else {
    $content_topic = null;
}

// include Xoops header
include XOOPS_ROOT_PATH . '/header.php';

// Add Stylesheet
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $forMods->getVar('dirname') . '/css/style.css');
switch ($template) {
    case 'legacy':
        $xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $forMods->getVar('dirname') . '/css/legacy.css');
        break;
    case 'ui':
        $xoTheme->addStylesheet(XOOPS_URL . '/modules/system/css/ui/' . xoops_getModuleOption('jquery_theme', 'system') . '/ui.all.css');
        $xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $forMods->getVar('dirname') . '/css/ui.css');
        break;
    case 'html5':
        $xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $forMods->getVar('dirname') . '/css/legacy.css');
        $xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $forMods->getVar('dirname') . '/css/css3.css');
        break;
}

if (isset($content_topic)) {
//    $topics     = $topicHandler->getAll($content_topic);
    $topics     = $topicHandler->getAll();
    $view_topic = $topics[$content_topic];
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
        exit();
    }

    // get topic information
    $topic_title  = $default_title = $view_topic->getVar('topic_title');
    $topic_alias  = $default_alias = $view_topic->getVar('topic_alias');
    $topic_id     = $default_id = $view_topic->getVar('topic_id');
    $topic_img    = $view_topic->getVar('topic_img');
    $topic_imgurl = XOOPS_URL . xoops_getModuleOption('img_dir', $forMods->getVar('dirname')) . $view_topic->getVar('topic_img');
    $topic_desc   = $view_topic->getVar('topic_desc');

    $xoopsTpl->assign('topic_desc', $topic_desc);
    $xoopsTpl->assign('topic_img', $topic_img);
    $xoopsTpl->assign('topic_imgurl', $topic_imgurl);
    $xoopsTpl->assign('topic_title', $topic_title);
    $xoopsTpl->assign('xoops_pagetitle', $topic_title);

    if ($view_topic->getVar('topic_showtype') > '0') {
        $showtype = $view_topic->getVar('topic_showtype');
    } else {
        $showtype = xoops_getModuleOption('showtype', $forMods->getVar('dirname'));
    }

    if ($view_topic->getVar('topic_columns') > '0') {
        $columns = $view_topic->getVar('topic_columns');
    } else {
        $columns = xoops_getModuleOption('columns', $forMods->getVar('dirname'));
    }

    if ($view_topic->getVar('topic_perpage') > '0') {
        $content_perpage = $view_topic->getVar('topic_perpage');
    } else {
        $content_perpage = xoops_getModuleOption('perpage', $forMods->getVar('dirname'));
    }
    $type = 'type' . $view_topic->getVar('topic_homepage');

    $content_subtopic = $topicHandler->getSubTopics($forMods, $content_topic, $topics);
} else {
    // get all topic informations
    $topics        = $topicHandler->getAll($content_topic);
    $default_title = xoops_getModuleOption('static_name', $forMods->getVar('dirname'));
    $default_alias = fmcontent_Filter($default_title);
    $topic_id      = $default_id = '0';
    // get module configs
    $showtype         = xoops_getModuleOption('showtype', $forMods->getVar('dirname'));
    $columns          = xoops_getModuleOption('columns', $forMods->getVar('dirname'));
    $content_perpage  = xoops_getModuleOption('perpage', $forMods->getVar('dirname'));
    $type             = xoops_getModuleOption('homepage', $forMods->getVar('dirname'));
    $content_subtopic = null;
}

// get module configs
$content_order = xoops_getModuleOption('showorder', $forMods->getVar('dirname'));
$content_sort  = xoops_getModuleOption('showsort', $forMods->getVar('dirname'));

// get limited information
if (Request::hasVar('limit', 'REQUEST ')) {
    $content_limit = Request::getInt('limit', 0);
} else {
    $content_limit = $content_perpage;
}

// get start information
if (Request::hasVar('start', 'REQUEST ')) {
    $content_start = Request::getInt('start', 0);
} else {
    $content_start = 0;
}

$content_infos = [
    'topics'           => $topics,
    'content_limit'    => $content_limit,
    'content_topic'    => $content_topic,
    'content_user'     => $content_user,
    'content_start'    => $content_start,
    'content_order'    => $content_order,
    'content_sort'     => $content_sort,
    'content_status'   => '1',
    'content_static'   => true,
    'admin_side'       => false,
    'content_subtopic' => $content_subtopic,
    'id'               => $default_id,
    'title'            => $default_title,
    'alias'            => $default_alias,
];

// Get Information for Show in indexpage or topic pages
$contents = Fmcontent\Utils::homepage($forMods, $content_infos, $type);

$pagenav = $contents ['pagenav'] ?? null;

$info = [];
if (isset($content_topic) && $content_topic > 0
    && '0' != $view_topic->getVar('topic_showtype')) { // The option for select setting from topic or module options must be added
    if ($view_topic->getVar('topic_showauthor')) {
        $info ['author'] = '1';
    }
    if ($view_topic->getVar('topic_showdate')) {
        $info ['date'] = '1';
    }
    if ($view_topic->getVar('topic_showhits')) {
        $info ['hits'] = '1';
    }
    if ($view_topic->getVar('topic_showcoms')) {
        $info ['coms'] = '1';
    }
    if ($view_topic->getVar('topic_showtopic')) {
        $info ['showtopic'] = '1';
    }
} else {
    if (xoops_getModuleOption('disp_date', $forMods->getVar('dirname'))) {
        $info ['date'] = '1';
    }
    if (xoops_getModuleOption('disp_author', $forMods->getVar('dirname'))) {
        $info ['author'] = '1';
    }
    if (xoops_getModuleOption('disp_hits', $forMods->getVar('dirname'))) {
        $info ['hits'] = '1';
    }
    if (xoops_getModuleOption('disp_coms', $forMods->getVar('dirname'))) {
        $info ['coms'] = '1';
    }
    if (xoops_getModuleOption('disp_topic', $forMods->getVar('dirname'))) {
        $info ['showtopic'] = '1';
    }
}

if (xoops_getModuleOption('img_lightbox', $forMods->getVar('dirname'))) {
    // Add scripts
    $xoTheme->addScript('browse.php?Frameworks/jquery/jquery.js');
    $xoTheme->addScript('browse.php?Frameworks/jquery/plugins/jquery.lightbox.js');
    // Add Stylesheet
    $xoTheme->addStylesheet('browse.php?modules/system/css/lightbox.css');
    $xoopsTpl->assign('img_lightbox', true);
}

if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $forMods->getVar('dirname') . '/language/' . $GLOBALS ['xoopsConfig'] ['language'] . '/main.php')) {
    $xoopsTpl->assign('xoops_language', $GLOBALS ['xoopsConfig'] ['language']);
} else {
    $xoopsTpl->assign('xoops_language', 'english');
}

// breadcrumb
if (xoops_getModuleOption('bc_show', $forMods->getVar('dirname'))) {
    $breadcrumb = Fmcontent\Utils::breadcrumb($forMods, false, '', $topic_id, ' &raquo; ', 'topic_title');
}

// Get default content
$default_info = ['id' => $default_id, 'title' => $default_title, 'alias' => $default_alias];
$default      = $pageHandler->contentDefault($forMods, $default_info);

$xoopsTpl->assign('content_topic', $content_topic);
$xoopsTpl->assign('content_limit', $content_limit);
$xoopsTpl->assign('showtype', $showtype);
$xoopsTpl->assign('columns', $columns);
$xoopsTpl->assign('content_pagenav', $pagenav);
$xoopsTpl->assign('info', $info);
$xoopsTpl->assign('contents', $contents ['content']);
$xoopsTpl->assign('modname', $forMods->getVar('name'));
$xoopsTpl->assign('rss', xoops_getModuleOption('rss_show', $forMods->getVar('dirname')));
$xoopsTpl->assign('imgwidth', xoops_getModuleOption('imgwidth', $forMods->getVar('dirname')));
$xoopsTpl->assign('imgfloat', xoops_getModuleOption('imgfloat', $forMods->getVar('dirname')));
$xoopsTpl->assign('alluserpost', xoops_getModuleOption('alluserpost', $forMods->getVar('dirname')));
$xoopsTpl->assign('breadcrumb', $breadcrumb);
$xoopsTpl->assign('type', $type);
$xoopsTpl->assign('default', $default);
$xoopsTpl->assign('advertisement', xoops_getModuleOption('advertisement', $forMods->getVar('dirname')));

// include Xoops footer
include XOOPS_ROOT_PATH . '/footer.php';
