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

$item_info = db_find('app_entity_' . $current_entity_id, $item_id);

$reports_query = db_query("select * from app_ext_ganttchart where id='" . str_replace('ganttreport', '', $app_redirect_to) . "'");
if($reports = db_fetch_array($reports_query))
{
    if(ganttchart::get_duration_unit($reports)=='hour')
    {
        $item_info['field_' . $reports['end_date']] = strtotime("+1 hour", $item_info['field_' . $reports['end_date']]); 
    }
    else
    {
        $item_info['field_' . $reports['end_date']] = strtotime("+1 day", $item_info['field_' . $reports['end_date']]);
    }
    
}

$heading_field_id = fields::get_heading_id($current_entity_id);

if($heading_field_id > 0)
{
    $field = db_find('app_fields', $heading_field_id);

    $value = items::prepare_field_value_by_type($field, $item_info);

    $output_options = array(
        'class' => $field['type'],
        'value' => $value,
        'field' => $field,
        'item' => $item_info,
        'is_export' => true,
        'is_print' => true,
    );

    //add custom colors
    $heading_link_color = '';

    if(isset($item_info['field_' . $reports['use_background']]))
    {
        $choices_colors = fields::get_field_choices_background_data($reports['use_background']);

        $choices_id = $item_info['field_' . $reports['use_background']];

        if(isset($choices_colors[$choices_id]))
        {
            $item_info['color'] = $choices_colors[$choices_id]['background'];

            if(isset($choices_colors[$choices_id]['color']))
            {
                $heading_link_color = 'class="color-white"';
            }
        }
    }

    $item_info['field_' . $heading_field_id] = '<a ' . $heading_link_color . ' href="' . url_for('items/info', 'path=' . $app_path) . '" target="_blank">' . fields_types::output($output_options) . '</a>';
}


//add fields in listing
if(strlen($reports['fields_in_listing']))
{
    foreach(explode(',', $reports['fields_in_listing']) as $k)
    {
        $field = db_find('app_fields', $k);

        $value = items::prepare_field_value_by_type($field, $item_info);

        $output_options = array(
            'class' => $field['type'],
            'value' => $value,
            'field' => $field,
            'item' => $item_info,
            'is_export' => true,
            'is_print' => true,
        );

        $item_info['field_' . $k] = fields_types::output($output_options);
    }
}

echo json_encode($item_info);
