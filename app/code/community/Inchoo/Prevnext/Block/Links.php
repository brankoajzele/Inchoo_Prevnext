<?php
/**
 * @category    Inchoo
 * @package     Inchoo_Prevnext
 * @author      Branko Ajzele <ajzele@gmail.com>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Inchoo_Prevnext_Block_Links extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('inchoo/prevnext/links.phtml');
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    public function getPreviousProduct()
    {
        return $this->helper('inchoo_prevnext')->getPreviousProduct();
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    public function getNextProduct()
    {
        return $this->helper('inchoo_prevnext')->getNextProduct();
    }
}
