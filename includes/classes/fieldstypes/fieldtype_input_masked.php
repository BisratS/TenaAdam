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

class fieldtype_input_masked
{
  public $options;
  
  function __construct()
  {
    $this->options = array('title' => TEXT_FIELDTYPE_INPUT_MASKED);
  }
  
  function get_configuration()
  {
    $cfg = array();
    
    $cfg[] = array('title'=>TEXT_ALLOW_SEARCH, 'name'=>'allow_search','type'=>'checkbox','tooltip_icon'=>TEXT_ALLOW_SEARCH_TIP);
    
    $cfg[] = array('title'=>TEXT_WIDHT, 
                   'name'=>'width',
                   'type'=>'dropdown',
                   'choices'=>array('input-small'=>TEXT_INPTUT_SMALL,'input-medium'=>TEXT_INPUT_MEDIUM,'input-large'=>TEXT_INPUT_LARGE,'input-xlarge'=>TEXT_INPUT_XLARGE),
                   'tooltip_icon'=>TEXT_ENTER_WIDTH,
                   'params'=>array('class'=>'form-control input-medium'));
                   
    $cfg[] = array('title'=>TEXT_INPUT_FIELD_MASK, 'name'=>'mask','type'=>'input','tooltip'=>TEXT_INPUT_FIELD_MASK_TIP,'params'=>array('class'=>'form-control'));
    
    $cfg[] = array('title'=>TEXT_INPUT_FIELD_MASK_DEFINITIONS, 'name'=>'mask_definitions','type'=>'textarea','tooltip_icon'=>TEXT_INPUT_FIELD_MASK_DEFINITIONS_TIP_ICON,'tooltip'=>TEXT_INPUT_FIELD_MASK_DEFINITIONS_TIP,'params'=>array('class'=>'form-control'));
    
    $cfg[] = array('title' => TEXT_IS_UNIQUE_FIELD_VALUE, 'name' => 'is_unique', 'type' => 'dropdown', 'choices' => fields_types::get_is_unique_choices(_POST('entities_id')), 'tooltip_icon' => TEXT_IS_UNIQUE_FIELD_VALUE_TIP, 'params' => array('class' => 'form-control input-large'));
    $cfg[] = array('title'=>TEXT_ERROR_MESSAGE, 'name'=>'unique_error_msg','type'=>'input','tooltip_icon'=>TEXT_UNIQUE_FIELD_VALUE_ERROR_MSG_TIP,'tooltip'=>TEXT_DEFAULT . ': ' . TEXT_UNIQUE_FIELD_VALUE_ERROR,'params'=>array('class'=>'form-control input-xlarge'));
    
    return $cfg;
  }
  
  function render($field,$obj,$params = array())
  {
    $cfg =  new fields_types_cfg($field['configuration']);
    
    $attributes = array('class'=>'form-control ' . $cfg->get('width') . 
                        ' fieldtype_input field_' . $field['id'] . 
                        ($field['is_required']==1 ? ' required':'') . 
                        ($cfg->get('is_unique')>0 ? ' is-unique':''),
                        );
    
    $attributes = fields_types::prepare_uniquer_error_msg_param($attributes,$cfg);
    
    $script = '';
    
    if(strlen($cfg->get('mask'))>0)
    {
    	$mask_definitions = '';
    	if(strlen($cfg->get('mask_definitions')))
    	{    	    		    	
    		foreach(explode("\n",$cfg->get('mask_definitions')) as $v)
    		{    		
    			$vv = explode('=',$v,2);    			    		
    			$mask_definitions .= "$.mask.definitions['" . trim($vv[0]) . "']='" . trim($vv[1]) . "';\n";
    		}
    	}
    	    	     	
      $script = '
        <script>
          jQuery(function($){   
      			 ' . $mask_definitions . '	
             $(".field_' . $field['id'] . '").mask("' . $cfg->get('mask') . '");                 
          });
        </script>
      ';
    }
    
    return input_tag('fields[' . $field['id'] . ']',$obj['field_' . $field['id']],$attributes) . $script;
  }
  
  function process($options)
  {
    return db_prepare_input($options['value']);
  }
  
  function output($options)
  {
    return $options['value'];
  }
}