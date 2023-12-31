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

if(!users::has_access('update'))
{
    redirect_to('dashboard/access_forbidden');
}

switch($app_module_action)
{
    case 'remove_selected_items':
        $related_entities_id = _POST('related_entities_id');
        if(isset($_POST['items']))
        {
            $table_info = related_records::get_related_items_table_name($current_entity_id, $related_entities_id);
            
            foreach($_POST['items'] as $item_id)
            {
                related_records::autocreate_comments_delete($related_entities_id, $item_id, $current_entity_id, $current_item_id);
                
                db_query("delete from {$table_info['table_name']} where entity_{$related_entities_id}{$table_info['sufix']}_items_id={$item_id} and entity_{$current_entity_id}_items_id={$current_item_id}");
            }
        }
        
        redirect_to('items/info', 'path=' . $_GET['path']);
        
        break;
    case 'remove_related_items';

        if(isset($_POST['items']))
        {
            $table_info = related_records::get_related_items_table_name($current_entity_id, $_POST['related_entities_id']);

            foreach($_POST['items'] as $id)
            {
                $relatd_items_info = db_find($table_info['table_name'], $id);
                related_records::autocreate_comments_delete($_POST['related_entities_id'], $relatd_items_info['entity_' . $_POST['related_entities_id'] . $table_info['sufix'] . '_items_id'], $current_entity_id, $current_item_id);

                db_delete_row($table_info['table_name'], $id);
                
                
            }
        }       

        redirect_to('items/info', 'path=' . $_GET['path']);
        break;
    case 'remove_related_item':

        $table_info = related_records::get_related_items_table_name($current_entity_id, $_GET['related_entity_id']);
        db_delete_row($table_info['table_name'], $_GET['id']);

        redirect_to('items/info', 'path=' . $_GET['path']);

        exit();
        break;

    case 'add_related_item':

        if(isset($_POST['items']) and isset($_POST['related_entities_id']))
        {
            $related_entities_id = (int) $_POST['related_entities_id'];

            $table_info = related_records::get_related_items_table_name($current_entity_id, $related_entities_id);

            foreach($_POST['items'] as $related_items_id)
            {
                $check_query = db_query("select * from " . $table_info['table_name'] . " where entity_" . $current_entity_id . "_items_id=" . (int) $current_item_id . " and entity_" . $related_entities_id . $table_info['sufix'] . "_items_id = " . (int) $related_items_id . "");
                if(!$check = db_fetch_array($check_query))
                {
                    $sql_data = array('entity_' . $current_entity_id . '_items_id' => $current_item_id,
                        'entity_' . $related_entities_id . $table_info['sufix'] . '_items_id' => $related_items_id);

                    db_perform($table_info['table_name'], $sql_data);

                    related_records::autocreate_comments($related_entities_id, $related_items_id, $current_entity_id, $current_item_id);
                }
            }
        }

        redirect_to('items/info', 'path=' . $_GET['path']);

        break;
}