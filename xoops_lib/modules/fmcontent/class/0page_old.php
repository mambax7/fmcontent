<?php

//namespace XoopsModules\Fmcontent;

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
 */

use XoopsModules\Fmcontent\{
    Helper,
    TopicHandler
};

/**
 * Class Content
 */
class Content extends \XoopsObject
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
        $this->initVar('content_pid', XOBJ_DTYPE_INT, 0);
        $this->initVar('content_type', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_short', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('content_text', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('content_link', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_words', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_desc', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_alias', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_display', XOBJ_DTYPE_INT, 1);
        $this->initVar('content_default', XOBJ_DTYPE_INT, 0);
        $this->initVar('content_status', XOBJ_DTYPE_INT, 1);
        $this->initVar('content_create', XOBJ_DTYPE_OTHER);
        $this->initVar('content_update', XOBJ_DTYPE_OTHER);
        $this->initVar('content_uid', XOBJ_DTYPE_INT, 0);
        $this->initVar('content_author', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_source', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_groups', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_order', XOBJ_DTYPE_INT, 0);
        $this->initVar('content_next', XOBJ_DTYPE_INT, 0);
        $this->initVar('content_prev', XOBJ_DTYPE_INT, 0);
        $this->initVar('content_modid', XOBJ_DTYPE_INT, '');
        $this->initVar('content_img', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('content_topic', XOBJ_DTYPE_INT);
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1);
        $this->initVar('doxcode', XOBJ_DTYPE_INT, 1);
        $this->initVar('dosmiley', XOBJ_DTYPE_INT, 1);
        $this->initVar('doimage', XOBJ_DTYPE_INT, 1);
        $this->initVar('dobr', XOBJ_DTYPE_INT, 0);

        $this->db    = $GLOBALS ['xoopsDB'];
        $this->table = $this->db->prefix('fmcontent');
    }

    /**
     * @param        $forMods
     * @param string $contentType
     */
    public function getContentForm($forMods, $contentType = 'content')
    {
        $form = new \XoopsThemeForm(_FMCONTENT_FORM, 'content', 'backend.php', 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        if ($this->isNew()) {
            $groups = xoops_getModuleOption('groups', $forMods->getVar('dirname', 'e'));
            $form->addElement(new \XoopsFormHidden('op', 'add'));
        } else {
            $groups = explode(' ', $this->getVar('content_groups', 'e'));
            $form->addElement(new \XoopsFormHidden('op', 'edit'));
            $contentType = $this->getVar('content_type', 'e');
        }
        // Content Id
        $form->addElement(new \XoopsFormHidden('content_id', $this->getVar('content_id', 'e')));
        // User Id
        $form->addElement(new \XoopsFormHidden('content_uid', $GLOBALS ['xoopsUser']->getVar('uid')));
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
        // Content menu text
        $form->addElement(new \XoopsFormText(_FMCONTENT_FORMALIAS, 'content_alias', 50, 255, $this->getVar('content_alias', 'e')), true);
        // Topic
        $topic_Handler = Helper::getInstance()->getHandler('Topic');
        $criteria      = new \CriteriaCompo();
        $criteria->add(new \Criteria('topic_modid', $forMods->getVar('mid')));
        $topic = $topic_Handler->getObjects($criteria);
        $tree  = new \XoopsObjectTree($topic, 'topic_id', 'topic_pid');
        ob_start();
        echo $tree->makeSelBox('content_topic', 'topic_title', '--', $this->getVar('content_topic', 'e'), true);
        $form->addElement(new \XoopsFormLabel(_FMCONTENT_CONTENT_TOPIC, ob_get_clean()));
        // Menu
        ob_start();
        echo $tree->makeSelBox('content_pid', 'topic_title', '--', $this->getVar('content_pid', 'e'), true);
        $form->addElement(new \XoopsFormLabel(_FMCONTENT_MENU_TOPIC, ob_get_clean()));
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
        // Image
        $form->addElement(new \XoopsFormText(_FMCONTENT_IMG, 'content_img', 50, 255, $this->getVar('content_img', 'e')), false);
        // Metas
        $form->addElement(new \XoopsFormTextArea('Metas Keyword', 'content_words', $this->getVar('content_words', 'e'), 5, 90));
        $form->addElement(new \XoopsFormTextArea('Metas Description', 'content_desc', $this->getVar('content_desc', 'e'), 5, 90));
        // Content Source
        $form->addElement(new \XoopsFormText(_FMCONTENT_FORMSOURCE, 'content_source', 50, 255, $this->getVar('content_source', 'e')), false);
        // Groups access
        $form->addElement(new \XoopsFormSelectGroup(_FMCONTENT_FORMGROUP, 'content_groups', true, $groups, 5, true));
        // Next & prev
        ob_start();
        echo $tree->makeSelBox('content_prev', 'content_title', '--', $this->getVar('content_prev', 'e'), true);
        $form->addElement(new \XoopsFormLabel(_FMCONTENT_FORMPREV, ob_get_clean()));
        ob_start();
        echo $tree->makeSelBox('content_next', 'content_title', '--', $this->getVar('content_next', 'e'), true);
        $form->addElement(new \XoopsFormLabel(_FMCONTENT_FORMNEXT, ob_get_clean()));
        // Options
        $options = new \XoopsFormElementTray(_FMCONTENT_FORMOPTION);
        // Active
        $actif = new \XoopsFormCheckBox('', 'content_status', $this->getVar('content_status', 'e'));
        $actif->addOption(1, _FMCONTENT_FORMACTIF);
        $options->addElement($actif);
        // Menu
        $menu = new \XoopsFormCheckBox('', 'content_display', $this->getVar('content_display', 'e'));
        $menu->addOption(1, _FMCONTENT_FORMDISPLAY);
        $options->addElement($menu);
        // Default
        $default = new \XoopsFormCheckBox('', 'content_default', $this->getVar('content_default', 'e'));
        $default->addOption(1, _FMCONTENT_FORMDEFAULT);
        $options->addElement($default);
        $form->addElement($options);

        // Submit buttons
        $button_tray = new \XoopsFormElementTray('', '');
        $submit_btn  = new \XoopsFormButton('', 'post', _SUBMIT, 'submit');
        $button_tray->addElement($submit_btn);
        $cancel_btn = new \XoopsFormButton('', 'cancel', _CANCEL, 'cancel');
        $cancel_btn->setExtra('onclick="javascript:history.go(-1);"');
        $button_tray->addElement($cancel_btn);
        $form->addElement($button_tray);
        $form->display();
    }

    /**
     * @param        $forMods
     * @param string $contentType
     */
    public function getMenuForm($forMods, $contentType = 'link')
    {
        $form = new \XoopsThemeForm(_FMCONTENT_FORM, 'link', 'backend.php', 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        if ($this->isNew()) {
            $groups = xoops_getModuleOption('groups', $forMods->getVar('dirname', 'e'));
            $form->addElement(new \XoopsFormHidden('op', 'add'));
        } else {
            $groups = explode(' ', $this->getVar('content_groups', 'e'));
            $form->addElement(new \XoopsFormHidden('op', 'edit'));
            $contentType = $this->getVar('content_type', 'e');
        }
        // Content Id
        $form->addElement(new \XoopsFormHidden('content_id', $this->getVar('content_id', 'e')));
        // User Id
        $form->addElement(new \XoopsFormHidden('content_uid', $GLOBALS ['xoopsUser']->getVar('uid')));
        // Module Id
        $form->addElement(new \XoopsFormHidden('content_modid', $forMods->getVar('mid')));

        // Link
        $form->addElement(new \XoopsFormText(_FMCONTENT_FORMLINK, 'content_link', 50, 255, $this->getVar('content_link', 'e')), true);
        // Content menumenu
        $form->addElement(new \XoopsFormText(_FMCONTENT_FORMMENU, 'content_title', 50, 255, $this->getVar('content_title', 'e')), true);
        // Parent
        $pageHandler = Helper::getInstance()->getHandler('Page');
        $criteria        = new \CriteriaCompo();
        $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));
        $page = $pageHandler->getObjects($criteria);
        $tree = new \XoopsObjectTree($page, 'content_id', 'content_pid');
        ob_start();
        echo $tree->makeSelBox('content_pid', 'content_title', '--', $this->getVar('content_pid', 'e'), true);
        $form->addElement(new \XoopsFormLabel(_FMCONTENT_FORMPARENT, ob_get_clean()));
        // Groups access
        $form->addElement(new \XoopsFormSelectGroup(_FMCONTENT_FORMGROUP, 'content_groups', true, $groups, 5, true));
        // Options
        $options = new \XoopsFormElementTray(_FMCONTENT_FORMOPTION);
        // Active
        $actif = new \XoopsFormCheckBox('', 'content_status', $this->getVar('content_status', 'e'));
        $actif->addOption(1, _FMCONTENT_FORMACTIF);
        $options->addElement($actif);
        $form->addElement($options);

        $button_tray = new \XoopsFormElementTray('', '');
        $submit_btn  = new \XoopsFormButton('', 'post', _SUBMIT, 'submit');
        $button_tray->addElement($submit_btn);
        $cancel_btn = new \XoopsFormButton('', 'cancel', _CANCEL, 'cancel');
        $cancel_btn->setExtra('onclick="javascript:history.go(-1);"');
        $button_tray->addElement($cancel_btn);
        $form->addElement($button_tray);
        $form->display();
    }

    /**
     * Check if content alias already exist
     *
     * @param string $alias
     * @return bool
     **/
    public function existAlias($alias)
    {
        $query  = 'SELECT `content_id` FROM ' . $this->table . " WHERE `content_alias` = '" . $alias . "'";
        $result = $this->db->query($query);
        $count  = $this->db->getRowsNum($result);
        if (0 == $count) {
            return false;
        }

        return true;
    }

    /**
     * @param $child
     * @return mixed
     */
    public function getFirstChild($child)
    {
        $ret = [];
        foreach ($child as $item) {
            return $item;
        }
    }

    /**
     * @param $child
     * @param $id
     * @return mixed
     */
    public function getInChild($child, $id)
    {
        foreach ($child as $page) {
            if ($page ['content_id'] == $id) {
                if ($page ['child']) {
                    return $this->getFirstChild($page ['child']);
                }
            }
            if ($page ['child']) {
                $this->getInChild($page ['child'], $id);
            }
        }
    }

    /**
     * Search next page for navigation
     *
     * @param int    $id   id of current page
     * @param string $sort query sort
     * @return array
     **/
    public function getNextLink($id, $sort = 'content_order')
    {
        $pageHandler = Helper::getInstance()->getHandler('Page');
        $content         = $pageHandler->getTree($this->mod->getEntry('modid'));
        $ok              = false;
        foreach ($content as $page) {
            if ($ok) {
                return $page;
            }
            if ($page ['content_id'] == $id) {
                if ($page ['child']) {
                    return $this->getFirstChild($page ['child']);
                }
                $ok = true;
                continue;
            }
            if (!$ok && $page ['child']) {
                $this->getInChild($page ['child'], $id);
            }
        }
        exit();

        $return = [];
        // Define criteria
        $criteria = new \CriteriaCompo();
        $criteria = new \Criteria('content_status', 1);
        $criteria->setSort($sort);
        // Initialize content handler
        $pageHandler = Helper::getInstance()->getHandler('Page');
        $links           = $pageHandler->getObjects($criteria, false, false);
        $current         = false;
        foreach (array_keys($links) as $i) {
            if ($current && 'content' == $links [$i] ['content_type']) {
                $return ['id']    = $links [$i] ['content_id'];
                $return ['alias'] = $links [$i] ['content_alias'];
                $return ['title'] = $links [$i] ['content_title'];

                return $return;
            }
            if ($links [$i] ['content_id'] == $id) {
                $current = true;
            }
        }

        return $return;
    }

    /**
     * Search previous page for navigation
     *
     * @param int    $id   id of current page
     * @param string $sort query sort
     * @return array
     **/
    public function getPrevLink($id, $sort = 'content_order')
    {
        $return = [];
        // Define criteria
        $criteria = new \CriteriaCompo();
        $criteria = new \Criteria('content_status', 1);
        $criteria->setSort($sort);
        $criteria->setOrder('desc');
        // Initialize content handler
        $pageHandler = Helper::getInstance()->getHandler('Page');
        $links           = $pageHandler->getObjects($criteria, false, false);
        $current         = false;
        foreach (array_keys($links) as $i) {
            if ($current && 'content' == $links [$i] ['content_type']) {
                $return ['id']    = $links [$i] ['content_id'];
                $return ['alias'] = $links [$i] ['content_alias'];
                $return ['title'] = $links [$i] ['content_title'];

                return $return;
            }
            if ($links [$i] ['content_id'] == $id) {
                $current = true;
            }
        }

        return $return;
    }

    /**
     * return a parent select box option
     *
     * @param mixed $name
     * @param mixed $order
     * @param mixed $id
     * @param mixed $none
     * @param mixed $onchange
     * @return array
     **/
    public function makeParentSelbox($name, $order, $id, $none = true, $onchange = '')
    {
        $myts = \MyTextSanitizer::getInstance();
        echo "<select name='" . $name . "'";
        if ('' != $onchange) {
            echo " onchange='" . $onchange . "'";
        }
        echo ">\n";
        $query = 'SELECT content_id, content_name FROM ' . $this->table . ' WHERE `parent_id` = 0';
        if ('' != $order) {
            $query .= ' ORDER BY ' . $order;
        }
        $result = $this->db->query($query);
        if ($none) {
            echo "<option value='0'>none</option>\n";
        }
        $i = 1;
        while (list($contentId, $content_name) = $this->db->fetchRow($result)) {
            $sel = '';
            if ($contentId == $id) {
                $sel = ' selected';
            }
            echo "<option value='" . $contentId . "'" . $sel . '>' . $i . '&nbsp;' . $content_name . "</option>\n";
            // Search child
            $arr = $this->getChildTreeArray($contentId, $order);
            $j   = 1;
            foreach ($arr as $option) {
                $option ['prefix'] = str_replace('.', $i . '.' . $j, $option ['prefix']);
                $catpath           = $option ['prefix'] . '&nbsp;' . $option ['content_name'];
                $sel               = '';
                if ($option ['content_id'] == $id) {
                    $sel = ' selected';
                }
                echo "<option value='" . $option ['content_id'] . "'" . $sel . '>' . $catpath . "</option>\n";
                $sel = '';
                ++$j;
            }
            unset($arr);
            ++$i;
        }
        echo "</select>\n";
    }

    /**
     * @param $id
     */
    public function next($id)
    {
        $query  = 'UPDATE ' . $this->table . ' SET `content_order` = ' . $this->getVar('content_order') . ' WHERE `content_id` = ' . $id;
        $result = $this->db->queryF($query);
        $query  = 'UPDATE ' . $this->table . ' SET `content_order` = ' . ($this->getVar('content_order') + 1) . ' WHERE `content_id` = ' . $this->getVar('content_id');
        $result = $this->db->queryF($query);
    }

    /**
     * @param $id
     */
    public function prev($id)
    {
        $query  = 'UPDATE ' . $this->table . ' SET `content_order` = ' . $this->getVar('content_order') . ' WHERE `content_id` = ' . $id;
        $result = $this->db->queryF($query);
        $query  = 'UPDATE ' . $this->table . ' SET `content_order` = ' . ($this->getVar('content_order') - 1) . ' WHERE `content_id` = ' . $this->getVar('content_id');
        $result = $this->db->queryF($query);
    }

    /**
     * @param        $id
     * @param string $order
     * @param array  $parray
     * @return array|mixed
     */
    public function getChildTreeArray($id, $order = '', $parray = [])
    {
        $query = 'SELECT content_id, content_name FROM ' . $this->table . ' WHERE `parent_id` = ' . $id;
        if ('' != $order) {
            $query .= ' ORDER BY ' . $order;
        }
        $result = $this->db->query($query);
        $count  = $this->db->getRowsNum($result);
        if (0 == $count) {
            return $parray;
        }
        while (false !== ($row = $this->db->fetchArray($result))) {
            $row ['prefix'] = '.';
            $parray[]       = $row;
        }

        return $parray;
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

/**
 * Content handler class
 *
 **/
class ContentHandler extends \XoopsPersistableObjectHandler
{
    /**
     * ContentHandler constructor.
     * @param $db
     */
    public function __construct($db)
    {
        parent::__construct($db, 'fmcontent', Content::class, 'content_id', 'content_alias');
    }

    /**
     * @param $alias
     * @return int|mixed|\XoopsObject
     */
    public function getId($alias)
    {
        if ('' != $alias) {
            $criteria = new \Criteria($this->identifierName, $alias);
            $criteria->setLimit(1);
            $obj_array = $this->getObjects($criteria, false, false);
            if (1 != count($obj_array)) {
                $obj = $this->create();

                return $obj;
            }

            return $obj_array [0] [$this->keyName];
        }
        $criteria = new \CriteriaCompo();
        $criteria = new \Criteria('default_content', 1);
        $criteria->setLimit(1);
        $obj_array = $this->getObjects($criteria, false, false);
        if (1 != count($obj_array)) {
            return 0;
        }

        return $obj_array [0] [$this->keyName];
    }

    /**
     * @param null $criteria
     * @return int|mixed
     */
    public function getDefault($criteria = null)
    {
        $obj_array = $this->getObjects($criteria, false, false);
        if (1 != count($obj_array)) {
            return 0;
        }

        return $obj_array [0] [$this->keyName];
    }

    /**
     * @param int $parent_id
     * @return int
     */
    public function getLastOrder($parent_id = 0)
    {
        $query  = 'SELECT MAX(item_order) AS `last_order` FROM ' . $this->table . ' WHERE `parent_id` = ' . $parent_id;
        $result = $this->db->query($query);
        while (false !== ($row = $this->db->fetchArray($result))) {
            return (int)$row ['last_order'];
        }

        return 0;
    }

    /*
function getMenuTree($order='', $right=true)
{
    global $xoopsModules;
    $parray = array(); $menu = array();
    $query = "SELECT * FROM ".$this->table." WHERE `active` = 1 AND `show_in_menu` = 1";
    if ($order != '') {
        $query .= " ORDER BY ".$order;
    }
    $result = $this->db->query($query);
    $count = $this->db->getRowsNum($result);
    if ($count == 0) {
        return $parray;
    }
    while ( $row = $this->db->fetchArray($result) ) {
        array_push($parray, $row);
    }
    foreach ($parray as $line) {
        list($root, $mid, $end) = explode('.', $line['hierarchy']);
        if ((int)($mid)==0 && (int)($end)==0) {
            switch ($line['content_type']) {
                case 'content':
                    $page = array('id' => $line['content_id'], 'alias' => $line['content_alias']);
                    $line['link'] = content_constructUrl($page);
                    break;
                case 'link':
                    $line['link'] = $line['content_link'];
                    break;
                default:
                    $line['link'] = '#';
                    break;
            }
            $child = $this->getChildTree((int)($root), 0, $parray, 2, $right);
            $line['child_array'] = $child;
            $line['child_nb'] = count($child);
            if ($right) {
                global $xoopsUser;
                $group = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
                $groups = explode(" ",$line['groups']);
                if (count(array_intersect($group,$groups)) > 0) {
                    array_push($menu, $line);
                }
            } else {
                array_push($menu, $line);
            }
        }
    }

    return $menu;
}*/
    /*
    function getChildTree($id, $rid, $content=array(), $level=2, $right=true)
    {
        $parray=array(); $child = array();
        foreach ($content as $row) {
            list($root, $mid, $end) = explode('.', $row['hierarchy']);
            switch ($level) {
                case 2:
                    if ((int)($root)==$id && (int)($mid)!=0 && (int)($end)==0) {
                        switch ($row['content_type']) {
                            case 'content':
                                $page = array('id' => $row['content_id'], 'alias' => $row['content_alias']);
                                $row['link'] = content_constructUrl($page);
                                break;
                            case 'link':
                                $line['link'] = $row['content_link'];
                                break;
                            default:
                                $line['link'] = '#';
                                break;
                        }
                        $child = $this->getChildTree((int)($root), (int)($mid), $content, 3, $right);
                        $row['child_array'] = $child;
                        $row['child_nb'] = count($child);
                        if ($right) {
                            global $xoopsUser;
                            $group = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
                            $groups = explode(" ",$row['groups']);
                            if (count(array_intersect($group,$groups)) > 0) {
                                array_push($parray, $row);
                            }
                        } else {
                            array_push($parray, $row);
                        }
                    }
                    break;

                case 3:
                    if ((int)($root)==$id && (int)($mid)==$rid && (int)($end)!=0) {
                        switch ($row['content_type']) {
                            case 'content':
                                $page = array('id' => $row['content_id'], 'alias' => $row['content_alias']);
                                $row['link'] = content_constructUrl($page);
                                break;
                            case 'link':
                                $line['link'] = $row['content_link'];
                                break;
                            default:
                                $line['link'] = 'javascript:void(0)';
                                break;
                        }
                        if ($right) {
                            global $xoopsUser;
                            $group = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
                            $groups = explode(" ",$row['groups']);
                            if (count(array_intersect($group,$groups)) > 0) {
                                array_push($parray, $row);
                            }
                        } else {
                            array_push($parray, $row);
                        }
                    }
                    break;
            }
        }

        return $parray;
    }  */

    /**
     * @param        $forMods
     * @param int    $id
     * @param null   $criteria
     * @param string $order
     * @return array
     */
    public function getTree($forMods, $id = 0, $criteria = null, $order = 'content_order')
    {
        $ret = [];
        $tab = [];
        if (!isset($criteria)) {
            $criteria = new \CriteriaCompo();
        }
        if (isset($criteria)) {
            $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));
            //$criteria->add( new \Criteria('content_display', '1' ) );
            //$criteria->add( new \Criteria('content_status', '1' ) );
            $criteria->add(new \Criteria('content_pid', $id));
            $criteria->setSort('content_order');
        }

        $obj = $this->getObjects($criteria, false);
        if ($obj) {
            foreach ($obj as $root) {
                $tab           = $root->toArray();
                $tab ['owner'] = \XoopsUser::getUnameFromId($root->getVar('content_uid'));
                switch ($tab ['content_type']) {
                    case 'content':
                        $tab ['link'] = fmcontent_Url('fmcontent', $tab);
                        break;
                    case 'link':
                        $tab ['link'] = $tab ['content_link'];
                        break;
                    default:
                        $tab ['link'] = 'javascript:void(0)';
                        break;
                }
                $tab ['child']      = $this->getTree($forMods, $root->getVar('content_id'));
                $tab ['topic']      = TopicHandler::getTopicFromId($root->getVar('content_topic'));
                $tab ['order_prev'] = $this->getOrder($root->getVar('content_id'), $root->getVar('content_pid'), $order, 'prev');
                $tab ['order_next'] = $this->getOrder($root->getVar('content_id'), $root->getVar('content_pid'), $order, 'next');
                $ret []             = $tab;
            }
        }

        return $ret;
    }

    /**
     * @param $limit
     * @param $topic
     * @param $user
     * @param $start
     * @param $forMods
     * @param $order
     * @param $sort
     * @param $status
     * @param $static
     * @return array
     */
    public function getContentList($limit, $topic, $user, $start, $forMods, $order, $sort, $status, $static)
    {
        $ret = [];
        $tab = [];
        if (!isset($criteria)) {
            $criteria = new \CriteriaCompo();
        }
        if (isset($criteria)) {
            $criteria->add(new \Criteria('content_type', 'content'));
            $criteria->add(new \Criteria('content_status', $status));
            if (!isset($static)) {
                $criteria->add(new \Criteria('content_topic', '0', '>'));
            }
            $criteria->add(new \Criteria('content_modid', $forMods->getVar('mid')));
            $criteria->add(new \Criteria('content_topic', $topic));
            $criteria->add(new \Criteria('content_uid', $user));
            $criteria->setSort($sort);
            $criteria->setOrder($order);
            $criteria->setLimit($limit);
            $criteria->setStart($start);
        }

        $obj = $this->getObjects($criteria, false);
        if ($obj) {
            foreach ($obj as $root) {
                //-> error : permition have error
                $group  = is_object($xoopsUser) ? $xoopsUser->getGroups() : [XOOPS_GROUP_ANONYMOUS];
                $groups = explode(' ', $root->getVar('content_groups'));
                if (count(array_intersect($group, $groups)) > 0) {
                    $tab           = $root->toArray();
                    $tab ['owner'] = \XoopsUser::getUnameFromId($root->getVar('content_uid'));
                    $tab ['topic'] = TopicHandler::getTopicFromId($root->getVar('content_topic'));
                    $ret []        = $tab;
                }
            }
        }

        return $ret;
    }

    /**
     * @param $topic
     * @return int
     */
    public function getContentCount($topic)
    {
        $criteria = new \Criteria('content_topic', $topic);

        return $this->getCount($criteria);
    }

    /**
     * @param        $id
     * @param        $pid
     * @param string $order
     * @param string $type
     * @return int|mixed
     */
    public function getOrder($id, $pid, $order = '', $type = 'next')
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE `content_pid` = ' . $pid;
        if ('' != $order) {
            $query .= ' ORDER BY ' . $order;
        }
        $result = $this->db->query($query);
        $count  = $this->db->getRowsNum($result);
        if (0 == $count) {
            return 0;
        }
        $previd = 0;
        while (false !== ($row = $this->db->fetchArray($result))) {
            if ('prev' == $type && $row ['content_id'] == $id) {
                return $previd;
            }
            if ('next' == $type && $previd == $id) {
                return $row ['content_id'];
            }
            $previd = $row ['content_id'];
        }

        return 0;
    }

    public function reorder()
    {
        $content = $this->getTree(0, 'item_order');
        $i       = 1;
        foreach ($content as $row) {
            $this->db->queryF('UPDATE ' . $this->db->prefix('content') . ' SET item_order = ' . $i . ", `hierarchy` = '" . ((1 == mb_strlen($i)) ? '00' : ((2 == mb_strlen($i)) ? '0' : '')) . $i . ".000.000' WHERE `content_id` = " . $row ['content_id']);
            $j = 1;
            foreach ($row ['child_array'] as $child) {
                $this->db->queryF(
                    'UPDATE '
                    . $this->db->prefix('content')
                    . ' SET item_order = '
                    . $j
                    . ", `hierarchy` = '"
                    . ((1 == mb_strlen($i)) ? '00' : ((2 == mb_strlen($i)) ? '0' : ''))
                    . $i
                    . ((1 == mb_strlen($j)) ? '.00' : ((2 == mb_strlen($j)) ? '.0' : '.'))
                    . $j
                    . ".000' WHERE `content_id` = "
                    . $child ['content_id']
                );
                $k = 1;
                foreach ($child ['child_array'] as $last) {
                    $this->db->queryF(
                        'UPDATE ' . $this->db->prefix('content') . ' SET item_order = ' . $k . ", `hierarchy` = '" . ((1 == mb_strlen($i)) ? '00' : ((2 == mb_strlen($i)) ? '0' : '')) . $i . ((1 == mb_strlen($j)) ? '.00' : ((2 == mb_strlen($j)) ? '.0' : '.')) . $j . ((1 == mb_strlen(
                                $k
                            )) ? '.00' : ((2 == mb_strlen($k)) ? '.0' : '.')) . $k . "' WHERE `content_id` = " . $last ['content_id']
                    );
                    ++$k;
                }
                ++$j;
            }
            ++$i;
        }
    }
}
