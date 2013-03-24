<?php
namespace Dachtera\Enyojs\Controller;

use Dachtera\Enyojs\ExtensionUtility;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Frontend\Page\PageGenerator;
use TYPO3\CMS\Frontend\Page\PageRepository;

class AjaxController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    /**
     * @var \TYPO3\CMS\Frontend\Page\PageRepository
     * @inject
     */
    protected $pageRepository;

    public function __construct() {
        $this->pageRepository = new \TYPO3\CMS\Frontend\Page\PageRepository();
        $this->pageRepository->init(TRUE);
    }

    /**
     * @param \string $controllerName
     * @return \string
     */
    public function javascriptAction() {
        $this->response->setHeader('Content-Type', 'application/javascript');
        $kindConfigs = ExtensionUtility::generateKindConfigs();
        $this->view->assign('kindConfigs', $kindConfigs);
        $this->view->assign('baseUrl', '/index.php');
    }

    /**
     * @param int $pageUid
     */
    public function pageTreeAction($pageUid = 0) {
        return json_encode($this->getPageTree($pageUid));
    }

    /**
     * @param int $pageUid
     * @return string
     */
    public function renderPageAction($pageUid = 1) {
        $cObj = new \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer();
        $cObj->start($this->pageRepository->getPage($pageUid), 'pages');

        $pageContent = $cObj->cObjGet($this->getPageTemplate($pageUid));

        return $pageContent;
    }

    /**
     * @param int $pageUid Ausgangspunkt / Root page
     * @param array $pageTree
     * @return array
     */
    protected function getPageTree($pageUid = 0, $pageTree = array()) {
        $pageTree = $this->pageRepository->getMenu($pageUid);
        foreach($pageTree as &$page) {
            $page['subPages'] = $this->getPageTree($page['uid'], $pageTree);
        }

        return $pageTree;
    }

    protected function getPageTemplate($pageUid) {
        $rootline = $this->pageRepository->getRootLine($pageUid);
        $typoscriptObject = new \TYPO3\CMS\Core\TypoScript\ExtendedTemplateService();
        $typoscriptObject->tt_track = 0;
        $typoscriptObject->init();
        $typoscriptObject->runThroughTemplates($rootline);
        $typoscriptObject->generateConfig();
        return $typoscriptObject;
    }
}
?>