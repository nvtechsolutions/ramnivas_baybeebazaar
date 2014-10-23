<?php

class Bc_Feedback_Block_Adminhtml_Feedback_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('feedback_form', array('legend'=>Mage::helper('feedback')->__('Item information')));
     
      $fieldset->addField('sku', 'text', array(
          'label'     => Mage::helper('feedback')->__('SKU'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'sku',
      ));

      $fieldset->addField('qty', 'text', array(
          'label'     => Mage::helper('feedback')->__('Qty'),
          'required'  => false,
          'name'      => 'qty',
	  ));
	  
      if ( Mage::getSingleton('adminhtml/session')->getFeedbackData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getFeedbackData());
          Mage::getSingleton('adminhtml/session')->setFeedbackData(null);
      } elseif ( Mage::registry('feedback_data') ) {
          $form->setValues(Mage::registry('feedback_data')->getData());
      }
      return parent::_prepareForm();
  }
}
