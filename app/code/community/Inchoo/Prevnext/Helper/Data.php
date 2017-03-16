<?php
/**
 * @category    Inchoo
 * @package     Inchoo_Prevnext
 * @author      Branko Ajzele <ajzele@gmail.com>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Inchoo_Prevnext_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_moduleName = 'Inchoo_Prevnext';

    /**
     * @return Mage_Catalog_Model_Product or FALSE
     */
    public function getPreviousProduct()
    {
        $currentProduct = Mage::registry('current_product');
        if (!$currentProduct) {
            return false;
        }

        $prodId = $currentProduct->getId();
        $positions = $this->getProductPositions($currentProduct);

        $cpk = @array_search($prodId, $positions);
        $slice = array_reverse(array_slice($positions, 0, $cpk));

        return $this->getFirstProduct($slice);
    }

    /**
     * @return Mage_Catalog_Model_Product or FALSE
     */
    public function getNextProduct()
    {
        $currentProduct = Mage::registry('current_product');
        if (!$currentProduct) {
            return false;
        }

        $prodId = $currentProduct->getId();
        $positions = $this->getProductPositions($currentProduct);

        $cpk = @array_search($prodId, $positions);
        $slice = array_slice($positions, $cpk + 1, count($positions));

        return $this->getFirstProduct($slice);
    }

    /**
     * Get Product Position in Category
     *
     * @param Mage_Catalog_Model_Product
     * @return array
     */
    public function getProductPositions($product)
    {
        $positions = Mage::getSingleton('core/session')->getInchooFilteredCategoryProductCollection();

        if (!$positions) {
            $currentCategory = Mage::registry('current_category');

            /* Accessed product directly via URL not through category?! */
            if (!$currentCategory) {
                $categoryIds = $product->getCategoryIds();

                if (!count($categoryIds)) {
                    return false;
                }

                $categoryId = current($categoryIds);
                $currentCategory = Mage::getModel('catalog/category')->load($categoryId);
                Mage::register('current_category', $currentCategory);
            }

            if (Mage::getResourceModel('catalog/category_collection')->getDisableFlat()) {
                $positions = Mage::registry('current_category')->getProductsPosition();
            } else {
                $positions = Mage::getResourceModel('catalog/category')->getProductsPosition($currentCategory);
            }

            $positions = array_reverse(array_keys($positions));
        }
        
        return $positions;
    }

    /**
     * Get first product from ID-array
     *
     * @param array $ids Product IDs
     * @param Mage_Catalog_Model_Product|false
     */
    public function getFirstProduct(array $productIds)
    {
        foreach ($productIds as $productId) {
            $product = Mage::getModel('catalog/product')->load($productId);
            if ($product && $product->isVisibleInCatalog() && $product->isVisibleInSiteVisibility()) {
                return $product;
            }
        }
        return false;
    }
}
