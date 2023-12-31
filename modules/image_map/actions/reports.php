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

//check if report exist
$reports_query = db_query("select * from app_ext_image_map where id='" . db_input(_get::int('reports_id')) . "'");
if(!$reports = db_fetch_array($reports_query))
{
	die(TEXT_REPORT_NOT_FOUND);
}

//check access
if(!image_map::has_access($reports['users_groups']))
{
	die();
}

$choices_query = db_query("select * from app_fields_choices where fields_id = '" . db_input($reports['fields_id']). "' and parent_id=0 order by sort_order, name limit 1");
if($choices = db_fetch_array($choices_query))
{
	$map_id = $choices['id'];
}
else
{
	exit();
}

if (!app_session_is_registered('image_map_report_filter'))
{
	$image_map_report_filter = array();
	app_session_register('image_map_report_filter');
}

if(!isset($image_map_report_filter[$reports['id']]))
{
	$image_map_report_filter[$reports['id']] = $map_id; 
}

if(isset($_GET['map_id']))
{	
	$choices_query = db_query("select * from app_fields_choices where id = '" . _get::int('map_id'). "' and parent_id=0 order by sort_order, name limit 1");
	if($choices = db_fetch_array($choices_query))
	{
		$image_map_report_filter[$reports['id']] = $choices['id'];
	}
}

$app_layout = 'map_layout.php';