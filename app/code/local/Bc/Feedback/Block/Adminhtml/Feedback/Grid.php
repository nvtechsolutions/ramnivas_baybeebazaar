<?php

class Bc_Feedback_Block_Adminhtml_Feedback_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('feedbackGrid');
      $this->setDefaultSort('id');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('feedback/feedback')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();

  }

  protected function _prepareColumns()
  {
      $this->addColumn('id', array(
          'header'    => Mage::helper('feedback')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'id',
      ));

      $this->addColumn('email', array(
          'header'    => Mage::helper('feedback')->__('Email Id'),
          'align'     =>'left',
          'index'     => 'email',
      ));
      
      $this->addColumn('subject', array(
          'header'    => Mage::helper('feedback')->__('Subject'),
          'align'     =>'left',
          'index'     => 'subject',
      ));
      
      $this->addColumn('detail', array(
          'header'    => Mage::helper('feedback')->__('Details'),
          'align'     =>'left',
          'index'     => 'detail',
      ));
      
      $this->addColumn('created_at', array(
          'header'    => Mage::helper('feedback')->__('Created At'),
          'align'     =>'left',
          'index'     => 'created_at',
          'type'      => 'datetime',



      ));

      
      
      
		
		$this->addExportType('*/*/exportCsv', Mage::helper('feedback')->__('CSV'));
	  
      return parent::_prepareColumns();
  }
	
	

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('feedback');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('feedback')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('feedback')->__('Are you sure?')
        ));

        //$statuses = Mage::getSingleton('feedback/status')->getOptionArray();

        //array_unshift($statuses, array('label'=>'', 'value'=>''));
        
        return $this;
    }

  // public function getRowUrl($row)
  // {
  //     return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  // }

}
