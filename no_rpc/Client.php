<?php

$data = [
    'xixi' => 'haha',
    'aaa' => 'xxx',
    'timestamp' => time(),
];

require_once '../ServerCode.php';

$c = new ServerCode();
var_dump($c->testParams($params));
