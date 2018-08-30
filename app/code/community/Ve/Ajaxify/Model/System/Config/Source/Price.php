<?php
/**
* Virtual Employee Ajaxify 
* 
* @category     VE
* @package      Ve_Ajaxify
* @copyright    Copyright (c) 2015 VE (http://www.virtualemployee.com/)
* @author       VE (Magento Team)
* @version      Release: 1.0.0
* @Class        Ve_Ajaxify_Model_System_Config_Source_Price   
*/ 

class Ve_Ajaxify_Model_System_Config_Source_Price extends Varien_Object
{
    public function toOptionArray()
    {
        $options = array();
        
        $options[] = array(
                'value'=> 'default',
                'label' => Mage::helper('ajaxify')->__('Default')
        );
        $options[] = array(
                'value'=> 'slider',
                'label' => Mage::helper('ajaxify')->__('Slider')
        );
        $options[] = array(
                'value'=> 'input',
                'label' => Mage::helper('ajaxify')->__('Input')
        );
        $options[] = array(
                'value'=> 'both',
                'label' => Mage::helper('ajaxify')->__('Both Slider with Input')
        );
        
        return $options;
    }
} 
