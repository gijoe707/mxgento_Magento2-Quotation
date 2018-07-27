<?php
/**
 * Copyright © Magestore, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\Quotation\Model\Quote\Email;

class SenderBuilder extends \Magento\Sales\Model\Order\Email\SenderBuilder
{
    /**
     * @return TransportBuilder
     */
    public function getTransportBuilder(){
        return $this->transportBuilder;
    }

    /**
     * @return string
     */
    public function getEmailHtml(){
        $this->configureEmailTemplate();
        $this->transportBuilder->addTo(
            $this->identityContainer->getCustomerEmail(),
            $this->identityContainer->getCustomerName()
        );
        return $this->transportBuilder->getContentHtml();
    }
}
