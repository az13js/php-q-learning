<?php
namespace QL;

/**
 * 超参数
 */
class HyperParam
{
    /**
     * @var float 大于0，小于等于1的值。此值越接近于0，Agent 学习速度越慢。
     */
    private static $alpha = 0.05;

    /**
     * @var float 大于等于0小于等于1的值。此值越接近0，Agent 越容易被当前利益
     * 影响
     */
    private static $gamma = 0.7;

    /**
     * 返回 $alpha
     *
     * @return float
     */
    public static function getAlpha()
    {
        return self::$alpha;
    }

    /**
     * 返回 $gamma
     *
     * @return float
     */
    public static function getGamma()
    {
        return self::$gamma;
    }
}
