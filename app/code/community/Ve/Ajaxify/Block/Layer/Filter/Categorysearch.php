<?php
/**
* Virtual Employee Ajaxify 
* 
* @category     VE
* @package      Ve_Ajaxify
* @copyright    Copyright (c) 2015 VE (http://www.virtualemployee.com/)
* @author       VE (Magento Team)
* @version      Release: 1.0.0
* @Class        Ve_Ajaxify_Block_Layer_Filter_Price  
* @Overwrite    Ve_Ajaxify_Block_Layer_Filter_Category
*/ 


class Ve_Ajaxify_Block_Layer_Filter_Categorysearch extends Ve_Ajaxify_Block_Layer_Filter_Category
{
    public function __construct()
    {

        parent::__construct();
		//Load Custom PHTML of category search
        $this->setTemplate('ajaxify/filter_category_search.phtml');
		//Set Filter Model Name
        $this->_filterModelName = 'ajaxify/layer_filter_categorysearch'; 
    }
    
} 
