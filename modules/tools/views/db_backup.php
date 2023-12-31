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

<h3 class="page-title"><?php echo TEXT_HEADING_DB_BACKUP ?></h3>

<div>
    <?php
    echo button_tag(TEXT_BUTTON_CREATE_BACKUP, url_for('tools/db_backup_form')) . ' ' .
    button_tag(TEXT_MENU_DATABASE_EXPORT, url_for('tools/db_export'), true, array('class' => 'btn btn-default')) . ' ' .
    button_tag('<i class="fa fa-repeat" aria-hidden="true"></i> ' . TEXT_BUTTON_DB_RESOTRE_FROM_FILE, url_for('tools/db_restore_file'), true, array('class' => 'btn btn-default'));
    ?>
</div>

<div class="table-scrollable">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th><?php echo TEXT_ACTION ?></th>        
                <th><?php echo TEXT_ID ?></th>        
                <th><?php echo TEXT_FILENAME ?></th>
                <th><?php echo TEXT_SIZE ?></th>
                <th><?php echo TEXT_DATE_ADDED ?></th>                          
                <th><?php echo TEXT_CREATED_BY ?></th>
                <th width="100%"><?php echo TEXT_COMMENT ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(db_count('app_backups') == 0) echo '<tr><td colspan="7">' . TEXT_NO_RECORDS_FOUND . '</td></tr>'; ?>
            <?php
            $backups_query = "select * from app_backups where is_auto=0 order by date_added desc";
            $listing_split = new split_page($backups_query, 'records_listing');
            $backups_query = db_query($listing_split->sql_query);
            while($backups = db_fetch_array($backups_query)):
                ?>
                <tr>
                    <td class="nowrap"><?php echo button_icon_delete(url_for('tools/db_backup_delete', 'id=' . $backups['id'])) . ' ' . button_icon(TEXT_BUTTON_RESTORE, 'fa fa fa-repeat', url_for('tools/db_restore', 'id=' . $backups['id'])) . ' ' . button_icon(TEXT_BUTTON_DOWNLOAD, 'fa fa-download', url_for('tools/db_backup', 'action=download&id=' . $backups['id']), false); ?></td>
                    <td><?php echo $backups['id'] ?></td>           

                    <td ><?php echo $backups['filename']; ?></td>    
                    <td><?php
                        if(is_file($file_path = DIR_FS_BACKUPS . $backups['filename']))
                        {
                            echo attachments::file_size_convert(filesize($file_path));
                        }
                        ?></td>
                    <td><?php echo format_date_time($backups['date_added']) ?></td>							
                    <td><?php echo ($backups['users_id'] > 0 ? users::get_name_by_id($backups['users_id']) : TEXT_BACKUP_TYPE_AUTO) ?></td>
                    <td class="white-space-nomral"><?php echo nl2br($backups['description']) ?></td>
                </tr>
            <?php endwhile ?>

        <tbody>
    </table>
</div>

<?php
echo '
  <table width="100%">
    <tr>
      <td>' . $listing_split->display_count() . '</td>
      <td align="right">' . $listing_split->display_links() . '</td>
    </tr>
  </table>';
?>

<div><?php echo TEXT_BACKUP_FOLDER . ': ' . DIR_FS_BACKUPS ?></div>

<script>
    function load_items_listing(listing_container, page)
    {
        location.href = "<?php echo url_for('tools/db_backup') ?>&page=" + page;
    }
</script>
