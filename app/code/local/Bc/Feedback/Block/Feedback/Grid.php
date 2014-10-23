<?php

class Bc_Feedback_Block_Adminhtml_Feedback_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('feedbackGrid');
      $this->setDefaultSort('updated_at');
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
      $this->addColumn('feedback_id', array(
          'header'    => Mage::helper('feedback')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'feedback_id',
      ));

      $this->addColumn('age', array(
          'header'    => Mage::helper('feedback')->__('SKU'),
          'align'     =>'left',
          'index'     => 'age',
      ));
      
      $this->addColumn('comingfrom', array(
          'header'    => Mage::helper('feedback')->__('Qty'),
          'align'     =>'left',
          'index'     => 'comingfrom',
      ));
      
      $this->addColumn('created_at', array(
          'header'    => Mage::helper('feedback')->__('Created At'),
          'align'     =>'left',
          'index'     => 'created_at',
          'type'      => 'datetime',



      ));

      $this->addColumn('updated_at', array(
          'header'    => Mage::helper('feedback')->__('Last Modified'),
          'align'     =>'left',
          'index'     => 'updated_at',
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

        $statuses = Mage::getSingleton('feedback/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('feedback')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('feedback')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  // public function getRowUrl($row)
  // {
  //     return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  // }

}
