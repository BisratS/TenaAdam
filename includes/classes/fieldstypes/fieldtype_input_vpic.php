<?php

/**
 * Этот файл является частью программы "CRM Руководитель" - конструктор CRM систем для бизнеса
 * https://www.rukovoditel.net.ru/
 * 
 * CRM Руководитель - это свободное программное обеспечение, 
 * распространяемое на условиях GNU GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
 * 
 * Автор и правообладатель программы: Харчишина Ольга Александровна (RU), Харчишин Сергей Васильевич (RU).
 * Государственная регистрация программы для ЭВМ: 2023664624
 * https://fips.ru/EGD/3b18c104-1db7-4f2d-83fb-2d38e1474ca3
 */

class fieldtype_input_vpic
{
  public $options;
  
  function __construct()
  {
    $this->options = array('title' => TEXT_FIELDTYPE_INPUT_VPIC_TITLE);
  }
  
  function get_configuration()
  {
    $cfg = array();
    
    $cfg[] = array('title'=>TEXT_VPIC_AUTO_FILL_FIELDS, 'name'=>'auto_fill_fields','type'=>'checkbox','tooltip_icon'=>TEXT_VPIC_AUTO_FILL_FIELDS_TIP);
    
    $cfg[] = array('title'=>TEXT_VPIC_OTHER_DETAILS, 'name'=>'other_details','type'=>'input','tooltip_icon'=>TEXT_VPIC_OTHER_DETAILS_TIP,'params'=>array('class'=>'form-control'));
    
    $cfg[] = array('title'=>TEXT_ALLOW_SEARCH, 'name'=>'allow_search','type'=>'checkbox','tooltip_icon'=>TEXT_ALLOW_SEARCH_TIP);
                                
    $cfg[] = array('title'=>TEXT_HIDE_FIELD_IF_EMPTY, 'name'=>'hide_field_if_empty','type'=>'checkbox','tooltip_icon'=>TEXT_HIDE_FIELD_IF_EMPTY_TIP);
    
    $cfg[] = array('title' => TEXT_IS_UNIQUE_FIELD_VALUE, 'name' => 'is_unique', 'type' => 'dropdown', 'choices' => fields_types::get_is_unique_choices(_POST('entities_id')), 'tooltip_icon' => TEXT_IS_UNIQUE_FIELD_VALUE_TIP, 'params' => array('class' => 'form-control input-large'));
    $cfg[] = array('title'=>TEXT_ERROR_MESSAGE, 'name'=>'unique_error_msg','type'=>'input','tooltip_icon'=>TEXT_UNIQUE_FIELD_VALUE_ERROR_MSG_TIP,'tooltip'=>TEXT_DEFAULT . ': ' . TEXT_UNIQUE_FIELD_VALUE_ERROR,'params'=>array('class'=>'form-control input-xlarge'));
                                            
    return $cfg;
  }
  
  function render($field,$obj,$params = array())
  {
    $cfg =  new fields_types_cfg($field['configuration']);
    
    $attributes = array('class'=>'form-control input-medium'. 
                                 ' fieldtype_input field_' . $field['id'] . 
                                 ($field['is_required']==1 ? ' required noSpace':'') .
                                 ($cfg->get('is_unique')>0 ? ' is-unique':''),
    										'maxlength'=>17,
                        );
    
    $attributes = fields_types::prepare_uniquer_error_msg_param($attributes,$cfg);
    
    $html ='
    		<div class="input-group input-medium">'. 
	    		input_tag('fields[' . $field['id'] . ']',$obj['field_' . $field['id']],$attributes) . 
	    		'<div class="input-group-btn">  			
	        		<button type="button" title="' . TEXT_DECODE_VIN . '" class="btn btn-default vpic-vin-decoder" data-field-id="' . $field['id'] .'" data-toggle="dropdown"><i class="fa fa-search"></i></button>
	        				<div class="dropdown-menu hold-on-click dropdown-checkboxes" role="menu">
  									<div id="field_' . $field['id'] . '_vin_data">
  											
  									</div>	  			
	  			        </div>
  			
	  		   </div>
	    	</div>
	      ';
    
    return $html;
  }
  
  function process($options)
  {
    return db_prepare_input($options['value']);
  }
  
  function output($options)
  {
  	if(isset($options['is_export']))
  	{
  		return $options['value'];
  	}
  	else
  	{
  		if($options['field']['is_heading'])
  		{
  			return $options['value'];
  		}
  		else
  		{
  			return '<a href="https://vpic.nhtsa.dot.gov/decoder/Decoder?VIN=' . $options['value'] . '" target="blank">' . $options['value']. '</a>';
  		}
  	}
    
  }
}