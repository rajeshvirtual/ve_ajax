<?php
/**
* Virtual Employee Ajaxify 
* 
* @category     VE
* @package      Ve_Ajaxify
* @copyright    Copyright (c) 2015 VE (http://www.virtualemployee.com/)
* @author       VE (Magento Team)
* @version      Release: 1.0.0
* @Class        Ve_Ajaxify_Block_Search_Layer   
*/
class Ve_Ajaxify_Block_Search_Layer extends Ve_Ajaxify_Block_Layer_View {

    public function getLayer() {
        return Mage::getSingleton('catalogsearch/layer');
    }

    /**
     * Check availability display layer block
     *
     * @return bool
     */
    public function canShowBlock() {

        $availableResCount = (int) Mage::app()->getStore()
                        ->getConfig(Mage_CatalogSearch_Model_Layer::XML_PATH_DISPLAY_LAYER_COUNT);

        if (!$availableResCount || ($availableResCount >= $this->getLayer()->getProductCollection()->getSize())) {
            return parent::canShowBlock();
        }
        return false;
    }

    protected function createCategoriesBlock() {

        $categoryBlock = $this->getLayout()
                ->createBlock('ajaxify/layer_filter_categorysearch')
                ->setLayer($this->getLayer())
                ->init();
        $this->setChild('category_filter', $categoryBlock);
    }

}
