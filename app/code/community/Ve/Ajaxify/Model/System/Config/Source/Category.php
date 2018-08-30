<?php
/**
* Virtual Employee Ajaxify 
* 
* @category     VE
* @package      Ve_Ajaxify
* @copyright    Copyright (c) 2015 VE (http://www.virtualemployee.com/)
* @author       VE (Magento Team)
* @version      Release: 1.0.0
* @Class        Ve_Ajaxify_Model_System_Config_Source_Category   
*/ 

class Ve_Ajaxify_Model_System_Config_Source_Category extends Varien_Object
{
    public function toOptionArray()
    {
        $options = array();
        
        $options[] = array(
                'value'=> 'breadcrumbs',
                'label' => Mage::helper('ajaxify')->__('Breadcrumbs')
        );
        $options[] = array(
                'value'=> 'none',
                'label' => Mage::helper('ajaxify')->__('None')
        );
        
        return $options;
    }
} 
