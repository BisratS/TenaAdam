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
?>

<?php require(component_path('entities/navigation')) ?>
    
<h3 class="page-title"><?php echo  TEXT_HIDE_BY_CONDITION . ' (' . TEXT_ITEM_PAGE_PARENT_ITEM . ')' ?></h3>

<p><?php echo TEXT_HIDE_BY_CONDITION_SUBENTITY_INFO ?></p>

<?php 
  echo button_tag(TEXT_BUTTON_ADD_NEW_REPORT_FILTER,url_for('entities/hide_subentity_filters_form','reports_id=' . $reports_info['id'] . '&entities_id=' . $_GET['entities_id'] ));
?>

<div class="table-scrollable">
<table class="table table-striped table-bordered table-hover">
<thead>
  <tr>
    <th><?php echo TEXT_ACTION ?></th>        
    <th width="100%"><?php echo TEXT_FIELD ?></th>    
    <th><?php echo TEXT_VALUES ?></th>
            
  </tr>
</thead>
<tbody>
<?php  
  $filters_query = db_query("select rf.*, f.name, f.type from app_reports_filters rf left join app_fields f on rf.fields_id=f.id where rf.reports_id='" . db_input($reports_info['id']) . "' order by rf.id");
  
  if(db_num_rows($filters_query)==0) echo '<tr><td colspan="5">' . TEXT_NO_RECORDS_FOUND. '</td></tr>';
  
  while($v = db_fetch_array($filters_query)):
?>
  <tr>
    <td style="white-space: nowrap;"><?php 
    echo button_icon_delete(url_for('entities/hide_subentity_filters_delete','id=' . $v['id'] . '&reports_id=' . $reports_info['id']. '&entities_id=' . $_GET['entities_id'])) . ' ' . button_icon_edit(url_for('entities/hide_subentity_filters_form','id=' . $v['id'] . '&reports_id=' . $reports_info['id']. '&entities_id=' . $_GET['entities_id']))  ?></td>    
    <td><?php echo fields_types::get_option($v['type'],'name',$v['name']) ?></td>    
    <td class="nowrap"><?php echo reports::render_filters_values($v['fields_id'],$v['filters_values'],'<br>',$v['filters_condition']) ?></td>            
  </tr>
<?php endwhile?>  
</tbody>
</table>
</div>

<?php echo '<a class="btn btn-default" href="' . url_for('entities/item_page_configuration','entities_id=' .  $entities_info['parent_id'] ). '">' . TEXT_BUTTON_BACK . '</a>';?>