<?php
/**
 * qbees_custom_catalog
 * @copyright Copyright © 2019 QBees. All rights reserved.
 * @author    andrey008cot@gmail.com
 */
namespace QBees\CustomCatalog\Controller\Adminhtml\Product;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Api\ProductRepositoryInterface as ProductRepository;
use QBees\CustomCatalog\Controller\Adminhtml\Product;

/**
 * Class Delete
 *
 * @package QBees\CustomCatalog\Controller\Adminhtml\Product
 */
class Delete extends Product
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @param Context $context
     * @param ProductRepository $productRepository
     */
    public function __construct(
        Context $context,
        ProductRepository $productRepository
    ) {
        parent::__construct($context);

        $this->productRepository = $productRepository;
    }

    /**
     * @return Redirect
     * @throws \Exception
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $id = $this->getRequest()->getParam('entity_id');

        if ($id) {
            try {
                $this->productRepository->deleteById($id);

                $this->messageManager->addSuccessMessage(
                    __('You deleted the product.')
                );

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
            }
        }

        $this->messageManager->addErrorMessage(
            __('We can\'t find a product to delete.')
        );

        return $resultRedirect->setPath('*/*/');
    }
}