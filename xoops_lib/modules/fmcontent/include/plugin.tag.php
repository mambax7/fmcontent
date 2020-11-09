<?php

use XoopsModules\Fmcontent;
use XoopsModules\Fmcontent\Helper;

/**
 * @param $items
 * @return false
 */
function fmcontent_tag_iteminfo(&$items)
{
    if (empty($items) || !is_array($items)) {
        return false;
    }

    $items_id = [];
    foreach (array_keys($items) as $cat_id) {
        foreach (array_keys($items[$cat_id]) as $item_id) {
            $items_id[] = (int)$item_id;
        }
    }

    $item_handler = Helper::getInstance()->getHandler('Page');
    $items_obj    = $item_handler->getObjects(new \Criteria('content_id', '(' . implode(', ', $items_id) . ')', 'IN'), true);

    foreach (array_keys($items) as $cat_id) {
        foreach (array_keys($items[$cat_id]) as $item_id) {
            if (isset($items_obj[$item_id])) {
                $item_obj                 =& $items_obj[$item_id];
                $items[$cat_id][$item_id] = [
                    'title'   => $item_obj->getVar('content_title'),
                    'uid'     => $item_obj->getVar('content_author'),
                    'link'    => "content.php?id={$item_obj->getVar('content_id')}",
                    'time'    => $item_obj->getVar('content_create'),
                    'tags'    => '',
                    'content' => '',
                ];
            }
        }
    }
    unset($items_obj);
}

/**
 * @param $mid
 */
function fmcontent_tag_synchronization($mid)
{
    $item_handler = Helper::getInstance()->getHandler('Page');
    $link_handler = xoops_getModuleHandler('link', 'tag');

    /* clear tag-item links */
    if (version_compare(mysqli_get_server_info(), '4.1.0', 'ge')):
        $sql = "    DELETE FROM {$link_handler->table}"
               . '    WHERE '
               . "        tag_modid = {$mid}"
               . '        AND '
               . '        ( tag_itemid NOT IN '
               . "            ( SELECT DISTINCT {$item_handler->keyName} "
               . "                FROM {$item_handler->table} "
               . "                WHERE {$item_handler->table}.content_status > 0"
               . '            ) '
               . '        )';
    else:
        $sql = "    DELETE {$link_handler->table} FROM {$link_handler->table}"
               . "    LEFT JOIN {$item_handler->table} AS aa ON {$link_handler->table}.tag_itemid = aa.{$item_handler->keyName} "
               . '    WHERE '
               . "        tag_modid = {$mid}"
               . '        AND '
               . "        ( aa.{$item_handler->keyName} IS NULL"
               . '            OR aa.content_status < 1'
               . '        )';
    endif;
    if (!$result = $link_handler->db->queryF($sql)) {
        //xoops_error($link_handler->db->error());
    }
}
