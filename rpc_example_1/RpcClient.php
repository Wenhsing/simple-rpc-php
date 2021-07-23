<?php

class RpcClient
{
    private $server;

    private $className;

    public function __construct($name)
    {
        $this->className = $name;
        $this->linkServer();
    }

    // 链接服务
    protected function linkServer()
    {
        $this->server = stream_socket_client("tcp://127.0.0.1:3333", $errno, $errstr);
        if (!$this->server) {
            exit("[$errno] $errstr");
        }
    }

    public function __call($method, $params)
    {
        // 将数据打包发送到服务器
        fwrite($this->server, json_encode([
            'className' => $this->className,
            'actionName' => $method,
            'params' => $params,
        ]));
        // 读取返回
        $res = fread($this->server, 2048);
        fclose($this->server);
        // 解包
        return json_decode($res, true);
    }

    public static function __callStatic($method, $params)
    {
        return new self($method);
    }
}

// 运行
var_dump(RpcClient::serverCode()->testParams(['wenhsing' => 'haha']));
