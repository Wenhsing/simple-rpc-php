<?php

// 导入服务器具体代码，这里只导入一个作为示例
require_once '../ServerCode.php';

class RpcServer
{
    protected $server;

    public function __construct(array $params)
    {
        $this->createServer();
    }

    // 创建服务
    protected function createServer()
    {
        $this->server = stream_socket_server("tcp://0.0.0.0:3333", $errno, $errstr);
        if (!$this->server) {
            throw new \RuntimeException($errstr, $errno);
        }
    }

    // 运行
    public function run()
    {
        while ($socket = stream_socket_accept($this->server)) {
            $data = $this->unpack(fread($socket, 1024));
            var_dump($data);
            $this->doAction(
                $socket,
                $data['className'] ?? '',
                $data['actionName'] ?? '',
                $data['params'] ?? ''
            );
            fclose($socket);
        }
    }

    // 解包
    protected function unpack($data)
    {
        return json_decode($data, true);
    }

    // 执行
    protected function doAction($socket, $className, $actionName, $params = null)
    {
        $className = ucfirst($className);
        if (class_exists($className)) {
            $c = new $className();
            if (method_exists($c, $actionName)) {
                $res = $c->$actionName($params);
                // 返回数据
                fwrite($socket, json_encode($res));
            }
        }
    }
}

$c = new RpcServer([]);
$c->run();
