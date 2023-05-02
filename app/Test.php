<?php

require './vendor/autoload.php';
use Amp\TaskGraph;
use Amp\ClosureTask;
$taskDag = (new TaskGraph())

    // root task in graph (there might be multiple root tasks)
    ->addTask(new ClosureTask('1', function () {
        echo $this->name;
    }))

    // these two tasks were be executed only after task with name '1'
    // the order of execution is not guaranteed
    ->addTask(new ClosureTask('2', function () {
        echo 2;
    }, ['1']))
    ->addTask(new ClosureTask('3', function () {
        echo 3;
    }, ['1']))

    // this is the last task in graph (there might be multiple leaf tasks)
    ->addTask(new ClosureTask('4', function () {
        echo 4;
    }, ['3', '2']));

print_r($taskDag);
//Amp\Loop::run($taskDag); // will output "1234"