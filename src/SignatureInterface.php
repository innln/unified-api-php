<?php declare(strict_types=1);
namespace innln\unifiedapi;


/**
 * 签名接口
 * @package innln\unifiedapi
 */
interface SignatureInterface
{
    /**
     * @param array $parameters 参与签名的参数
     * @param int $sortingType 排序类型
     * @return array
     *
     */
    public function sort(array $parameters, int $sortingType):array;

    /**
     * 签名
     * @param $parameters 参与签名的参数，已经排序过
     * @return string
     */
    public function sign(array $parameters):string;
}