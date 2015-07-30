<?php
/**
 * INCHOO's FREE EXTENSION DISCLAIMER
 *
 * Please do not edit or add to this file if you wish to upgrade Magento
 * or this extension to newer versions in the future.
 *
 * Inchoo developers (Inchooer's) give their best to conform to
 * "non-obtrusive, best Magento practices" style of coding.
 * However, Inchoo does not guarantee functional accuracy of specific
 * extension behavior. Additionally we take no responsibility for any
 * possible issue(s) resulting from extension usage.
 *
 * We reserve the full right not to provide any kind of support for our free extensions.
 *
 * You are encouraged to report a bug, if you spot any,
 * via sending an email to bugreport@inchoo.net. However we do not guaranty
 * fix will be released in any reasonable time, if ever,
 * or that it will actually fix any issue resulting from it.
 *
 * Thank you for your understanding.
 */

/**
 * @category Inchoo
 * @package Inchoo_Prevnext
 * @author Branko Ajzele <ajzele@gmail.com, http://foggyline.net>
 * @copyright Inchoo <http://inchoo.net>
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Inchoo_Prevnext_Helper_Data extends Mage_Core_Helper_Abstract
{

    protected $_ProdPositions = array();

	/**
     * @return Product_id or empty array
	 * Updates SNH 30jul2015
     */
    public function detectProductPosition()
    {
		if ($this->_ProdPositions) {
			return $this->_ProdPositions;
		}
		
		$positions = array();
			
		if (Mage::registry('current_category')) {
			$categoryId = Mage::registry('current_category')->getId();
			$category = new Varien_Object(array('id'=>$categoryId));
			$positions = array_keys(Mage::getResourceModel('catalog/category')->getProductsPosition($category));
		} else {
			$categoryIds = Mage::registry('current_product')->getCategoryIds();
			if (!empty($categoryIds)) {
				$categoryId = current($categoryIds);
				$category = new Varien_Object(array('id'=>$categoryId));
				$positions = array_reverse(array_keys(Mage::getResourceModel('catalog/category')->getProductsPosition($category)));
				// $positions = Mage::getResourceModel('catalog/category')->getProductsPosition($category);
				Mage::register('current_category', $category); // to speed up next prev/next click
			}
		}
	
		$this->_ProdPositions = $positions;

		return $positions;
	}
	
	
	/**
     * @return Mage_Catalog_Model_Product or FALSE
     */
    public function getPreviousProduct()
    {
        $prodId = Mage::registry('current_product')->getId();
        $positions = Mage::getSingleton('core/session')->getInchooFilteredCategoryProductCollection();

		if (!$positions) {
			$positions = $this->detectProductPosition();
		}

		$cpk = array_search($prodId, $positions);

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
			$positions = $this->detectProductPosition();
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
