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
 * @Overwrite    Mage_Catalog_Block_Layer_Filter_Attribute
 */
class Ve_Ajaxify_Block_Layer_Filter_Attribute extends Mage_Catalog_Block_Layer_Filter_Attribute {

    public function __construct() {
        parent::__construct();
        //Load Custom PHTML of attributes 
        $this->setTemplate('ajaxify/filter_attribute.phtml');
        //Set Filter Model Name
        $this->_filterModelName = 'ajaxify/layer_filter_attribute';
    }

    public function getVar() {
        //Get request variable name which is used for apply filter
        return $this->_filter->getRequestVar();
    }

    public function getClearUrl() {
        //Get URL and rewrite with SEO frieldly URL
        $_seoURL = '';
        //Get request filters with URL 
        $query = Mage::helper('ajaxify')->getParams();
        if (!empty($query[$this->getVar()])) {
            $query[$this->getVar()] = null;
            $_seoURL = Mage::getUrl('*/*/*', array(
                        '_use_rewrite' => true,
                        '_query' => $query,
            ));
        }

        return $_seoURL;
    }

    public function getHtmlId($item) {
        //Make HTMLID with requested filter + value of param
        return $this->getVar() . '-' . $item->getValueString();
    }

    public function Selectedfilter($item) {
        //Set Selected filters 
        $ids = (array) $this->_filter->getActiveState();
        return in_array($item->getValueString(), $ids);
    }

    public function getFiltersArray() {

        $_filtersArray = array();
        //$hideLinks = Mage::getStoreConfig('ajaxify/ajaxifynavigation/remove_links');
        //Get all filter items  ( use getItems method of Mage_Catalog_Model_Layer_Filter_Abstract )
        foreach ($this->getItems() as $_item) {
		
		  
            $showSwatches = Mage::getStoreConfig('ajaxify/ajaxifynavigation/show_swatches');
            $_htmlFilters = 'id="' . $this->getHtmlId($_item) . '" ';
            $var_href = "#";

            //Create URL
            $var_href = html_entity_decode($currentUrl = Mage::app()->getRequest()->getBaseUrl() . Mage::getSingleton('core/session')->getRequestPath());
            $_htmlFilters .= 'href="' . $var_href . '" ';
            $_htmlFilters .= 'class="ve_layered_attribute '
                    . ($this->Selectedfilter($_item) ? 've_layered_attribute_selected' : '') . '" ';
            //Check the number of products against filter
            $qty = '';
            if (!$this->getHideQty())
                $qty = ''; //'(' .  $_item->getCount() .')';
            if ($this->getName() == "Color") {
			
			
			if ($showSwatches == "iconslinks") {
	
           $_html = "";
           $_html .= '<div class="color">
                                        <a ' . $_htmlFilters . '><div class="color_box" style="background-color:' . $iconCode . ';"></div>
                                        ' . $_item->getLabel() . '</a><span>' . $qty . '</span>
                                </div>';
                } 
                 elseif ($showSwatches == "icons") {

                    $iconCode = Mage::helper('ajaxify')->checkColor($_item->getLabel());
                    $_html = "";
                    $_html .= '<div class="color">
                                        <a ' . $_htmlFilters . '><div class="color_box" style="background-color:' . $iconCode . ';"></div>
                                        </a><span>' . $qty . '</span>
                                </div>';
                } elseif ($showSwatches == "optionimage") {
			?>  <style type="text/css">
				#ve_layered_color li{ display: inline-block; margin-right: 5px; width: 20px; }
               </style>
			<?php	
             $iconode = Mage::helper('attributeoptionimage')->getAttributeOptionThumb($_item->getValue()); 
             $_html = "";
             $_html .= '<a style="background:none!important; padding:0!important; display: inline-block;" ' . $_htmlFilters . '><img src="'.$iconode.'" width="20"/></a><span>' . $qty . '</span>';
                }
				
				else {
					
                    $_html = "";
                    $_html .= '<a ' . $_htmlFilters . '>' . $_item->getLabel() . '</a><span>' . $qty . '</span>';
                }
            }
            if ($this->getName() == "Color") {
                $_filtersArray[] = $_html;
            } else {
                $_filtersArray[] = '<a ' . $_htmlFilters . '>' . $_item->getLabel() . '</a>' . $qty;
            }
        }

        return $_filtersArray;
    }

}

