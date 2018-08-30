<?php
/**
* Virtual Employee Ajaxify 
* 
* @category     VE
* @package      Ve_Ajaxify
* @copyright    Copyright (c) 2015 VE (http://www.virtualemployee.com/)
* @author       VE (Magento Team)
* @version      Release: 1.0.0
* @Class        Ve_Ajaxify_Block_Rewrite_RewriteCatalogCategoryView   
*/
 

class Ve_Ajaxify_Block_Rewrite_RewriteCatalogCategoryView extends Mage_Catalog_Block_Category_View
{ 
    public function getProductListHtml()
    {
        $html = parent::getProductListHtml();
        if ($this->getCurrentCategory()->getIsAnchor()){
            $html = Mage::helper('ajaxify')->wrapProducts($html);
        }
        return $html;
    }   
} 
