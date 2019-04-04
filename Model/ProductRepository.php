<?php
/**
 * qbees_custom_catalog
 * @copyright Copyright © 2019 QBees. All rights reserved.
 * @author    andrey008cot@gmail.com
 */
namespace QBees\CustomCatalog\Model;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;

/**
 * Class ProductRepository
 * @package QBees\CustomCatalog\Model
 */
class ProductRepository
{

    const CUSTOM_CATALOG_UPDATE_TOPIC = "customcatalog.update.product.queue.topic";
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var PublisherInterface
     */
    private $publisher;
    /**
     * @var Json
     */
    private $serializer;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ProductRepository constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param StoreManagerInterface $storeManager
     * @param PublisherInterface $publisher
     * @param Json $serializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        StoreManagerInterface $storeManager,
        PublisherInterface $publisher,
        Json $serializer,
        LoggerInterface $logger
    ) {
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeManager = $storeManager;
        $this->publisher = $publisher;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * Get products with filter.
     *
     * @param string $vpn
     * @return \Magento\Catalog\Api\Data\ProductInterface[]
     */
    public function getByVPN($vpn)
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('vpn', $vpn)->create();
        $products = $this->productRepository->getList($searchCriteria);
        return $products->getItems();
    }

    /**
     * @param array|bool $data
     */
    public function update($data)
    {
        $payloadData = $this->serializer->unserialize($data);

        try {
            $productData = $payloadData['data'];
            $productId = $productData['entity_id'];
            $storeId = $payloadData['options']['store_id'];

            $product = $this->productRepository->getById($productId, false, $storeId);

            $this->productRepository->save($product->addData($productData));
        } catch (\Exception $e) {
            $this->logger->error($e, $payloadData);
        }
    }

    /**
     * @param $productId
     * @param $vpn
     * @param $copyWriteInfo
     * @param null $storeId
     */
    public function updateRequest($productId, $vpn, $copyWriteInfo, $storeId = null){
        if(!$storeId){
            $storeId = $this->storeManager->getStore()->getId();
        }
        if($product = $this->productRepository->getById($productId)){
            $data = [
                'data' => [
                    'entity_id' => $productId,
                    'vpn' => $vpn,
                    'copy_write_info' => $copyWriteInfo,
                ],
                'options' => [
                    'store_id' => $storeId
                ]
            ];
            $this->publisher->publish(
                self::CUSTOM_CATALOG_UPDATE_TOPIC,
                $this->serializer->serialize($data)
            );
        }
    }
}