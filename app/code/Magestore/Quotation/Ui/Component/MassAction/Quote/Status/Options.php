<?php

/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\Quotation\Ui\Component\MassAction\Quote\Status;

use Magento\Framework\UrlInterface;
use Zend\Stdlib\JsonSerializable;
use Magestore\Quotation\Model\Source\Quote\Status as QuoteStatus;

/**
 * Class Options
 * @package Magestore\Quotation\Ui\Component\MassAction\Quote\Status
 */
class Options implements JsonSerializable
{
    /**
     * @var array
     */
    protected $_options;

    /**
     * Additional options params
     *
     * @var array
     */
    protected $_data;

    /**
     * @var UrlInterface
     */
    protected $_urlBuilder;

    /**
     * Base URL for subactions
     *
     * @var string
     */
    protected $_urlPath;

    /**
     * Param name for subactions
     *
     * @var string
     */
    protected $_paramName;

    /**
     * Additional params for subactions
     *
     * @var array
     */
    protected $_additionalData = [];

    /**
     *
     * @param UrlInterface $urlBuilder
     * @param array $data
     */
    public function __construct(
        UrlInterface $urlBuilder,
        array $data = []
    ) {
        $this->_data = $data;
        $this->_urlBuilder = $urlBuilder;
    }

    /**
     * Get action options
     *
     * @return array
     */
    public function jsonSerialize()
    {
        if ($this->_options === null) {
            $options = QuoteStatus::getOptionArray();
            $this->prepareData();
            foreach ($options as $value => $label) {
                $this->_options[$value] = [
                    'type' => 'status_' . $value,
                    'label' => $label,
                ];

                if ($this->_urlPath && $this->_paramName) {
                    $this->_options[$value]['url'] = $this->_urlBuilder->getUrl(
                        $this->_urlPath,
                        [$this->_paramName => $value]
                    );
                }

                $this->_options[$value] = array_merge_recursive(
                    $this->_options[$value],
                    $this->_additionalData
                );
            }
            $this->_options = array_values($this->_options);
        }
        return $this->_options;
    }

    /**
     * Prepare addition data for subactions
     *
     * @return void
     */
    protected function prepareData()
    {
        foreach ($this->_data as $key => $value) {
            switch ($key) {
                case 'urlPath':
                    $this->_urlPath = $value;
                    break;
                case 'paramName':
                    $this->_paramName = $value;
                    break;
                default:
                    $this->_additionalData[$key] = $value;
                    break;
            }
        }
    }
}
