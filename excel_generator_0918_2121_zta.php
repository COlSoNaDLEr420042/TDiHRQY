<?php
// 代码生成时间: 2025-09-18 21:21:34
// Excel表格自动生成器

use Phalcon\Di;
use Phalcon\Loader;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\View;
use Phalcon\Config\Adapter\Ini as ConfigIni;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Model\MetaData\Memory as MetaData;
use Phalcon\Mvc\Model\Manager;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use PhpOffice\PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelGenerator extends Application
{
    public function __construct($di = null)
    {
        parent::__construct($di);
    }

    public function main()
    {
        try {
            // 读取配置文件
            $config = new ConfigIni(__DIR__ . '/config/config.ini');

            // 设置依赖注入容器
            $di = new Di();
            $di->setShared('config', function () use ($config) {
                return $config;
            });

            // 设置数据库连接
            $di->setShared('db', function () use ($config) {
                $dbConfig = $config->database->toArray();
                return new DbAdapter($dbConfig);
            });

            // 设置模型元数据
            $di->setShared('modelsMetadata', function () {
                return new MetaData();
            });

            // 设置模型管理器
            $di->setShared('modelsManager', function () {
                return new Manager();
            });

            // 设置事务管理器
            $di->setShared('transactionManager', function () {
                return new TransactionManager();
            });

            // 设置URL服务
            $di->setShared('url', function () {
                $url = new Url();
                $url->setBaseUri('/excel_generator/');
                return $url;
            });

            // 设置视图服务
            $di->set('view', function () {
                $view = new View();
                $view->setViewsDir(__DIR__ . '/views/');
                $view->registerEngines([
                    '.volt' => function ($view, $di) {
                        $volt = new VoltEngine($view, $di);
                        $volt->setOptions([
                            'compiledPath' => __DIR__ . '/cache/volt/',
                            'compiledSeparator' => '_',
                        ]);
                        return $volt;
                    },
                    '.phtml' => PhpEngine::class,
                ]);
                return $view;
            });

            // 设置事件管理器
            $di->setShared('eventsManager', function () {
                $eventsManager = new Manager();
                // 绑定事件
                $eventsManager->attach('db:beforeQuery', function ($event, $connection) {
                    echo $connection->getSQLStatement(), "\
";
                });
                return $eventsManager;
            });

            // 设置调度器
            $di->setShared('dispatcher', function () {
                $dispatcher = new Dispatcher();
                // 设置默认模块
                $dispatcher->setDefaultNamespace('ExcelGenerator\Modules');
                // 设置默认任务和操作
                $dispatcher->setDefaultAction('index');
                return $dispatcher;
            });

            // 运行应用程序
            $this->setDI($di);
            $response = $this->handle();
            return $response;
        } catch (Exception $e) {
            // 错误处理
            echo $e->getMessage();
        }
    }

    public function generateExcel()
    {
        try {
            // 创建一个新的Excel对象
            $spreadsheet = new PhpSpreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // 设置表头
            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'Email');

            // 从数据库获取数据
            $data = Db::find();
            foreach ($data as $row) {
                $sheet->setCellValue('A' . ($row->id + 2), $row->id);
                $sheet->setCellValue('B' . ($row->id + 2), $row->name);
                $sheet->setCellValue('C' . ($row->id + 2), $row->email);
            }

            // 设置Excel文件名称
            $writer = new Xlsx($spreadsheet);
            $filename = 'Users.xlsx';
            $writer->save($filename);

            // 下载Excel文件
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename=