<?php
// 代码生成时间: 2025-09-22 15:39:46
use Phalcon\Db\Adapter\PdoFactory;
use Phalcon\Db\Migration;
use Phalcon\Di\FactoryDefault;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File;
use Phalcon\Logger\Formatter\Line;
use Phalcon\Version;

class DatabaseMigrationTool
{
    private $di;
    private $config;
    private $logger;

    public function __construct($config)
    {
        $this->config = $config;
        $this->di = new FactoryDefault();
        $this->setupServices();
        $this->logger = $this->di->getShared('logger');
    }

    private function setupServices()
    {
        $di = $this->di;

        $di->setShared('db', function () {
            $connection = new PdoFactory($this->config);
            return $connection->create(['host' => $this->config->database->host,
                                      'username' => $this->config->database->username,
                                      'password' => $this->config->database->password,
                                      'dbname' => $this->config->database->dbname
            ]);
        });

        $di->setShared('logger', function () {
            $logger = new Logger\Adapter\File('migration.log');
            $formatter = new Line();
            $logger->setFormatter($formatter);
            return $logger;
        });
    }

    public function migrate($migrationsPath)
    {
        try {
            $migrations = new Migration($migrationsPath, $this->di);
            $migrations->run();
            $this->logger->info("Migrations executed successfully.");
        } catch (Exception $e) {
            $this->logger->error("Error executing migrations: " . $e->getMessage());
            throw $e;
        }
    }

    public function getDi()
    {
        return $this->di;
    }
}

// Example usage:
// $config = new \Phalcon\Config(array(
//     'database' => array(
//         'host' => 'localhost',
//         'username' => 'user',
//         'password' => 'password',
//         'dbname' => 'dbname'
//     )
// ));
// $migrationTool = new DatabaseMigrationTool($config);
// $migrationTool->migrate('/path/to/migrations');
