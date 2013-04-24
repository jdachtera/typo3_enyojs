<?php

namespace Dachtera\Enyojs\Utility;



use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class ExtensionUtility {

    /**
     * @var array The array of configuration items
     */
    public static $configuration = array();

    /**
     * @var array Some default configuration
     */
    public static $configurationDefaults = array(
        'mvc' => array(
            'requestHandlers' => array(
                'TYPO3\\CMS\\Extbase\\Mvc\\Web\\FrontendRequestHandler' => 'TYPO3\\CMS\\Extbase\\Mvc\\Web\\FrontendRequestHandler'
            )
        ),
        'settings' => array()
    );

    /**
     * Registers an extension for use in Enyojs
     * @param $extensionName
     * @param $pluginName
     * @param array $controllerActions
     * @param array $nonCacheableControllerActions
     */
    public static function configurePlugin($extensionName, $pluginName, array $controllerActions, array $nonCacheableControllerActions = array()) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin($extensionName, $pluginName, $controllerActions, $nonCacheableControllerActions);

        $vendorName = NULL;
        $delimiterPosition = strrpos($extensionName, '.');
        if ($delimiterPosition !== FALSE) {
            $vendorName = str_replace('.', '\\', substr($extensionName, 0, $delimiterPosition));
            $extensionName = substr($extensionName, $delimiterPosition + 1);
        }
        $extensionName = str_replace(' ', '', ucwords(str_replace('_', ' ', $extensionName)));


        self::$configuration[$extensionName . '_' . $pluginName] = array(
            'vendorName'     => $vendorName,
            'extensionName'  => $extensionName,
            'pluginName'     => $pluginName,
        );
    }

    /**
     * @param $extensionName
     * @param $pluginName
     * @return string
     * @throws \ErrorException
     */
    public static function run($extensionName, $pluginName) {
        global $TYPO3_CONF_VARS, $TSFE;
        if (is_array(self::$configuration[$extensionName . '_' . $pluginName])) {
            $id = GeneralUtility::_GP('id');
            $TSFE = new TypoScriptFrontendController($TYPO3_CONF_VARS, $id, GeneralUtility::_GP('type'));
			$TSFE->connectToDB();
            $TSFE->initFEuser();
            $TSFE->checkAlternativeIdMethods();
            $TSFE->determineId();

			self::initExtended();

            $bootstrap = new \TYPO3\CMS\Extbase\Core\Bootstrap();
            $configuration = GeneralUtility::array_merge_recursive_overrule(
                self::$configuration[$extensionName . '_' . $pluginName],
                self::$configurationDefaults
            );

            return $bootstrap->run('', $configuration);
        } else {
            throw new \ErrorException();
        }

    }

    public static function initExtended() {
        global $TSFE;
        $TSFE->getCompressedTCarray();
        $TSFE->getPageAndRootline();
        $TSFE->initTemplate();
        $TSFE->getConfigArray();
    }

    /**
     * Generates the javascript for all registered extension plugins
     * @return string
     */
    public static function generateControllerKindConfigs() {
        $kindConfigs = array();
        foreach(self::$configuration as $plugin) {
            $controllers = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][$plugin['extensionName']]['plugins'][$plugin['pluginName']]['controllers'];
            foreach($controllers as $controller => $conf) {
                $classpath = $plugin['vendorName'] . '\\' . $plugin['extensionName'] . '\\Controller\\'. $controller . 'Controller';
                $classReflection = new \TYPO3\CMS\Extbase\Reflection\ClassReflection($classpath);
                $config = array(
                    'name'          => str_replace("\\", ".", $classpath),
                    'controller'    => $controller,
                    'extensionName' => $plugin['extensionName'],
                    'pluginName'    => $plugin['pluginName'],
                    'actions'       => array()
                );
                foreach($conf['actions'] as $action) {
                    $method = $classReflection->getMethod($action . 'Action');
                    $actionConf = array(
                        'name' => $action,
                        'description' => $method->getDescription(),
                        'parameters' => array()
                    );
                    foreach($method->getParameters() as $argument) {
                        $actionConf['parameters'][] = array(
                            'name' => $argument->name
                        );
                    }
                    $config['actions'][] = $actionConf;
                }
                $kindConfigs[] = $config;
            }
        }
        return $kindConfigs;
    }
}