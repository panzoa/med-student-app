<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

date_default_timezone_set('Europe/London');

// these varialbes can be also defined in .htaccess file
defined('APPLICATION_PATH') || define('APPLICATION_PATH', dirname(__FILE__) . '/src');
defined('APPLICATION_ENV') || define('APPLICATION_ENV', 'staging');
			  
set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH,             // Zend Framework 1 is expected at that path in Zend folder
    APPLICATION_PATH . '/models', // path to application's models directory for Zend Autoloader to serve it as well
    get_include_path()            // retaining initial value
)));

try {
    // initialising Zend autoloader
    require_once 'Zend/Loader/Autoloader.php';
    $loader = Zend_Loader_Autoloader::getInstance();
    $loader->setFallbackAutoloader(true); // asking autoloader to act as a catch-all for any namespace

    // here we don't have a bootstrap to request the INI file reference from, so let's use this chance
    // to demonstrate a custom singleton model and its usage
    $config = Config::getConfig();
    Zend_Db_Table::setDefaultAdapter(Db::getDb());
  
    // now we need to start proccessing our request
    // the necessary thing for that is controller initialisation which we'll perform below
    // in a more complicated application other actions are required (e.g. dealing with rules and modules)
    // which we are skipping as explained above

    Zend_Layout::startMVC();  // this makes the action views output to be used with our general layout

    // now the controllers...
    $frontController = Zend_Controller_Front::getInstance();
    $frontController->throwExceptions(true);

    $frontController->setControllerDirectory(array(
        'default' => APPLICATION_PATH . '/controllers', // controllers directory
    ));

    // god help us!
    $frontController->dispatch();
  
} catch (Exception $e) {
  // as we only really need the config file in this block (we refer admin email below), we could have skipped creating the singleton above
  // but generally the config should be always available, so the good style is to keep it where it is
?>
	<h1>Houston, we've had a problem</h1>
      <p>
        <strong>Details</strong>
    </p>
    <p>
        <?php echo nl2br($e->__toString()); ?>
    </p>
<?php
}
?>