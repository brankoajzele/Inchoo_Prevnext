<?php
/**
 * @category    Inchoo
 * @package     Inchoo_Prevnext
 * @author      Branko Ajzele <ajzele@gmail.com>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Inchoo_Prevnext_Model_Observer
{
    public function setInchooFilteredCategoryProductCollection(Varien_Event_Observer $observer)
    {
        /**
         * There might be some illogical buggy behavior when coming directly
         * from "Related products" / "Recently viewed" products block.
         * Nothing that should break the page however.
         */
        $action = $observer->getEvent()->getControllerAction();
        if ($action->getFullActionName() == 'catalog_category_view') {
            $products = Mage::app()->getLayout()
                ->getBlockSingleton('Mage_Catalog_Block_Product_List')
                ->getLoadedProductCollection()
                ->getColumnValues('entity_id');

            Mage::getSingleton('core/session')->setInchooFilteredCategoryProductCollection($products);

            unset($products);
        }

        return $this;
    }
}
