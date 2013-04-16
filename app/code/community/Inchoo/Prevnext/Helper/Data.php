<?php
/**
 * @category    Inchoo
 * @package     Inchoo_Prevnext
 * @author      Branko Ajzele <ajzele@gmail.com>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Inchoo_Prevnext_Helper_Data extends Mage_Core_Helper_Abstract
{
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
            
            $positions = Mage::getSingleton('core/session')
                                ->getInchooFilteredCategoryProductCollection();
            
            if (!$positions) {
                
                $currentCategory = Mage::registry('current_category');
                
                /* Accessed product directly via URL not through category?! */
                if (!$currentCategory) {
                    $categoryIds = Mage::registry('current_product')->getCategoryIds();
                    $categoryId = current($categoryIds);
                
                    $currentCategory = Mage::getModel('catalog/category')
                                            ->load($categoryId);
                    
                    Mage::register('current_category', $currentCategory);
                }
                
                $positions = array_reverse(array_keys(Mage::registry('current_category')->getProductsPosition()));
                //$positions = array_keys(Mage::registry('current_category')->getProductsPosition());
                //Zend_Debug::dump($positions, '$positions');
            }
            
            $cpk = @array_search($prodId, $positions);

            $slice = array_reverse(array_slice($positions, 0, $cpk));

            foreach ($slice as $productId) {
                    $product = Mage::getModel('catalog/product')
                                                    ->load($productId);

                    if ($product && $product->getId() && $product->isVisibleInCatalog() && $product->isVisibleInSiteVisibility()) {
                            return $product;
                    }
            }

            return false;
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
            
            $positions = Mage::getSingleton('core/session')
                                ->getInchooFilteredCategoryProductCollection();
            
            if (!$positions) {
                
                $currentCategory = Mage::registry('current_category');
                
                /* Accessed product directly via URL not through category?! */
                if (!$currentCategory) {
                    $categoryIds = Mage::registry('current_product')->getCategoryIds();
                    $categoryId = current($categoryIds);
                
                    $currentCategory = Mage::getModel('catalog/category')
                                            ->load($categoryId);
                    
                    Mage::register('current_category', $currentCategory);
                }
                
                $positions = array_reverse(array_keys(Mage::registry('current_category')->getProductsPosition()));
                //$positions = array_keys(Mage::registry('current_category')->getProductsPosition());
            }          
            
            $cpk = @array_search($prodId, $positions);
            
            $slice = array_slice($positions, $cpk + 1, count($positions));

            foreach ($slice as $productId) {
                    $product = Mage::getModel('catalog/product')
                                                    ->load($productId);

                    if ($product && $product->getId() && $product->isVisibleInCatalog() && $product->isVisibleInSiteVisibility()) {
                            return $product;
                    }
            }

            return false;
    }
}
