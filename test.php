<?php
/**
 * 简单验证算法是否正确
 */
require_once 'autoload.php';

use \QL\Action;
use \QL\Agent;
use \QL\Status;

$left = new Action('_');
$right = new Action('T');
$actions = [$left, $right];

$agent = new Agent($actions);

$status = new Status('death');
$reward = 0;
for ($i = 0; $i < 2000; $i++) {
    $action = $agent->getAction($status, $reward);
    echo $action->actionName();
    if ($action->actionName() == '_') {
        $reward = 0.009;
    } else {
        $reward = 0.01;
    }
}
echo PHP_EOL;
