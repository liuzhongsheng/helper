<?php
namespace HelperLib;
class MoneyLog{
    // 发送redis消息队列
    // 使用方式：
    // $redis = new Redis;
    // $redis->connect('127.0.0.1', 6379);
    // $queue = 'user-1';
    // $data= ['some', 'data'];
    // redis_queue_send($redis, $queue, $data);
    /**
     * @param $redis redis连接对象
     * @param $queue 队列名称
     * @param $data  数据
     * @param int $delay
     * @return mixed
     */
    public function redis_queue_send($redis, $queue, $data, $delay = 0) {
        phpinfo();
        $queue_waiting = $queue.'redis-queue-waiting';
        $queue_delay = $queue.'redis-queue-delayed';
        $now = time();
        $package_str = json_encode([
            'id'       => rand(),
            'time'     => $now,
            'delay'    => 0,
            'attempts' => 0,
            'queue'    => $queue,
            'data'     => $data
        ]);
        if ($delay) {
            return $redis->zAdd($queue_delay, $now + $delay, $package_str);
        }
        return $redis->lPush($queue_waiting.$queue, $package_str);
    }
}