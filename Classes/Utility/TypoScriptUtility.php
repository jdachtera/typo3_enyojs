<?php
namespace Dachtera\Enyojs\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class TypoScriptUtility {
    /**
     * Returns the GET vars as a json string
     * @return string The json string
     */
    public function _GETJson($content = '', $conf = array()) {
        $getVars = GeneralUtility::_GET();
        $getVars['id'] = $GLOBALS['TSFE']->id;
        return json_encode($getVars);
    }

    /**
     * Build the javascript files for the registered controllers
     * @param string $content
     * @param array $conf
     */
    public function buildControllerJsCache($content = '', $conf = array()) {
        $resDir = ExtensionManagementUtility::extPath('enyojs') . 'Resources/';
        $controllerJsFile = $resDir . 'Public/enyo-lib/Controllers.js';
        $controllerJsTemplate = $resDir . 'Private/Templates/Controllers.js';

        if (!@is_file($controllerJsFile) || $GLOBALS['TSFE']->no_cache) {
            $view = new \TYPO3\CMS\Fluid\View\StandaloneView();
            $view->setFormat('js');
            $view-> setTemplatePathAndFilename($controllerJsTemplate);
            $view->assign('kindConfigs', ExtensionUtility::generateControllerKindConfigs());
            $state = GeneralUtility::writeFile($controllerJsFile, $view->render());
        }
    }

    /**
     * Clear the javascript generated files
     * @param string $content
     * @param array $conf
     */
    public function clearControllerJsCache($content = '', $conf = array()) {
        $resDir = ExtensionManagementUtility::extPath('enyojs') . 'Resources/';
        $controllerJsFile = $resDir . 'Public/enyo-lib/Controllers.js';
        @unlink($controllerJsFile);
    }


}

?>