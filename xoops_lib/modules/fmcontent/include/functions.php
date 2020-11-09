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
 * FmContent Functions
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Andricq Nicolas (AKA MusS)
 * @param mixed $global
 * @param mixed $key
 * @param mixed $default
 * @param mixed $type
 */

/**
 * Get variables passed by GET or POST method
 * @param        $global
 * @param        $key
 * @param string $default
 * @param string $type
 * @return int|mixed|string
 */
function fmcontent_CleanVars($global, $key, $default = '', $type = 'int')
{
    switch ($type) {
        case 'string':
            $ret = $global[$key] ?? $default;
            //$ret = ( isset( $global[$key] ) ) ? filter_var( $global[$key], FILTER_SANITIZE_MAGIC_QUOTES ) : $default;
            break;
        case 'int':
        default:
            $ret = isset($global[$key]) ? (int)$global[$key] : (int)$default;
            //$ret = ( isset( $global[$key] ) ) ? filter_var( $global[$key], FILTER_SANITIZE_NUMBER_INT ) : $default;
            break;
    }
    if (false === $ret) {
        return $default;
    }

    return $ret;
}

/**
 * @param $module
 * @return bool
 */
function fmcontent_isEditorHTML($module)
{
    $editor = xoops_getModuleOption('form_editor', $module);
    if (isset($editor) && in_array($editor, ['tinymce', 'fckeditor', 'koivi', 'inbetween', 'spaw', 'ckeditor'], true)) {
        return true;
    }

    return false;
}

/**
 * Replace all escape, character, ... for display a correct url
 *
 * @String  $url    string to transform
 * @String  $type   string replacement for any blank case
 * @param mixed $url
 * @param mixed $type
 * @param mixed $module
 * @return false|mixed|string|string[] $url
 */
function fmcontent_Filter($url, $type = '', $module = 'fmcontent')
{
    // Get regular expression from module setting. default setting is : `[^a-z0-9]`i
    $regular_expression = xoops_getModuleOption('regular_expression', $module);

    $url = strip_tags($url);
    $url = preg_replace("`\[.*\]`U", '', $url);
    $url = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '-', $url);
    $url = htmlentities($url, ENT_COMPAT, 'utf-8');
    $url = preg_replace('`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\\1', $url);
    $url = preg_replace([$regular_expression, '`[-]+`'], '-', $url);
    $url = ('' == $url) ? $type : mb_strtolower(trim($url, '-'));

    return $url;
}

/**
 * Replace all escape, character, ... for display a correct Meta
 *
 * @String  $meta    string to transform
 * @String  $type   string replacement for any blank case
 * @param mixed $meta
 * @param mixed $type
 * @param mixed $module
 * @return false|mixed|string|string[] $meta
 */
function fmcontent_MetaFilter($meta, $type = '', $module = 'fmcontent')
{
    // Get regular expression from module setting. default setting is : `[^a-z0-9]`i
    $regular_expression = xoops_getModuleOption('regular_expression', $module);

    $meta = strip_tags($meta);
    $meta = preg_replace("`\[.*\]`U", '', $meta);
    $meta = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', ',', $meta);
    $meta = htmlentities($meta, ENT_COMPAT, 'utf-8');
    $meta = preg_replace('`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\\1', $meta);
    $meta = preg_replace([$regular_expression, '`[,]+`'], ',', $meta);
    $meta = ('' == $meta) ? $type : mb_strtolower(trim($meta, ','));

    return $meta;
}

/**
 * Replace all escape, character, ... for display a correct text
 *
 * @String  $text    string to transform
 * @String  $type   string replacement for any blank case
 * @param mixed $text
 * @param mixed $type
 * @return string $text
 */
function fmcontent_AjaxFilter($text, $type = '')
{
    $text = strip_tags($text);
    $text = preg_replace("`\[.*\]`U", '', $text);
    $text = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '-', $text);
    $text = htmlentities($text, ENT_COMPAT, 'utf-8');
    $text = preg_replace('`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\\1', $text);
    $text = stripslashes($text);

    return $text;
}

/**
 * @param        $url
 * @param int    $time
 * @param string $message
 */
