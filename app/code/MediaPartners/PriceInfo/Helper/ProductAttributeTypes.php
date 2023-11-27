<?php
declare(strict_types=1);

namespace MediaPartners\PriceInfo\Helper;

class ProductAttributeTypes
{
    const PRODUCT_DETAILED_0 = [
        'sku' => 'sku',
        'url' => 'url_key',
//        'manufacturer',
//        'model',
//        'ean',
        'price' => 'price',
//        'availability',
//        'itemsAvailable',
        'updated' => 'updated_at',
    ];

    const PRODUCT_DETAILED_1 = [
        'name',
        'description',
        'category',
        'itemCondition',
        'delivery'
    ];

    public function getRequestedData($itemData) {
        $data = [];

        foreach (self::PRODUCT_DETAILED_0 as $key => $attribute) {
            $data[$key] = $itemData[$attribute];
        }

        return $data;
    }
}
