<style>
	textarea#content{
		width:100%;
	}
</style>
<?php
// Tabs
function sh_contextual_tabs(){
	switch($_REQUEST['tab']):
		case 'menu':
			echo '<a class="nav-tab" href="'.SH_CONTEXTUAL_HELP_PATH.'/settings.php">Wordpress Contextual Help</a><a class="nav-tab nav-tab-active" href="'.SH_CONTEXTUAL_HELP_PATH.'/settings.php&tab=menu">Custom Help Page</a><a class="nav-tab" href="'.SH_CONTEXTUAL_HELP_PATH.'/settings.php&tab=dashboard">Dashboard Widget</a>';
		break;
		case 'dashboard':
			echo '<a class="nav-tab" href="'.SH_CONTEXTUAL_HELP_PATH.'/settings.php">Wordpress Contextual Help</a><a class="nav-tab" href="'.SH_CONTEXTUAL_HELP_PATH.'/settings.php&tab=menu">Custom Help Page</a><a class="nav-tab nav-tab-active" href="'.SH_CONTEXTUAL_HELP_PATH.'/settings.php&tab=dashboard">Dashboard Widget</a>';
		break;
		default:
			echo '<a class="nav-tab nav-tab-active" href="'.SH_CONTEXTUAL_HELP_PATH.'/settings.php">Wordpress Contextual Help</a><a class="nav-tab" href="'.SH_CONTEXTUAL_HELP_PATH.'/settings.php&tab=menu">Custom Help Page</a><a class="nav-tab" href="'.SH_CONTEXTUAL_HELP_PATH.'/settings.php&tab=dashboard">Dashboard Widget</a>';
		break;
	endswitch;
}
// Contextual Help list
function sh_contextual_help($title='Wordpress Contextual Help'){
	global $wp_version;
	$menus = get_option('sh_help_menus');
	if($_REQUEST['submit']&&$_REQUEST['action']):
		$menus[$_REQUEST['id']]['default'] = 0;
		$menus[$_REQUEST['id']]['content'] = wpautop(stripslashes($_REQUEST['content']));
		if($wp_version >= '3.3'):
			$menus[$_REQUEST['id']]['tab_title'] = $_REQUEST['tab_title'];
			$menus[$_REQUEST['id']]['remove'] = $_REQUEST['remove'];
			$menus[$_REQUEST['id']]['help_sidebar'] = $_REQUEST['help_sidebar'];
			$menus[$_REQUEST['id']]['default_sidebar'] = $_REQUEST['default_sidebar'];
		else:
			$menus[$_REQUEST['id']]['position'] = $_REQUEST['position'];
		endif;
		update_option('sh_help_menus',$menus);
		echo '<div id="message" class="updated fade">Successfuly updated</div>';
	elseif($_REQUEST['submit']):
		switch($_REQUEST['type']):
			case 'post':
				$post_type = get_post_type_object($_REQUEST['slug']);
				$base = $post_type->labels->menu_name;
				$title = $post_type->labels->name;
				$screen_id = 'edit-'.$_REQUEST['slug'];
				if($post_type):
					if($wp_version >= '3.3'):
						$menus[] = array('base'=>$base,'title'=>$title,'screen_id'=>$screen_id,'default'=>1,'tab_title'=>'Custom','remove'=>0,'help'=>'','delete'=>1);
					else:
						$menus[] = array('base'=>$base,'title'=>$title,'screen_id'=>$screen_id,'default'=>1,'position'=>'after','help'=>'','delete'=>1);
					endif;
				endif;
				// Add New
				$post_type = get_post_type_object($_REQUEST['slug']);
				$base = $post_type->labels->menu_name;
				$title = 'Add New';
				$screen_id = $_REQUEST['slug'];
				if($post_type):
					if($wp_version >= '3.3'):
						$menus[] = array('base'=>$base,'title'=>$title,'screen_id'=>$screen_id,'default'=>1,'tab_title'=>'Custom','remove'=>0,'help'=>'','delete'=>1);
					else:
						$menus[] = array('base'=>$base,'title'=>$title,'screen_id'=>$screen_id,'default'=>1,'position'=>'after','help'=>'','delete'=>1);
					endif;
				endif;
			break;
			case 'tax':
				$tax = get_taxonomy($_REQUEST['slug']);
				$post_type = get_post_type_object($tax->object_type[0]);
				$base = $post_type->labels->menu_name;
				$title = $tax->labels->name;
				$screen_id = 'edit-'.$_REQUEST['slug'];
				if($wp_version >= '3.3'):
					$menus[] = array('base'=>$base,'title'=>$title,'screen_id'=>$screen_id,'default'=>1,'tab_title'=>'Custom','remove'=>0,'help'=>'','delete'=>1);
				else:
					$menus[] = array('base'=>$base,'title'=>$title,'screen_id'=>$screen_id,'default'=>1,'position'=>'after','help'=>'','delete'=>1);
				endif;
			break;
		endswitch;
		if($post_type):
			update_option('sh_help_menus',$menus);
			echo '<div id="message" class="updated fade">Successfuly Added</div>';
		else:
			echo '<div id="message" class="error fade">There is no such Custom Post Type or Taxonomy. Please check your input again.</div>';
		endif;
	endif;
?>
    <?php
		switch($_REQUEST['action']):
			case 'delete':
				echo '<div id="message" class="updated fade">Successfuly delete ('.$menus[$_REQUEST['id']]['base'].' &raquo; '.$menus[$_REQUEST['id']]['title'].')</div>';
				unset($menus[$_REQUEST['id']]);
				update_option('sh_help_menus',$menus);
	?>
    <h3><?php echo $title; ?><a class="button add-new-h2" href="#add">Add New</a></h3>
	<table class="widefat">
    	<thead>
        	<tr>
            	<th>Admin Menu</th>
                <th>Title</th>
                <th>Action</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php for($i=0;$i<count($menus);$i++): ?>
        	<tr>
            	<th><?php echo $menus[$i]['base']; ?></th>
                <td><?php echo $menus[$i]['title']; ?></td>
                <td><a href="<?php echo SH_CONTEXTUAL_HELP_PATH; ?>/settings.php&action=edit&id=<?php echo $i; ?>" title="Edit">Edit</a> / <a href="<?php echo SH_CONTEXTUAL_HELP_PATH; ?>/settings.php&action=reset&id=<?php echo $i; ?>" title="Reset to Default">Reset</a>
                <?php if($menus[$i]['delete']==1): ?>
                / <a href="<?php echo SH_CONTEXTUAL_HELP_PATH; ?>/settings.php&action=delete&id=<?php echo $i; ?>" title="Delete">Delete</a>
                <?php endif; ?>
        </td>
                <td>
                <?php if($menus[$i]['default']==1): ?>
                	Default
                <?php else: ?>
                	Custom
                <?php endif; ?>
                </td>
            </tr>
        <?php endfor; ?>
        </tbody>
    </table>
    <h3 id="add">Add New Custom Post Type / Taxonomy</h3>
    <form action="<?php echo SH_CONTEXTUAL_HELP_PATH; ?>/settings.php" method="post">
        <table class="form-table">
        	<tbody>
            	<tr valign="top">
                	<td scope="row"><label for="type">Type</label></td>
                    <td>
                    	<select name="type" id="type">
                        	<option value="post" selected="selected">Custom Post Type</option>
                          <option value="tax">Taxonomy</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                	<td scope="row"><label for="slug">Slug</label></td>
                    <td><input type="text" id="slug" name="slug" class="regular-text" /></td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
    		<input type="submit" name="submit" value="Add New" />
    	</p>
	</form>
    <?php
			break;
			case 'reset':
				$menus[$_REQUEST['id']]['default'] = 1;
				update_option('sh_help_menus',$menus);
				echo '<div id="message" class="updated fade">Successfuly reset ('.$menus[$_REQUEST['id']]['base'].' &raquo; '.$menus[$_REQUEST['id']]['title'].') to default</div>';
	?>
    <h3><?php echo $title; ?><a class="button add-new-h2" href="#add">Add New</a></h3>
	<table class="widefat">
    	<thead>
        	<tr>
            	<th>Admin Menu</th>
                <th>Title</th>
                <th>Action</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php for($i=0;$i<count($menus);$i++): ?>
        	<tr>
            	<th><?php echo $menus[$i]['base']; ?></th>
                <td><?php echo $menus[$i]['title']; ?></td>
                <td><a href="<?php echo SH_CONTEXTUAL_HELP_PATH; ?>/settings.php&action=edit&id=<?php echo $i; ?>" title="Edit">Edit</a> / <a href="<?php echo SH_CONTEXTUAL_HELP_PATH; ?>/settings.php&action=reset&id=<?php echo $i; ?>" title="Reset to Default">Reset</a>
                <?php if($menus[$i]['delete']==1): ?>
                / <a href="<?php echo SH_CONTEXTUAL_HELP_PATH; ?>/settings.php&action=delete&id=<?php echo $i; ?>" title="Delete">Delete</a>
                <?php endif; ?>
                </td>
                <td>
                <?php if($menus[$i]['default']==1): ?>
                	Default
                <?php else: ?>
                	Custom
                <?php endif; ?>
                </td>
            </tr>
        <?php endfor; ?>
        </tbody>
    </table>
    <h3 id="add">Add New Custom Post Type / Taxonomy</h3>
    <form action="<?php echo SH_CONTEXTUAL_HELP_PATH; ?>/settings.php" method="post">
        <table class="form-table">
        	<tbody>
            	<tr valign="top">
                	<td scope="row"><label for="type">Type</label></td>
                    <td>
                    	<select name="type" id="type">
                        	<option value="post" selected="selected">Custom Post Type</option>
                            <option value="tax">Taxonomy</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                	<td scope="row"><label for="slug">Slug</label></td>
                    <td><input type="text" id="slug" name="slug" class="regular-text" /></td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
    		<input type="submit" name="submit" value="Add New" />
    	</p>
	</form>
    <?php
			break;
			case 'edit':
				$menus[$_REQUEST['id']]['default_sidebar'] = ($menus[$_REQUEST['id']]['default_sidebar'] == '') ? 1 : $menus[$_REQUEST['id']]['default_sidebar'];
	?>
    <h3><?php echo $title; ?> (<?php echo $menus[$_REQUEST['id']]['base']; ?> &raquo; <?php echo $menus[$_REQUEST['id']]['title']; ?>)</h3>
    <form method="post">
    	<input type="hidden" name="mid" value="<?php echo $_GET['id']; ?>" />
        <table class="form-table">
        	<tbody>
            <?php if($wp_version >= '3.3'): ?>
            	<tr valign="top">
                	<th scope="row"><label for="tab_title">Tab Title</label></th>
            		<td>
                    	<input type="text" id="tab_title" name="tab_title" value="<?php echo $menus[$_REQUEST['id']]['tab_title']; ?>" class="regular-text" />
                    </td>
            	</tr>
          	<?php endif; ?>
            	<tr valign="top">
                	<th scope="row"><label for="title">What do you want to do with this content?</label></th>
                    <td>
                    <?php if($wp_version >= '3.3'): ?>
                    	<select id="title" name="remove">
                        	<option value="0" <?php if($menus[$_REQUEST['id']]['remove'] == 0): echo 'selected'; endif; ?>>Add new tab below default tabs</option>
                            <option value="1"<?php if($menus[$_REQUEST['id']]['remove'] == 1): echo 'selected'; endif; ?>>Replace default tabs</option>
                        </select>
                    <?php else: ?>
                    	<select id="title" name="position">
                        	<option value="after" <?php if($menus[$_REQUEST['id']]['position'] == 'after'): echo 'selected'; endif; ?>>Insert after default content</option>
                            <option value="before"<?php if($menus[$_REQUEST['id']]['position'] == 'before'): echo 'selected'; endif; ?>>Insert before default content</option>
                            <option value="middle"<?php if($menus[$_REQUEST['id']]['position'] == 'middle'): echo 'selected'; endif; ?>>Insert before "For more information"</option>
                            <option value="replace"<?php if($menus[$_REQUEST['id']]['position'] == 'replace'): echo 'selected'; endif; ?>>Replace default content</option>
                        </select>
                 	<?php endif; ?>
                    </td>
                </tr>		
                <tr valign="top">
                	<th scope="row"><label for="help">Content</label></th>
                    <td>
                    <?php if($wp_version >= '3.3'): ?>
						<?php wp_editor( $menus[$_REQUEST['id']]['content'], 'content'); ?>
                    <?php else: ?>
                    	<div id="poststuff"><?php the_editor($menus[$_REQUEST['id']]['content'], $id = 'content', $prev_id = 'title', $media_buttons = true); ?></div>
                    <?php endif; ?>
                    </td>
                </tr>
		<?php if($wp_version >= '3.3'): ?>
		<tr valign="top">
		  <th scope="row"><label for="default_sidebar">Help Sidebar</label></th>
                  <td>
		  <select id="default_sidebar" name="default_sidebar">
			<option value="1" <?php if($menus[$_REQUEST['id']]['default_sidebar']): echo 'selected'; endif; ?>>Use Default Sidebar</option>
			<option value="0" <?php if(!$menus[$_REQUEST['id']]['default_sidebar']): echo 'selected'; endif; ?>>Use Custom Sidebar Content</option>
		  </select>
		  </td>
                </tr>
		<tr valign="top">
		  <th scope="row"><label for="help_sidebar">Sidebar Content</label><br><span class="description">(Leave blank if you want to remove sidebar.)</span></th>
                  <td><?php wp_editor( $menus[$_REQUEST['id']]['help_sidebar'], 'help_sidebar'); ?></td>
                </tr>
		<?php endif; ?>
        	</tbody>
        </table>
        <p class="submit">
    		<input type="submit" name="submit" value="Update" />
    	</p>
    </form>
    <?php
			break;
			default:
	?>
    <h3><?php echo $title; ?><a class="button add-new-h2" href="#add">Add New</a></h3>
    <table class="widefat">
    	<thead>
        	<tr>
            	<th>Admin Menu</th>
                <th>Title</th>
                <th>Action</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php for($i=0;$i<count($menus);$i++): ?>
        	<tr>
            	<th><?php echo $menus[$i]['base']; ?></th>
                <td><?php echo $menus[$i]['title']; ?></td>
                <td><a href="<?php echo SH_CONTEXTUAL_HELP_PATH; ?>/settings.php&action=edit&id=<?php echo $i; ?>" title="Edit">Edit</a> / <a href="<?php echo SH_CONTEXTUAL_HELP_PATH; ?>/settings.php&action=reset&id=<?php echo $i; ?>" title="Reset to Default">Reset</a>
                <?php if($menus[$i]['delete']==1): ?>
                / <a href="<?php echo SH_CONTEXTUAL_HELP_PATH; ?>/settings.php&action=delete&id=<?php echo $i; ?>" title="Delete">Delete</a>
                <?php endif; ?>
        </td>
                <td>
                <?php if($menus[$i]['default']==1): ?>
                	Default
                <?php else: ?>
                	Custom
                <?php endif; ?>
                </td>
            </tr>
        <?php endfor; ?>
        </tbody>
    </table>
	<h3 id="add">Add New Custom Post Type / Taxonomy</h3>
    <form method="post">
        <table class="form-table">
        	<tbody>
            	<tr valign="top">
                	<td scope="row"><label for="type">Type</label></td>
                    <td>
                    	<select name="type" id="type">
                        	<option value="post" selected="selected">Custom Post Type</option>
                            <option value="tax">Taxonomy</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                	<td scope="row"><label for="slug">Slug</label></td>
                    <td><input type="text" id="slug" name="slug" class="regular-text" /></td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
    		<input type="submit" name="submit" value="Add New" />
    	</p>
	</form>
<?php
			break;
		endswitch;
}

