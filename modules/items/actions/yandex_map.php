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

switch($app_module_action)
{
    case 'save_value_in':
        $filed_id = _post::int('filed_id');
        $value =(is_numeric($_POST['value']) ? $_POST['value'] : 0);

        $item_info_query = db_query("select field_{$filed_id} from app_entity_{$current_entity_id} where id={$current_item_id}");
        if ($item_info = db_fetch_array($item_info_query))
        {
            if($item_info['field_' . $filed_id]!=$value)
            {
                db_query("update app_entity_{$current_entity_id} set field_{$filed_id}={$value} where id={$current_item_id}");
                
                echo 'UPDATED';
            }
        }
        exit();
        break;
    case 'update_latlng':

        $filed_id = _post::int('filed_id');

        $item_info_query = db_query("select field_{$filed_id} from app_entity_{$current_entity_id} where id={$current_item_id}");
        if ($item_info = db_fetch_array($item_info_query))
        {

            //get current address
            if (strlen($item_info['field_' . $filed_id]))
            {
                $value = explode("\t", $item_info['field_' . $filed_id]);
                
                $cord = $_POST['cord'];

                $value[0] = $cord[0];
                $value[1] = $cord[1];

                db_query("update app_entity_{$current_entity_id} set field_{$filed_id}='" . db_input(implode("\t",$value)) . "' where id='" . db_input($current_item_id) . "'");
            }
        }
        
        break;
}

exit();