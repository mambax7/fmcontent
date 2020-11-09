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
 * FmContent action script file
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Andricq Nicolas (AKA MusS)
 * @author      Hossein Azizabadi (AKA Voltan)
 */

use XoopsModules\Fmcontent;

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

/**
 * @param \XoopsModule $module
 * @return bool
 */
function xoops_module_pre_install_fmcontent(\XoopsModule $module)
{
    $db    = &$GLOBALS['xoopsDB'];
    $error = false;
    if (mb_substr(XOOPS_VERSION, 0, 9) < 'XOOPS 2.5') {
        $module->setErrors('The module only works for XOOPS 2.5+');

        return false;
    }

    $sqlfile = ['mysql' => 'sql/mysql.sql'];

    $indexFile = XOOPS_ROOT_PATH . '/uploads/index.html';
    $blankFile = XOOPS_ROOT_PATH . '/uploads/blank.gif';
    //Creation du fichier creator dans uploads
    $module_uploads = XOOPS_ROOT_PATH . '/uploads/fmcontent';
    if (!is_dir($module_uploads)) {
        if (!mkdir($module_uploads, 0777) && !is_dir($module_uploads)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $module_uploads));
        }
    }
    chmod($module_uploads, 0777);
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/fmcontent/index.html');

    //Creation du fichier price dans uploads
    $module_uploads = XOOPS_ROOT_PATH . '/uploads/fmcontent/img';
    if (!is_dir($module_uploads)) {
        if (!mkdir($module_uploads, 0777) && !is_dir($module_uploads)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $module_uploads));
        }
    }
    chmod($module_uploads, 0777);
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/fmcontent/img/index.html');
    copy($blankFile, XOOPS_ROOT_PATH . '/uploads/fmcontent/img/blank.gif');

    //Creation du fichier price dans uploads
    $module_uploads = XOOPS_ROOT_PATH . '/uploads/fmcontent/file';
    if (!is_dir($module_uploads)) {
        if (!mkdir($module_uploads, 0777) && !is_dir($module_uploads)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $module_uploads));
        }
    }
    chmod($module_uploads, 0777);
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/fmcontent/file/index.html');
    copy($blankFile, XOOPS_ROOT_PATH . '/uploads/fmcontent/file/blank.gif');

    if (is_array($sqlfile) && !empty($sqlfile[XOOPS_DB_TYPE])) {
        $sql_file_path = XOOPS_ROOT_PATH . '/modules/fmcontent/' . $sqlfile[XOOPS_DB_TYPE];
        if (!is_file($sql_file_path)) {
            $module->setErrors('<p>' . sprintf(_FMCONTENT_SQL_NOT_FOUND, "<strong>{$sql_file_path}</strong>"));
            $error = true;
        } else {
            $msgs[] = '<p>' . sprintf(_FMCONTENT_SQL_FOUND, "<strong>{$sql_file_path}</strong>") . '<br  />' . _FMCONTENT_CREATE_TABLES;

            require_once $GLOBALS['xoops']->path('/class/database/sqlutility.php');
            $sql_query = fread(fopen($sql_file_path, 'rb'), filesize($sql_file_path));
            $sql_query = trim($sql_query);
            SqlUtility::splitMySqlFile($pieces, $sql_query);
            $created_tables = [];
            foreach ($pieces as $piece) {
                // [0] contains the prefixed query
                // [4] contains unprefixed table name
                $prefixed_query = SqlUtility::prefixQuery($piece, $db->prefix());
                if (!$prefixed_query) {
                    $module->setErrors('<p>' . sprintf(_FMCONTENT_SQL_NOT_VALID, '<strong>' . $piece . '</strong>'));
                    $error = true;
                    break;
                }

                if (!isset($reservedTables)) {
                    $reservedTables = [];
                }
                // check if the table name is reserved
                if (!in_array($prefixed_query[4], $reservedTables, true)) {
                    // not reserved, so try to create one
                    if (!$db->query($prefixed_query[0])) {
                        $errs[] = $db->error();
                        $error  = true;
                        break;
                    }
                    if (!in_array($prefixed_query[4], $created_tables, true)) {
                        $msgs[]           = '&nbsp;&nbsp;' . sprintf(_FMCONTENT_TABLE_CREATED, '<strong>' . $db->prefix($prefixed_query[4]) . '</strong>');
                        $created_tables[] = $prefixed_query[4];
                    } else {
                        $msgs[] = '&nbsp;&nbsp;' . sprintf(_FMCONTENT_INSERT_DATA, '<strong>' . $db->prefix($prefixed_query[4]) . '</strong>');
                    }
                } else {
                    // the table name is reserved, so halt the installation
                    $module->setErrors('&nbsp;&nbsp;' . sprintf(_FMCONTENT_TABLE_RESERVED, '<strong>' . $prefixed_query[4] . '</strong>'));
                    $error = true;
                    break;
                }
            }
            // if there was an error, delete the tables created so far, so the next installation will not fail
            if (true === $error) {
                foreach ($created_tables as $ct) {
                    $db->query('DROP TABLE ' . $db->prefix($ct));
                }

                return false;
            }
        }

        return true;
    }

    return false;
}

