<?php

/**
 * Virtual Employee Ajaxify 
 * 
 * @category     VE
 * @package      Ve_Ajaxify
 * @copyright    Copyright (c) 2015 VE (http://www.virtualemployee.com/)
 * @author       VE (Magento Team)
 * @version      Release: 1.0.0
 * @Class        Ve_Ajaxify_FrontController   
 */
class Ve_Ajaxify_FrontController extends Mage_Core_Controller_Front_Action {

    public function categoryAction() {
        // init category
        $categoryId = (int) $this->getRequest()->getParam('id', false);
        if (!$categoryId) {
            $this->_forward('noRoute');
            return;
        }

        $category = Mage::getModel('catalog/category')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($categoryId);
        Mage::register('current_category', $category);

        $this->loadLayout();
        $response = array();
        $response['layer'] = $this->getLayout()->getBlock('layer')->toHtml();
		
        if(Mage::getStoreConfig('ajaxify/ajaxifycart/ajaxify_cart')) {
            $response['products'] = $this->getLayout()->getBlock('root')->toHtml();
			
        } else {
            $response['products'] = $this->getLayout()->getBlock('defaultroot')->toHtml();
        }
        
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    public function searchAction() {
        $this->loadLayout();
        $response = array();
        $response['layer'] = $this->getLayout()->getBlock('layer')->toHtml();
        if(Mage::getStoreConfig('ajaxify/ajaxifycart/ajaxify_cart')) {
            $response['products'] = $this->getLayout()->getBlock('root')->setIsSearchMode()->toHtml();
        } else {
            $response['products'] = $this->getLayout()->getBlock('defaultroot')->setIsSearchMode()->toHtml();
        }
        

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    public function productAction() {
        $productid = Mage::app()->getRequest()->getParam('id', false);
        if (!$productid) {
            $this->_forward('noRoute');
            return;
        }
        $_product = Mage::getModel('catalog/product')->load($productid);
        Mage::register('product', $_product);
        Mage::register('current_product', $_product);
        
        $this->loadLayout();
        $this->getLayout();
        $this->renderLayout();
    }

}
