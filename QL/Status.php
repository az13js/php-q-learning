<?php
namespace QL;

/**
 * 状态对象
 */
class Status
{
    /** @var string 字符串，表示状态 */
    private $sv;

    /**
     * 初始化状态对象
     *
     * @param string $val 字符串，表示状态
     */
    public function __construct($val)
    {
        $this->sv = $val;
    }

    /**
     * 获得状态
     *
     * @return string
     */
    public function statusValue()
    {
        return $this->sv;
    }
}
