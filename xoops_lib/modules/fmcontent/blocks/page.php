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
 * Module block page file
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Andricq Nicolas (AKA MusS)
 * @param mixed $options
 */

use XoopsModules\Fmcontent;
use XoopsModules\Fmcontent\Helper;
use XoopsModules\Fmcontent\Registry;

/**
 * @param $options
 * @return array
 */
function fmcontent_page_show($options)
{
    global $xoTheme, $xoopsTpl, $module_header;
    // Create Module Instance
    $module_handler = xoops_getHandler('module');
    $forMods        = $module_handler->getByDirname('fmcontent');
    // Initialize content handler
    $pageHandler = Helper::getInstance()->getHandler('Page');
    $topicHandler   = Helper::getInstance()->getHandler('Topic');
    // Get the content menu
    $content = $pageHandler->get($options[0]);
    // Add block data
    $block                = $content->toArray();
    $topic                = $topicHandler->get($block['content_topic']);
    $topic                = $topic->toArray();
    $block['topic_id']    = $topic['topic_id'];
    $block['topic_title'] = $topic['topic_title'];
    $block['topic_alias'] = $topic['topic_alias'];
    $block['link']        = fmcontent_Url($forMods->getVar('dirname'), $block);
    $block['imgurl']      = XOOPS_URL . xoops_getModuleOption('img_dir', $forMods->getVar('dirname'));
    $block['width']       = xoops_getModuleOption('imgwidth', $forMods->getVar('dirname'));
    $block['float']       = xoops_getModuleOption('imgfloat', $forMods->getVar('dirname'));
    // Add styles
    $xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $forMods->getVar('dirname') . '/css/blocks.css', null);

    // Return block array
    return $block;
}

/**
 * @param $options
 * @return string
 */
function fmcontent_page_edit($options)
{
    //    require_once XOOPS_TRUST_PATH . '/modules/fmcontent/class/registry.php';
    $registry = Registry::getInstance();
    // Initialize content handler
    $pageHandler = Helper::getInstance()->getHandler('Page');

    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('content_type', 'content'));
    $criteria->add(new \Criteria('content_status', '1'));
    $content = $pageHandler->getObjects($criteria);
    $form    = _MI_FMCONTENT_SELECTPAGE . '<select name="options[]">';
    foreach (array_keys($content) as $i) {
        $form .= '<option value="' . $content[$i]->getVar('content_id') . '"';
        if ($options[0] == $content[$i]->getVar('content_id')) {
            $form .= ' selected';
        }
        $form .= '>' . $content[$i]->getVar('content_title') . "</option>\n";
    }
    $form .= "</select>\n";

    //$form .= "<input type='hidden' value='" . $options[1] . "'>\n";
    return $form;
}