/**
 * @param $module
 * @param $version
 */
function xoops_module_update_fmcontent(&$module, $version)
{
    $db        = &$GLOBALS['xoopsDB'];
    $indexFile = XOOPS_ROOT_PATH . '/uploads/index.html';
    $blankFile = XOOPS_ROOT_PATH . '/uploads/blank.gif';

    // Add topic_alias table in DB
    if (!Fmcontent\Utils::FieldExists('topic_alias', $db->prefix('fmcontent_topic'))) {
        Fmcontent\Utils::AddField('`topic_alias` VARCHAR( 255 ) NOT NULL ', $db->prefix('fmcontent_topic'));
    }

    // Add topic_homepage table in DB
    if (!Fmcontent\Utils::FieldExists('topic_homepage', $db->prefix('fmcontent_topic'))) {
        Fmcontent\Utils::AddField('`topic_homepage` TINYINT( 4 ) NOT NULL ', $db->prefix('fmcontent_topic'));
    }

    // Add topic_show table in DB
    if (!Fmcontent\Utils::FieldExists('topic_show', $db->prefix('fmcontent_topic'))) {
        Fmcontent\Utils::AddField("`topic_show` TINYINT( 1 ) NOT NULL default '1' ", $db->prefix('fmcontent_topic'));
    }

    // Add content_file content in DB
    if (!Fmcontent\Utils::FieldExists('content_file', $db->prefix('fmcontent_content'))) {
        Fmcontent\Utils::AddField("`content_file` TINYINT( 3 ) NOT NULL default '0' AFTER `content_comments` ", $db->prefix('fmcontent_content'));
    }

    // Add file table
    if (!Fmcontent\Utils::TableExists('fmcontent_file')) {
        Fmcontent\Utils::AddTable(
            '
                CREATE TABLE `' . $db->prefix('fmcontent_file') . "` (
                `file_id` INT (11) UNSIGNED NOT NULL  AUTO_INCREMENT,
                `file_modid` INT(11) NOT NULL,
                `file_title` VARCHAR (255)   NOT NULL ,
                `file_name` VARCHAR (255)   NOT NULL ,
                `file_content` INT(11) NOT NULL,
                `file_date` INT(10) NOT NULL DEFAULT '0',
                `file_type` VARCHAR(64) NOT NULL DEFAULT '',
                `file_status` TINYINT(1) NOT NULL,
                PRIMARY KEY (`file_id`,`file_modid`),
                UNIQUE KEY `file_id` (`file_id`,`file_modid`)
                ) ENGINE=MyISAM;
         "
        );
    }

    if (!file_exists($GLOBALS['xoops']->path('/uploads/fmcontent/file/index.html'))) {
        //Creation du fichier price dans uploads
        $module_uploads = XOOPS_ROOT_PATH . '/uploads/fmcontent/file';
        if (!is_dir($module_uploads)) {
            if (!mkdir($module_uploads, 0777) && !is_dir($module_uploads)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $module_uploads));
            }
        }
        chmod($module_uploads, 0777);
        copy($indexFile, XOOPS_ROOT_PATH . '/uploads/fmcontent/file/index.html');
        copy($blankFile, XOOPS_ROOT_PATH . '/uploads/fmcontent/file/blank.gif');
    }
}

/**
 * @param $module
 * @return bool
 */
function xoops_module_uninstall_fmcontent(&$module)
{
    $db = &$GLOBALS['xoopsDB'];

    //$created_tables = array(0 => 'fmcontent');
    $created_tables = [0 => 'fmcontent_content', 1 => 'fmcontent_topic', 2 => 'fmcontent_file'];

    foreach ($created_tables as $ct) {
        $db->query('DROP TABLE ' . $db->prefix($ct));
    }

    return true;
}
