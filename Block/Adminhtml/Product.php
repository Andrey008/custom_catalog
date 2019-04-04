<?php
/**
 * qbees_custom_catalog
 * @copyright Copyright © 2019 QBees. All rights reserved.
 * @author    andrey008cot@gmail.com
 */
namespace QBees\CustomCatalog\Block\Adminhtml;

use Magento\Catalog\Block\Adminhtml\Product as CatalogProduct;

/**
 * Class Product
 *
 * @package QBees\CustomCatalog\Block\Adminhtml
 */
class Product extends CatalogProduct
{
    /**
     * @param string $type
     * @return string
     */
    protected function _getProductCreateUrl($type)
    {
        return $this->getUrl(
            'catalog/*/new',
            [
                'set' => $this->_productFactory->create()->getDefaultAttributeSetId(),
                'type' => $type,
                'customcatalogref' => true
            ]
        );
    }
}