<?php
// 代码生成时间: 2025-10-12 02:32:24
use Phalcon\Db\Adapter\Pdo;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\MiddlewareInterface;
use Phalcon\Di\FactoryDefault;
use Phalcon\Di;

class DbReadWriteSplittingMiddleware extends Plugin implements MiddlewareInterface
{
    // 构造函数，传入DI容器
    public function __construct(Di $di)
    {
        parent::__construct($di);
    }

    // 处理中间件逻辑
    public function call(\$resolved, \$forward)
    {
        try {
            // 检查是否为数据库查询操作
            if (\$this->isDatabaseOperation(\$resolved)) {
                // 根据操作类型选择数据库连接
                \$db = \$this->getDatabaseConnection();

                // 将选择的数据库连接赋值给DI容器
                \$this->getDI()->setShared('db', \$db);
            }

            // 继续执行下一个中间件或控制器
            return \$forward->next();
        } catch (Exception \$e) {
            // 错误处理
            \$this->getDI()->get('logger')->error(\$e->getMessage());
            throw new \Exception(\$e->getMessage());
        }
    }

    // 检查当前操作是否为数据库操作
    protected function isDatabaseOperation(\$resolved)
    {
        // 这里可以根据实际情况添加更多的逻辑判断
        return strpos(\$resolved->getActionName(), 'find') !== false ||
               strpos(\$resolved->getActionName(), 'save') !== false ||
               strpos(\$resolved->getActionName(), 'delete') !== false;
    }

    // 获取数据库连接，根据操作类型选择读或写数据库
    protected function getDatabaseConnection()
    {
        \$config = \$this->getDI()->getShared('config');

        // 默认使用写数据库
        \$dbConfig = \$config->database->toArray();

        // 如果是读操作，选择读数据库
        if (\$this->isReadOperation()) {
            \$dbConfig = \$config->databaseRead->toArray();
        }

        // 创建并返回数据库连接实例
        return new Pdo(\$dbConfig);
    }

    // 检查当前操作是否为读操作
    protected function isReadOperation()
    {
        // 这里可以根据实际情况添加更多的逻辑判断
        return strpos(\$this->request->getURI(), 'read') !== false;
    }
}
