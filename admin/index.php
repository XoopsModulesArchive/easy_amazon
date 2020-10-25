<?php

/*
6/23/2004: Version 0.94
=============================
Author: unadon
http://u-u-club.ddo.jp/~XOOPS/
E-mail:unadon@jobs.co.jp

*/
if (require('../../../include/cp_header.php')) {
    if (file_exists('../language/' . $xoopsConfig['language'] . '/main.php')) {
        include '../language/' . $xoopsConfig['language'] . '/main.php';
    } else {
        include '../language/english/main.php';
    }

    if (!defined('XOOPS_MAINFILE_INCLUDED') || !defined('XOOPS_ROOT_PATH') || !defined('XOOPS_URL')) {
        trigger_error('Bad!!Access error none mainfile:');

        exit();
    }

    if (!is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid())) {
        trigger_error('Access Denied');

        exit('Access Denied');
    }

    xoops_cp_header();

    if (is_object($xoopsModule)) {
        if (1 == $xoopsModule->getVar('hasconfig') || 1 == $xoopsModule->getVar('hascomments') || $xoopsModule->getVar('hasnotification')) {
            echo "<a href='" . XOOPS_URL . '/modules/system/admin.php?fct=preferences&op=showmod&mod=' . $xoopsModule->getVar('mid') . "'>" . _PREFERENCES . '</a>';
        }
    }

    xoops_cp_footer();
} else {
    redirect_header(XOOPS_URL . '/index.php', 0, _NOPERM);
}
