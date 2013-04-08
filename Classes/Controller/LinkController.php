<?php
namespace Dachtera\Enyojs\Controller;

use Dachtera\Enyojs\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;



class LinkController extends ActionController {

    /**
     * @param array $getVars
     * @return string
     */
    public function generateAction(array $getVars) {
        $id = $getVars['id'];
        unset($getVars['id']);
        $cObj = new ContentObjectRenderer();
        return json_encode(array('url' => $cObj->getTypoLink_URL($id, $getVars)));
    }

    /**
     * Parses an url and returns the GET parameters
     * @param string $url
     * @return array
     */
    public function parseAction($url = '') {
        return json_encode(array(

        ));
    }

}
?>