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

<?php echo ajax_modal_template_header(TEXT_UNLINK) ?>

<?php
if(!isset($app_selected_items[$_GET['reports_id']]) or count($app_selected_items[$_GET['reports_id']])==0)
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

<?php echo form_tag('remove_related_items', url_for('items/related_item','action=remove_selected_items&path=' . $_GET['path'])) ?>
<?php echo input_hidden_tag('related_entities_id',_GET('related_entities_id')) ?>
<div class="modal-body">
    
<?php
$html = '<ul>';
foreach($app_selected_items[$_GET['reports_id']] as $item_id)
{
    $html .= '<li>' . items::get_heading_field(_GET('related_entities_id'), $item_id) . input_hidden_tag('items[]',$item_id). '</li>';
}
$html .='</ul>';
echo $html;
?>
    
</div>

<?php echo ajax_modal_template_footer(TEXT_UNLINK) ?>

</form>

<?php } ?>