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
 * @author      Hossein Azizabadi (AKA Voltan)
 */

use Xmf\Request;
use XoopsModules\Fmcontent;
use XoopsModules\Fmcontent\Helper;

include __DIR__ . '/admin_header.php';
include_once XOOPS_ROOT_PATH . '/class/xoopstopic.php';
include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
//require_once XOOPS_TRUST_PATH . '/modules/fmcontent/class/topic.php'; //mb

// Display Admin header
xoops_cp_header();
// Add module stylesheet
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $forMods->getVar('dirname') . '/css/admin.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/system/css/admin.css');

$topicHandler = Helper::getInstance()->getHandler('Topic'); //mb

$permtoset                = Request::getInt('permtoset', 1, 'POST');
$selected                 = ['', '', ''];
$selected[$permtoset - 1] = ' selected';

$xoopsTpl->assign('selected0', $selected[0]);
$xoopsTpl->assign('selected1', $selected[1]);
$xoopsTpl->assign('selected2', $selected[2]);

$module_id = $forMods->getVar('mid');

switch ($permtoset) {
    case 1:
        $title_of_form      = _FMCONTENT_PERMISSIONS_GLOBAL;
        $perm_name          = 'fmcontent_ac';
        $perm_desc          = '';
        $global_perms_array = [
            //'4' => _FMCONTENT_PERMISSIONS_GLOBAL_4, //we add Rate system for next version
            '8'  => _FMCONTENT_PERMISSIONS_GLOBAL_8,
            '16' => _FMCONTENT_PERMISSIONS_GLOBAL_16,
        ];
        break;
    case 2:
        $title_of_form = _FMCONTENT_PERMISSIONS_ACCESS;
        $perm_name     = 'fmcontent_access';
        $perm_desc     = '';
        break;
    case 3:
        $title_of_form = _FMCONTENT_PERMISSIONS_SUBMIT;
        $perm_name     = 'fmcontent_submit';
        $perm_desc     = '';
        break;
}

$permform = new \XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/permissions.php');

if (1 == $permtoset) {
    foreach ($global_perms_array as $perm_id => $perm_name) {
        $permform->addItem($perm_id, $perm_name);
    }
    $xoopsTpl->assign('permform', $permform->render());
} else {
    $xt        = new \XoopsTopic($xoopsDB->prefix('fmcontent_topic'));
    $alltopics = $xt->getTopicsList();

    foreach ($alltopics as $topic_id => $topic) {
        $permform->addItem($topic_id, $topic['title'], $topic['pid']);
    }

    //check if topics exist before rendering the form and redirect, if there are no topics
    if ($topicHandler->getTopicCount($forMods)) {
        $xoopsTpl->assign('permform', $permform->render());
    } else {
        fmcontent_Redirect('topic.php?op=new_topic', 02, _FMCONTENT_MSG_NOPERMSSET);
        // Include footer
        xoops_cp_footer();
        exit();
    }
}

$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, 1);

if ($moduleConfig['usetips']) {
    $xoopsTpl->assign('fmcontent_tips', _FMCONTENT_PERMISSIONS_TIPS);
}

// Call template file
$xoopsTpl->display(XOOPS_ROOT_PATH . '/modules/' . $forMods->getVar('dirname') . '/templates/admin/fmcontent_permissions.tpl');
unset($permform);

include __DIR__ . '/admin_footer.php';
