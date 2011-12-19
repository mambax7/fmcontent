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
 * News Admin page
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Andricq Nicolas (AKA MusS)
 * @author      Hossein Azizabadi (AKA Voltan)
 * @version     $Id$
 */

require dirname(__FILE__) . '/header.php';

if (!isset($forMods)) exit('Module not found');
$index_admin = new ModuleAdmin();
// Display Admin header
xoops_cp_header();
// Add module stylesheet
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $forMods->getVar('dirname') . '/css/admin.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/system/css/admin.css');

$topic_handler = xoops_getmodulehandler('topic', 'news');
$story_handler = xoops_getmodulehandler('story', 'news');

$folder = array(
	XOOPS_ROOT_PATH . '/uploads/news/', 
	XOOPS_ROOT_PATH . '/uploads/news/img',
	XOOPS_ROOT_PATH . '/uploads/news/file'
);

$story_infos = array(
   'story_topic' => null,
);
        
$index_admin = new ModuleAdmin();
$index_admin->addInfoBox(_NEWS_AM_INDEX_ADMENU1);
$index_admin->addInfoBox(_NEWS_AM_INDEX_ADMENU2);
$index_admin->addInfoBoxLine(_NEWS_AM_INDEX_ADMENU1, _NEWS_AM_INDEX_TOPICS, $topic_handler->getTopicCount($forMods));
$index_admin->addInfoBoxLine(_NEWS_AM_INDEX_ADMENU2, _NEWS_AM_INDEX_CONTENTS, $story_handler->getAllContentCount($forMods));
$index_admin->addInfoBoxLine(_NEWS_AM_INDEX_ADMENU2, _NEWS_AM_INDEX_CONTENTS_OFFLINE, $story_handler->getOfflineContentCount($forMods , $story_infos));
$index_admin->addInfoBoxLine(_NEWS_AM_INDEX_ADMENU2, _NEWS_AM_INDEX_CONTENTS_EXPIRE, $story_handler->getExpireContentCount($forMods , $story_infos));

foreach (array_keys( $folder) as $i) {
    $index_admin->addConfigBoxLine($folder[$i], 'folder');
    $index_admin->addConfigBoxLine(array($folder[$i], '777'), 'chmod');
}
    
$xoopsTpl->assign('navigation', 'index');
$xoopsTpl->assign('navtitle', _NEWS_MI_HOME);
$xoopsTpl->assign('renderindex', $index_admin->renderIndex());

// Call template file
$xoopsTpl->display(XOOPS_ROOT_PATH . '/modules/' . $forMods->getVar('dirname') . '/templates/admin/news_index.html');

// Display Xoops footer
include "footer.php";
xoops_cp_footer();
?>