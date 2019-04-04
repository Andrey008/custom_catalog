<?php
/**
 * qbees_custom_catalog
 * @copyright Copyright © 2019 QBees. All rights reserved.
 * @author    andrey008cot@gmail.com
 */
namespace QBees\CustomCatalog\Controller\Adminhtml\Product;

use QBees\CustomCatalog\Controller\Adminhtml\Product;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Api\ProductRepositoryInterface as ProductRepository;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class InlineEdit
 *
 * @package QBees\CustomCatalog\Controller\Adminhtml\Product
 */
class InlineEdit extends Product
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @param Context $context
     * @param ProductRepository $productRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        ProductRepository $productRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);

        $this->productRepository = $productRepository;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @return ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        /** @var Json $resultJson */
        $resultJson = $this->jsonFactory->create();

        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);

            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $entityId) {
                    $product = $this->productRepository->getById($entityId);

                    try {
                        $product->setData(
                            array_merge($product->getData(), $postItems[$entityId])
                        );

                        $this->productRepository->save($product);
                    } catch (\Exception $e) {
                        $messages[] = $e->getMessage();

                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}
