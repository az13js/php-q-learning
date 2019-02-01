<?php
namespace QL;

/**
 * 行为对象
 */
class Action
{
    /** @var string 行为的名称 */
    private $name;

    /**
     * 可以给行为指定一个名称，方便调试
     *
     * @param string|null $name 行为的名称，当值为null的时候，会自动命名。默认为null。
     */
    public function __construct($name = null)
    {
        $this->name = $name;
        if (is_null($name)) {
            $this->name = 'unknow action ' . date('Y-m-d H:i:s')
                . ' ' . uniqid();
        }
    }

    /**
     * 返回行为的名称
     *
     * @return string
     */
    public function actionName()
    {
        return $this->name;
    }
}
