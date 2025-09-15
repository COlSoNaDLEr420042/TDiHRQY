<?php
// 代码生成时间: 2025-09-16 05:11:33
// backup_sync_tool.php
// 这是一个使用PHP和PHALCON框架实现的文件备份和同步工具。

use Phalcon\Mvc\Controller;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Di\FactoryDefault;
use Phalcon\Cli\Task;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use FilesystemIterator;
use ZipArchive;
use Exception;

class BackupSyncTool extends Task
{
    protected $sourceDir;
    protected $backupDir;
    protected $logger;
    protected $config;

    // 构造函数
    public function __construct($di = null)
    {
        parent::__construct($di);
        $this->config = $this->getDI()->getShared('config');
        $this->sourceDir = $this->config->application->sourceDir;
        $this->backupDir = $this->config->application->backupDir;
        $this->logger = new FileLogger('/path/to/backup_sync_tool.log');
    }

    // 备份文件
    public function backupAction()
    {
        try {
            // 创建备份目录
            if (!file_exists($this->backupDir)) {
                mkdir($this->backupDir, 0777, true);
            }

            // 获取源目录内容
            $sourceFiles = $this->getSourceFiles($this->sourceDir);

            // 遍历并备份文件
            foreach ($sourceFiles as $file) {
                // 复制文件到备份目录
                if (!copy($file, $this->backupDir . '/' . basename($file))) {
                    $this->logger->error('Failed to backup file: ' . $file);
                } else {
                    $this->logger->info('Backup successful for file: ' . $file);
                }
            }

            $this->logger->info('Backup completed successfully.');
        } catch (Exception $e) {
            $this->logger->error('Backup failed: ' . $e->getMessage());
        }
    }

    // 同步文件
    public function syncAction()
    {
        try {
            // 获取源目录和备份目录的内容
            $sourceFiles = $this->getSourceFiles($this->sourceDir);
            $backupFiles = $this->getSourceFiles($this->backupDir);

            // 遍历并同步文件
            foreach ($sourceFiles as $file) {
                $relativePath = $this->getRelativePath($file, $this->sourceDir);
                $backupFile = $this->backupDir . '/' . $relativePath;

                if (!in_array($backupFile, $backupFiles)) {
                    // 复制文件到备份目录
                    if (!copy($file, $backupFile)) {
                        $this->logger->error('Failed to sync file: ' . $file);
                    } else {
                        $this->logger->info('Sync successful for file: ' . $file);
                    }
                }
            }

            $this->logger->info('Sync completed successfully.');
        } catch (Exception $e) {
            $this->logger->error('Sync failed: ' . $e->getMessage());
        }
    }

    // 获取源目录文件
    protected function getSourceFiles($dir)
    {
        $files = [];
        $iterator = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $filesIterator = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($filesIterator as $file) {
            if ($file->isFile()) {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    // 获取相对路径
    protected function getRelativePath($file, $baseDir)
    {
        $relativePath = str_replace($baseDir, '', $file);
        return trim($relativePath, '/');
    }
}
