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


<h3 class="page-title"><?php echo TEXT_NAV_COMMENTS_ACCESS ?></h3>

<p><?php echo TEXT_COMMENTS_ACCESS_INFO ?></p>

<?php echo form_tag('cfg', url_for('entities/comments_access','action=set_access&entities_id=' . $_GET['entities_id'])) ?>

<div class="table-scrollable">
<table class="table table-striped table-bordered table-hover">
  <tr>
    <th><?php echo TEXT_USERS_GROUPS ?></th>
    <th><?php echo TEXT_ACCESS ?></th>    
  </tr>
  <tr>
    <td><?php echo TEXT_ADMINISTRATOR ?></td>
    <td><?php echo TEXT_YES ?></td>    
  </tr>
    
<?php       
  $count = 0;
  $groups_query = db_query("select ag.* from app_access_groups ag, app_entities_access ea where ea.access_groups_id=ag.id and ea.entities_id='" . db_input($_GET['entities_id']) . "' and length(ea.access_schema)>0 order by ag.sort_order, ag.name");
  while($v = db_fetch_array($groups_query))
  {        
    $count++; 
           
    $schema = '';
    $acess_info_query = db_query("select access_schema from app_comments_access where entities_id='" . db_input($_GET['entities_id']) . "' and access_groups_id='" . $v['id']. "'");
    if($acess_info = db_fetch_array($acess_info_query))
    {
      $schema = str_replace(',','_',$acess_info['access_schema']??'');      
    }
               
    echo '
      <tr>
        <td>' . $v['name']. '</td>
        <td>' . select_tag('access[' . $v['id']. ']',comments::get_access_choices(),$schema,array('class'=>'form-control input-medium')) . '</td>        
      </tr>    
    ';    
  }  
?>
</table>
</div>

<br>
<?php if($count>0) echo submit_tag(TEXT_BUTTON_SAVE) ?>
</form>



