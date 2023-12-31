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

$app_reports_groups_id = (isset($_GET['id']) ? _get::int('id') : 0);

if($app_reports_groups_id>0)
{	
	$reports_groups_info_query = db_query("select * from app_reports_groups where ((created_by = '" . $app_user['id'] . "' and is_common=0) or (" . ($app_user['group_id']>0 ? "find_in_set(" . $app_user['group_id'] . ",users_groups) and ":"") . " is_common=1)) and id='" . $app_reports_groups_id . "'");
	if(!$reports_groups_info = db_fetch_array($reports_groups_info_query))
	{
		redirect_to('dashboard/access_forbidden');
	}
}
else
{
    $reports_groups_info['is_common'] = false;
}

switch($app_module_action)
{	
	case 'save':
		redirect_to('dashboard/reports','id=' . $app_reports_groups_id);
		break;
	case 'sort_reports':	
		if(isset($_POST['reports_on_dashboard']))
		{
			$sql_data = array('reports_list'=>str_replace('report_','',$_POST['reports_on_dashboard']));
			db_perform('app_reports_groups',$sql_data,'update',"id='" .  $app_reports_groups_id . "'" );		
		}
		
		exit();
		break;
	
	case 'sort_reports_counter':			
		if(isset($_POST['reports_counter_on_dashboard']))
		{
			$sql_data = array('counters_list'=>str_replace('report_','',$_POST['reports_counter_on_dashboard']));
			db_perform('app_reports_groups',$sql_data,'update',"id='" .  $app_reports_groups_id . "'" );
		}
			
		exit();
		break;
		
	//handle sections
	case 'add_section':            
		$sql_data = array('reports_groups_id'=>$app_reports_groups_id,'created_by'=>$app_user['id'],'count_columns'=>_POST('columns'));
		db_perform('app_reports_sections',$sql_data);
		 
		$sections = new reports_sections($app_reports_groups_id, $reports_groups_info['is_common']);		
		echo $sections->render();
		 
		exit();
		break;
	case 'get_sections':
		$sections = new reports_sections($app_reports_groups_id, $reports_groups_info['is_common']);
		echo $sections->render();
		exit();
		break;
	case 'delete_section':
		if(isset($_POST['section_id']))
		{
			db_delete_row('app_reports_sections', $_POST['section_id']);
		}
		exit();
		break;
	case 'edit_section':
		if(isset($_POST['section_id']))
		{
			$value = $_POST['value'];
			
			$check_query = db_query("select id from app_reports_sections where ((report_left='{$value}' and length(report_left)>0) or (report_right='{$value}' and length(report_right)>0)) and reports_groups_id={$app_reports_groups_id} and created_by='{$app_user['id']}'");
			if(!$check = db_fetch_array($check_query))
			{
				$sql_data = array($_POST['type']=>$value);
				db_perform('app_reports_sections',$sql_data,'update',"id='" .  $_POST['section_id'] . "'" );
			}
			else
			{
				echo TEXT_REPORT_ALREADY_ASSIGNED;
			}
		}
		exit();
		break;
	case 'sort_sections':
		if(isset($_POST['section_panel']))
		{
			$sort_order = 0;
			foreach(explode(',',$_POST['section_panel']) as $v)
			{
				$sql_data = array('sort_order'=>$sort_order);
				db_perform('app_reports_sections',$sql_data,'update',"id='" . db_input(str_replace('section_panel_','',$v)) . "'");
				$sort_order++;
			}
		}
		exit();
		break;
}