function fmcontent_Redirect($url, $time = 3, $message = '')
{
    global $xoopsModule;
    if (preg_match('/[\\0-\\31]|about:|script:/i', $url)) {
        if (!preg_match('/^\b(java)?script:([\s]*)history\.go\(-[0-9]*\)([\s]*[;]*[\s]*)$/si', $url)) {
            $url = XOOPS_URL;
        }
    }
    // Create Template instance
    $tpl = new \XoopsTpl();
    // Assign Vars
    $tpl->assign('url', $url);
    $tpl->assign('time', $time);
    $tpl->assign('message', $message);
    $tpl->assign('ifnotreload', sprintf(_IFNOTRELOAD, $url));
    // Call template file
    echo $tpl->fetch(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/templates/admin/fmcontent_redirect.tpl');
    // Force redirection
    header('refresh: ' . $time . '; url=' . $url);
}

/**
 * @param $page
 * @param $message
 * @param $id
 * @param $handler
 */
function fmcontent_Message($page, $message, $id, $handler)
{
    global $xoopsModule;
    $tpl = new \XoopsTpl();
    //ob_start();
    $tpl->assign('message', $message);
    $tpl->assign('id', $id);
    $tpl->assign('url', $page);
    $tpl->assign('handler', $handler);
    $tpl->assign('ifnotreload', sprintf(_IFNOTRELOAD, $page));
    echo $tpl->fetch(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/templates/admin/fmcontent_confirm.tpl');
    //ob_flush();
}

/**
 * @param $module
 * @param $array
 * @return string
 */
function fmcontent_TopicUrl($module, $array)
{
    $lenght_id    = xoops_getModuleOption('lenght_id', $module);
    $friendly_url = xoops_getModuleOption('friendly_url', $module);
    if (0 != $lenght_id) {
        $id = $array['topic_id'];
        while (mb_strlen($id) < $lenght_id) {
            $id = '0' . $id;
        }
    } else {
        $id = $array['topic_id'];
    }

    switch ($friendly_url) {
        case 'none':
            $rewrite_base = '/modules/';
            $page         = 'page=' . $array['topic_alias'];

            return XOOPS_URL . $rewrite_base . $module . '/index.php?topic=' . $id . '&amp;' . $page;
            break;
        case 'rewrite':
            $rewrite_base = xoops_getModuleOption('rewrite_mode', $module);
            $rewrite_ext  = xoops_getModuleOption('rewrite_ext', $module);
            $module_name  = '';
            if (xoops_getModuleOption('rewrite_name', $module)) {
                $module_name = xoops_getModuleOption('rewrite_name', $module) . '/';
            }
            $page = $array['topic_alias'];
            $type = xoops_getModuleOption('topic_name', $module) . '/';
            $id .= '/';

            return XOOPS_URL . $rewrite_base . $module_name . $type . $id . $page . $rewrite_ext;
            break;
        case 'short':
            $rewrite_base = xoops_getModuleOption('rewrite_mode', $module);
            $rewrite_ext  = xoops_getModuleOption('rewrite_ext', $module);
            $module_name  = '';
            if (xoops_getModuleOption('rewrite_name', $module)) {
                $module_name = xoops_getModuleOption('rewrite_name', $module) . '/';
            }
            $page = $array['topic_alias'];
            $type = xoops_getModuleOption('topic_name', $module) . '/';

            return XOOPS_URL . $rewrite_base . $module_name . $type . $page . $rewrite_ext;
            break;
    }
}

/**
 * @param        $module
 * @param        $array
 * @param string $type
 * @return string
 */
function fmcontent_Url($module, $array, $type = 'content')
{
    $comment      = '';
    $lenght_id    = xoops_getModuleOption('lenght_id', $module);
    $friendly_url = xoops_getModuleOption('friendly_url', $module);

    if (0 != $lenght_id) {
        $id = $array['content_id'];
        while (mb_strlen($id) < $lenght_id) {
            $id = '0' . $id;
        }
    } else {
        $id = $array['content_id'];
    }

    if (isset($array['topic_alias']) && $array['topic_alias']) {
        $topic_name = $array['topic_alias'];
    } else {
        $topic_name = fmcontent_Filter(xoops_getModuleOption('static_name', $module));
    }

    switch ($friendly_url) {
        case 'none':
            if ($topic_name) {
                $topic_name = 'topic=' . $topic_name . '&amp;';
            }
            $rewrite_base = '/modules/';
            $page         = 'page=' . $array['content_alias'];

            return XOOPS_URL . $rewrite_base . $module . '/' . $type . '.php?' . $topic_name . 'id=' . $id . '&amp;' . $page . $comment;
            break;
        case 'rewrite':
            if ($topic_name) {
                $topic_name .= '/';
            }
            $rewrite_base = xoops_getModuleOption('rewrite_mode', $module);
            $rewrite_ext  = xoops_getModuleOption('rewrite_ext', $module);
            $module_name  = '';
            if (xoops_getModuleOption('rewrite_name', $module)) {
                $module_name = xoops_getModuleOption('rewrite_name', $module) . '/';
            }
            $page = $array['content_alias'];
            $type .= '/';
            $id   .= '/';
            if ('content/' === $type) {
                $type = '';
            }

            if ('comment-edit/' === $type || 'comment-reply/' === $type || 'comment-delete/' === $type) {
                return XOOPS_URL . $rewrite_base . $module_name . $type . $id . '/';
            }

            return XOOPS_URL . $rewrite_base . $module_name . $type . $topic_name . $id . $page . $rewrite_ext;
            break;
        case 'short':
            if ($topic_name) {
                $topic_name .= '/';
            }
            $rewrite_base = xoops_getModuleOption('rewrite_mode', $module);
            $rewrite_ext  = xoops_getModuleOption('rewrite_ext', $module);
            $module_name  = '';
            if (xoops_getModuleOption('rewrite_name', $module)) {
                $module_name = xoops_getModuleOption('rewrite_name', $module) . '/';
            }
            $page = $array['content_alias'];
            $type .= '/';
            if ('content/' === $type) {
                $type = '';
            }

            if ('comment-edit/' === $type || 'comment-reply/' === $type || 'comment-delete/' === $type) {
                return XOOPS_URL . $rewrite_base . $module_name . $type . $id . '/';
            }

            return XOOPS_URL . $rewrite_base . $module_name . $type . $topic_name . $page . $rewrite_ext;
            break;
    }
}

/**
 * @param        $array
 * @param        $key
 * @param string $order
 * @return array
 */
function order_array_num($array, $key, $order = 'ASC')
{
    $tmp = [];
    foreach ($array as $akey => $array2) {
        $tmp[$akey] = $array2[$key];
    }

    if ('DESC' === $order) {
        arsort($tmp, SORT_NUMERIC);
    } else {
        asort($tmp, SORT_NUMERIC);
    }

    $tmp2 = [];
    foreach ($tmp as $key => $value) {
        $tmp2[$key] = $array[$key];
    }

    return $tmp2;
}
