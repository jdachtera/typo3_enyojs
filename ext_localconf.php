<?php
if (!defined ('TYPO3_MODE')) {
    die ('Access denied.');
}

\Dachtera\Enyojs\Utility\ExtensionUtility::configurePlugin(
    'Dachtera.' . $_EXTKEY,
    'Enyojs_Ajax',
    array(
        'Link' => 'generate,parse',
    ),
    array(
        'Link' => '',
    )
);

$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['enyojs'] = 'Dachtera\Enyojs\Utility\TypoScriptUtility->clearControllerJsCache';

$TYPO3_CONF_VARS['FE']['eID_include']['enyojs'] = 'EXT:enyojs/eID.php';
?>