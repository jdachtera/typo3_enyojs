<?php

namespace Dachtera\Enyojs;



use TYPO3\CMS\Core\Utility\GeneralUtility;

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
     * Generates the javascript for all registered extension plugins
     * @return string
     */
    public static function generateKindConfigs() {
        $kindConfigs = array();
        foreach(self::$configuration as $plugin) {
            foreach($plugin['controllers'] as $controller) {
                $namespace = $plugin['vendorName'] . '.' . $plugin['extensionName'] . '.' . $controller;
                $classpath = $plugin['vendorName'] . '\\' . $plugin['extensionName'] . '\\Controller\\'. $controller . 'Controller';
                $classReflection = new \TYPO3\CMS\Extbase\Reflection\ClassReflection($classpath);
                $methods = $classReflection->getMethods(256);
                $actions = array();
                foreach($methods as $method) {
                    $length = count($method->name);
                    if (substr($method->name, $length - 7) === 'Action') {
                        $actions[] = $method;
                    }
                }

                $config = array(
                    'name'          => $namespace,
                    'kind'          => 'TYPO3.Enyojs.AjaxBridge',
                    'controller'    => $controller,
                    'extensionName' => $plugin['extensionName'],
                    'pluginName'    => $plugin['pluginName'],
                    'actions'       => array()
                );

                foreach($actions as $action) {
                    $actionName = substr($action->name, 0, count($action->name) - 7);
                    $config['actions'][$actionName] = array();
                    foreach($action->getParameters() as $argument) {
                        $config['actions'][$actionName][] = $argument->name;
                    }
                }
                $kindConfigs[] = json_encode($config);
            }
        }
        return $kindConfigs;
    }

    /**
     * Registers an extension for use in Enyojs
     * @param array $configuration
     */
    public static function configurePlugin(array $configuration) {
        self::$configuration[$configuration['extensionName'] . '_' .$configuration['pluginName']] = $configuration;
    }

    /**
     * Returns the GET vars as a json string
     * @return string The json string
     */
    public static function GETJson() {
        return json_encode(GeneralUtility::_GET());
    }

    /**
     * @param $key
     * @return string
     * @throws \ErrorException
     */
    public static function run($extensionName, $pluginName) {
        if (is_array(self::$configuration[$extensionName . '_' . $pluginName])) {
            $GLOBALS['TSFE'] = GeneralUtility::makeInstance('tslib_fe', $TYPO3_CONF_VARS, 0, '0', 1, '', '','','');
            $GLOBALS['TSFE']->sys_page = GeneralUtility::makeInstance('t3lib_pageSelect');
            $GLOBALS['TSFE']->fe_user = \TYPO3\CMS\Frontend\Utility\EidUtility::initFeUser();
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
}