// Help Menu
function sh_contextual_help_menu($title='Custom Help Page'){
	global $wp_version;
	if($_REQUEST['submit']):
		update_option('sh_menu_help',$_REQUEST['menu']);
		update_option('sh_menu_help_menu_title',$_REQUEST['menu_title']);
		update_option('sh_menu_help_title',$_REQUEST['title']);
		update_option('sh_menu_help_role',$_REQUEST['role']);
		update_option('sh_menu_help_content',wpautop(stripslashes($_REQUEST['content'])));
		echo '<div id="message" class="updated fade">Successfuly updated</div>';
	endif;
?>
	<h3><?php echo $title; ?></h3>
    <form method="post">
        <table class="form-table">
        	<tbody>
            	<tr valign="top">
                	<th scope="row"><label for="menu">Enable/Disable Menu</label></th>
                    <td><input type="radio" value="1" name="menu" <?php if(get_option('sh_menu_help') == 1): echo 'checked="checked"'; endif; ?> /> Enable <input type="radio" value="0" name="menu" <?php if(get_option('sh_menu_help') == 0): echo 'checked="checked"'; endif; ?> /> Disable</td>
                </tr>
                <tr valign="top">
                	<th scope="row"><label for="menu_title">Menu Title</label></th>
                    <td><input type="text" id="menu_title" name="menu_title" value="<?php echo get_option('sh_menu_help_menu_title'); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                	<th scope="row"><label for="title">Page Title</label></th>
                    <td><input type="text" id="title" name="title" value="<?php echo get_option('sh_menu_help_title'); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                	<th scope="row"><label for="role">Display for below users</label></th>
                    <td>
                    <select id="role" name="role">
                    	<option value="8" <?php if(get_option('sh_menu_help_role') == 8): echo 'selected="selected"'; endif; ?>>Administrator</option>
                        <option value="3" <?php if(get_option('sh_menu_help_role') == 3): echo 'selected="selected"'; endif; ?>>Administrator,Editor</option>
                        <option value="2" <?php if(get_option('sh_menu_help_role') == 2): echo 'selected="selected"'; endif; ?>>Administrator,Editor,Author</option>
                        <option value="1" <?php if(get_option('sh_menu_help_role') == 1): echo 'selected="selected"'; endif; ?>>Administrator,Editor,Author,Contributor</option>
                        <option value="0" <?php if(get_option('sh_menu_help_role') == 0): echo 'selected="selected"'; endif; ?>>All</option>
                        
                    </select>
                    </td>
                </tr>
<tr valign="top">
                	<th scope="row"><label for="help">Help Content</label></th>
                    <td>
                    <?php if($wp_version >= '3.3'): ?>
						<?php wp_editor(get_option('sh_menu_help_content'), 'content'); ?>
                    <?php else: ?>
                    	<div id="poststuff"><?php the_editor(get_option('sh_menu_help_content'), $id = 'content', $prev_id = 'title', $media_buttons = true); ?></div>
                    <?php endif; ?>
                    </td>
                </tr>
        	</tbody>
        </table>
        <p class="submit">
    		<input type="submit" name="submit" value="Update" />
    	</p>
    </form>
<?php
}
// Dashboard Widget
function sh_contextual_help_dashboard_widget($title='Dashboard Widget'){
	global $wp_version;
	if($_REQUEST['submit']):
		update_option('sh_menu_help_widget',$_REQUEST['dashboard']);
		update_option('sh_menu_help_widget_title',$_REQUEST['title']);
		update_option('sh_menu_help_widget_content',wpautop(stripslashes($_REQUEST['content'])));
		echo '<div id="message" class="updated fade">Successfuly updated</div>';
	endif;
?>
	<h3><?php echo $title; ?></h3>
    <form method="post">
        <table class="form-table">
        	<tbody>
            	<tr valign="top">
                	<th scope="row"><label for="dashboard">Enable/Disable Dashboard Widget</label></th>
                    <td><input type="radio" value="1" name="dashboard" <?php if(get_option('sh_menu_help_widget') == 1): echo 'checked="checked"'; endif; ?> /> Enable <input type="radio" value="0" name="dashboard" <?php if(get_option('sh_menu_help_widget') == 0): echo 'checked="checked"'; endif; ?> /> Disable</td>
                </tr>
                <tr valign="top">
                	<th scope="row"><label for="title">Title</label></th>
                    <td><input type="text" id="title" name="title" value="<?php echo get_option('sh_menu_help_widget_title'); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                	<th scope="row"><label for="content">Content</label></th>
                    <td>
                    <?php if($wp_version >= '3.3'): ?>
						<?php wp_editor(get_option('sh_menu_help_widget_content'), 'content'); ?>
                    <?php else: ?>
                    	<div id="poststuff"><?php the_editor(get_option('sh_menu_help_widget_content'), $id = 'content', $prev_id = 'title', $media_buttons = true); ?></div>
                    <?php endif; ?>
                    </td>
                </tr>
        	</tbody>
        </table>
        <p class="submit">
    		<input type="submit" name="submit" value="Update" />
    	</p>
    </form>
<?php
}
?>