<?php

// 服务端代码
class ServerCode
{
    public function test()
    {
        return 'This is test.';
    }

    public function testParams($params)
    {
        return $params;
    }
}
