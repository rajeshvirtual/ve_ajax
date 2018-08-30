<?php
/**
* Virtual Employee Ajaxify 
* 
* @category     VE
* @package      Ve_Ajaxify
* @copyright    Copyright (c) 2015 VE (http://www.virtualemployee.com/)
* @author       VE (Magento Team)
* @version      Release: 1.0.0
* @Class        Ve_Ajaxify_Model_Select   
*/ 

class Ve_Ajaxify_Model_Select extends Zend_Db_Select 
{
    public function __construct()
    {
    }

    public function setPart($part, $val){
        $this->_parts[$part] = $val;
    }   
} 
