<?php


namespace innln\unifiedapi;

/**
 * 基础对象类
 * @package innln\unifiedapi\configure
 */
class BaseObject
{
    /**
     * BaseObject constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        // 属性初始化
        if(!empty($config) && is_array($config)){
            foreach($config as $property => $value){
                if(property_exists($this, $property)){
                    $this->$property = $value;
                }
            }
        }
    }
}