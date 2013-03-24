<?php
if (!defined ('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Dachtera.' . $_EXTKEY,
    'Enyojs_Ajax',
    array(
        'Ajax' => 'javascript,pageTree,renderPage',
    ),
    array(
        'Ajax' => 'javascript,pageTree,renderPage',
    )
);

\Dachtera\Enyojs\ExtensionUtility::configurePlugin(array(
    'vendorName'    => 'Dachtera',
    'extensionName' => 'Enyojs',
    'pluginName'    => 'Enyojs_Ajax',
    'controllers'   => array('Ajax')
));

$TYPO3_CONF_VARS['FE']['eID_include']['enyojs'] = 'EXT:enyojs/eID.php';
?>