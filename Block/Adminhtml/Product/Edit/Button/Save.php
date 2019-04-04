<?php
/**
 * qbees_custom_catalog
 * @copyright Copyright © 2019 QBees. All rights reserved.
 * @author    andrey008cot@gmail.com
 */
namespace QBees\CustomCatalog\Block\Adminhtml\Product\Edit\Button;

use Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Save as CatalogSave;

/**
 * Class Save
 *
 * @package QBees\CustomCatalog\Block\Adminhtml\Product\Edit\Button
 */
class Save extends CatalogSave
{
    const CUSTOM_CATALOG_BACK_URL = 'customcatalog/*';

    /**
     * @param array $buttonData
     *
     * @return array
     */
    public function getUIButtonDataWithCustomRedirectParam($buttonData)
    {
        $isButtonActionProvided = isset($buttonData['data_attribute']['mage-init']['buttonAdapter']['actions']);

        if ($isButtonActionProvided) {
            $buttonActions = &$buttonData['data_attribute']['mage-init']['buttonAdapter']['actions'];

            foreach ($buttonActions as &$buttonAction) {
                if (!isset($buttonAction['params'])) {
                    $buttonAction['params'] = [];
                }

                $buttonAction['params'][]['customcatalogref'] = true;
            }
        }

        return $buttonData;
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        $isCustomCatalogReferrer = $this->context
            ->getRequestParam('customcatalogref');

        $buttonData = parent::getButtonData();

        if (!$isCustomCatalogReferrer) {
            return $buttonData;
        }

        return $this->getUIButtonDataWithCustomRedirectParam($buttonData);
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        $buttonOptions = parent::getOptions();

        return array_map(function ($optionData) {
            return $this->getUIButtonDataWithCustomRedirectParam($optionData);
        }, $buttonOptions);
    }
}