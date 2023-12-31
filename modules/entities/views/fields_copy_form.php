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

<?php echo ajax_modal_template_header(TEXT_COPY_FIELDS) ?>

<?php echo form_tag('form-copy-to', url_for('entities/fields','action=copy_selected&entities_id=' . $_GET['entities_id']),array('class'=>'form-horizontal')) ?>
<?php echo input_hidden_tag('selected_fields') ?>
<div class="modal-body" >
  <div id="modal-body-content">    
    
    <div class="form-group">
    	<label class="col-md-4 control-label" for="type"><?php echo TEXT_SELECT_ENTITY ?></label>
      <div class="col-md-8">	
    	  <?php echo select_tag('copy_to_entities_id',entities::get_choices(),$_GET['entities_id'],array('class'=>'form-control')) ?>        
      </div>			
    </div>
    
    <div id="entities_form_tabs"></div>
      
  </div>
</div> 
<?php echo ajax_modal_template_footer(TEXT_COPY) ?>

</form>  

<script>
  $(function(){
     if($('.fields_checkbox:checked').length==0)
     {
       $('#modal-body-content').html('<?php echo TEXT_PLEASE_SELECT_FIELDS ?>')
       $('.btn-primary-modal-action').hide()
     }
     else
     {
       selected_fields_list = $('.fields_checkbox:checked').serialize().replace(/fields%5B%5D=/g,'').replace(/&/g,',');
       $('#selected_fields').val(selected_fields_list);
       
       get_entities_form_tabs();
     } 
     
     $('#copy_to_entities_id').change(function(){
       get_entities_form_tabs();
     })  

     $('#form-copy-to').validate({
 			submitHandler: function(form){
 				app_prepare_modal_action_loading(form)
 				form.submit();
 			}
     });        
  })
  
  
  function get_entities_form_tabs()
  {
    $('#entities_form_tabs').load('<?php echo url_for('entities/fields','action=get_entities_form_tabs') ?>',{entities_id:$('#copy_to_entities_id').val()})
  }  
</script>