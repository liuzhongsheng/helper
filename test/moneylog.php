<?php
require "../vendor/autoload.php";
//连接本地的 Redis 服务
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
echo "Connection to server successfully";
//查看服务是否运行
echo "Server is running: " . $redis->ping();
$log = new \HelperLib\MoneyLog();
// 使用方式：
// $redis = new Redis;
// $redis->connect('127.0.0.1', 6379);
// $queue = 'user-1';
$data= ['some', 'data'];
$log->redis_queue_send($redis,'money_change_queue',$data);