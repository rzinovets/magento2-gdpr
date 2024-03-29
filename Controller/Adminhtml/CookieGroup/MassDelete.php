<?php

namespace Redepy\GDPR\Controller\Adminhtml\CookieGroup;

use Redepy\GDPR\Api\CookieGroupsRepositoryInterface;
use Redepy\GDPR\Controller\Adminhtml\AbstractCookieGroup;
use Redepy\GDPR\Model\ResourceModel\CookieGroup\Collection;
use Redepy\GDPR\Model\ResourceModel\CookieGroup\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Psr\Log\LoggerInterface;

class MassDelete extends AbstractCookieGroup
{
    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var CollectionFactory
     */
    private $cookieGroupCollectionFactory;

    /**
     * @var CookieGroupsRepositoryInterface
     */
    private $cookieGroupRepository;

    /**
     * @param Action\Context $context
     * @param Filter $filter
     * @param LoggerInterface $logger
     * @param CollectionFactory $cookieGroupCollectionFactory
     * @param CookieGroupsRepositoryInterface $cookieGroupRepository
     */
    public function __construct(
        Action\Context                  $context,
        Filter                          $filter,
        LoggerInterface                 $logger,
        CollectionFactory               $cookieGroupCollectionFactory,
        CookieGroupsRepositoryInterface $cookieGroupRepository
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->logger = $logger;
        $this->cookieGroupCollectionFactory = $cookieGroupCollectionFactory;
        $this->cookieGroupRepository = $cookieGroupRepository;
    }

    /**
     * @throws LocalizedException
     */
    public function execute() {
        $this->filter->applySelectionOnTargetProvider();

        /** @var Collection $collection */
        $collection = $this->filter->getCollection($this->cookieGroupCollectionFactory->create());
        $deletedCookieGroups = 0;

        if ($collection->count() > 0) {
            try {
                foreach ($collection->getItems() as $cookieGroup) {
                    $this->cookieGroupRepository->delete($cookieGroup);
                    $deletedCookieGroups++;
                }

                $this->messageManager->addSuccessMessage(
                    __('%1 cookie group(s) has been successfully deleted', $deletedCookieGroups)
                );

            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('An error has occurred'));
                $this->logger->critical($e);
            }
        }

        $this->_redirect($this->_redirect->getRefererUrl());
    }
}
