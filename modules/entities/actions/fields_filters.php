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

$fields_info_query = db_query("select * from app_fields where id='" . $_GET['fields_id']. "'");
if(!$fields_info = db_fetch_array($fields_info_query))
{
	redirect_to('entities/fields','entities_id=' . $_GET['entities_id']);
}	

$cfg = new fields_types_cfg($fields_info['configuration']);

$entity_id = _get::int('entities_id');

switch($fields_info['type'])
{
    case 'fieldtype_related_records':
        $reports_type = 'related_items_' . $_GET['fields_id'];
        break;
    default:
        $reports_type = 'fieldfilter' . $_GET['fields_id'];
        break;
}

 
$reports_info_query = db_query("select * from app_reports where entities_id='" . db_input($entity_id). "' and reports_type='{$reports_type}'");
if(!$reports_info = db_fetch_array($reports_info_query))
{
  $sql_data = array('name'=>'',
                    'entities_id'=>$entity_id,
                    'reports_type'=>$reports_type,                                              
                    'in_menu'=>0,
                    'in_dashboard'=>0,
                    'created_by'=>0,
                    );
  db_perform('app_reports',$sql_data);
  
  redirect_to('entities/fields_filters','entities_id=' . $_GET['entities_id']. '&fields_id=' . $_GET['fields_id']);
}  

switch($app_module_action)
{
  case 'save':
    
    $values = '';
    
    if(isset($_POST['values']))
    {
      if(is_array($_POST['values']))
      {
        $values = implode(',',$_POST['values']);
      }
      else
      {
        $values = $_POST['values'];
      }
    }
    $sql_data = array('reports_id'=>$_GET['reports_id'],
                      'fields_id'=>$_POST['fields_id'],
                      'filters_condition'=>$_POST['filters_condition'],                                              
                      'filters_values'=>$values,
                      );
        
    if(isset($_GET['id']))
    {        
      db_perform('app_reports_filters',$sql_data,'update',"id='" . db_input($_GET['id']) . "'");       
    }
    else
    {               
      db_perform('app_reports_filters',$sql_data);                  
    }
        
    redirect_to('entities/fields_filters','reports_id=' . $_GET['reports_id'] . '&entities_id=' . $_GET['entities_id']. '&fields_id=' . $_GET['fields_id']);      
  break;
  case 'delete':
      if(isset($_GET['id']))
      {      

        db_query("delete from app_reports_filters where id='" . db_input($_GET['id']) . "'");
                            
        $alerts->add(TEXT_WARN_DELETE_FILTER_SUCCESS,'success');
     
                
        redirect_to('entities/fields_filters','reports_id=' . $_GET['reports_id'] . '&entities_id=' . $_GET['entities_id']. '&fields_id=' . $_GET['fields_id']);  
      }
    break;   
}
