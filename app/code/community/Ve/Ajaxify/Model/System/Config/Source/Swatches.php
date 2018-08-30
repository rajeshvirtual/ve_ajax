<?php
/**
* Virtual Employee Ajaxify 
* 
* @category     VE
* @package      Ve_Ajaxify
* @copyright    Copyright (c) 2015 VE (http://www.virtualemployee.com/)
* @author       VE (Magento Team)
* @version      Release: 1.0.0
* @Class        Ve_Ajaxify_Model_System_Config_Source_Swatches   
*/ 

class Ve_Ajaxify_Model_System_Config_Source_Swatches extends Varien_Object
{
    public function toOptionArray()
    {
        $options = array();
        
        $options[] = array(
                'value'=> 'link',
                'label' => Mage::helper('ajaxify')->__('Links Only')
        );
        $options[] = array(
                'value'=> 'icons',
                'label' => Mage::helper('ajaxify')->__('Icons Only')
        );
        $options[] = array(
                'value'=> 'iconslinks',
                'label' => Mage::helper('ajaxify')->__('Icons + Links')
        );
		
		$options[] = array(
                'value'=> 'optionimage',
                'label' => Mage::helper('ajaxify')->__('Option-Image')
        );
        
        return $options;
    }
} 
