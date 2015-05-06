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
        $prodId = Mage::registry('current_product')->getId();

        $positions = Mage::getSingleton('core/session')->getInchooFilteredCategoryProductCollection();

		if (!$positions) {
			if (Mage::registry('current_category')) {
			  $current_category = Mage::registry('current_category');
			  $category = new Varien_Object(array('id'=>$current_category));
			  $positions = Mage::getResourceModel('catalog/category')->getProductsPosition($category);
			} else {
			  $categoryIds = Mage::registry('current_product')->getCategoryIds();
			  if (!empty($categoryIds)) {
				$categoryId = current($categoryIds);
				$category = Mage::getModel('catalog/category')->load($categoryId);
				$positions = Mage::getResourceModel('catalog/category')->getProductsPosition($category);
				Mage::register('current_category', $category); // to speed up next prev/next click
			  }
			}
		  }

        if (!$positions) {
            $positions = array();
        }

        $cpk = @array_search($prodId, $positions);

        $slice = array_reverse(array_slice($positions, 0, $cpk));

        foreach ($slice as $productId) {
            $product = Mage::getModel('catalog/product')->load($productId);

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
        $prodId = Mage::registry('current_product')->getId();

        $positions = Mage::getSingleton('core/session')->getInchooFilteredCategoryProductCollection();

		if (!$positions) {
			if (Mage::registry('current_category')) {
			  $current_category = Mage::registry('current_category');
			  $category = new Varien_Object(array('id'=>$current_category));
			  $positions = Mage::getResourceModel('catalog/category')->getProductsPosition($category);
			} else {
			  $categoryIds = Mage::registry('current_product')->getCategoryIds();
			  if (!empty($categoryIds)) {
				$categoryId = current($categoryIds);
				$category = Mage::getModel('catalog/category')->load($categoryId);
				$positions = Mage::getResourceModel('catalog/category')->getProductsPosition($category);
				Mage::register('current_category', $category); // to speed up next prev/next click
			  }
			}
		  }

        if (!$positions) {
            $positions = array();
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
