<?php
require __DIR__ . '/vendor/autoload.php';

use Workerman\Worker;
use Workerman\Lib\Timer;
use Workerman\RedisQueue\Client;

$worker = new Worker();
if(!is_dir('log')){
    mkdir('log', 0755, true);
}
$worker->count = 4;
$worker->onWorkerStart = function () {
    global $db;
    $client = new Client('redis://127.0.0.1:6379');
    
    // $db = new \Workerman\MySQL\Connection('host', 'port', 'user', 'password', 'db_name');
   $db = new \Workerman\MySQL\Connection('47.111.90.78', '3306', 'distributors', 'BDmXefirTiBj4EFT', 'distributors');
   // 订阅
    $client->subscribe('d_users_money_log_queues', function($data){
        global $db;
        $insert_id = $db->insert('d_users_money_log')->cols($data)->query();
        if($insert_id <= 0){
            file_put_contents('log/'.date('Y-m-d').'_money_change_queues_error.log', "运行时间".var_export(json_encode($data,true),true). PHP_EOL, FILE_APPEND);
            throw new Exception('未能写入成功');
        }
        file_put_contents('log/'.date('Y-m-d').'_users_money_log_queues.log', "运行时间".date('Y-m-d H:i:s').' 结果：'.var_export(json_encode($data,true),true). PHP_EOL, FILE_APPEND);
    });
    // Timer::add(1, function()use($client){
    //     $client->send('d_users_money_log_queues', [
    //             'user_number' => 'U1606204219962',
    //             'money'       => '10',
    //             'type'        => '提现',
    //             'before'      => '90.00',
    //             'after'       => '80',
    //             'createtime'  => 1606289514
    //         ]);
    // });
};

Worker::runAll();