<?php

namespace Reviewscouk\Reviews\Model\Config\Source;

use Magento\Framework as Framework;

class Region implements Framework\Option\ArrayInterface
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'UK', 'label' => __('UK')],
            ['value' => 'US', 'label' => __('US')]
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'UK' => __('UK'),
            'US' => __('US')
        ];
    }
}
