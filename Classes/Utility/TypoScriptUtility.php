<?php
namespace Dachtera\Enyojs\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\ContentContentObject;

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


    protected function getState() {
        return array(
            'url' => GeneralUtility::getIndpEnv('REQUEST_URI'),
            'id' => $GLOBALS['TSFE']->id,
            'title' => $GLOBALS['TSFE']->page['title'],
            '_GET' => GeneralUtility::_GET()
        );
    }


    /**
     * @param int $colPos
     * @return string
     */
    public function getPageContent($content, $conf = array()) {
        $id = GeneralUtility::_GP('id');
        $colPos = GeneralUtility::_GP('colPos');
        if (!$colPos) {
            $colPos = 0;
        }

        $c = new ContentContentObject(new ContentObjectRenderer());
        $content = $c->render(array(
            "table" => "tt_content",
            "select." => array(
                "pidInList" => $id,
                "orderBy" => "sorting",
                "where" => "colPos=" . $colPos
            )
        ));

        $dom = new \DOMDocument();
        $dom->loadHTML($content);

        $components = $this->node2Component($dom->getElementsByTagName("body")->item(0));

        return json_encode(array(
            'state' => $this->getState(),
            'content' => $components['components']
        ));
    }

    protected function node2Component(\DOMNode $node) {
        $component = array();
        $components = array();

        foreach($node->childNodes as $n) {
            if ($n instanceof \DOMComment) {
                continue;
            }
            $c = $this->node2Component($n);
            if (count($c)) {
                $components[] = $c;
            }

        }
        switch($node->tagName) {

            case 'div':
                break;
            case NULL:
                $component['tag'] = 'span';
                break;
            case 'a':
                $component['ontap'] = 'linkTap';
            default:
                $component['tag'] = $node->tagName;

        }
        if ($node->attributes) {
            $classes = $node->attributes->getNamedItem('class');
            if ($classes) {
                $component['classes'] = $classes->value;
            }
            $attributes = array();
            foreach($node->attributes as $attribute) {
                if ($attribute->name !== 'class') {
                    $attributes[$attribute->name] = $attribute->value;
                }
            }
            if (count($attributes)) {
                $component['attributes'] = $attributes;
            }
        }
        if (count($components)) {
            $component['components'] = $components;
        } else if(trim($node->textContent) !== "") {
            $component['content'] = $node->textContent;
        }


        return $component;
    }


}

?>