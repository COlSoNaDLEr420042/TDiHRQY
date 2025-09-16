<?php
// 代码生成时间: 2025-09-17 04:39:18
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Mvc\User\Component;

class AuditLog extends Component
{
    // Path to the directory where logs will be stored
    protected $logPath;

    public function __construct()
    {
        $this->logPath = $this->config->path . '/log/audit/';
        if (!is_dir($this->logPath)) {
            mkdir($this->logPath, 0777, true);
        }
    }

    /**
     * Logs an event to the audit log file.
     *
     * @param string $message The message to log.
     * @param string $type The type of the log (INFO, ERROR, etc.).
     */
    public function log($message, $type = Logger::INFO)
    {
        try {
            $logger = new FileLogger('audit', [
                'filePath' => $this->logPath . 'audit.log'
            ]);

            $logger->log($message, $type);
        } catch (Exception $e) {
            // Handle any exceptions that occur during logging
            throw new Exception('Error logging audit message: ' . $e->getMessage());
        }
    }

    /**
     * Retrieves the audit log entries.
     *
     * @param int $limit The number of entries to retrieve.
     * @return array An array of log entries.
     */
    public function getLogs($limit = 10)
    {
        $file = $this->logPath . 'audit.log';
        if (!file_exists($file)) {
            return [];
        }

        clearstatcache(); // Clear the file stat cache
        $fileSize = filesize($file);
        $endOfFile = $fileSize - 1;
        $line = [];

        $handle = fopen($file, 'r');
        if ($handle === false) {
            throw new Exception('Unable to open log file for reading.');
        }

        fseek($handle, $endOfFile);
        for ($i = 0; $i < $limit; $i++) {
            $char = fgetc($handle);
            while ($char !== PHP_EOL && $char !== false && $endOfFile > 0) {
                $endOfFile--;
                $line[] = $char;
                $char = fgetc($handle);
            }
            $line[] = PHP_EOL;
            $endOfFile--;
        }
        fclose($handle);

        $logEntries = implode('', $line);
        $logEntries = array_reverse(explode(PHP_EOL, $logEntries));

        return $logEntries;
    }
}
