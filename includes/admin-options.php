<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $menu,$submenu;
$submenu_items = array();
?>
<form method="post">
<div class="bp-dashboard-row">
    <h1><?php _e( 'Menu Editor Settings', WP_DME_DOMAIN );?></h1>
    
        <table id="responseOptions">
            <tbody>
                <?php foreach( $menu as $key=>$value ): ?>
                    <tr class='menu-editor-rows' id='<?php echo $value[2];?>'>
                        <td>
                            <div id='<?php echo $key;?>' class='menu-items'>
                                <?php _e( $value[0], WP_DME_DOMAIN ); ?>
                            </div>
                            <div class="submenu-items">                                  
                                <?php 
                                if ( !empty( $submenu[ $value[2] ] ) ) {
                                    $submenu_items[ $key ] = $submenu[ $value[2] ];?>
                                    <table class="submenusoptions" id='submenus-<?php echo $key; ?>'>
                                        <tbody>
                                            <?php foreach( $submenu[ $value[2] ] as $k=>$v ): ?>                                 
                                                <tr class="submenu-editor-rows" id='<?php echo $v[2];?>'>
                                                    <td>
                                                        <?php _e( $v[0], WP_DME_DOMAIN ); ?>
                                                    </td>
                                                </tr>                                                   
                                            <?php endforeach; ?>
                                        </tbody> 
                                    </table>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <input type='button' value="<?php _e( 'Save Menus', WP_DME_DOMAIN ); ?>" class="button button-primary menu_edit bp-dashboard-submit1" name="save_menus"/>
</div>
<input type='button' value="<?php _e( 'Save Menus', WP_DME_DOMAIN ); ?>" class="button button-primary menu_edit bp-dashboard-submit2" name="save_menus"/>
</form>
