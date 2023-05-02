<?php

    $server = new Swoole\WebSocket\Server("0.0.0.0", 9501);
    //异步任务池
    task($server);

    $server->on('open', function (Swoole\WebSocket\Server $server, $request) {
        echo "server: handshake success with fd{$request->fd}\n";
    });

    $server->on('message', function (Swoole\WebSocket\Server $server, $frame) {

//        asyncData();
        $data = $frame->data;
        if($data==1 || $data==2){
            $server->task(['data'=>$frame->data,'id'=>$frame->fd]);
        }
        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";

        $server->push($frame->fd, "this is server");
    });

    $server->on('close', function ($ser, $fd) {
        echo "client {$fd} closed\n";
    });


$server->start();



function task($server){
    //设置异步任务的工作进程数量
    $server->set([
        'task_worker_num' => 2
    ]);

    $server->on('Task', function ($serv, $task_id, $reactor_id, $data) {
        echo "New AsyncTask[id={$task_id}]".PHP_EOL;

        if($data['data']==1){
            setp(1);
            asyncData();
            $serv->push($data['id'], "指纹吗:qweqweqweqweqweqweqweqweq");

        }
        if($data['data']==2){
            setp(2);
            echo "关闭指令  \n";
        }

        //返回任务执行的结果
        $serv->finish("{$data['data']} -> OK");
    });

    $server->on('Finish', function ($serv, $task_id, $data) {
        echo "AsyncTask[{$task_id}] Finish: {$data}".PHP_EOL;
    });
}




function asyncData(){

    for ($i=0;$i<10;$i++){
        if(getp()==1){
            sleep(1);
            echo "---- $i\n";
        }else{
            echo "结束  \n";
            break;
        }

    }
}
function getp(){
    return file_get_contents('./p.log');
}

function setp($v){
    file_put_contents('./p.log',$v);
}