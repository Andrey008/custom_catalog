<?php
/**
 * qbees_custom_catalog
 * @copyright Copyright © 2019 QBees. All rights reserved.
 * @author    andrey008cot@gmail.com
 */
namespace QBees\CustomCatalog\Api;

/**
 * Interface ProductInterface
 * @package QBees\CustomCatalog\Api
 */
interface ProductRepositoryInterface
{
    /**
     * @param string $vpn
     * @return \Magento\Catalog\Api\Data\ProductInterface[]
     */
    public function getByVPN($vpn);

    /**
     * @param int $productId
     * @param string $vpn
     * @param string $copyWriteInfo
     * @return string
     */
    public function updateRequest($productId, $vpn, $copyWriteInfo);

    /**
     * @param $data
     * @return string
     */
    public function update($data);
}