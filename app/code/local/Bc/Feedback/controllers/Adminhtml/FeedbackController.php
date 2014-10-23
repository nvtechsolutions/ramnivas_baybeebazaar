<?php

class Bc_Feedback_Adminhtml_FeedbackController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('feedback/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('feedback/feedback')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('feedback_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('feedback/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('feedback/adminhtml_feedback_edit'))
				->_addLeft($this->getLayout()->createBlock('feedback/adminhtml_feedback_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('feedback')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			$data['created_at'] = NOW();
			$data['updated_at'] = NOW();
			try {
				$item = Mage::getModel('feedback/feedback')->load($data['sku'],'sku');
				if($item->getFeedbackId())
				{
					$newqty = $item->getQty().",".$data['qty'];
					$item->setQty($newqty);
					$item->setUpdatedAt($data['updated_at']);
				}
				else
				{
					$item->setQty($data['qty']);
					$item->setSku($data['sku']);
					$item->setUpdatedAt($data['updated_at']);
					$item->setCreatedAt($data['created_at']);
					
				}				
				$item->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('feedback')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $item->getFeedbackId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('feedback')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('feedback/feedback');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $feedbackIds = $this->getRequest()->getParam('feedback');
        if(!is_array($feedbackIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($feedbackIds as $feedbackId) {
                    $feedback = Mage::getModel('feedback/feedback')->load($feedbackId);
                    $feedback->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($feedbackIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $feedbackIds = $this->getRequest()->getParam('feedback');
        if(!is_array($feedbackIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($feedbackIds as $feedbackId) {
                    $feedback = Mage::getSingleton('feedback/feedback')
                        ->load($feedbackId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($feedbackIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    
    public function exportCsvAction()
    {
        $fileName   = 'feedback_report.csv';
        $content    = $this->getLayout()->createBlock('feedback/adminhtml_feedback_grid')->setIsExport(true)
            ->getCsv();

        $this->_prepareDownloadResponse($fileName, $content);
    }
}
