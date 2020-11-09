<?php

namespace XoopsModules\Fmcontent;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * FmContent page class
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Andricq Nicolas (AKA MusS)
 * @author      Hossein Azizabadi (AKA Voltan)
 */
class Page extends \XoopsObject
{
    public $mod;
    public $db;
    public $table;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->initVar('content_id', XOBJ_DTYPE_INT);
        $this->initVar('content_title', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_titleview', XOBJ_DTYPE_INT, 1);
        $this->initVar('content_menu', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_topic', XOBJ_DTYPE_INT);
        $this->initVar('content_type', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_short', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('content_text', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('content_link', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_words', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_desc', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_alias', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_status', XOBJ_DTYPE_INT, 1);
        $this->initVar('content_display', XOBJ_DTYPE_INT, 0);
        $this->initVar('content_default', XOBJ_DTYPE_INT, 0);
        $this->initVar('content_create', XOBJ_DTYPE_INT, '');
        $this->initVar('content_update', XOBJ_DTYPE_INT, '');
        $this->initVar('content_uid', XOBJ_DTYPE_INT, 0);
        $this->initVar('content_author', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_source', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_groups', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_order', XOBJ_DTYPE_INT, 0);
        $this->initVar('content_next', XOBJ_DTYPE_INT, 0);
        $this->initVar('content_prev', XOBJ_DTYPE_INT, 0);
        $this->initVar('content_modid', XOBJ_DTYPE_INT, '');
        $this->initVar('content_hits', XOBJ_DTYPE_INT, '');
        $this->initVar('content_img', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_comments', XOBJ_DTYPE_INT, '');
        $this->initVar('content_file', XOBJ_DTYPE_INT, '');
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1);
        $this->initVar('doxcode', XOBJ_DTYPE_INT, 1);
        $this->initVar('dosmiley', XOBJ_DTYPE_INT, 1);
        $this->initVar('doimage', XOBJ_DTYPE_INT, 1);
        $this->initVar('dobr', XOBJ_DTYPE_INT, 0);

        $this->db    = $GLOBALS ['xoopsDB'];
        $this->table = $this->db->prefix('fmcontent_content');
    }

    /**
     * @param        $forMods
     * @param string $contentType
     * @return \XoopsThemeForm
     */
    public function getContentForm($forMods, $contentType = 'content')
    {
        $form = new \XoopsThemeForm(_FMCONTENT_FORM, 'content', 'backend.php', 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        if ($this->isNew()) {
            $groups = xoops_getModuleOption('groups', $forMods->getVar('dirname', 'e'));
            $form->addElement(new \XoopsFormHidden('op', 'add'));
            $form->addElement(new \XoopsFormHidden('content_uid', $GLOBALS ['xoopsUser']->getVar('uid')));
        } else {
            $groups = explode(' ', $this->getVar('content_groups', 'e'));
            $form->addElement(new \XoopsFormHidden('op', 'edit'));
            $contentType = $this->getVar('content_type', 'e');
        }
        // Content Id
        $form->addElement(new \XoopsFormHidden('content_id', $this->getVar('content_id', 'e')));
        // Module Id
        $form->addElement(new \XoopsFormHidden('content_modid', $forMods->getVar('mid')));
        // Content type
        $form->addElement(new \XoopsFormHidden('content_type', $contentType));
        // Content title
        $title = new \XoopsFormElementTray(_FMCONTENT_FORMTITLE);
        $title->addElement(new \XoopsFormText('', 'content_title', 50, 255, $this->getVar('content_title', 'e')), true);
        $display_title = new \XoopsFormCheckBox('', 'content_titleview', $this->getVar('content_titleview', 'e'));
        $display_title->addOption(1, _FMCONTENT_FORMTITLE_DISP);
        $title->addElement($display_title);
        $form->addElement($title);
        // Content menu
        $form->addElement(new \XoopsFormText(_FMCONTENT_CONTENT_MENU, 'content_menu', 50, 255, $this->getVar('content_menu', 'e')), true);
        // Content menu text/alias
        $form->addElement(new \XoopsFormText(_FMCONTENT_FORMALIAS, 'content_alias', 50, 255, $this->getVar('content_alias', 'e')), true);
        // Topic
        $topic_Handler = \XoopsModules\Fmcontent\Helper::getInstance()->getHandler('Topic');
        $criteria      = new \CriteriaCompo();
        $criteria->add(new \Criteria('topic_modid', $forMods->getVar('mid')));
        $topic = $topic_Handler->getObjects($criteria);
        if ($topic) {
            $tree = new \XoopsObjectTree($topic, 'topic_id', 'topic_pid');
            ob_start();
            $topicSelect = $tree->makeSelectElement('content_topic', 'topic_title', '', $this->getVar('content_topic', 'e'), true, 0, '', '');;
            echo $topicSelect->render();

            $topic_sel = new \XoopsFormLabel(_FMCONTENT_CONTENT_TOPIC, ob_get_contents());
            $topic_sel->setDescription(_FMCONTENT_CONTENT_TOPIC_DESC);
            $form->addElement($topic_sel);
            ob_end_clean();
        } else {
            $form->addElement(new \XoopsFormHidden('content_topic', 0));
        }
        // Short
        $form->addElement(new \XoopsFormTextArea(_FMCONTENT_SHORT, 'content_short', $this->getVar('content_short', 'e'), 5, 90));
        // Editor
        $editor_tray = new \XoopsFormElementTray(_FMCONTENT_FORMTEXT, '<br>');
        if (class_exists('XoopsFormEditor')) {
            $configs = [
                'name'   => 'content_desc',
                'value'  => $this->getVar('content_text', 'e'),
                'rows'   => 25,
                'cols'   => 90,
                'width'  => '100%',
                'height' => '400px',
                'editor' => xoops_getModuleOption('form_editor', $forMods->getVar('dirname', 'e')),
            ];
            $editor_tray->addElement(new \XoopsFormEditor('', 'content_text', $configs, false, $onfailure = 'textarea'));
        } else {
            $editor_tray->addElement(new \XoopsFormDhtmlTextArea('', 'content_text', $this->getVar('content_text', 'e'), '100%', '100%'));
        }
        $editor_tray->setDescription(_FMCONTENT_FORMTEXT_DESC);
        if (!fmcontent_isEditorHTML($forMods->getVar('dirname', 'e'))) {
            if ($this->isNew()) {
                $this->setVar('dohtml', 0);
                $this->setVar('dobr', 1);
            }
            // HTML
            $html_checkbox = new \XoopsFormCheckBox('', 'dohtml', $this->getVar('dohtml', 'e'));
            $html_checkbox->addOption(1, _FMCONTENT_DOHTML);
            $editor_tray->addElement($html_checkbox);
            // Break line
            $breaks_checkbox = new \XoopsFormCheckBox('', 'dobr', $this->getVar('dobr', 'e'));
            $breaks_checkbox->addOption(1, _FMCONTENT_BREAKS);
            $editor_tray->addElement($breaks_checkbox);
        } else {
            $form->addElement(new \XoopsFormHidden('dohtml', 1));
            $form->addElement(new \XoopsFormHidden('dobr', 0));
        }
        // Xoops Image
        $doimage_checkbox = new \XoopsFormCheckBox('', 'doimage', $this->getVar('doimage', 'e'));
        $doimage_checkbox->addOption(1, _FMCONTENT_DOIMAGE);
        $editor_tray->addElement($doimage_checkbox);
        // Xoops Code
        $xcodes_checkbox = new \XoopsFormCheckBox('', 'doxcode', $this->getVar('doxcode', 'e'));
        $xcodes_checkbox->addOption(1, _FMCONTENT_DOXCODE);
        $editor_tray->addElement($xcodes_checkbox);
        // Xoops Smiley
        $smiley_checkbox = new \XoopsFormCheckBox('', 'dosmiley', $this->getVar('dosmiley', 'e'));
        $smiley_checkbox->addOption(1, _FMCONTENT_DOSMILEY);
        $editor_tray->addElement($smiley_checkbox);
        // Editor and options
        $form->addElement($editor_tray);
        //tag
        if (xoops_getModuleOption('usetag', $forMods->getVar('dirname')) && is_dir(XOOPS_ROOT_PATH . '/modules/tag')) {
            $items_id = $this->isNew() ? 0 : $this->getVar('content_id');
            include_once XOOPS_ROOT_PATH . '/modules/tag/include/formtag.php';
            $form->addElement(new \TagFormTag('item_tag', 60, 255, $items_id, $catid = 0));
        }
        // Image
        $content_img                = $this->getVar('content_img') ?: 'blank.gif';
        $uploadirectory_content_img = xoops_getModuleOption('img_dir', $forMods->getVar('dirname'));
        $fileseltray_content_img    = new \XoopsFormElementTray(_FMCONTENT_IMG, '<br>');
        $fileseltray_content_img->addElement(new \XoopsFormLabel('', "<img class='fromimage' src='" . XOOPS_URL . $uploadirectory_content_img . $content_img . "' name='image_content_img' id='image_content_img' alt='' />"));
        if ($this->getVar('content_img')) {
            $delete_img = new \XoopsFormCheckBox('', 'deleteimage', 0);
            $delete_img->addOption(1, _DELETE);
            $fileseltray_content_img->addElement($delete_img);
        }
        $fileseltray_content_img->addElement(new \XoopsFormFile(_FMCONTENT_FORMUPLOAD, 'content_img', xoops_getModuleOption('img_size', $forMods->getVar('dirname'))), false);
        $form->addElement($fileseltray_content_img);
        // Files
        $uploadirectory_file = xoops_getModuleOption('file_dir', $forMods->getVar('dirname'));
        $fileseltray_file    = new \XoopsFormFile(_FMCONTENT_SELECT_FILE, 'file_name', xoops_getModuleOption('file_size', $forMods->getVar('dirname')));
        $file                = new \XoopsFormElementTray(_FMCONTENT_FILE);
        $file->addElement($fileseltray_file);
        $file->setDescription(_FMCONTENT_CONTENT_FILE_DESC);
        $form->addElement($file);
        // Metas
        $form->addElement(new \XoopsFormTextArea('Metas Keyword', 'content_words', $this->getVar('content_words', 'e'), 5, 90));
        $form->addElement(new \XoopsFormTextArea('Metas Description', 'content_desc', $this->getVar('content_desc', 'e'), 5, 90));
        // Content author
        $form->addElement(new \XoopsFormText(_FMCONTENT_FORMAUTHOR, 'content_author', 50, 255, $this->getVar('content_author', 'e')), false);
        // Content Source
        $form->addElement(new \XoopsFormText(_FMCONTENT_FORMSOURCE, 'content_source', 50, 255, $this->getVar('content_source', 'e')), false);
        // Groups access
        $form->addElement(new \XoopsFormSelectGroup(_FMCONTENT_FORMGROUP, 'content_groups', true, $groups, 5, true));
        // Next & prev
        if (!$this->isNew()) {
            $content_Handler = \XoopsModules\Fmcontent\Helper::getInstance()->getHandler('Page');
            $criteria        = new \CriteriaCompo();
            $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));
            $criteria->add(new \Criteria('content_status', '1'));
            $criteria->add(new \Criteria('content_topic', $this->getVar('content_topic', 'e')));
            $content = $content_Handler->getObjects($criteria);
            $tree    = new \XoopsObjectTree($content, 'content_id', 'content_topic');
            ob_start();
            $prevTitleSelect = $tree->makeSelectElement('content_prev', 'content_title', '', $this->getVar('content_prev', 'e'), true, 0, '', '');;
            echo $prevTitleSelect->render();

            $form->addElement(new \XoopsFormLabel(_FMCONTENT_FORMPREV, ob_get_clean()));
            ob_start();
            $nextTitleSelect = $tree->makeSelectElement('content_next', 'content_title', '', $this->getVar('content_next', 'e'), true, 0, '', '');;
            echo $nextTitleSelect->render();

            $form->addElement(new \XoopsFormLabel(_FMCONTENT_FORMNEXT, ob_get_clean()));
        }
        // Active
        $form->addElement(new \XoopsFormRadioYN(_FMCONTENT_FORMACTIF, 'content_status', $this->getVar('content_status', 'e')));
        // Menu
        $form->addElement(new \XoopsFormRadioYN(_FMCONTENT_FORMDISPLAY, 'content_display', $this->getVar('content_display', 'e')));
        // Default
        $form->addElement(new \XoopsFormRadioYN(_FMCONTENT_FORMDEFAULT, 'content_default', $this->getVar('content_default', 'e')));
        // Submit buttons
        $button_tray = new \XoopsFormElementTray('', '');
        $submit_btn  = new \XoopsFormButton('', 'post', _SUBMIT, 'submit');
        $button_tray->addElement($submit_btn);
        $cancel_btn = new \XoopsFormButton('', 'cancel', _CANCEL, 'cancel');
        $cancel_btn->setExtra('onclick="javascript:history.go(-1);"');
        $button_tray->addElement($cancel_btn);
        $form->addElement($button_tray);
        $form->display();

        return $form;
    }

    /**
     * @param        $forMods
     * @param string $contentType
     * @return \XoopsThemeForm
     */
    public function getContentSimpleForm($forMods, $contentType = 'content')
    {
        $form = new \XoopsThemeForm(_FMCONTENT_FORM, 'content', 'submit.php', 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        if ($this->isNew()) {
            $groups = xoops_getModuleOption('groups', $forMods->getVar('dirname', 'e'));
            $form->addElement(new \XoopsFormHidden('op', 'add'));
            $form->addElement(new \XoopsFormHidden('content_uid', $GLOBALS ['xoopsUser']->getVar('uid')));
        } else {
            $groups = explode(' ', $this->getVar('content_groups', 'e'));
            $form->addElement(new \XoopsFormHidden('op', 'edit'));
            $contentType = $this->getVar('content_type', 'e');
        }
        // Content Id
        $form->addElement(new \XoopsFormHidden('content_id', $this->getVar('content_id', 'e')));
        // Module Id
        $form->addElement(new \XoopsFormHidden('content_modid', $forMods->getVar('mid')));
        // Content type
        $form->addElement(new \XoopsFormHidden('content_type', $contentType));
        // Content title
        $form->addElement(new \XoopsFormText(_FMCONTENT_FORMTITLE, 'content_title', 50, 255, $this->getVar('content_title', 'e')), true);
        // Topic
        $topic_Handler = \XoopsModules\Fmcontent\Helper::getInstance()->getHandler('Topic');
        $perm_handler  = Permission::getHandler();
        $topics        = Permission::getItemIds('fmcontent_submit', $forMods);
        $criteria      = new \CriteriaCompo();
        $criteria->add(new \Criteria('topic_modid', $forMods->getVar('mid')));
        global $xoopsUser;
        if ($xoopsUser) {
            if (!$xoopsUser->isAdmin($forMods->getVar('mid'))) {
                $criteria->add(new \Criteria('topic_id', '(' . implode(',', $topics) . ')', 'IN'));
            }
        } else {
            $criteria->add(new \Criteria('topic_id', '(' . implode(',', $topics) . ')', 'IN'));
        }
        $topic = $topic_Handler->getObjects($criteria);
        if ($topic) {
            $tree = new \XoopsObjectTree($topic, 'topic_id', 'topic_pid');
            ob_start();
            echo $tree->makeSelBox('content_topic', 'topic_title', '--', $this->getVar('content_topic', 'e'), true);
            $topic_sel = new \XoopsFormLabel(_FMCONTENT_CONTENT_TOPIC, ob_get_contents());
            $topic_sel->setDescription(_FMCONTENT_CONTENT_TOPIC_DESC);
            $form->addElement($topic_sel);
            ob_end_clean();
        } else {
            $form->addElement(new \XoopsFormHidden('content_topic', 0));
        }
        // Short
        $form->addElement(new \XoopsFormTextArea(_FMCONTENT_SHORT, 'content_short', $this->getVar('content_short', 'e'), 5, 80));
        // Editor
        $editor_tray = new \XoopsFormElementTray(_FMCONTENT_FORMTEXT, '<br>');
        if (class_exists('XoopsFormEditor')) {
            $configs = [
                'name'   => 'content_desc',
                'value'  => $this->getVar('content_text', 'e'),
                'rows'   => 15,
                'cols'   => 80,
                'width'  => '95%',
                'height' => '250px',
                'editor' => xoops_getModuleOption('form_editor', $forMods->getVar('dirname', 'e')),
            ];
            $editor_tray->addElement(new \XoopsFormEditor('', 'content_text', $configs, false, $onfailure = 'textarea'));
        } else {
            $editor_tray->addElement(new \XoopsFormDhtmlTextArea('', 'content_text', $this->getVar('content_text', 'e'), '100%', '100%'));
        }
        $editor_tray->setDescription(_FMCONTENT_FORMTEXT_DESC);
        // Editor and options
        $form->addElement($editor_tray);
        if (!fmcontent_isEditorHTML($forMods->getVar('dirname', 'e'))) {
            $form->addElement(new \XoopsFormHidden('dohtml', 0));
            $form->addElement(new \XoopsFormHidden('dobr', 1));
        } else {
            $form->addElement(new \XoopsFormHidden('dohtml', 1));
            $form->addElement(new \XoopsFormHidden('dobr', 0));
        }
        $form->addElement(new \XoopsFormHidden('doimage', 1));
        $form->addElement(new \XoopsFormHidden('doxcode', 1));
        $form->addElement(new \XoopsFormHidden('dosmiley', 1));
        //tag
        if (xoops_getModuleOption('usetag', $forMods->getVar('dirname')) && is_dir(XOOPS_ROOT_PATH . '/modules/tag')) {
            $items_id = $this->isNew() ? 0 : $this->getVar('content_id');
            include_once XOOPS_ROOT_PATH . '/modules/tag/include/formtag.php';
            $form->addElement(new \TagFormTag('item_tag', 60, 255, $items_id, $catid = 0));
        }
        // Image
        $content_img                = $this->getVar('content_img') ?: 'blank.gif';
        $uploadirectory_content_img = xoops_getModuleOption('img_dir', $forMods->getVar('dirname'));
        $fileseltray_content_img    = new \XoopsFormElementTray(_FMCONTENT_IMG, '<br>');
        $fileseltray_content_img->addElement(new \XoopsFormLabel('', "<img class='fromimage' src='" . XOOPS_URL . $uploadirectory_content_img . $content_img . "' name='image_content_img' id='image_content_img' alt='' />"));
        $fileseltray_content_img->addElement(new \XoopsFormFile(_FMCONTENT_FORMUPLOAD, 'content_img', xoops_getModuleOption('img_size', $forMods->getVar('dirname'))), false);
        $form->addElement($fileseltray_content_img);
        // Files
        $uploadirectory_file = xoops_getModuleOption('file_dir', $forMods->getVar('dirname'));
        $fileseltray_file    = new \XoopsFormFile(_FMCONTENT_SELECT_FILE, 'file_name', xoops_getModuleOption('file_size', $forMods->getVar('dirname')));
        $file                = new \XoopsFormElementTray(_FMCONTENT_FILE);
        $file->addElement($fileseltray_file);
        $file->setDescription(_FMCONTENT_CONTENT_FILE_DESC);
        $form->addElement($file);
        // Submit buttons
        $button_tray = new \XoopsFormElementTray('', '');
        $submit_btn  = new \XoopsFormButton('', 'post', _SUBMIT, 'submit');
        $button_tray->addElement($submit_btn);
        $cancel_btn = new \XoopsFormButton('', 'cancel', _CANCEL, 'cancel');
        $cancel_btn->setExtra('onclick="javascript:history.go(-1);"');
        $button_tray->addElement($cancel_btn);
        $form->addElement($button_tray);
        $form->display();

        return $form;
    }

    /**
     * @param        $forMods
     * @param string $contentType
     * @return \XoopsThemeForm
     */
    public function getMenuForm($forMods, $contentType = 'link')
    {
        $form = new \XoopsThemeForm(_FMCONTENT_FORM, 'link', 'backend.php', 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        if ($this->isNew()) {
            $groups = xoops_getModuleOption('groups', $forMods->getVar('dirname', 'e'));
            $form->addElement(new \XoopsFormHidden('op', 'add'));
            $form->addElement(new \XoopsFormHidden('content_uid', $GLOBALS ['xoopsUser']->getVar('uid')));
        } else {
            $groups = explode(' ', $this->getVar('content_groups', 'e'));
            $form->addElement(new \XoopsFormHidden('op', 'edit'));
            $contentType = $this->getVar('content_type', 'e');
        }
        // Content Id
        $form->addElement(new \XoopsFormHidden('content_id', $this->getVar('content_id', 'e')));
        // Module Id
        $form->addElement(new \XoopsFormHidden('content_modid', $forMods->getVar('mid')));
        // Display menu
        $form->addElement(new \XoopsFormHidden('content_display', '1'));
        // Content type
        $form->addElement(new \XoopsFormHidden('content_type', $contentType));
        // Content menumenu
        $form->addElement(new \XoopsFormText(_FMCONTENT_CONTENT_MENU, 'content_menu', 50, 255, $this->getVar('content_menu', 'e')), true);
        // Link
        $form->addElement(new \XoopsFormText(_FMCONTENT_FORMLINK, 'content_link', 50, 255, $this->getVar('content_link', 'e')), true);
        // Topic
        $topic_Handler = \XoopsModules\Fmcontent\Helper::getInstance()->getHandler('Topic');
        $criteria      = new \CriteriaCompo();
        $criteria->add(new \Criteria('topic_modid', $forMods->getVar('mid')));
        $topic = $topic_Handler->getObjects($criteria);
        $tree  = new \XoopsObjectTree($topic, 'topic_id', 'topic_pid');
        ob_start();
         $topicSelect = $tree->makeSelectElement('content_topic', 'topic_title', '', $this->getVar('content_topic', 'e'), true, 0, '', '');;
        echo $topicSelect->render();

        $form->addElement(new \XoopsFormLabel(_FMCONTENT_CONTENT_TOPIC, ob_get_clean()));
        // Groups access
        $form->addElement(new \XoopsFormSelectGroup(_FMCONTENT_FORMGROUP, 'content_groups', true, $groups, 5, true));
        // Options
        $options = new \XoopsFormElementTray(_FMCONTENT_FORMOPTION);
        // Active
        $form->addElement(new \XoopsFormRadioYN(_FMCONTENT_FORMACTIF, 'content_status', $this->getVar('content_status', 'e')));

        $button_tray = new \XoopsFormElementTray('', '');
        $submit_btn  = new \XoopsFormButton('', 'post', _SUBMIT, 'submit');
        $button_tray->addElement($submit_btn);
        $cancel_btn = new \XoopsFormButton('', 'cancel', _CANCEL, 'cancel');
        $cancel_btn->setExtra('onclick="javascript:history.go(-1);"');
        $button_tray->addElement($cancel_btn);
        $form->addElement($button_tray);
        $form->display();

        return $form;
    }

    /**
     * Returns an array representation of the object
     *
     * @return array
     **/
    public function toArray()
    {
        $ret  = [];
        $vars = $this->getVars();
        foreach (array_keys($vars) as $i) {
            $ret [$i] = $this->getVar($i);
        }

        return $ret;
    }
}
