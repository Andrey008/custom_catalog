<?php
/**
 * qbees_custom_catalog
 * @copyright Copyright © 2019 QBees. All rights reserved.
 * @author    andrey008cot@gmail.com
 */
namespace QBees\CustomCatalog\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Data
 *
 * @package QBees\CustomCatalog\Helper
 */
class Data extends AbstractHelper
{
    const VPN_ATTRIBUTE_CODE = 'vpn';
    const COPYWRITE_INFO_ATTRIBUTE_CODE = 'copywrite_info';
    const PRODUCT_MESSAGE_QUEUE_TOPIC = 'customcatalog.update.product.request';
    const REQUEST_PARAM_CUSTOM_CATALOG_FLAG = 'customcatalogref';
}
