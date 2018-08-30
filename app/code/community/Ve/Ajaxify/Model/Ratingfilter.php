<?php
/**
 * @category   Acegento
 * @package    Acegento_Ratingfilter
 * @author     ds.gupta@hotmail.com
 * @website    http://acegento
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Ve_Ajaxify_Model_Ratingfilter extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('ajaxify/ajaxify');
    }
}
