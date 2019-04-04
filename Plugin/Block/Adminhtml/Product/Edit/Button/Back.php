<?php
/**
 * qbees_custom_catalog
 * @copyright Copyright © 2019 QBees. All rights reserved.
 * @author    andrey008cot@gmail.com
 */
namespace QBees\CustomCatalog\Plugin\Block\Adminhtml\Product\Edit\Button;

use Magento\Framework\App\RequestInterface as Request;
use Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Back as CatalogBack;
use Magento\Framework\UrlInterface as UrlBuilder;

/**
 * Class Back
 *
 * @package QBees\CustomCatalog\Plugin\Block\Adminhtml\Product\Edit\Button
 */
class Back
{
    const CUSTOM_CATALOG_BACK_URL = 'customcatalog/*';

    /**
     * @var Request
     */
    private $request;

    /**
     * @var UrlBuilder
     */
    private $urlBuilder;

    /**
     * @param Request $request
     * @param UrlBuilder $urlBuilder
     */
    public function __construct(
        Request $request,
        UrlBuilder $urlBuilder
    ) {
        $this->request = $request;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param array $buttonData
     *
     * @return array
     */
    public function getUIButtonDataWithCustomRedirect($buttonData)
    {
        return array_merge(
            $buttonData,
            [
                'on_click' => sprintf(
                    "location.href = '%s';",
                    $this->urlBuilder->getUrl(self::CUSTOM_CATALOG_BACK_URL)
                ),
            ]
        );
    }

    /**
     * @param CatalogBack $subject
     * @param array $buttonData
     *
     * @return array
     */
    public function afterGetButtonData(CatalogBack $subject, $buttonData)
    {
        $isCustomCatalogReferrer = $this->request->getParam('customcatalogref');

        if (!$isCustomCatalogReferrer) {
            return $buttonData;
        }

        return $this->getUIButtonDataWithCustomRedirect($buttonData);
    }
}