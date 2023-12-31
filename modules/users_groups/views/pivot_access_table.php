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
<?php
$breadcrumb = array();
$breadcrumb[] = '<li>' . link_to(TEXT_MENU_USERS_ACCESS_GROUPS, url_for('users_groups/users_groups')) . '<i class="fa fa-angle-right"></i></li>';
$breadcrumb[] = '<li>' . $users_groups_info['name'] . '</li>';
?>

<ul class="page-breadcrumb breadcrumb">
    <?php echo implode('', $breadcrumb) ?>  
</ul>

<h3 class="page-title"><?php echo TEXT_PIVOT_ACCESS_TABLE . ' <i class="fa fa-angle-right"></i> ' . $users_groups_info['name'] ?></h3>

<?php echo button_tag(TEXT_COPY_ACCESS, url_for('users_groups/copy_access', 'id=' . $users_groups_info['id'])) ?>

<?php echo form_tag('pivot_access_form', url_for('users_groups/pivot_access_table', 'action=set_access&id=' . $users_groups_info['id'])) ?>
<div class="table-scrollable" style="overflow-x:visible;overflow-y:visible; ">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>    
                <th><?php echo input_checkbox_tag('select_all_items', '', array('class' => 'select_all_items')) ?></th>
                <th width="100%"><?php echo TEXT_ENTITY ?></th>
                <th><?php echo TEXT_VIEW_ACCESS ?></th>    
                <th><?php echo TEXT_ACCESS ?></th>           
            </tr>
        </thead>
        <tbody>
            <?php
            foreach(entities::get_tree() as $v):

                $access_schema = array();

                $acess_info_query = db_query("select access_schema from app_entities_access where entities_id='" . db_input($v['id']) . "' and access_groups_id='" . $users_groups_info['id'] . "'");
                if($acess_info = db_fetch_array($acess_info_query))
                {
                    $access_schema = explode(',', $acess_info['access_schema']);
                }

                $comments_schema = '';
                $comments_acess_info_query = db_query("select access_schema from app_comments_access where entities_id='" . db_input($v['id']) . "' and access_groups_id='" . $users_groups_info['id'] . "'");
                if($comments_acess_info = db_fetch_array($comments_acess_info_query))
                {
                    $comments_schema = str_replace(',', '_', $comments_acess_info['access_schema']);
                }

                $entity_cfg = new entities_cfg($v['id']);
                ?>
                <tr>  
                    <td><?php echo input_checkbox_tag('items[]', $v['id'], array('class' => 'items_checkbox')) ?></td>
                    <td style="white-space: nowrap">
    <?php echo str_repeat('&nbsp;<i class="fa fa-minus" aria-hidden="true"></i>&nbsp;', $v['level']) . ' <a href="' . url_for('entities/entities_configuration', 'entities_id=' . $v['id']) . '">' . $v['name'] . '</a>' ?>  	
                    </td>  
                    <td><?php
    echo select_tag('view_access[' . $v['id'] . '][]', access_groups::get_access_view_choices(), access_groups::get_access_view_value($access_schema), array( 'class' => 'form-control input-large access-schema-settings', 'data-entity-id' => $v['id'], 'onChange' => 'check_access_schema(this.value,' . $v['id'] . ')'));

    echo '<div style="padding-top: 5px; text-align: right;">' . button_tag(TEXT_NAV_FIELDS_ACCESS, url_for('users_groups/fields_access', 'id=' . $users_groups_info['id'] . '&entities_id=' . $v['id']), true, array('class' => 'btn btn-default btn-sm')) . '</div>';
    ?></td>
                    <td><?php
                        echo select_tag('access[' . $v['id'] . '][]', access_groups::get_access_choices(), $access_schema, array( 'class' => 'form-control input-xlarge chosen-select access-schema-settings', 'data-entity-id' => $v['id'], 'multiple' => 'multiple'));

                        if($entity_cfg->get('use_comments'))
                        {
                            echo '<div style="padding-top: 5px; "><ul class="list-inline"><li>' . TEXT_COMMENTS . ':</li><li>' . select_tag('comments_access[' . $v['id'] . ']', comments::get_access_choices(), $comments_schema, array('class' => 'form-control input-medium access-schema-settings', 'data-entity-id' => $v['id'])) . '</li><ul></div>';
                        }
                        ?></td>

                </tr>  
<?php endforeach ?>
        </tbody>
    </table>
</div>
</form>

<?php echo '<a class="btn btn-default" href="' . url_for('users_groups/users_groups') . '">' . TEXT_BUTTON_BACK . '</a>' ?>

<script>
    function check_access_schema(access, entity_id)
    {
        if (access == '')
        {
            $('#access_shcema_' + entity_id).val('');
            $('#access_shcema_' + entity_id).trigger("chosen:updated");

            $('#comments_access_' + entity_id).val('');

        }
    }

    $(function ()
    {

        $('.access-schema-settings').change(function ()
        {
            form = $('#pivot_access_form');
            entity_id = $(this).attr('data-entity-id')
            $.ajax({type: "POST", url: form.attr('action') + '&entities_id=' + entity_id, data: {view_access: $('#view_access_'+entity_id).val(),access: $('#access_'+entity_id).val(),comments_access: $('#comments_access_'+entity_id).val()}});
        })


        $('#select_all_items').click(function ()
        {
            select_all_by_classname('select_all_items', 'items_checkbox')
        })

    })
</script> 