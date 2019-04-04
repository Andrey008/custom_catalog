<?php
/**
 * qbees_custom_catalog
 * @copyright Copyright © 2019 QBees. All rights reserved.
 * @author    andrey008cot@gmail.com
 */
namespace QBees\CustomCatalog\Plugin\Controller\Adminhtml\Product;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Catalog\Controller\Adminhtml\Product\Save as CatalogSaveController;
use Magento\Framework\Registry;

/**
 * Class Save
 *
 * @package QBees\CustomCatalog\Plugin\Controller\Adminhtml\Product
 */
class Save
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(
        Registry $registry
    ) {
        $this->registry = $registry;
    }

    /**
     * @param CatalogSaveController $subject
     * @param $resultRedirect
     * @return Redirect
     */
    private function getCustomRedirect(CatalogSaveController $subject, $resultRedirect) {
        $product = $this->registry->registry('product');
        $productId = $product->getId();
        $redirectBack = $subject->getRequest()->getParam('back', false);

        $isProductSaved = $product->getOrigData('entity_id');
        if (!$isProductSaved) {
            $redirectBack = 'new';
        }

        $productAttributeSetId = $subject->getRequest()->getParam('set');
        $productTypeId = $subject->getRequest()->getParam('type');
        $storeId = $subject->getRequest()->getParam('store', 0);

        if ($redirectBack === 'new') {
            $resultRedirect->setPath(
                'catalog/*/new',
                [
                    'set' => $productAttributeSetId,
                    'type' => $productTypeId,
                    'customcatalogref' => true
                ]
            );
        } elseif ($redirectBack === 'duplicate' && isset($newProduct)) {
            $resultRedirect->setPath(
                'catalog/*/edit',
                [
                    'id' => $newProduct->getEntityId(),
                    'back' => null,
                    '_current' => true,
                    'customcatalogref' => true
                ]
            );
        } elseif ($redirectBack) {
            $resultRedirect->setPath(
                'catalog/*/edit',
                [
                    'id' => $productId,
                    '_current' => true,
                    'set' => $productAttributeSetId,
                    'customcatalogref' => true
                ]
            );
        } else {
            $resultRedirect->setPath('customcatalog/*/', ['store' => $storeId]);
        }

        return $resultRedirect;
    }

    /**
     * @param CatalogSaveController $subject
     * @param Redirect $resultRedirect
     *
     * @return Redirect
     */
    public function afterExecute(CatalogSaveController $subject, $resultRedirect)
    {
        $isCustomCatalogReferrer = $subject->getRequest()->getParam('customcatalogref');

        if (!$isCustomCatalogReferrer) {
            return $resultRedirect;
        }

        return $this->getCustomRedirect($subject, $resultRedirect);
    }
}