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

<?php $template_info = db_find('app_ext_xml_export_templates',_get::int('templates_id'))?>

<?php echo ajax_modal_template_header($template_info['name']) ?>

<?php
if(!isset($app_selected_items[$_GET['reports_id']])) $app_selected_items[$_GET['reports_id']] = array();

if(count($app_selected_items[$_GET['reports_id']])==0)
{
  echo '
    <div class="modal-body">    
      <div>' . TEXT_PLEASE_SELECT_ITEMS . '</div>
    </div>    
  ' . ajax_modal_template_footer('hide-save-button');
}
else
{
?>


<?php echo form_tag('export-form', url_for('items/xml_export_multiple','path=' . $_GET['path'] . '&templates_id=' . $_GET['templates_id'])) ?>
<?php echo input_hidden_tag('action','export') ?>
<?php echo input_hidden_tag('reports_id', $_GET['reports_id']) ?>

<div class="modal-body">    



<p>
<?php
	
	$filename = $template_info['name'] .  ' ' . $app_entities_cache[$current_entity_id]['name'];
	
  echo TEXT_FILENAME . '<br>' . input_tag('filename',$filename,array('class'=>'form-control input-large')); 
?>
</p>

</div> 

<?php  
  echo ajax_modal_template_footer(TEXT_EXPORT) 
?>

</form>  

</form>  

<?php } ?>