# 欢迎使用刘中胜的私密小助手

**加载助手**
`composer require liuzhongsheng/helper`

## 消息队列记录
**功能描述**
1. 增加消息队列:Lib/Mq.php
2. 增加余额变动实例对应数据库文件位于：MoneyLogDb/users_money_log.sql
3. 增加基于workerman/redis-queue的余额变动消息队列演示：test/MoneyLogMqServer/server.php
可以用于金额变动、积分变动等日志场景

**使用说明**
#### 1.1 加入队列如：用户金额变动
                    $logData = [
                        // 用户编号:id
                        'user_number' => '',
                        // 变更金额
                        'money'       => '',
                        // 类型:提现
                        'type'        => '',
                        // 变更前余额
                        'before'      => '',
                        // 变更后余额
                        'after'       => '',
                        // 创建时间
                        'createtime'  => '',
                    ];
                    $redis = new \Redis();
                    $redis->connect('127.0.0.1', 6379);
                    $log = new \HelperLib\MoneyLog();
                    $log->redis_queue_send($redis,'money_change_queue',$logData)

#### 1.2 启动服务
**将test目录下的MoneyLogMqServer复制到您的运行目录，然后使用启动(参考workerman):**
`php server.php start`
