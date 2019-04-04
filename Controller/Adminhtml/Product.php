<?php
/**
 * qbees_custom_catalog
 * @copyright Copyright © 2019 QBees. All rights reserved.
 * @author    andrey008cot@gmail.com
 */
namespace QBees\CustomCatalog\Controller\Adminhtml;

use Magento\Backend\App\Action;

/**
 * Class Product
 *
 * @package QBees\CustomCatalog\Controller\Adminhtml
 */
abstract class Product extends Action
{
    const ADMIN_RESOURCE = 'QBees_CustomCatalog::product';
}
