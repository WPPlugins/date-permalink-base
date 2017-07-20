<?php
/*
Plugin Name: Date Permalink Base
Version: 0.1
Author: Ono Oogami
Author URI: http://oogami.name/
Plugin URI: http://oogami.name/
Description: allow changing date base of permalink structure

Released under GPL V3 License
*/

add_action('admin_menu', create_function("","add_settings_field('date_base', 'Date base', 'dpb_field', 'permalink', 'optional', array('label_for'=>'date_base'));"));
function dpb_field() {
	$v = get_option('date_base');
	if ( isset($_POST['date_base']) ) {
		if( !empty($_POST['date_base']) ) {
			update_option('date_base',$_POST['date_base']);
			$v = $_POST['date_base'];
		}
		else {
			delete_option('date_base');
			$v = '';
		}
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}
	echo '<input id="date_base" type="text" class="regular-text code" name="date_base" value="' . $v . '" />';
}

if( get_option('date_base') != null && get_option('date_base') !='' ) {
	add_filter('date_rewrite_rules', 'dpb');
	foreach ( array( 'year_link', 'month_link', 'day_link') as $filter )
		add_filter( $filter, 'dpb_replace' );
}

function dpb($dpb_rules) {
	return array_combine( array_map('dpb_replace',  array_keys($dpb_rules)) , array_values($dpb_rules) );
}

function dpb_replace($s) {
	return str_replace('date',get_option('date_base'),$s);
}

?>