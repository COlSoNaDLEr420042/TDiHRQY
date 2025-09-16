<?php
// 代码生成时间: 2025-09-16 19:33:46
class CsvBatchProcessor {

    /**
     * @var string 存储CSV文件的目录路径
     */
    protected $directoryPath;

    /**
     * @var array 存储CSV文件的文件名列表
     */
    protected $csvFiles;

    public function __construct($directoryPath) {
        $this->directoryPath = $directoryPath;
        $this->csvFiles = $this->getFilesFromDirectory();
    }

    /**
     * 获取目录中的CSV文件列表
     *
     * @return array
     */
    protected function getFilesFromDirectory() {
        $files = [];
        if ($handle = opendir($this->directoryPath)) {
            while (false !== ($entry = readdir($handle))) {
                if (pathinfo($entry, PATHINFO_EXTENSION) === 'csv') {
                    $files[] = $entry;
                }
            }
            closedir($handle);
        } else {
            throw new Exception('Failed to open directory: ' . $this->directoryPath);
        }
        return $files;
    }

    /**
     * 处理CSV文件
     *
     * @param callable $callback 回调函数，用于处理每行数据
     */
    public function processFiles(callable $callback) {
        foreach ($this->csvFiles as $file) {
            $this->processFile($file, $callback);
        }
    }

    /**
     * 处理单个CSV文件
     *
     * @param string $file 文件名
     * @param callable $callback 回调函数
     */
    protected function processFile($file, callable $callback) {
        try {
            $filePath = $this->directoryPath . DIRECTORY_SEPARATOR . $file;
            if (!file_exists($filePath)) {
                throw new Exception('File not found: ' . $filePath);
            }
            $handle = fopen($filePath, 'r');
            if ($handle === false) {
                throw new Exception('Failed to open file: ' . $filePath);
            }

            while (($data = fgetcsv($handle)) !== false) {
                $callback($data);
            }

            fclose($handle);
        } catch (Exception $e) {
            // 错误处理，记录日志或重试等
            error_log($e->getMessage());
        }
    }
}

// 使用示例
try {
    $processor = new CsvBatchProcessor('/path/to/csv/directory');
    $processor->processFiles(function($data) {
        // 处理每行数据的逻辑
        echo "Processing data: " . implode(',', $data) . "
";
    });
} catch (Exception $e) {
    error_log($e->getMessage());
}
