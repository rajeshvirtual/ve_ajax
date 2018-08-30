<?php
/**
* Virtual Employee Ajaxify 
* 
* @category     VE
* @package      Ve_Ajaxify
* @copyright    Copyright (c) 2015 VE (http://www.virtualemployee.com/)
* @author       VE (Magento Team)
* @version      Release: 1.0.0
* @Class        Ve_Ajaxify_Block_Rewrite_RewriteCatalogLayerView   
*/
 
class Ve_Ajaxify_Block_Rewrite_RewriteCatalogLayerView extends Mage_Catalog_Block_Layer_View
{
const review_FILTER_POSITION = 5;
 
    /**
     * State block name
     *
     * @var string
     */
    protected $_reviewBlockName;
 
    /**
     * Initialize blocks names
     */
    protected function _initBlocks()
    {
        parent::_initBlocks();
 
        $this->_reviewBlockName = 'ratingfilter/catalog_layer_filter_rating';
    }
 
    /**
     * Prepare child blocks
     *
     * @return Mage_Catalog_Block_Layer_View
     */
    protected function _prepareLayout()
    {
        $reviewBlock = $this->getLayout()->createBlock($this->_reviewBlockName)
                ->setLayer($this->getLayer())
                ->init();
 
        $this->setChild('review_filter', $reviewBlock);
 
        return parent::_prepareLayout();
    }
 
    /**
     * Get all layer filters
     *
     * @return array
     */
    public function getFilters()
    {
        $filters = parent::getFilters();
 
        if (($reviewFilter = $this->_getReviewFilter())) {
            // Insert review filter to the self::review_FILTER_POSITION position
            $filters = array_merge(
                array_slice(
                    $filters, 0,self::review_FILTER_POSITION - 1
                ),
                array($reviewFilter),
                array_slice(
                    $filters, self::review_FILTER_POSITION - 1, count($filters) - 1
                )
            );
        }
 
        return $filters;
    }
 
    /**
     * Get review filter block
     *
     * @return Mage_Catalog_Block_Layer_Filter_review
     */
    protected function _getReviewFilter()
    {
        return $this->getChild('review_filter');
    }
  
}
