<?php

namespace Redepy\GDPR\Controller\Adminhtml\CookieGroup;

use Redepy\GDPR\Controller\Adminhtml\AbstractCookieGroup;
use Redepy\GDPR\Model\Repository\CookieGroupsRepository;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;

class Edit extends AbstractCookieGroup
{
    /**
     * @var CookieGroupsRepository
     */
    private $cookieGroupsRepository;

    /**
     * @param Context $context
     * @param CookieGroupsRepository $cookieGroupsRepository
     */
    public function __construct(
        Context                $context,
        CookieGroupsRepository $cookieGroupsRepository
    ) {
        parent::__construct($context);
        $this->cookieGroupsRepository = $cookieGroupsRepository;
    }

    /**
     * @return Page
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute() {
        $id = (int)$this->getRequest()->getParam('id');

        $title = __('New Cookie Group');

        if ($id) {
            $model = $this->cookieGroupsRepository->getById($id);
            $title = __('Edit Cookie Group %1', $model->getName());
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        if (!$id) {
            $resultPage->getLayout()->unsetElement('store_switcher');
        }

        $resultPage->setActiveMenu('Redepy_GDPR::cookie_group');
        $resultPage->addBreadcrumb(__('Cookies'), __('Cookies'));
        $resultPage->getConfig()->getTitle()->prepend($title);

        return $resultPage;
    }
}
