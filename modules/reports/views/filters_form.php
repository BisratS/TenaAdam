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
<?php echo ajax_modal_template_header(TEXT_HEADING_REPORTS_FILTER_IFNO . ' (' . entities::get_name_by_id($reports_info['entities_id']). ')') ?>

<?php echo form_tag('reports_filters', url_for('reports/filters','action=save&reports_id=' . $_GET['reports_id'] . (isset($_GET['parent_reports_id']) ? '&parent_reports_id=' . $_GET['parent_reports_id']:'').  (isset($_GET['id']) ? '&id=' . $_GET['id']:'') ),array('class'=>'form-horizontal')) ?>

<?php echo input_hidden_tag('redirect_to',$app_redirect_to) ?>
<?php   if(isset($_GET['path'])) echo input_hidden_tag('path',$_GET['path']) ?>

<div class="modal-body">
  <div class="form-body ajax-modal-width-790">
     
	<div class="form-group">
  	<label class="col-md-3 control-label" for="is_active"><?php echo tooltip_icon(TEXT_IS_ACTIVE_FILTER_INFO) . TEXT_IS_ACTIVE_FILTER ?></label>
    <div class="col-md-9">	
  	  <p class="form-control-static"><?php echo  input_checkbox_tag('is_active',1,array('checked'=>$obj['is_active'])) ?></p>
    </div>			
  </div> 
  
  <div class="form-group">
  	<label class="col-md-3 control-label" for="fields_id"><?php echo TEXT_SELECT_FIELD ?></label>
    <div class="col-md-9">	
  	  <?php echo select_tag('fields_id',fields::get_filters_choices($reports_info['entities_id'], (isset($_GET['path'])? false: true)),$obj['fields_id'],array('class'=>'form-control required chosen-select','onChange'=>'load_fitlers_options(this.value)')) ?>
    </div>			
  </div>     
     
<div id="filters_options"></div>
 
   </div>
</div> 
 
<?php echo ajax_modal_template_footer() ?>

</form> 

<script>


  $(function() { 
    $('#reports_filters').validate({
				submitHandler: function(form){
					app_prepare_modal_action_loading(form)
					form.submit();
				}
      });
    
    load_fitlers_options($('#fields_id').val());                                                                      
  });
  
  
function load_fitlers_options(fields_id)
{
  $('#filters_options').html('<div class="ajax-loading"></div>');
  
  $('#filters_options').load('<?php echo url_for("reports/filters_options")?>',{fields_id:fields_id, id:'<?php echo $obj["id"] ?>',path:'<?php echo $app_path ?>'},function(response, status, xhr) {
    if (status == "error") {                                 
       $(this).html('<div class="alert alert-error"><b>Error:</b> ' + xhr.status + ' ' + xhr.statusText+'<div>'+response +'</div></div>')                    
    }
    else
    {   
      appHandleUniform();
    }
  });
}  
  
</script>  

    
 
