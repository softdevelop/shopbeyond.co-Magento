<?php
/**
 * boot strap file which should be called on every request
 * it defines some basic constants and loads the autoloader class, which handles the classloading,
 * constructing and holds the singletons
 *
 * @author    Rudolf Batt <rb@omikron.net>
 * @version  $Id: Loader.php 25893 2010-06-29 08:19:43Z rb $
 * @package  FACTFinder\Common
 */

/**
 * short cut for the constant DIRECTORY_SEPARATOR
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/**
 * contains the complete lib directory path
 */
if (!defined('LIB_DIR')) {
    define('LIB_DIR', dirname(dirname(__FILE__)));
}

/**
 * set as include path if this is not the case yet
 */
$includePaths = explode(PATH_SEPARATOR, get_include_path());
if ( array_search(LIB_DIR, $includePaths, true) === false ) {
    set_include_path( get_include_path() . PATH_SEPARATOR . LIB_DIR);
}
spl_autoload_register(array('FACTFinder_Loader', 'autoload'));

// don't know, whether I should do that
if (function_exists('__autoload') && array_search('__autoload', spl_autoload_functions()) === false) {
    spl_autoload_register('__autoload');
}

/**
 * shortcut / alias for the loader class
 *
 * @author    Rudolf Batt <rb@omikron.net>
 */
final class FF extends FACTFinder_Loader{}


/**
 * handles different loading tasks
 *
 * @author    Rudolf Batt <rb@omikron.net>
 */
class FACTFinder_Loader
{
    protected static $singletons = array();
    protected static $classNames = array();
    protected static $logger = null;

    public static function autoload($classname)
    {
        $filename = self::getFilename($classname);
        if (file_exists($filename)) {
            include_once $filename;
        }
    }

    private static function getFilename($classname)
    {
        return LIB_DIR . DS . str_replace('_', DS, $classname) . '.php';
    }

    private static function canLoadClass($classname)
    {
        return file_exists(self::getFilename($classname));
    }

    /**
     * Creates an instance of a class taking into account classes with the prefix "Custom_" instead of "FACTFinder_".
     * USE THIS method instead of the PHP "new" keyword.
     * Eg. "$obj = new myclass;" should be "$obj = FACTFinder_Loader::getInstance("myclass")" instead!
     * You can also pass arguments for a constructor:
     *     FACTFinder_Loader::getInstance('myClass', $arg1, $arg2,  ..., $argN)
     *
     * @param    string class name to instantiate
     * @param    mixed optional as many parameters as the class needs to be created
     * @return    object A reference to the object
     */
    public static function getInstance($name)
    {
        if (isset(self::$classNames[$name])) {
            $className = self::$classNames[$name];
        } else {
            $className = self::getClassName($name);
            self::$classNames[$name] = $className;
        }

        // this snippet is from the typo3 class "t3lib_div" writen by Kasper Skaarhoj <kasperYYYY@typo3.com>
        if (func_num_args() > 1) {
            // getting the constructor arguments by removing this
            // method's first argument (the class name)
            $constructorArguments = func_get_args();
            array_shift($constructorArguments);

            $reflectedClass = new ReflectionClass($className);
            $instance = $reflectedClass->newInstanceArgs($constructorArguments);
        } else {
            $instance = new $className;
        }

        return $instance;
    }

    /**
     * creates an instance of the class once and returns it everytime. uses getInstance
     *
     * @param    string class name to instantiate
     * @param    mixed optional as many parameters as the class needs to be created
     * @return    object A reference to the object
     */
    public static function getSingleton($name)
    {
        if (!isset(self::$singletons[$name])) {
            $params = func_get_args();
            self::$singletons[$name] = call_user_func_array(array("self", "getInstance"), $params);
        }
        return self::$singletons[$name];
    }

    /**
     * sets the static Logger class from code
     * be aware that only the root loggers configuration will affect how the framework's interna are logged
     *
     * @param    string file name of the configuration file
     */
    public static function setLogger(FACTFinder_Logger_LoggerInterface $logger)
    {
        self::$logger = $logger;
    }
    
    /**
     * gets a logger. if no logger is specified, the root logger is returned. otherwise, the specified one.
     * this can be configured differently to use it within the shop for example
     *
     * @param      string the logger's name
     * @return    Logger the specified logger
     */
    public static function getLogger($name = null)
    {
        if (self::$logger == null) {
            self::$logger = new FACTFinder_Logger_BlackHole();
        }
        
        return self::$logger;
    }

    /**
     * check whether there is a custom class with the prefix "FACTFinderCustom_" instead of "FACTFinder_"
     * if non of them exists, it also checks if the name is the classname itselft
     */
    protected static function getClassName($name)
    {
        $name = trim(str_replace('factfinder/', '', $name));
        $name = str_replace(' ', '_', ucwords(str_replace('/', ' ', $name)));

        // check whether there is a custom or lib-unrelated class
        $oldCustomClassName  = 'Custom_' . $name;
        $customClassName     = 'FACTFinderCustom_' . $name;
        $factfinderClassName = 'FACTFinder_' . $name;
        $defaultClassName    = $name;

        if (self::canLoadClass($customClassName)) {
            $className = $customClassName;
        } else if (self::canLoadClass($oldCustomClassName)) {
            $className = $oldCustomClassName;
        } else if (self::canLoadClass($factfinderClassName)) {
            $className = $factfinderClassName;
        } else if (class_exists($defaultClassName)) { //trigger other autload methods
            $className = $defaultClassName;
        } else {
            $this->log->error("Could not load class '$defaultClassName'.");
            throw new Exception("class '$defaultClassName' not found");
        }
        return $className;
    }
}