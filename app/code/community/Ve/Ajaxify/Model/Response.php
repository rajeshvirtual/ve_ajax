<?php
/**
* Virtual Employee Ajaxify 
* 
* @category     VE
* @package      Ve_Ajaxify
* @copyright    Copyright (c) 2015 VE (http://www.virtualemployee.com/)
* @author       VE (Magento Team)
* @version      Release: 1.0.0
* @Class        Ve_Ajaxify_Model_Response   
*/ 
class Ve_Ajaxify_Model_Response extends Mage_Catalog_Block_Product_Abstract {

    public function send() {
        Zend_Json::$useBuiltinEncoderDecoder = true;
        if ($this->getError())
            $this->setR('error');
        else
            $this->setR('success');
        Mage::app()->getFrontController()->getResponse()->setHeader('Content-Type', 'text/plain')->setBody(Zend_Json::encode($this->getData()));
        Mage::app()->getFrontController()->getResponse()->sendResponse();
        die;
    }

    public function addUpdatedBlocks(&$_response) {
        $layout = Mage::getSingleton('core/layout');
        $res = array();
        $value = $layout->getBlock('cart_sidebar');
        if ($value) {
            $res[] = array('key' => '.block.block-cart', 'value' => $value->toHtml());
        }
        $value = $layout->getBlock('top.links');
        if ($value) {
            $res[] = array('key' => '.header .links', 'value' => $value->toHtml());
        }
        $value = $layout->getBlock('checkout.cart');
        if ($value) {
            $res[] = array('key' => '.checkout-cart-index .cart', 'value' => $value->toHtml());
        }
        $value = $layout->getBlock('minicart_head');
        if ($value) {
            $res[] = array('key' => '.header-minicart', 'value' => $value->toHtml());
        }

        if (!empty($res)) {
            $_response->setUpdateBlocks($res);
        }
    }

    public function addConfigurableOptionsBlock(&$_response) {
        $layout = Mage::getSingleton('core/layout');
        $res = '';
        $_product = Mage::registry('current_product');

        $layout->getUpdate()->addHandle('ajaxify_configurable_options');

        if ($_product->getTypeId() == 'bundle')
            $layout->getUpdate()->addHandle('ajaxify_bundle_options');

        // set unique cache ID to bypass caching
        $cacheId = 'LAYOUT_' . Mage::app()->getStore()->getId() . md5(join('__', $layout->getUpdate()->getHandles()));
        $layout->getUpdate()->setCacheId($cacheId);

        $layout->getUpdate()->load();
        $layout->generateXml();
        $layout->generateBlocks();

        $value = $layout->getBlock('ajaxify.configurable.options');

        if ($value) {
            $res .= $value->toHtml();
        }

        if ($_product->getTypeId() == 'bundle') {
            $value = $layout->getBlock('product.info.bundle');

            if ($value) {
                $res .= $value->toHtml();
            }
        }

        if (!empty($res)) {
            $_response->setConfigurableOptionsBlock($res);
        }
    }

    public function addGroupProductItemsBlock(&$_response) {
        $layout = Mage::getSingleton('core/layout');
        $res = '';

        $layout->getUpdate()->addHandle('ajaxify_grouped_options');

        // set unique cache ID to bypass caching
        $cacheId = 'LAYOUT_' . Mage::app()->getStore()->getId() . md5(join('__', $layout->getUpdate()->getHandles()));
        $layout->getUpdate()->setCacheId($cacheId);

        $layout->getUpdate()->load();
        $layout->generateXml();
        $layout->generateBlocks();

        $value = $layout->getBlock('ajaxify.grouped.options');

        if ($value) {
            $res .= $value->toHtml();
        }

        if (!empty($res)) {
            $_response->setConfigurableOptionsBlock($res);
        }
    }

}
