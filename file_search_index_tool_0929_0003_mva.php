<?php
// 代码生成时间: 2025-09-29 00:03:08
use Phalcon\Mvc\Application;
use Phalcon\DI\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;

// Autoload the dependencies
$loader = new Loader();
$loader->registerDirs(
    array(
        __DIR__ . '/models/',
        __DIR__ . '/controllers/',
    )
)->register();

// Set up the dependency injection container
$di = new FactoryDefault();

// Set up the view component
$di->setShared('view', function () {
    $view = new View();
    $view->setViewsDir(__DIR__ . '/views/');
    return $view;
});

// Handle routing
$di->set('router', function () {
    $router = new Phalcon\Mvc\Router();
    $router->add('/search/{searchTerm}', array(
        'controller' => 'search',
        'action' => 'index',
        'searchTerm' => 1,
    ));
    return $router;
});

// Create a new application
$app = new Application($di);

// Register the installed modules
$app->registerModules(array(
    'index' => 'IndexController',
));

// Run the application
$app->handle();

class IndexController extends Phalcon\Mvc\Controller
{
    /**
     * Index action. This action is called by default.
     *
     * @param string $searchTerm
     */
    public function indexAction($searchTerm = '')
    {
        try {
            // Initialize the search and indexing logic
            $fileSearcher = new FileSearcher();
            $results = $fileSearcher->searchFiles($searchTerm);

            // Display the search results
            $this->view->setVar('results', $results);
            $this->view->render('index', 'index');
        } catch (Exception $e) {
            // Handle any exceptions that occur during the search
            $this->flash->error('Error: ' . $e->getMessage());
            $this->view->setVar('results', array());
            $this->view->render('index', 'index');
        }
    }
}

class FileSearcher
{
    /**
     * Search for files based on the given search term.
     *
     * @param string $searchTerm
     * @return array
     */
    public function searchFiles($searchTerm)
    {
        // Define the directory to search in
        $directory = __DIR__ . '/data/';

        // Initialize an array to store the search results
        $results = array();

        // Check if the directory exists
        if (!is_dir($directory)) {
            throw new Exception('The specified directory does not exist.');
        }

        // Scan the directory for files
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        // Iterate through the files and search for the term
        foreach ($files as $name => $file) {
            if ($file->isFile() && stristr($file->getRealPath(), $searchTerm)) {
                // Add the file path to the results array
                $results[] = $file->getRealPath();
            }
        }

        return $results;
    }
}
