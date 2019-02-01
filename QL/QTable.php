<?php
namespace QL;

/**
 * 质量表
 */
class QTable
{
    /**
     * @var array 记录状态和行动的对应关系。s是状态，a是对应的行动，v表示对应的行动带来的
     * 回报，是一个数字值。
     */
    private $sa = ['s' => [], 'a' => [], 'v' => []];

    /**
     * 根据给定的状态和行动初始化 Q 表
     *
     * 随机设置回报值。
     *
     * @param Status[] $status 状态对象数组，可以允许重复值相同的状态
     * @param Action[] $action 行为对象数组，允许重复
     */
    public function __construct($status, $action)
    {
        if (count($status, COUNT_NORMAL) != count($action, COUNT_NORMAL)) {
            $msg = PHP_EOL;
            $msg .= 'Exception:' . PHP_EOL;
            $msg .= 'Count(s)=' . count($status, COUNT_NORMAL) . PHP_EOL;
            $msg .= 'Count(a)=' . count($action, COUNT_NORMAL) . PHP_EOL;
            $msg .= 'Count(s) != Count(a)' . PHP_EOL;
            throw new \Exception($msg);
        }
        foreach ($status as $s) {
            $this->sa['s'][] = $s;
        }
        foreach ($action as $a) {
            $this->sa['a'][] = $a;
        }
        for ($i = count($status, COUNT_NORMAL); $i > -1; $i--) {
            $this->sa['v'][] = mt_rand() / mt_getrandmax();
        }
    }

    /**
     * 根据状态返回匹配到的令期望最大的行为和期望值
     *
     * @param Status $status
     * @return array
     */
    public function queryBest($status)
    {
        $bestIndex = mt_rand(0, count($this->sa['s'], COUNT_NORMAL) - 1);
        $bestV = $this->sa['v'][$bestIndex];
        $bestAction = $this->sa['a'][$bestIndex];

        foreach ($this->sa['s'] as $index => $s) {
            if ($s->statusValue() == $status->statusValue()) {
                if ($this->sa['v'][$index] > $bestV) {
                    $bestIndex = $index;
                    $bestV = $this->sa['v'][$bestIndex];
                    $bestAction = $this->sa['a'][$bestIndex];
                }
            }
        }
        return ['a' => $bestAction, 'v' => $bestV];
    }

    /**
     * 根据给定的状态和行为，返回其当前的期望值
     *
     * @param Status $status 一个状态对象
     * @param Action $action 一个行为对象
     * @return float|false 期望值，找不到的时候返回false。
     */
    public function getValue($status, $action)
    {
        foreach ($this->sa['s'] as $index => $s) {
            if ($s->statusValue() == $status->statusValue() && $this->sa['a'][$index]->actionName() == $action->actionName()) {
                return $this->sa['v'][$index];
            }
        }
        return false;
    }

    /**
     * 根据给定的状态和行为，设置期望值
     *
     * @param float $val 期望值
     * @param Status $status 一个状态对象
     * @param Action $action 一个行为对象
     * @return float|false 设置成功返回true，匹配不到时返回false
     */
    public function setValue($val, $status, $action)
    {
        foreach ($this->sa['s'] as $index => $s) {
            if ($s->statusValue() == $status->statusValue() && $this->sa['a'][$index]->actionName() == $action->actionName()) {
                $this->sa['v'][$index] = $val;
                return true;
            }
        }
        return false;
    }

    /**
     * 判断状态对象是否存在
     *
     * @param Status $status 状态对象
     * @return bool 存在返回true，不存在返回false
     */
    public function know($status)
    {
        foreach ($this->sa['s'] as $s) {
            if ($s->statusValue() == $status->statusValue()) {
                return true;
            }
        }
        return false;
    }

    /**
     * 追加状态到行为的映射，给新增加的记录赋值随机的期望
     *
     * @param Status $status
     * @param Action[] $actions
     * @return void
     */
    public function learn($status, $actions)
    {
        foreach ($actions as $a) {
            $this->sa['s'][] = $status;
            $this->sa['a'][] = $a;
            $this->sa['v'][] = mt_rand() / mt_getrandmax();
        }
    }
}
