<?php
namespace Dachtera\Enyojs;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class TypoScriptFunctions {
    /**
     * Returns the GET vars as a json string
     * @return string The json string
     */
    public function _GETJson($content = '', $conf = array()) {
        return json_encode(GeneralUtility::_GET());
    }
}

?>