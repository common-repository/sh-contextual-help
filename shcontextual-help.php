<?php
/*
Plugin Name: SH Contextual Help
Plugin URI: http://wordpress.org/plugins/sh-contextual-help/
Description: Simple modify Wordpress default help content or custom post type help content. Additional one help menu for custom help guide. You also can add dashboard widget. This plugin is more for theme developer.
Version: 3.2.1
Author: Sam Hoe
Author URI: sg.linkedin.com/pub/sam-hoe/37/604/894/
License: GPLv2 or later
*/

/*  Copyright 2013  Sam Hoe  (email : SH Contextual Help Sam Hoe samhoamt@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



// Define Path
$tmp = basename(dirname(__FILE__)); // Plugin folder
define(SH_CONTEXTUAL_HELP_FOLDER, $tmp);
define(SH_CONTEXTUAL_HELP_PATH, 'options-general.php?page=sh-contextual-help');
define(PLUGIN_BASENAME, plugin_basename( __FILE__ ));

// Add Actions and Filters
add_action('admin_menu','sh_help_menu');
add_action('admin_head','sh_help_tinymce');
add_action('contextual_help','shcontextual_help', 10, 3);
add_action('wp_dashboard_setup', 'sh_help_dashboard_widget' );
add_filter( "plugin_action_links_".PLUGIN_BASENAME, 'sh_add_settings_link' );
register_activation_hook(__FILE__,'set_sh_help_options');
register_deactivation_hook(__FILE__,'unset_sh_help_options');

// Add settings link
function sh_add_settings_link($links){
	$settings_link = '<a href="options-general.php?page=sh-contextual-help/settings.php">Settings</a>';
	array_push($links, $settings_link);
	return $links;
}

// TinyMce
function sh_help_tinymce(){
	global $wp_version;
	if($wp_version < '3.3'):
		wp_enqueue_style('thickbox');
		//do_action( 'admin_print_footer_scripts', 'footer_script');
		wp_print_scripts('media-upload');
		do_action('admin_print_styles');
		wp_enqueue_script('wp-ajax-response');
	endif;
}

function footer_script(){
	wp_preload_dialogs( array( 'plugins' => 'wpdialogs,wplink,wpfullscreen' ) );
}

// Add Menu
function sh_help_menu(){
	if(get_option('sh_menu_help')==1):
		$role = (get_option('sh_menu_help_role') == '') ? 8:get_option('sh_menu_help_role');
		add_menu_page(get_option('sh_menu_help_menu_title'),get_option('sh_menu_help_menu_title'),$role,SH_CONTEXTUAL_HELP_FOLDER.'/help.php');
	endif;
	add_submenu_page('options-general.php','Modify Contextual Help','SH Contextual Help',8,SH_CONTEXTUAL_HELP_FOLDER.'/settings.php');
}

// Initial Data
function set_sh_help_options(){
	global $wp_version;
	if($wp_version >= '3.3'): // wordpress version 3.3
		$menus[] = array('base'=>'Dashboard','title'=>'Dashboard','screen_id'=>'dashboard','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1, 'delete'=>0);
		$menus[] = array('base'=>'Dashboard','title'=>'Updates','screen_id'=>'update-core','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Posts','title'=>'Posts','screen_id'=>'edit-post','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Posts','title'=>'Add New','screen_id'=>'post','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Posts','title'=>'Categories','screen_id'=>'edit-category','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Posts','title'=>'Post Tags','screen_id'=>'edit-post_tag','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Media','title'=>'Library','screen_id'=>'upload','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Media','title'=>'Add New','screen_id'=>'media','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Links','title'=>'Links','screen_id'=>'link-manager','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Links','title'=>'Add New','screen_id'=>'link','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Links','title'=>'Link Categories','screen_id'=>'edit-link_category','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Pages','title'=>'Pages','screen_id'=>'edit-page','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Pages','title'=>'Add New','screen_id'=>'page','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Comments','title'=>'Comments','screen_id'=>'edit-comments','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Comments','title'=>'Edit Comment','screen_id'=>'comment','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Appearance','title'=>'Themes','screen_id'=>'themes','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Appearance','title'=>'Widgets','screen_id'=>'widgets','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Appearance','title'=>'Menus','screen_id'=>'nav-menus','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Appearance','title'=>'Editor','screen_id'=>'theme-editor','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Plugins','title'=>'Plugins','screen_id'=>'plugins','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Plugins','title'=>'Add New','screen_id'=>'plugin-install','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Plugins','title'=>'Editor','screen_id'=>'plugin-editor','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Users','title'=>'Users','screen_id'=>'users','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Users','title'=>'Edit User','screen_id'=>'user_edit','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Users','title'=>'Add New','screen_id'=>'user','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Users','title'=>'Your Profile','screen_id'=>'profile','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Tools','title'=>'Tools','screen_id'=>'tools','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Tools','title'=>'Import','screen_id'=>'import','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Tools','title'=>'Export','screen_id'=>'export','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Settings','title'=>'General','screen_id'=>'options-general','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Settings','title'=>'Writing','screen_id'=>'options-writing','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Settings','title'=>'Reading','screen_id'=>'options-reading','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Settings','title'=>'Discussion','screen_id'=>'options-discussion','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Settings','title'=>'Media','screen_id'=>'options-media','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Settings','title'=>'Privacy','screen_id'=>'options-privacy','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
		$menus[] = array('base'=>'Settings','title'=>'Permalinks','screen_id'=>'options-permalink','default'=>1,'tab_title'=>'Custom','remove'=>0,'content'=>'', 'help_sidebar' => '', 'default_sidebar' => 1,'delete'=>0);
	else:
		$menus[] = array('base'=>'Dashboard','title'=>'Dashboard','screen_id'=>'dashboard','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Dashboard','title'=>'Updates','screen_id'=>'update-core','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Posts','title'=>'Posts','screen_id'=>'edit-post','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Posts','title'=>'Add New','screen_id'=>'post','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Posts','title'=>'Categories','screen_id'=>'edit-category','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Posts','title'=>'Post Tags','screen_id'=>'edit-post_tag','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Media','title'=>'Library','screen_id'=>'upload','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Media','title'=>'Add New','screen_id'=>'media','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Links','title'=>'Links','screen_id'=>'link-manager','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Links','title'=>'Add New','screen_id'=>'link','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Links','title'=>'Link Categories','screen_id'=>'edit-link_category','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Pages','title'=>'Pages','screen_id'=>'edit-page','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Pages','title'=>'Add New','screen_id'=>'page','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Comments','title'=>'Comments','screen_id'=>'edit-comments','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Comments','title'=>'Edit Comment','screen_id'=>'comment','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Appearance','title'=>'Themes','screen_id'=>'themes','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Appearance','title'=>'Widgets','screen_id'=>'widgets','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Appearance','title'=>'Menus','screen_id'=>'nav-menus','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Appearance','title'=>'Editor','screen_id'=>'theme-editor','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Plugins','title'=>'Plugins','screen_id'=>'plugins','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Plugins','title'=>'Add New','screen_id'=>'plugin-install','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Plugins','title'=>'Editor','screen_id'=>'plugin-editor','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Users','title'=>'Users','screen_id'=>'users','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Users','title'=>'Edit User','screen_id'=>'user_edit','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Users','title'=>'Add New','screen_id'=>'user','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Users','title'=>'Your Profile','screen_id'=>'profile','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Tools','title'=>'Tools','screen_id'=>'tools','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Tools','title'=>'Import','screen_id'=>'import','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Tools','title'=>'Export','screen_id'=>'export','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Settings','title'=>'General','screen_id'=>'options-general','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Settings','title'=>'Writing','screen_id'=>'options-writing','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Settings','title'=>'Reading','screen_id'=>'options-reading','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Settings','title'=>'Discussion','screen_id'=>'options-discussion','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Settings','title'=>'Media','screen_id'=>'options-media','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Settings','title'=>'Privacy','screen_id'=>'options-privacy','default'=>1,'position'=>'after','help'=>'','delete'=>0);
		$menus[] = array('base'=>'Settings','title'=>'Permalinks','screen_id'=>'options-permalink','default'=>1,'position'=>'after','help'=>'','delete'=>0);
	endif;
	add_option('sh_help_menus',$menus);
	add_option('sh_menu_help',1);
	add_option('sh_menu_help_menu_title','Help');
	add_option('sh_menu_help_title','Custom Help');
	add_option('sh_menu_help_role', 8);
	add_option('sh_menu_help_content','');
	add_option('sh_menu_help_widget',0);
	add_option('sh_menu_help_widget_title','SH Contextual Help');
	add_option('sh_menu_help_widget_content','');
}

// Delete Data
function unset_sh_help_options(){
	delete_option('sh_help_menus');
}

// Add Custom Contextual Help
function shcontextual_help($help,$screen_id,$screen){
	$menus = get_option('sh_help_menus');
	global $wp_version;
	if($wp_version >= '3.3'): // wordpress version 3.3
		for($i=0;$i<count($menus);$i++):
			if($menus[$i]['screen_id'] == $screen_id):
				if($menus[$i]['default']==0):
					if($menus[$i]['remove']):
						// remove all default tabs
						$screen->remove_help_tabs();
					endif;

					// Add custom help tab
					$screen->add_help_tab( array(
						'id' => $menus[$i]['tab_title'],
						'title' => $menus[$i]['tab_title'],
						'content' => do_shortcode($menus[$i]['content']),
					) );
					if(!$menus[$i]['default_sidebar']):
						$screen->set_help_sidebar($menus[$i]['help_sidebar']);
					endif;
				endif;
			endif;
		endfor;
	else:
		for($i=0;$i<count($menus);$i++):
			if($menus[$i]['screen_id'] == $screen_id):
				if($menus[$i]['default']==0):
					$default_help = $help;
					switch($menus[$i]['position']):
						case 'after':
							$help .= do_shortcode($menus[$i]['content']);
						break;
						case 'before':
							$help = do_shortcode($menus[$i]['content']).$default_help;
						break;
						case 'middle':
							$helps = explode('<p><strong>For more information:</strong></p>',$help);
							$help = $helps[0].do_shortcode($menus[$i]['content']).'<p><strong>For more information:</strong></p>'.$helps[1];
						break;
						case 'replace':
							$help = do_shortcode($menus[$i]['content']);
						break;
					endswitch;
				endif;
			endif;
		endfor;
		return $help;
	endif;
}
// Add Dashboard widget
function sh_help_dashboard_widget(){
	if(get_option('sh_menu_help_widget')==1):
		wp_add_dashboard_widget('sh_contextual_help', get_option('sh_menu_help_widget_title'),'sh_help_dashboard_widget_function');
	endif;
}
// Dashboard widget function
function sh_help_dashboard_widget_function(){
?>
	<div id="sh_contextual_help">
    	<?php echo do_shortcode(get_option('sh_menu_help_widget_content')); ?>
    </div>
<?php
}
?>