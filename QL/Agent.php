<?php
namespace QL;

/**
 * 机器人
 */
class Agent
{
    /** @var Action[] Agent 所有可用的行动 */
    private $actions = [];

    /** @var Action */
    private $lastAction = null;

    /** @var Status */
    private $lastStatus = null;

    /** @var QTable */
    private $qTableObject = null;

    /**
     * 定义一个具有给定行为的 Agent
     *
     * @param Action[] Agent 具备的所有行为
     */
    public function __construct($action)
    {
        foreach ($action as $a) {
            $this->actions[] = $a;
        }
    }

    /**
     * 获取指定状态下 Agent 的行动
     *
     * 当第二个参数为 null 时，Agent 不更新自己的 QTable
     *
     * @param Status $status
     * @param float|null 上一个行动给 Agent 带来的奖励，null表示没有奖励，Agent 不更新
     * QTable。默认此值为 null。
     * @return Action
     */
    public function getAction($status, $reward = null)
    {
        if (is_null($this->qTableObject)) {
            $this->initQTable($status, $this->actions);
        } elseif (!$this->qTableObject->know($status)) {
            $this->qTableObject->learn($status, $this->actions);
        }

        $queryResult = $this->qTableObject->queryBest($status);
        if (!is_null($reward) && !is_null($this->lastStatus) && !is_null($this->lastAction)) {
            $newValue = (1 - HyperParam::getAlpha()) * $this->qTableObject->getValue($this->lastStatus, $this->lastAction) + HyperParam::getAlpha() * ($reward + HyperParam::getGamma() * $queryResult['v']);
            $this->qTableObject->setValue($newValue, $this->lastStatus, $this->lastAction);
        }
        $this->lastAction = $queryResult['a'];
        $this->lastStatus = $status;
        return $queryResult['a'];
    }

    /**
     * 初始化Q表
     *
     * @param Status $status
     * @param Action[] $actions
     * @return void
     */
    private function initQTable($status, $actions)
    {
        $statusFull = [];
        $totalAction = count($this->actions, COUNT_NORMAL);
        for ($i = 0; $i < $totalAction; $i++) {
            $statusFull[] = $status;
        }
        $this->qTableObject = new QTable($statusFull, $actions);
    }
}
