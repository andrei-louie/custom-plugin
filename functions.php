<?php
defined('ABSPATH') or die("No script kiddies please!");

/*
* Method: function for load back-end js & css
*/

add_action('admin_init','load_admin_js_css');
function load_admin_js_css(){
  wp_enqueue_script('jquery-ui-autocomplete', '', array('jquery-ui-widget', 'jquery-ui-position'));
  wp_register_style( 'main-css', plugin_dir_url( __FILE__ ).'/css/main.css',array(),'1.0.0');
  wp_enqueue_style( 'main-css' );
  wp_enqueue_script('main-js',plugin_dir_url( __FILE__ ).'js/main.js',array('jquery'),'1.0.0');
  $tc_site_data = array( 'adm_site_url' => site_url(),'fractions_list'=>get_fractions_list());
  wp_localize_script( 'main-js', 'tc_adm_data', $tc_site_data);
  //Load the datepicker script (pre-registered in WordPress).
	wp_enqueue_script( 'jquery-ui-datepicker' );
	//You need styling for the datepicker. For simplicity I've linked to Google's hosted jQuery UI CSS.
	wp_register_style( 'jquery-ui', plugin_dir_url( __FILE__ ).'/css/jquery-ui.css');
	wp_enqueue_style( 'jquery-ui' ); 
}

/*
* Method: function for load front-end js & css
*/

add_action('wp_enqueue_scripts','load_front_end_js_css', 10000);
function load_front_end_js_css(){
  wp_register_style( 'toughcookies-css', plugins_url( 'toughcookies/css/toughcookies.css' ),array(),'1.0.0' );
  wp_enqueue_style( 'toughcookies-css' );
	wp_enqueue_script('toughcookies-js',plugin_dir_url( __FILE__ ).'js/toughcookies.js',array('jquery'),'1.0.0');
  $user_id = get_current_user_id();
  $is_show_adon_notice_popup = 1;
  $cmanpsd_status_arr = array();
  if(!empty($user_id) && $user_id > 0){
    $cmanpsd_data = get_user_meta($user_id, 'change_meals_addon_notice_popup_show_dates', true);
    $cmanpsd_arr = (!empty($cmanpsd_data))?unserialize($cmanpsd_data):array();
    if(!empty($cmanpsd_arr) && count($cmanpsd_arr)>0){
      foreach ($cmanpsd_arr as $cmanpsd_key => $cmanpsd_val) {
        $wkhrsdif = round((time() - strtotime($cmanpsd_val))/3600, 1);
        $is_show_adon_notice_popup = ($wkhrsdif >= 24)?1:0;
        $cmanpsd_status_arr[$cmanpsd_key] = $is_show_adon_notice_popup;
      }
    }
  }
  $tc_site_url = array( 'site_url' => site_url(),'cmanpsds_arr' => $cmanpsd_status_arr);
  wp_localize_script( 'toughcookies-js', 'tc_site_url', $tc_site_url);
}

function defer_parsing_of_js ( $url ) {
  if ( FALSE === strpos( $url, '.js' ) ) return $url;
  if ( strpos( $url, 'jquery.js' ) ) return $url;
  return "$url' defer ";
}
add_filter( 'clean_url', 'defer_parsing_of_js', 11, 1 );

function iconic_remove_password_strength() {
    wp_dequeue_script( 'wc-password-strength-meter' );
}
add_action( 'wp_print_scripts', 'iconic_remove_password_strength', 10 );

// Remove query string from static files
function remove_cssjs_ver( $src ) {
  if( strpos( $src, '?ver=' ) )
    $src = remove_query_arg( 'ver', $src );
  return $src;
}

/*
* Method: function for manage menu items post
*/

add_action( 'init', 'register_custom_posts');
function register_custom_posts(){
  //register ingredients custom post
  $ind_args = array(
    'labels' => array(
      'name'               => __( 'Ingredients', 'toughcookies'),
      'all_items'          => __( 'All Ingredients', 'toughcookies' ),
      'singular_name'      => __( 'Ingredient', 'toughcookies' ),
      'add_new'            => __( 'Add New Ingredient', 'toughcookies' ),
      'add_new_item'       => __( 'Add New', 'toughcookies' ),
      'edit_item'          => __( 'Edit Ingredient', 'toughcookies' ),
      'new_item'           => __( 'New Ingredient', 'toughcookies' ),
      'view_item'          => __( 'View Ingredient', 'toughcookies' ),
      'search_items'       => __( 'Search Ingredient', 'toughcookies' ),
      'not_found'          => __( 'No Ingredient found', 'toughcookies' ),
      'not_found_in_trash' => __( 'No Ingredient found in Trash', 'toughcookies' ),
      'parent_item_colon'  => ''
    ),
    'description'   => 'Holds Ingredient related data.',
    'public'        => true,
    'supports'      => array( 'title', 'editor', 'thumbnail'),
    'menu_icon' => 'dashicons-media-spreadsheet',
    'has_archive'   => true,
  );
  register_post_type('ingredients', $ind_args );
  //register menu-items custom post
  $args = array(
    'labels' => array(
      'name'               => __( 'Meals', 'toughcookies'),
      'all_items'          => __( 'All Meals', 'toughcookies' ),
      'singular_name'      => __( 'Meal', 'toughcookies' ),
      'add_new'            => __( 'Add New Meal', 'toughcookies' ),
      'add_new_item'       => __( 'Add New', 'toughcookies' ),
      'edit_item'          => __( 'Edit Meal', 'toughcookies' ),
      'new_item'           => __( 'New Meal', 'toughcookies' ),
      'view_item'          => __( 'View Meal', 'toughcookies' ),
      'search_items'       => __( 'Search Meal', 'toughcookies' ),
      'not_found'          => __( 'No Meal found', 'toughcookies' ),
      'not_found_in_trash' => __( 'No Meal found in Trash', 'toughcookies' ),
      'parent_item_colon'  => ''
    ),
    'description'   => 'Holds Meal related data.',
    'public'        => true,
    //'menu_position' => 5,
    'supports'      => array( 'title', 'editor', 'thumbnail','comments'),
    'menu_icon' => 'dashicons-media-spreadsheet',
    'has_archive'   => true,
  );
  register_post_type('menu-items', $args );
  add_theme_support( 'post-thumbnails', array('post', 'menu-items') );
  //register sliders custom post
  $slider_args = array(
    'labels' => array(
      'name'               => __( 'Sliders', 'toughcookies'),
      'all_items'          => __( 'All Sliders', 'toughcookies' ),
      'singular_name'      => __( 'Slider', 'toughcookies' ),
      'add_new'            => __( 'Add New Slider', 'toughcookies' ),
      'add_new_item'       => __( 'Add Slider', 'toughcookies' ),
      'edit_item'          => __( 'Edit Slider', 'toughcookies' ),
      'new_item'           => __( 'New Slider', 'toughcookies' ),
      'view_item'          => __( 'View Slider', 'toughcookies' ),
      'search_items'       => __( 'Search Slider', 'toughcookies' ),
      'not_found'          => __( 'No Slider found', 'toughcookies' ),
      'not_found_in_trash' => __( 'No Slider found in Trash', 'toughcookies' ),
      'parent_item_colon'  => ''
    ),
    'description'   => 'Holds Slider related data.',
    'public'        => true,
    //'menu_position' => 5,
    'supports'      => array( 'title', 'editor', 'thumbnail'),
    'menu_icon' => 'dashicons-media-spreadsheet',
    'has_archive'   => true,
  );
  register_post_type('sliders', $slider_args );
  //register partner location custom post
    $partner_location_args = array(
      'labels' => array(
      'name' => __('Partners Location', 'toughcookies'),
      'all_items' => __('All Partners Location', 'toughcookies'),
      'singular_name' => __('Partner Location', 'toughcookies'),
      'add_new' => __('Add New Partner Location', 'toughcookies'),
      'add_new_item' => __('Add New Partner Location', 'toughcookies'),
      'edit_item' => __('Edit Partner Location', 'toughcookies'),
      'new_item' => __('New Partner Location', 'toughcookies'),
      'view_item' => __('View Partner Location', 'toughcookies'),
      'search_items' => __('Search Partner Location', 'toughcookies'),
      'not_found' => __('No partner location found', 'toughcookies'),
      'not_found_in_trash' => __('No partner location found in Trash', 'toughcookies'),
      'parent_item_colon' => ''
    ),
    'description' => 'Holds partner location related data.',
    'public' => true,
    'supports' => array('title', 'editor', 'thumbnail'),
    'menu_icon' => 'dashicons-media-spreadsheet',
    'has_archive' => true,
  );
  register_post_type('partners-location', $partner_location_args);
}

/*
* Method: function for change menu item title placeholder
*/

function wpb_change_title_text( $title ){
    $screen = get_current_screen();
    if  ( 'menu-items' == $screen->post_type ) {
      $title = 'Enter meal name';
    }
    return $title;
}
add_filter( 'enter_title_here', 'wpb_change_title_text' );

/*
* Method: function for change feature image lable
*/

function change_featured_image_labels( $labels ) {
  $labels->featured_image = 'Meal Image';
  $labels->set_featured_image = 'Set Meal Image';
  $labels->remove_featured_image = 'Remove Meal Image';
  $labels->use_featured_image = 'Use as Meal Image';
  return $labels;
}
add_filter( 'post_type_labels_menu-items', 'change_featured_image_labels', 10, 1 );

/*
* Method: Function for add custom taxonomy (category) for menu-items
*/

add_action( 'init', 'create_menu_items_category', 0 );
function create_menu_items_category() {
  register_taxonomy(
    'menu-items-category',
    'menu-items',
    array(
      'labels' => array(
        'name' => 'Meal Categories',
        'add_new_item' => 'Add New Category',
        'new_item_name' => "New Category"
      ),
      'show_ui' => true,
      'show_tagcloud' => false,
      'hierarchical' => true
    )
  );
}

add_action('add_meta_boxes', 'add_menu_items_plan_box');
function add_menu_items_plan_box() {
  if(isset($_GET['action']) && $_GET['action'] == 'edit'){
    add_meta_box(
      'menu_items_plan',
      'Meal Plans',
      'menu_items_plan_box_content',
      'menu-items',
      'side'
    );
  }
}

function menu_items_plan_box_content($post){
  ?>
  <script type="text/javascript">
    jQuery(document).ready(function() {
      jQuery('#publish').click(function(){
            var postType = jQuery("#post_type").val();
            if(postType =='menu-items'){
                if(jQuery("#menu_item_plan").val() == ''){
                  jQuery("#error_label").html('Please select plan!');
                  alert('Please select plan!');
                  return false;
                }
            }
        });
    });
  </script>
  <?php
    $_plan = get_post_meta( $post->ID, '_plan', true );
    $planGroupArr = get_membership_group();
  	$plang_html = '<select id="menu_item_plan" name="menu_item_plan">';
  	if(isset($planGroupArr) && !empty($planGroupArr)){
	  	foreach ($planGroupArr as $pg_key => $pg_val) {
	  		$slcted = (!empty($_plan) && $_plan == $pg_key)?'selected':'';
	  		$plang_html .= '<option value="'.$pg_key.'" '.$slcted.'>'.$pg_val.'</option>';
	  	}
	  }
  	$plang_html .= '<select>';
  	echo $plang_html;
}

add_action('add_meta_boxes', 'add_menu_items_category_box');
function add_menu_items_category_box() {
  add_meta_box(
    'menu_items_category',
    'Meal Categories',
    'menu_items_category_box_content',
    'menu-items',
    'side'
  );
}

function menu_items_category_box_content($post){
  ?>
  <script type="text/javascript">
    jQuery(document).ready(function() {
      jQuery('#publish').click(function(){
            var postType = jQuery("#post_type").val();
            if(postType =='menu-items'){
                if(jQuery("#meal_category").val() == ''){
                  jQuery("#error_label").html('Please select category!');
                  alert('Please select category!');
                  return false;
                }
            }
        });
    });
  </script>
  <?php
    global $wpdb;
    $meal_cat = get_the_terms($post->ID, 'menu-items-category');
    if(!empty($meal_cat) && is_array($meal_cat)){
      foreach ($meal_cat as $key => $val) {
        $_m_cat = isset($val->term_id) ? $val->term_id : '';
      }
    }
    $meal_cat_Arr = $wpdb->get_results("select name,t.term_id from wp_terms as t join wp_term_taxonomy as tt on t.term_id = tt.term_id where tt.taxonomy = 'menu-items-category'");
    $m_cat_html = '<select id="meal_category" name="meal_category">';
    if(isset($meal_cat_Arr) && !empty($meal_cat_Arr)){
      foreach ($meal_cat_Arr as $mc_key => $mc_val) {
        $slcted = (!empty($_m_cat) && $_m_cat == $mc_val->term_id)?'selected':'';
        $m_cat_html .= '<option value="'.$mc_val->term_id.'" '.$slcted.'>'.$mc_val->name.'</option>';
      }
    }
    $m_cat_html .= '<select>';
    echo $m_cat_html;
}

/*
* Method: Function for add custom post (menu items) meta box
*/

function add_custom_posts_metabox($post_type){
    $post_types = array('menu-items');
    if ($post_type == 'menu-items') {         
      add_meta_box('post_page_link', 'Menu Item Details', 'add_menu_items_meta', $post_type, 'normal');
      $screen = get_current_screen();
      if( 'add' == $screen->action ){
        add_meta_box('post_page_link2', 'Ingredients', 'add_ingredients_data', $post_type, 'normal');
      }
      add_meta_box('post_page_link1', 'User Rating Data', 'show_meal_ratings', $post_type, 'normal');
    }
    if ($post_type == 'membership-coupons') {
      add_meta_box('post_page_link', 'Coupon Data', 'add_membership_coupon_meta_box', $post_type, 'normal');
    }
    if ($post_type == 'ingredients') {
      add_meta_box('meta_box_for_category','Ingredients Category','add_ingredient_category_meta_box','ingredients','side','high');
      add_meta_box('meta_box_for_group','Ingredients Group','add_ingredient_group_meta_box','ingredients','side','high');
    }
    if($post_type == 'sliders'){
      add_meta_box('meta_box_for_sliders','Slider Status','add_sliders_data',$post_type,'side','high');
    }
    if ($post_type == 'partners-location') {
      add_meta_box('partners_location_post_meta_link', 'Pickup Location Details', 'add_partners_pickup_location_meta', $post_type, 'normal');
    }
}
add_action('add_meta_boxes',  'add_custom_posts_metabox');

/*
* Method: function for get allergies diets
*/

function get_allergies_diets(){
  return array('dairy_free'=>'Dairy free','gluten_free'=>'Gluten free','keto'=>'Keto','paleo'=>'Paleo','soy_free'=>'Soy free','vegan'=>'Vegan','vegetarian'=>'Vegetarian');
}

/*
* Method: function for get meal proteins
*/

function get_proteins(){
  return array('chicken'=>'Chicken','beef'=>'Beef','turkey'=>'Turkey','pork'=>'Pork','fish'=>'Fish','vegetarian'=>'Vegetarian');
}

/*
* Method: Function for display custom post (menu items) meta box content
*/

function add_menu_items_meta(){
  global $post, $wpdb;
  date_default_timezone_set("America/New_York");
  $_calories = get_post_meta($post->ID,'_calories',true);
  $_protein = get_post_meta($post->ID,'_protein',true);
  $_carbs = get_post_meta($post->ID,'_carbs',true);
  $_fat = get_post_meta($post->ID,'_fat',true);
  $_appear_date = get_post_meta($post->ID,'_appear_date',true);
  $_expire_date = get_post_meta($post->ID,'_expire_date',true);
  $_ingredients = get_post_meta($post->ID,'_ingredients',true);
  $_sub_title = get_post_meta($post->ID,'_sub_title',true);
  $_allergies_diets = get_post_meta($post->ID,'_allergies_diets',true);
  $_signal_tags = get_post_meta($post->ID,'_signal_tags',true);
  $_meal_protein = get_post_meta($post->ID,'_meal_protein',true);
  $allergies_diets_arr = get_allergies_diets();
  $fractions = get_fractions_list();
  $ingredients = $wpdb->get_results("SELECT ingredient_id, quantity, fraction_qty, unit_id, unit_abbreviation FROM ".$wpdb->prefix."meals_ingredients as m_ingr JOIN ".$wpdb->prefix."posts as ingr on ingr.ID = m_ingr.ingredient_id WHERE m_ingr.meal_id = ".$post->ID." AND ingr.post_status = 'publish'", ARRAY_A);
  $protein_arr = get_proteins();
  $_price = get_post_meta($post->ID,'_price',true);
  $_directions = get_post_meta($post->ID,'_directions',true);
  $membership_group = get_membership_group();
  $screen = get_current_screen();
    if( 'add' == $screen->action ){
  ?>
    <style type="text/css">
      #post_page_link .inside{margin: 0 !important;padding: 0 !important;}
      .wp-editor-tools:after {clear: none !important;}
    </style>
    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
      <div id="tc-meal-data" class="postbox meal-postbox">
        <div class="inside">
            <input type="hidden" name="add" id="add" value="true">
            <input type="hidden" id="tc_meta_nonce" name="" value="5f3c705f29"><input type="hidden" name="">
            <div class="panel-wrap">
              <div class="clearfix">
                <ul class="tc-tabs">
                  <?php
                    $count = 1; 
                    if(!empty($membership_group) && is_array($membership_group)){
                      foreach ($membership_group as $key => $value) { //$id[] = $key;?>
                        <li id="<?php echo 'li_'.$key; ?>" <?php echo ($count == 1) ? 'style="background:#e5e5e5"' : ''; ?> class="m_plan" >
                          <div id="<?php echo 'sideNav_'.$key; ?>" class="sideNav" data-mp-id="<?php echo $key; ?>" data-mp-value="<?php echo str_replace(' ','_', strtolower($value)); ?>">
                            <input class="meal_plan" type="checkbox" data-mp-id="<?php echo $key; ?>" data-mp-value="<?php echo str_replace(' ','_', strtolower($value)); ?>" name="meal_plan[<?php echo $key; ?>]" id="<?php echo 'inp_'.$key;?>" <?php echo ($count == 1) ? 'checked' : ''; ?> > 
                            <label class="label_plan" for="" data-mp-value="<?php echo str_replace(' ','_', strtolower($value)); ?>" data-mp-id="<?php echo $key; ?>" id="<?php echo 'lbl_'.$key; ?>"><?php echo $value; ?></label>
                            <input type="hidden" name="mp_nam_<?php echo $key; ?>" value="<?php echo $value; ?>">
                          </div>
                        </li>
                <?php 
                    $count++;
                      }
                    } ?>
              </ul>
                <div class="allPlansHidden">
                <?php
                  $count = 1; 
                    if(!empty($membership_group) && is_array($membership_group)){
                      foreach ($membership_group as $key => $value) { ?>
                        <div id="<?php echo str_replace(' ','_', strtolower($value)); ?>" <?php echo ($count == 1)? 'style="display:block;"' : 'style="display:none;"' ?> class="meal_tab">
                        <h1>Meta Data - <?php echo $value; ?> Plan</h1>
                          <table style="width: 100%;" cellspacing="10">
                            <tbody>
                              <tr>
                                <th width="18%"><label for="menu_items_sub_title" class="fleft">Sub Title <span class="required">*</span></label></th>
                                <td width="82%">
                                  <input class="mi-field-width" type="text" class="txt-fid-widt-100" name="menu_sub_title[<?php echo $key; ?>]" id="menu_sub_title" value="<?php echo (!empty($_sub_title))?$_sub_title:''; ?>" placeholder="Subtitle"/>
                                </td>
                              </tr>
                              <tr>
                                <th width="18%"><label for="menu_allergies_diets" class="fleft">Allergies/Diets</label></th>
                                <td width="82%">
                                  <?php foreach ($allergies_diets_arr as $ad_key => $ad_val) {
                                    $_chkd = (isset($_allergies_diets) && !empty($_allergies_diets) && in_array($ad_key, $_allergies_diets))?'checked':'';
                                   ?>
                                    <input type="checkbox" name="menu_allergies_diets[<?php echo $key; ?>][]" value="<?php echo $ad_key; ?>" <?php echo $_chkd; ?>/> <?php echo $ad_val; ?><br>
                                  <?php } ?>
                                </td>
                              </tr>
                              <tr>
                                <th width="18%"><label for="menu_signal_tags" class="fleft">Signal tags</label></th>
                                <td width="82%">
                                  <?php $chkd = (isset($_signal_tags) && !empty($_signal_tags) && $_signal_tags[0] == 'favorite') ? 'checked' : ''; ?>
                                    <input type="checkbox" name="menu_signal_tags[<?php echo $key; ?>][]" value="favorite" <?php echo $_chkd; ?>/> Favorite<br>
                                </td>
                              </tr>
                              <tr>
                                <th width="18%"><label for="meal_protein" class="fleft">Protein <span class="required">*</span></label></th>
                                <td width="82%">
                                  <?php foreach ($protein_arr as $protein_key => $protein_val) {
                                    $_selected = (!empty($_meal_protein) && ($protein_key == $_meal_protein))?'checked':'';
                                   ?>
                                    <input type="radio" name="meal_protein[<?php echo $key; ?>]" value="<?php echo $protein_key; ?>" <?php echo $_selected; ?>/><?php echo $protein_val; ?><br>
                                  <?php } ?>
                                </td>
                              </tr>
                              <tr>
                                <th width="18%"><label for="menu_items_date" class="fleft">Meal Dates <span class="required">*</span></label></th>
                                <td width="82%">
                                  <table>
                                    <tr>
                                      <th>Appear Date:</th>
                                      <td><input type="text" class="datepicker menu_appear_date1 mi-field-width" name="menu_appear_date[<?php echo $key; ?>]" data-id="<?php echo $key; ?>" id="menu_appear_date_<?php echo $key; ?>" value="<?php echo (!empty($_appear_date))?$_appear_date:''; ?>" readonly="true" /></td>
                                      <th>Expire Date:</th>
                                      <td><input type="text" class="datepicker menu_expire_date1 mi-field-width" name="menu_expire_date[<?php echo $key; ?>]" data-id="<?php echo $key; ?>" id="menu_expire_date_<?php echo $key; ?>" value="<?php echo (!empty($_expire_date))?$_expire_date:''; ?>" readonly="true" /></td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <th width="18%"><label for="menu_items_nutritions_info" class="fleft">Nutritions Info <span class="required">*</span></label></th>
                                <td width="82%">
                                  <table>
                                    <tr>
                                      <th>Calories:</th>
                                      <td><input class="mi-nutition-fld" type="text" name="calories[<?php echo $key; ?>]" placeholder="Calories" value="<?php echo (!empty($_calories))?$_calories:''; ?>"></td>
                                      <th>Protein:</th>
                                      <td><input class="mi-nutition-fld" type="text" name="protein[<?php echo $key; ?>]" placeholder="Protein" value="<?php echo (!empty($_protein))?$_protein:''; ?>"></td>
                                      <th>Carbs:</th>
                                      <td><input class="mi-nutition-fld" type="text" name="carbs[<?php echo $key; ?>]" placeholder="Carbs" value="<?php echo (!empty($_carbs))?$_carbs:''; ?>"></td>
                                      <th>Fat:</th>
                                      <td><input class="mi-nutition-fld" type="text" name="fat[<?php echo $key; ?>]" placeholder="Fat" value="<?php echo (!empty($_fat))?$_fat:''; ?>"></td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <th width="18%"><label for="meal_price" class="fleft">Price</label></th>
                                <td width="82%">
                                  <input class="mi-field-width" type="text" name="meal_price[<?php echo $key; ?>]" id="meal_price" value="<?php echo (!empty($_price))?$_price:''; ?>" placeholder="Price"/>
                                </td>
                              </tr>
                              <tr>
                                <th width="18%"><label for="meal_directions" class="fleft">Directions</label></th>
                                <td width="82%">
                                  <input class="mi-field-width" type="text" name="meal_directions[<?php echo $key; ?>]" id="meal_directions" value="<?php echo (!empty($_directions))?$_directions:''; ?>" placeholder="Directions"/>
                                </td>
                              </tr>
                              <tr>
                                <th width="18%"><label for="meal_descrption" class="fleft">Description <span class="required">*</span></label></th>
                                <td width="82%">
                                  <?php
                                    $settings = array(
                                        'textarea_name' => 'meal_descrption['.$key.']',
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                        'textarea_rows' => 10,
                                        'tinymce' => array(
                                            'theme_advanced_buttons1' => 'formatselect,|,bold,italic,underline,|,' .
                                            'bullist,blockquote,|,justifyleft,justifycenter' .
                                            ',justifyright,justifyfull,|,link,unlink,|' .
                                            ',spellchecker,wp_fullscreen,wp_adv'
                                        )
                                    );
                                    wp_editor( '', 'contentWlp', $settings );
                                  ?>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                <?php $count++;
                      }
                    } ?>
                </div>
                <script type="text/javascript">
                  $(document).ready(function(){
                    $(".meal_plan").click(function(){
                      var id = $(this).attr('data-mp-id');
                      var val = $(this).attr('data-mp-value');
                      var checkboxes = document.querySelectorAll('.meal_plan');
                      var checkedOne = Array.prototype.slice.call(checkboxes).some(x => x.checked);
                      if(!checkedOne){
                        alert('You have to select atleast one meal plan.');
                        $("#inp_"+id).prop('checked', true);
                      }
                      if($("#inp_"+id).is(':checked')){
                        if(checkedOne){
                          $('.meal_tab').css("background", "#fafafa");
                          $("#li_"+id).css("background", "#e5e5e5");
                          $('.meal_tab').hide();
                          $("#"+val).show();
                        }else{
                          $('.meal_tab').hide();
                          $("#"+val).show();
                          $("#li_"+id).css("background", "#e5e5e5");
                        }
                      }else{
                        $("#li_"+id).show();
                        $("#"+val).css("background", "#fafafa");
                        $("#li_"+id).css("background", "#fafafa");
                      }
                    });
                    $(".label_plan").click(function(){
                      var id = $(this).attr('data-mp-id');
                      var val = $(this).attr('data-mp-value');
                      $('.meal_tab').hide();
                      $("#"+val).show();
                      $('.m_plan').css("background", "#fafafa");
                      $("#li_"+id).css("background", "#e5e5e5");
                    });
                    $(".sideNav").click(function(){
                      var id = $(this).attr('data-mp-id');
                      var val = $(this).attr('data-mp-value');
                      $('.meal_tab').hide();
                      $("#"+val).show();
                      $('.m_plan').css("background", "#fafafa");
                      $("#li_"+id).css("background", "#e5e5e5");
                    });
                  });
                </script>
            </div>
              <div class="clear"></div>
            </div>
        </div>
      </div>
  </div>
 <?php }else{ ?>
  <div>
      <div class="wrap">
        <div class="meta-box-sortables ui-sortable">
          <div class="">
            <div id="menu_meta_msg"></div>
            <table style="width: 100%;" cellspacing="10">
              <tbody>
                <tr>
                  <th width="15%"><label for="menu_items_sub_title" class="fleft">Sub Title <span class="required">*</span></label></th>
                  <td width="85%">
                    <input type="text" class="mi-field-width" name="menu_sub_title" id="menu_sub_title" value="<?php echo (!empty($_sub_title))?$_sub_title:''; ?>" placeholder="Subtitle"/>
                  </td>
                </tr>
                <tr>
                  <th width="15%"><label for="menu_allergies_diets" class="fleft">Allergies/Diets</label></th>
                  <td width="85%">
                    <?php foreach ($allergies_diets_arr as $ad_key => $ad_val) {
                      $_chkd = (isset($_allergies_diets) && !empty($_allergies_diets) && in_array($ad_key, $_allergies_diets))?'checked':'';
                     ?>
                      <input type="checkbox" name="menu_allergies_diets[]" value="<?php echo $ad_key; ?>" <?php echo $_chkd; ?>/> <?php echo $ad_val; ?><br>
                    <?php } ?>
                  </td>
                </tr>
                <tr>
                  <th width="18%"><label for="menu_signal_tags" class="fleft">Signal tags</label></th>
                  <td width="82%">
                    <?php $chkd = (isset($_signal_tags) && !empty($_signal_tags) && $_signal_tags[0] == 'favorite') ? 'checked' : ''; ?>
                      <input type="checkbox" name="menu_signal_tags[]" value="favorite" <?php echo $chkd; ?>/> Favorite<br>
                  </td>
                </tr>
                <tr>
                  <th width="15%"><label for="meal_protein" class="fleft">Protein <span class="required">*</span></label></th>
                  <td width="85%">
                    <?php foreach ($protein_arr as $protein_key => $protein_val) {
                      $_selected = (!empty($_meal_protein) && ($protein_key == $_meal_protein))?'checked':'';
                     ?>
                      <input type="radio" name="meal_protein" value="<?php echo $protein_key; ?>" <?php echo $_selected; ?>/><?php echo $protein_val; ?><br>
                    <?php } ?>
                  </td>
                </tr>
                <tr>
                  <th width="15%"><label for="menu_items_date" class="fleft">Meal Dates <span class="required">*</span></label></th>
                  <td width="85%">
                    <table>
                      <tr>
                        <th>Appear Date:</th>
                        <td><input type="text" class="datepicker mi-field-width menu_appear_date" name="menu_appear_date" id="menu_appear_date" value="<?php echo (!empty($_appear_date))? $_appear_date:''; ?>" readonly="true" /></td>
                        <th>Expire Date:</th>
                        <td><input type="text" class="datepicker mi-field-width menu_expire_date" name="menu_expire_date" id="menu_expire_date" value="<?php echo (!empty($_expire_date))?$_expire_date:''; ?>" readonly="true" /></td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <th width="15%"><label for="menu_items_nutritions_info" class="fleft">Nutritions Info <span class="required">*</span></label></th>
                  <td width="85%">
                    <table>
                      <tr>
                        <th>Calories:</th>
                        <td><input type="text" name="calories" placeholder="Calories" value="<?php echo (!empty($_calories))?$_calories:''; ?>" class="mi-nutition-fld"></td>
                        <th>Protein:</th>
                        <td><input type="text" name="protein" placeholder="Protein" value="<?php echo (!empty($_protein))?$_protein:''; ?>" class="mi-nutition-fld"></td>
                        <th>Carbs:</th>
                        <td><input type="text" name="carbs" placeholder="Carbs" value="<?php echo (!empty($_carbs))?$_carbs:''; ?>" class="mi-nutition-fld"></td>
                        <th>Fat:</th>
                        <td><input type="text" name="fat" placeholder="Fat" value="<?php echo (!empty($_fat))?$_fat:''; ?>" class="mi-nutition-fld"></td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <th width="15%"><label for="meal_price" class="fleft">Price</label></th>
                  <td width="85%">
                    <input type="text" name="meal_price" id="meal_price" value="<?php echo (!empty($_price))?$_price:''; ?>" placeholder="Price" class="mi-field-width"/>
                  </td>
                </tr>
                <tr>
                  <th width="15%"><label for="meal_directions" class="fleft">Directions</label></th>
                  <td width="85%">
                    <input type="text" name="meal_directions" id="meal_directions" value="<?php echo (!empty($_directions))?$_directions:''; ?>" placeholder="Directions" class="mi-field-width"/>
                  </td>
                </tr>
                <tr>
                  <th width="15%"><label for="menu_items_ingredients" class="fleft">Ingredients <span class="required">*</span></label> </th>
                  <td width="85%">
                    <?php if(!empty($ingredients)){
                      $ig_slcted_ids = array_column($ingredients, 'ingredient_id');
                    ?>
                    <div class="input_fields_container">
                      <div id="foo">
                        <input type="hidden" name="ingredient_val" id="ingredient_val" value="">
                        <input type="hidden" name="slcted_ingredients" id="slcted_ingredients" value="<?php echo (count($ig_slcted_ids)>0)?implode(',', $ig_slcted_ids):''; ?>">
                        <?php
                        foreach ($ingredients as $ing_key => $ing_val) {
                          $ingred_id = (isset($ing_val['ingredient_id']) && !empty($ing_val['ingredient_id']))?$ing_val['ingredient_id']:'';
                        ?>
                          <div>
                            <input type="text" name="ingredients[]" class="ingredients" id="ingredients" placeholder="Ingredients" value="<?php echo get_the_title($ingred_id); ?>">
                              <input type="text" name="quantity[]" id="quantity" placeholder="Quantity" value="<?php echo (isset($ing_val['quantity']) && $ing_val['quantity'] > 0)?$ing_val['quantity']:0; ?>">
                              <select name="fractional_qty[]" id="fractional_qty">
                                <option value=" ">Select fraction</option>
                                <?php foreach ($fractions as $f_key => $f_val) { ?>
                                  <option value="<?php echo $f_key;?>" <?php echo (isset($ing_val['fraction_qty']) && $ing_val['fraction_qty'] == $f_key)?'selected':''; ?> ><?php echo $f_val; ?></option>
                                 <?php } ?>
                              </select>
                            <?php
                              $ig_sql = "select pm.meta_value as grp from ".$wpdb->prefix."posts as p join ".$wpdb->prefix."postmeta as pm on p.ID = pm.post_id AND meta_key ='_group' where post_id=".$ingred_id;
                              $ing_group = $wpdb->get_row($ig_sql);
                              $ing_units = array();
                              if(isset($ing_group->grp) && !empty($ing_group->grp)){
                                $ing_units = $wpdb->get_results("select t.term_id as iu_id, t.name as iu_name, tm2.meta_value as unit from ".$wpdb->prefix."terms as t join ".$wpdb->prefix."termmeta as tm1 on t.term_id = tm1.term_id join ".$wpdb->prefix."termmeta as tm2 on tm2.term_id = tm1.term_id where tm1.meta_value ='".$ing_group->grp."' and tm2.meta_key='_abbreviation' order by t.name ASC",ARRAY_A);
                              }
                            ?>
                              <select name="unit[]" id="unit" class="unit">
                                <?php if(count($ing_units)>0){
                                  foreach ($ing_units as $unit) {
                                    $iu_slcted = (isset($unit['iu_id']) && $unit['unit'] == $ing_val['unit_abbreviation'])?'selected':''; ?>
                                      <option value='<?php echo $unit['unit']; ?>' <?php echo $iu_slcted; ?>><?php echo $unit['iu_name']; ?></option>
                                    <?php
                                    }
                                  } ?>
                              </select>
                              <a href="javascript:void(0);" id="<?php echo $ingred_id; ?>" class="remove_field"><img class="cursor-point" alt="Remove" src="<?php echo plugins_url( 'images/cross.jpeg', __FILE__ ); ?>"></a>
                              </div>
                            <?php } ?>
                        <a class="add_more_button"><img class="cursor-point" alt="Add" src="<?php echo plugins_url( 'images/add.png', __FILE__ ); ?>"></a>
                      </div>
                    </div>
                  <?php }else{ ?>
                    <div class="input_fields_container">
                      <div id="foo">
                        <div>
                          <input type="text" name="ingredients[]" class="ingredients" id="ingredients1" placeholder="Ingredients">
                          <input type="hidden" name="ingredient_val" id="ingredient_val" value="">
                          <input type="hidden" name="slcted_ingredients" id="slcted_ingredients" value="">
                          <input type="text" name="quantity[]" id="quantity1" placeholder="Quantity">
                          <select name="fractional_qty[]" id="fractional_qty1">
                            <option value=" ">Select fraction</option>
                            <?php foreach ($fractions as $mif_key => $mif_val) { ?>
                              <option value="<?php echo $mif_key; ?>"><?php echo $mif_val; ?></option>
                             <?php } ?>
                          </select>
                          <select name="unit[]" id="unit1" class="unit">
                            <option value=" ">Select unit</option>
                          </select>
                           <a class="add_more_button"><img class="cursor-point" alt="Add" src="<?php echo plugins_url( 'images/add.png', __FILE__ ); ?>"></a>
                        </div>         
                      </div>
                      </div>
                    <?php }?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  </div>
<?php
  }    
}

/*
* Method: Function for adding ingredients data 
*/
function add_ingredients_data(){
  global $post, $wpdb;
  date_default_timezone_set("America/New_York");
  $_ingredients = get_post_meta($post->ID,'_ingredients',true);
  $ingredients = $wpdb->get_results("SELECT ingredient_id, quantity, fraction_qty, unit_id, unit_abbreviation FROM ".$wpdb->prefix."meals_ingredients as m_ingr JOIN ".$wpdb->prefix."posts as ingr on ingr.ID = m_ingr.ingredient_id WHERE m_ingr.meal_id = ".$post->ID." AND ingr.post_status = 'publish'", ARRAY_A);
  $fractions = get_fractions_list();
  ?>
  <table>
    <tbody>
      <tr>
        <th width="15%"><label for="menu_items_ingredients" class="fleft">Ingredients<span class="required">*</span></label></th>
        <td width="85%">
          <?php if(!empty($ingredients)){
            $ig_slcted_ids = array_column($ingredients, 'ingredient_id');
          ?>
          <div class="input_fields_container">
            <div id="foo">
              <input type="hidden" name="ingredient_val" id="ingredient_val" value="">
              <input type="hidden" name="slcted_ingredients" id="slcted_ingredients" value="<?php echo (count($ig_slcted_ids)>0)?implode(',', $ig_slcted_ids):''; ?>">
              <?php
              foreach ($ingredients as $ing_key => $ing_val) {
                $ingred_id = (isset($ing_val['ingredient_id']) && !empty($ing_val['ingredient_id']))?$ing_val['ingredient_id']:'';
              ?>
                <div>
                  <input type="text" name="ingredients[]" class="ingredients" id="ingredients" placeholder="Ingredients" value="<?php echo get_the_title($ingred_id); ?>">
                    <input type="text" name="quantity[]" id="quantity" placeholder="Quantity" value="<?php echo (isset($ing_val['quantity']) && $ing_val['quantity'] > 0)?$ing_val['quantity']:0; ?>">
                    <select name="fractional_qty[]" id="fractional_qty">
                      <option value=" ">Select fraction</option>
                      <?php foreach ($fractions as $f_key => $f_val) { ?>
                        <option value="<?php echo $f_key;?>" <?php echo (isset($ing_val['fraction_qty']) && $ing_val['fraction_qty'] == $f_key)?'selected':''; ?> ><?php echo $f_val; ?></option>
                       <?php } ?>
                    </select>
                  <?php
                    $ig_sql = "select pm.meta_value as grp from ".$wpdb->prefix."posts as p join ".$wpdb->prefix."postmeta as pm on p.ID = pm.post_id AND meta_key ='_group' where post_id=".$ingred_id;
                    $ing_group = $wpdb->get_row($ig_sql);
                    $ing_units = array();
                    if(isset($ing_group->grp) && !empty($ing_group->grp)){
                      $ing_units = $wpdb->get_results("select t.term_id as iu_id, t.name as iu_name, tm2.meta_value as unit from ".$wpdb->prefix."terms as t join ".$wpdb->prefix."termmeta as tm1 on t.term_id = tm1.term_id join ".$wpdb->prefix."termmeta as tm2 on tm2.term_id = tm1.term_id where tm1.meta_value ='".$ing_group->grp."' and tm2.meta_key='_abbreviation' order by t.name ASC",ARRAY_A);
                    }
                  ?>
                    <select name="unit[]" id="unit" class="unit">
                      <?php if(count($ing_units)>0){
                        foreach ($ing_units as $unit) {
                          $iu_slcted = (isset($unit['iu_id']) && $unit['unit'] == $ing_val['unit_abbreviation'])?'selected':''; ?>
                            <option value='<?php echo $unit['unit']; ?>' <?php echo $iu_slcted; ?>><?php echo $unit['iu_name']; ?></option>
                          <?php
                          }
                        } ?>
                    </select>
                    <a href="javascript:void(0);" id="<?php echo $ingred_id; ?>" class="remove_field"><img class="cursor-point" alt="Remove" src="<?php echo plugins_url( 'images/cross.jpeg', __FILE__ ); ?>"></a>
                    </div>
                  <?php } ?>
              <a class="add_more_button"><img class="cursor-point" alt="Add" src="<?php echo plugins_url( 'images/add.png', __FILE__ ); ?>"></a>
            </div>
          </div>
        <?php }else{ ?>
          <div class="input_fields_container">
            <div id="foo">
              <div id="asd">
                <input class="width" type="text" name="ingredients[]" class="ingredients" id="ingredients1" placeholder="Ingredients">
                <input class="width" type="hidden" name="ingredient_val" id="ingredient_val" value="">
                <input class="width" type="hidden" name="slcted_ingredients" id="slcted_ingredients" value="">
                <input class="width" type="text" name="quantity[]" id="quantity1" placeholder="Quantity">
                <select class="width" name="fractional_qty[]" id="fractional_qty1">
                  <option value=" ">Select fraction</option>
                  <?php foreach ($fractions as $mif_key => $mif_val) { ?>
                    <option value="<?php echo $mif_key; ?>"><?php echo $mif_val; ?></option>
                   <?php } ?>
                </select>
                <select class="width unit" name="unit[]" id="unit1">
                  <option value=" ">Select unit</option>
                </select>
                 <a class="add_more_button"><img class="cursor-point" alt="Add" src="<?php echo plugins_url( 'images/add.png', __FILE__ ); ?>"></a>
              </div>         
            </div>
            </div>
          <?php } ?>
        </td>
      </tr>
    </tbody>
  </table>
<?php  
}

/*
* Method: Function for removing editor in custom post
*/

add_action('init', 'rem_editor_from_meal');
function rem_editor_from_meal() {
  if(isset($_GET['action']) && $_GET['action'] != 'edit'){
    remove_post_type_support( 'menu-items', 'editor' );
  }elseif(isset($_GET['post_type']) && $_GET['post_type'] == 'menu-items'){
    remove_post_type_support( 'menu-items', 'editor' );
  }
}

/*
* Method: Function for save custom post meta data
*/

add_action( 'save_post', 'save_custom_posts_meta_data');
function save_custom_posts_meta_data(){
  global $post, $wpdb;
  date_default_timezone_set("America/New_York");
  $status = FALSE;
  if(isset($post) && !empty($post)){
    $postId = $post->ID;
    $meal_post = get_post($post->ID);
  }
  if (get_post_type() == 'menu-items') {
    if($meal_post->post_modified_gmt == $meal_post->post_date_gmt && (isset($_POST['add'])) && $_POST['add'] == 'true' ){
      $meal_plan = $_POST['meal_plan'];
      $count = 1;
      if(!empty($meal_plan) && is_array($meal_plan)){
        $featured_image = get_the_post_thumbnail_url($postId);
        $title   = get_the_title($postId);
        $oldpost = get_post($postId);
        $table_term_rel = $wpdb->prefix.'term_relationships';
        foreach ($meal_plan as $key => $value) {
          if(isset($_POST['meal_descrption'][$key])){
            $meal_description = $_POST['meal_descrption'][$key];
          }
          if(isset($_POST['post_title']) && !empty($_POST['post_title'])){
            $post_name = isset($_POST['mp_nam_'.$key]) ? str_replace(' ','-', strtolower($_POST['mp_nam_'.$key])).'-' : '';
            $post_name .= str_replace(' ','-', strtolower($_POST['post_title']));
          }
          $post_id = 0;
          if($count > 1){
            // duplicating post
            $meal_categories = get_the_terms($postId, "menu-items-category");
            if(!empty($meal_categories) && is_array($meal_categories)){
              $meal_category_ids = array_column($meal_categories,'term_taxonomy_id');
            }
            $post    = array(
              'post_title' => $title,
              'post_name' => $post_name,
              'post_status' => 'publish',
              'post_type' => $oldpost->post_type,
              'post_author' => 1,
              'post_content'=> $meal_description,
            );
            $post_id = wp_insert_post($post);
            if(!empty($post_id) && !empty($meal_category_ids) && is_array($meal_category_ids)){
              foreach ($meal_category_ids as $cat_key => $cat_value) {
                $wpdb->insert( $table_term_rel, array('object_id'=>$post_id,'term_taxonomy_id'=>$cat_value));
              }
            }
            $imageFile = $featured_image;
            $wpFileType = wp_check_filetype($imageFile, null);
            $attachment = array(
              'post_mime_type' => $wpFileType['type'],
              'post_title' => sanitize_file_name($imageFile),
              'post_content' => '',//could use the image description here as the content
              'post_status' => 'inherit'
            );
            // insert and return attachment id
            $attachmentId = wp_insert_attachment( $attachment, $imageFile, $post_id );
            // insert and return attachment metadata
            $attachmentData = wp_generate_attachment_metadata( $attachmentId, $imageFile);
            // update and return attachment metadata
            wp_update_attachment_metadata( $attachmentId, $attachmentData );
            // finally, associate attachment id to post id
            $success = set_post_thumbnail( $post_id, $attachmentId );
          }else{
            if(!empty($post_name)){
              $wpdb->update($wpdb->prefix.'posts',array('post_name'=>$post_name,'post_content'=>$meal_description),array('ID'=>$postId));
            }
            if(isset($_POST['meal_category']) && $_POST['meal_category'] > 0){
              if(!empty($postId) && !empty($_POST['meal_category'])){
                $wpdb->insert( $table_term_rel, array('object_id'=>$postId,'term_taxonomy_id'=>$_POST['meal_category']));
              }
            }
          }
          $postId = (isset($post_id) && $post_id > 0) ? $post_id : $postId;
          if(isset($_POST['calories'][$key])){
            update_post_meta($postId, '_calories', $_POST['calories'][$key]);
          }
          if(isset($_POST['protein'][$key])){
            update_post_meta($postId, '_protein', $_POST['protein'][$key]);
          }
          if(isset($_POST['carbs'][$key])){
            update_post_meta($postId, '_carbs', $_POST['carbs'][$key]);
          }
          if(isset($_POST['fat'][$key])){
            update_post_meta($postId, '_fat', $_POST['fat'][$key]);
          }
          if(isset($key)){
            update_post_meta($postId, '_plan', $key);
          }
          if(isset($_POST['menu_appear_date'][$key])){
            update_post_meta($postId, '_appear_date', $_POST['menu_appear_date'][$key]);
          }
          if(isset($_POST['menu_expire_date'][$key])){
            update_post_meta($postId, '_expire_date', $_POST['menu_expire_date'][$key]);
          }
          if(isset($_POST['menu_sub_title'][$key])){
            update_post_meta($postId, '_sub_title', $_POST['menu_sub_title'][$key]);
          }
          if(isset($_POST['menu_allergies_diets'][$key])){
            update_post_meta($postId, '_allergies_diets', $_POST['menu_allergies_diets'][$key]);
          }
          if(isset($_POST['menu_signal_tags'][$key])){
            update_post_meta($postId, '_signal_tags', $_POST['menu_signal_tags'][$key]);
          }
          if(isset($_POST['meal_protein'][$key])){
            update_post_meta($postId, '_meal_protein', $_POST['meal_protein'][$key]);
          }
          if(isset($_POST['meal_price'][$key])){
            update_post_meta($postId, '_price', $_POST['meal_price'][$key]);
          }
          if(isset($_POST['meal_directions'][$key])){
            update_post_meta($postId, '_directions', $_POST['meal_directions'][$key]);
          }
          if(isset($_POST['ingredients']) && !empty($_POST['ingredients'])){
            $tblName = $wpdb->prefix.'meals_ingredients';
            $insrt_arr = $ing_ids_arr = array();
            for ($i = 0; $i < count($_POST['ingredients']); $i++) {
              if(!empty($_POST['ingredients'][$i])){
                $ingredient_id = get_post_id_by_slug($_POST['ingredients'][$i]);
                if($ingredient_id > 0){
                  $ing_ids_arr[] = $ingredient_id;
                  $is_found = $wpdb->get_row("select meal_id, ingredient_id from ".$tblName." where meal_id =".$postId." and ingredient_id =".$ingredient_id);
                  $data = array(
                    'meal_id' => $postId,
                    'ingredient_id' => $ingredient_id,
                    'quantity' => $_POST['quantity'][$i],
                    'fraction_qty' => $_POST['fractional_qty'][$i],
                    'unit_id' => get_term_id_by_name($_POST['unit'][$i]),
                    'unit_abbreviation' => $_POST['unit'][$i],
                    'modified_date' => date('Y-m-d h:i:s')              
                  );
                  if($is_found){
                    $whr = array('meal_id' => $postId,'ingredient_id' => $ingredient_id);
                    $wpdb->update($tblName,$data,$whr);
                  }else{
                    $add = array('created_date'=>date('Y-m-d h:i:s'));
                    $insrt_arr[] = array_merge($data, $add);
                  }
                }
              }
            }
            if(count($insrt_arr) > 0){
              common_batch_insert($insrt_arr,$tblName);
            }
          }
          //remove deleted ingredients
          $added_ingredients = $wpdb->get_results("select ingredient_id from ".$wpdb->prefix."meals_ingredients where meal_id= ".$postId, ARRAY_A);
          $added_ingredients_sorted = array_column($added_ingredients, 'ingredient_id');
          $removed_ing_ids = array_diff($added_ingredients_sorted,$ing_ids_arr);
          if(!empty($removed_ing_ids)){
            $rm_sql_params = '';
            $i = 1;
            foreach ($removed_ing_ids as $rmkey => $rmval) {
              if($i > 1){
                $rm_sql_params .= ',';  
              }
              $rm_sql_params .= '('.$postId.','.$rmval.')';
              $i++;
            }
            $wpdb->query("DELETE FROM $tblName WHERE (meal_id,ingredient_id) IN ($rm_sql_params)");
          }
          $count++;
          if ($_REQUEST['action'] == 'trash'){
            $status = True;
          }else{
            $status = check_meal_status($postId);
          }
          if(!$status){
            $wpdb->update($wpdb->prefix.'posts',array('post_status'=>'draft'),array('ID'=>$postId));
          }
        }
      }
    }else{
      $status = FALSE;
      if(isset($_POST['calories'])){
        update_post_meta($post->ID, '_calories', $_POST['calories']);
      }
      if(isset($_POST['protein'])){
        update_post_meta($post->ID, '_protein', $_POST['protein']);
      }
      if(isset($_POST['carbs'])){
        update_post_meta($post->ID, '_carbs', $_POST['carbs']);
      }
      if(isset($_POST['fat'])){
        update_post_meta($post->ID, '_fat', $_POST['fat']);
      }
      if(isset($_POST['menu_item_plan'])){
        update_post_meta($post->ID, '_plan', $_POST['menu_item_plan']);
      }
      if(isset($_POST['menu_appear_date'])){
        update_post_meta($post->ID, '_appear_date', $_POST['menu_appear_date']);
      }
      if(isset($_POST['menu_expire_date'])){
        update_post_meta($post->ID, '_expire_date', $_POST['menu_expire_date']);
      }
      if(isset($_POST['menu_sub_title'])){
        update_post_meta($post->ID, '_sub_title', $_POST['menu_sub_title']);
      }
      $allergies_diets = (isset($_POST['menu_allergies_diets']) && !empty($_POST['menu_allergies_diets']))?$_POST['menu_allergies_diets']:array();
      update_post_meta($post->ID, '_allergies_diets', $allergies_diets);
      if(isset($_POST['menu_signal_tags'])){
        update_post_meta($post->ID, '_signal_tags', $_POST['menu_signal_tags']);
      }else{
        update_post_meta($post->ID, '_signal_tags', '');
      }
      if(isset($_POST['meal_protein'])){
        update_post_meta($post->ID, '_meal_protein', $_POST['meal_protein']);
      }
      if(isset($_POST['meal_price'])){
        update_post_meta($post->ID, '_price', $_POST['meal_price']);
      }
      if(isset($_POST['meal_directions'])){
        update_post_meta($post->ID, '_directions', $_POST['meal_directions']);
      }
      if(isset($_POST['meal_category'])){
        $meal_category_id = $_POST['meal_category'];
        if(!empty($post->ID) && !empty($meal_category_id)){
            $found = FALSE;
            $tablename = $wpdb->prefix.'term_relationships';
            $found = $wpdb->get_results( "SELECT * FROM $tablename WHERE object_id = $post->ID", OBJECT );
            if($found){
              $wpdb->update( $tablename, array('term_taxonomy_id'=>$meal_category_id),array('object_id'=>$post->ID));
            }else{
              $wpdb->insert( $tablename, array('object_id'=>$post->ID,'term_taxonomy_id'=>$meal_category_id));
            }
        }
      }
      if(isset($_POST['ingredients']) && !empty($_POST['ingredients'])){
        $tblName = $wpdb->prefix.'meals_ingredients';
        $insrt_arr = $ing_ids_arr = array();
        for ($i = 0; $i < count($_POST['ingredients']); $i++) {
          if(!empty($_POST['ingredients'][$i])){
            $ingredient_id = get_post_id_by_slug($_POST['ingredients'][$i]);
            if($ingredient_id > 0){
              $ing_ids_arr[] = $ingredient_id;
              $is_found = $wpdb->get_row("select meal_id, ingredient_id from ".$tblName." where meal_id =".$post->ID." and ingredient_id =".$ingredient_id);
              $data = array(
                'meal_id' => $post->ID,
                'ingredient_id' => $ingredient_id,
                'quantity' => $_POST['quantity'][$i],
                'fraction_qty' => $_POST['fractional_qty'][$i],
                'unit_id' => get_term_id_by_name($_POST['unit'][$i]),
                'unit_abbreviation' => $_POST['unit'][$i],
                'modified_date' => date('Y-m-d h:i:s')              
              );
              if($is_found){
                $whr = array('meal_id' => $post->ID,'ingredient_id' => $ingredient_id);
                $wpdb->update($tblName,$data,$whr);
              }else{
                $add = array('created_date'=>date('Y-m-d h:i:s'));
                $insrt_arr[] = array_merge($data, $add);
              }
            }
          }
        }
        if(count($insrt_arr) > 0){
          common_batch_insert($insrt_arr,$tblName);
        }
      }
      //remove deleted ingredients
      $added_ingredients = $wpdb->get_results("select ingredient_id from ".$wpdb->prefix."meals_ingredients where meal_id= ".$post->ID, ARRAY_A);
      $added_ingredients_sorted = array_column($added_ingredients, 'ingredient_id');
      $removed_ing_ids = array_diff($added_ingredients_sorted,$ing_ids_arr);
      if(!empty($removed_ing_ids)){
        $rm_sql_params = '';
        $i = 1;
        foreach ($removed_ing_ids as $rmkey => $rmval) {
          if($i > 1){
            $rm_sql_params .= ',';  
          }
          $rm_sql_params .= '('.$post->ID.','.$rmval.')';
          $i++;
        }
        $wpdb->query("DELETE FROM $tblName WHERE (meal_id,ingredient_id) IN ($rm_sql_params)");
      }
      if ($_REQUEST['action'] == 'trash'){
        $status = True;
      }else{
        $status = check_meal_status($postId);
      }
      if(!$status){
        $wpdb->update($wpdb->prefix.'posts',array('post_status'=>'draft'),array('ID'=>$postId));
      }
    }
    //manage meals revision
    $ml_sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE ID = $post->ID";
    $ml_data = $wpdb->get_row($ml_sql, ARRAY_A);
    if(isset($ml_data['ID']) && $ml_data['ID'] > 0){
      $added_ingredients = $wpdb->get_results("select * from ".$wpdb->prefix."meals_ingredients where meal_id= ".$ml_data['ID'], ARRAY_A);
      $ml_cats = get_the_terms($post->ID, 'menu-items-category');
      $ml_revision_data = array(
        'post_date' => $ml_data['post_date'],
        'post_content' => $ml_data['post_content'],
        'post_title' => $ml_data['post_title'],
        'post_excerpt' => $ml_data['post_excerpt'],
        'post_status' => $ml_data['post_status'],
        'post_name' => $ml_data['post_name'],
        'post_type' => $ml_data['post_type'],
        'calories' => $_POST['calories'],
        'protein' => $_POST['protein'],
        'carbs' => $_POST['carbs'],
        'fat' => $_POST['fat'],
        'plan' => $_POST['menu_item_plan'],
        'appear_date' => $_POST['menu_appear_date'],
        'expire_date' => $_POST['menu_expire_date'],
        'sub_title' => (isset($_POST['menu_sub_title']) && !empty($_POST['menu_sub_title']))?$_POST['menu_sub_title']:'',
        'allergies_diets' => (isset($_POST['menu_allergies_diets']) && !empty($_POST['menu_allergies_diets']))?serialize($_POST['menu_allergies_diets']):array(),
        'meal_protein' => (isset($_POST['meal_protein']) && !empty($_POST['meal_protein']))?$_POST['meal_protein']:'',
        'price' => (isset($_POST['meal_price']) && $_POST['meal_price'] > 0)?$_POST['meal_price']:0,
        'directions' => $_POST['meal_directions'],
        'ingredients' => (!empty($added_ingredients) && is_array($added_ingredients))?serialize($added_ingredients):array(),
        'category' => (!empty($ml_cats[0]->term_id) && !empty($ml_cats[0]->term_id))?$ml_cats[0]->term_id:0,
        'create_by' => get_current_user_id(),
        'ip_address' => get_client_ip()
      );
      $wpdb->insert($wpdb->prefix.'meals_revision',$ml_revision_data);
    }
  }
  //save membership coupons meta data values
  if (get_post_type() == 'membership-coupons') {
    if(isset($_POST['coupon_category'])){
      update_post_meta($post->ID, '_coupon_category', $_POST['coupon_category']);
    }
    if(isset($_POST['coupon_type'])){
      update_post_meta($post->ID, '_coupon_type', $_POST['coupon_type']);
    }
    if(isset($_POST['coupon_amount'])){
      update_post_meta($post->ID, '_amount', $_POST['coupon_amount']);
    }
    if(isset($_POST['coupon_expiry_date'])){
      update_post_meta($post->ID, '_expiry_date', $_POST['coupon_expiry_date']);
    }
    if(isset($_POST['usage_limit_per_coupon']) && !empty($_POST['usage_limit_per_coupon'])){
      update_post_meta($post->ID, '_usage_limit_per_coupon', $_POST['usage_limit_per_coupon']);
    }else{
      update_post_meta($post->ID, '_usage_limit_per_coupon', 0);
    }
    if(isset($_POST['usage_limit_per_user']) && !empty($_POST['usage_limit_per_user'])){
      update_post_meta($post->ID, '_usage_limit_per_user', $_POST['usage_limit_per_user']);
    }else{
      update_post_meta($post->ID, '_usage_limit_per_user', 0);
    }
    if(isset($_POST['coupon_one_time_or_recurring'])){
      update_post_meta($post->ID, '_one_time_or_recurring', $_POST['coupon_one_time_or_recurring']);
    }
    if(isset($_POST['number_of_billing_cycles'])){
      $nbc = 1;
      if($_POST['coupon_one_time_or_recurring'] == 'recurring'){
        $nbc = $_POST['number_of_billing_cycles'];
      }
      update_post_meta($post->ID, '_number_of_billing_cycles', $nbc);
    }
    if(isset($_POST['post_title']) && !empty($_POST['post_title'])){
      $coupon_code = $_POST['post_title'];
      $coupon_sql = "select ID, post_title from ".$wpdb->prefix."posts where post_type ='membership-coupons' AND post_status ='publish' AND post_title ='".$coupon_code."' And ID !=".$post->ID."";
      $coupon_found = $wpdb->get_results($coupon_sql);
      if(!empty($coupon_found)){
        $wpdb->update($wpdb->prefix.'posts',array('post_status'=>'draft'),array('ID'=>$post->ID));
        unique_cpn_err('not_unique_cpn','Coupon must be unique. Please try another coupon.');
      }
    }
    //add/update Coupon Rule 2 meta data
    if(isset($_POST['coupon_category']) && $_POST['coupon_category'] == 'coupon'){
      if(isset($_POST['slcted_affiliate_usr'])){
        update_post_meta($post->ID, '_linked_affiliate_user', $_POST['slcted_affiliate_usr']);
        $all_lnkd_aff_usrs = array();
        $is_send_email_to_aff = 0;
        if(!empty($_POST['slcted_affiliate_usr']) && $_POST['slcted_affiliate_usr'] > 0){
          $all_lnkd_aff_usrs = get_post_meta($post->ID,'all_linked_affiliate_users',true);
          if(!empty($all_lnkd_aff_usrs) && count($all_lnkd_aff_usrs) > 0){
            if(!in_array($_POST['slcted_affiliate_usr'], $all_lnkd_aff_usrs)){
              $all_lnkd_aff_usrs[] = $_POST['slcted_affiliate_usr'];
              $is_send_email_to_aff = 1;
            }
          }else{
            $all_lnkd_aff_usrs = array($_POST['slcted_affiliate_usr']);
            $is_send_email_to_aff = 1;
          }
        }
        update_post_meta($post->ID, 'all_linked_affiliate_users', $all_lnkd_aff_usrs);
        if($is_send_email_to_aff == 1){
          //send coupon code to affiliate user
          send_coupon_code_to_affiliate_user($_POST['slcted_affiliate_usr'],$post->ID);
        }
      }
      if(isset($_POST['is_add_rule_2']) && $_POST['is_add_rule_2'] == 1){
        update_post_meta($post->ID, '_is_add_rule_2', $_POST['is_add_rule_2']);
        if(isset($_POST['rule_2_coupon_type'])){
          update_post_meta($post->ID, '_rule_2_coupon_type', $_POST['rule_2_coupon_type']);
        }
        if(isset($_POST['rule_2_coupon_amount'])){
          update_post_meta($post->ID, '_rule_2_coupon_amount', $_POST['rule_2_coupon_amount']);
        }
        if(isset($_POST['rule_2_number_of_billing_cycles'])){
          update_post_meta($post->ID, '_rule_2_number_of_billing_cycles', $_POST['rule_2_number_of_billing_cycles']);
        }
      }else{
        delete_post_meta($post->ID, '_is_add_rule_2');
        delete_post_meta($post->ID, '_rule_2_coupon_type');
        delete_post_meta($post->ID, '_rule_2_coupon_amount');
        delete_post_meta($post->ID, '_rule_2_number_of_billing_cycles');
      }
      if(isset($_POST['force_pickup_location'])){
        update_post_meta($post->ID, '_force_pickup_location', $_POST['force_pickup_location']);
        if($_POST['force_pickup_location'] == 'yes' && isset($_POST['slcted_location_link'])){
          update_post_meta($post->ID, '_slcted_location_link', $_POST['slcted_location_link']);
        }else{
          delete_post_meta($post->ID, '_slcted_location_link');
        }
      }
      if(isset($_POST['apply_to_first_billing_period'])){
        update_post_meta($post->ID, '_apply_to_first_billing_period', $_POST['apply_to_first_billing_period']);
      }
    }else{
      delete_post_meta($post->ID, '_is_add_rule_2');
      delete_post_meta($post->ID, '_rule_2_coupon_type');
      delete_post_meta($post->ID, '_rule_2_coupon_amount');
      delete_post_meta($post->ID, '_rule_2_number_of_billing_cycles');
      delete_post_meta($post->ID, '_force_pickup_location');
      delete_post_meta($post->ID, '_slcted_location_link');
      delete_post_meta($post->ID, '_apply_to_first_billing_period');
      delete_post_meta($post->ID, '_linked_affiliate_user');
    }
  }
  //save ingredients meta data values
  if (get_post_type() == 'ingredients') {
    global $wpdb,$post;
    $ing_found = FALSE;
    if(isset($_POST['ing_category']) && !empty($_POST['ing_category'])){
      update_post_meta($post->ID, '_category', $_POST['ing_category']);
    }
    if(isset($_POST['ig_group']) && !empty($_POST['ig_group'])){
      update_post_meta($post->ID, '_group', $_POST['ig_group']);
    }
    if(isset($_POST['post_title']) && !empty($_POST['post_title'])){
      $ing_title = $_POST['post_title'];
      $ing_sql = "select ID, post_title from ".$wpdb->prefix."posts where post_type ='ingredients' AND post_status ='publish' AND post_title ='".$ing_title."' And ID !=".$post->ID."";
      $ing_found = $wpdb->get_results($ing_sql);
      if(!empty($ing_found)){
        $wpdb->update($wpdb->prefix.'posts',array('post_status'=>'draft'),array('ID'=>$post->ID));
        unique_ing_error('not_unique_ing','Ingredient name must be unique. Please enter another name.');
      }
    }
  }
  //save sliders meta data values
  if(get_post_type() == 'sliders'){
    if(isset($_POST['slider_status']) && !empty($_POST['slider_status'])){
      update_post_meta($post->ID, '_slider_status', $_POST['slider_status']);
    }
  }
  //Save pickup location meta data values
  if (get_post_type() == 'partners-location') {
    if (isset($_POST['ppp_address'])) {
      update_post_meta($post->ID, '_address', $_POST['ppp_address']);
    }
    if (isset($_POST['ppp_latitude'])) {
      update_post_meta($post->ID, '_latitude', $_POST['ppp_latitude']);
    }
    if (isset($_POST['ppp_longitude'])) {
      update_post_meta($post->ID, '_longitude', $_POST['ppp_longitude']);
    }
    if (isset($_POST['ppp_phone_number'])) {
      update_post_meta($post->ID, '_phone_number', $_POST['ppp_phone_number']);
    }
    if (isset($_POST['ppp_email'])) {
      update_post_meta($post->ID, '_email', $_POST['ppp_email']);
    }
    if (isset($_POST['ppp_email'])) {
      update_post_meta($post->ID, '_website', $_POST['ppp_website']);
    }
    if (isset($_POST['ppp_posted_day'])) {
      update_post_meta($post->ID, '_posted_day', $_POST['ppp_posted_day']);
    }
    if (isset($_POST['ppp_posted_hours'])) {
      update_post_meta($post->ID, '_posted_hours', $_POST['ppp_posted_hours']);
    }
  }
}

/*
* Method: Function to get transient of ingredients custom error and display them and remove 
*existing message
*/

function handle_ing_or_cpn_errors() {
  if(get_transient('ing_errors')){
    $errors = get_transient('ing_errors');
  }
  if(get_transient('cpn_err')){
    $errors = get_transient('cpn_err');
  }
  if(empty($errors)){
    return;
  }
  $msg = '<div id="custom-ing-error-notice" class="notice notice-error">';
  foreach($errors as $error) {
    if($error['type'] == 'error'){
      $msg .= '<p>' . $error['message'] . '</p>';
    }
  }
  $msg .= '</div>';
  echo $msg;
  delete_transient('ing_errors');
  delete_transient('cpn_err');
  remove_action('admin_notices', 'handle_ing_or_cpn_errors');
  echo '<script type="text/javascript">'; ?>
  jQuery(document).ready(function(){
    jQuery('#message').remove();
  });
  <?php
  echo '</script>';
}
add_action('admin_notices', 'handle_ing_or_cpn_errors');

/*
* Date: 
* Method: Function to set transient of ingredients custom error
*/
function unique_ing_error($slug,$err){
  add_settings_error(
    $slug,
    $slug,
    $err,
    'error'
  );
  set_transient('ing_errors', get_settings_errors(), 30);
}

/*
* Method: Function to remove messages based on status
*/
add_filter( 'post_updated_messages', 'remove_message_from_meal_page' );
function remove_message_from_meal_page( $messages ){
  global $post;
  $screen = get_current_screen();
  if($screen->id == 'menu-items'){
    $status = check_meal_status($post->ID);
    if(!$status){
      return array();
    }else{
      return $messages;
    }
  }else{
    return $messages;
  }
}

function the_content_limit($max_char) { 
	$content = get_the_content();
	$content = apply_filters('the_content', $content); $content = str_replace(']]>', ']]>', $content); 
	$content = strip_tags($content); 
	if ((strlen($content)>$max_char) && ($espacio = strpos($content, " ", $max_char ))) { 
		$content = substr($content, 0, $espacio); $content = $content; echo ""; 
		echo $content."..."; 
	} else { 
		echo $content;
	} 
}

/*
* Update: 14-Nov-2018
* Method: function for add custom column in Meals list table
*/

add_filter('manage_menu-items_posts_columns','filter_menuitems_columns' );
function filter_menuitems_columns( $columns ) {
  $columns = array(
    'cb' => __('<input type="checkbox" />'),
    'meal_card_image' => __('Image'),
    'title' => __( 'Name' ),
    'meal_category' => __('Category'),
    'meal_plan' => __('Plan'),
    'appear_date' => __( 'Appear Date' ),
    'expire_date' => __( 'Expire Date' ),
    'date' => __( 'Created Date' ),
    'status' => __( 'Status' ),
    'complete_incomplete' => __('Complete/Incomplete')
  );
  return $columns;
}

add_action( 'manage_posts_custom_column','menuitems_custom_columns_content', 10, 2 );
function menuitems_custom_columns_content ( $column_id, $post_id ) {
  $appear_date = get_post_meta($post_id, '_appear_date', true);
  $expire_date = get_post_meta($post_id, '_expire_date', true);
  switch( $column_id ) {
    case 'meal_card_image':
      //$card_image_src = wp_get_attachment_url(get_post_meta($post_id, '_card_image', true ));
      $card_image_src = get_the_post_thumbnail_url($post_id,'tc-thumbnail-image');
      if($card_image_src){
        echo '<img src="'.$card_image_src.'" width="48" height="48" alt="Meal Image">';
      }else{
        echo '<img src="'.TOUGHCOOKIES_URL.'images/no_image_found.png" width="48" height="48" alt="Meal Image">';
      }
    break;
    case 'meal_category':
      $meal_category = get_the_terms($post_id, 'menu-items-category');
      if(!empty($meal_category) && is_array($meal_category)){
        foreach ($meal_category as $key => $val) {
          $category[] = isset($val->name) ? $val->name : '';
        }
      }
      if(!empty($category) && is_array($category)){
        echo $category = implode(', ', $category);
      }
    break; 
    case 'meal_plan':
      $meal_plan_id = get_post_meta($post_id, '_plan', true );
      $meal_plan = get_term_by('id', $meal_plan_id, 'membershipgroup');
      $mem_grp_color = '';
      if(isset($meal_plan->slug) && !empty($meal_plan->slug) && isset($meal_plan->name) && !empty($meal_plan->name)){
        switch($meal_plan->slug){
          case 'weight-loss':
          case 'lose-weight':
            $mem_grp_color = '#5B83BB';
          break;
          case 'balanced':
            $mem_grp_color = '#D0AD69';
          break;
          case 'gain-muscle':
            $mem_grp_color = '#D26B6B';
          break;
        }
        echo '<span style="border-radius: 6px; border: 1px solid '.$mem_grp_color.';padding: 5px;color:'.$mem_grp_color.';">'.$meal_plan->name.'</span>';
      }
    break;
    case 'appear_date':
      echo $appear_date;
    break;
    case 'expire_date':
      echo $expire_date;
    break;
    case 'status':
      echo ($expire_date > date('Y-m-d')) ? '<span style="color:green;">Active</span>' : '<span style="color:red;">Inactive</span>' ;
    break;
    case 'complete_incomplete':
      $status = check_meal_status($post_id);
      if($status == 1){
        $_cls = 'green-dot';
        $_title = 'Complete';
      }else{
        $_cls = 'red-dot';
        $_title = 'Incomplete';
      }
      echo '<span class="'.$_cls.'" title="'.$_title.'"></span>';
    break;
  }
}

/*
* Method: function for display register form on website front-end
*/

add_shortcode('signup-form', 'register_form');
function register_form($atts) {
  $html = '<form action="" method="post" id="signup_frm">';
  $html .= '<div class="snglInpt">
            <input type="email" class="" name="email" id="email" placeholder="'.__("Email", "toughcookies").'" />
          </div>';
  $html .= '<div class="snglInpt">
            <input type="password" name="password" id="password" placeholder="'.__("Password", "toughcookies").'" />
          </div>';
  $html .= '<div class="snglInpt">
            <input type="text" name="zip_code" id="zip_code" placeholder="'.__("Zip code", "toughcookies").'" />
          </div>';
  $html .= '<div class="snglInpt signup-cont-btn">';
  $html .= '<button class="btn" type="button" id="signup-continue">'.__("CONTINUE", "toughcookies").'</button>
        </div>';
  $html .= '<div class="orBnt"><span>or</span></div>';
  $html .= '<div class="snglInpt">';
      $setting_data = get_option('psl_social_plugin');
      extract($setting_data['facebook_details']);
      if($enable_facebook == 'on'){
        $html .= '<a href="javascript:void(0);" class="btn faceBook fb-btn" onclick="facebook_login(2)"><img src="'.get_template_directory_uri().'/images/facebook-circle.svg" alt="">&nbsp;&nbsp;Continue with Facebook</a>';
        //$html .= '<a href="javascript:void(0);" class="btn faceBook" onclick="facebook_login(2)"><i class="fa fa-facebook-square" aria-hidden="true"></i>&nbsp;&nbsp;Continue with Facebook</a>';
      }
  $html .= '</div>';
  $html .= '<div class="termsCnd">
    <p>
        By clicking above, you agree to our
          <a href="'.site_url('/terms-of-use').'" target="_blank">Terms of Use</a> and consent to our <a href="'.site_url('/privacy-policy').'" target="_blank">Privacy Policy</a>
      </p>
  </div>
  <div class="snglInpt alrdyMembr">
      <p>Already a member? &nbsp; <a href="'.site_url('/sign-in').'">Log in</a></p>
  </div>';
  return $html;
}

/*
* Method: function for sign up or register user
*/

function signup_user(){
  date_default_timezone_set("America/New_York");
  $parameters = array();
  parse_str($_POST['fdata'], $parameters);
  $is_error = 0;
  $error_msg = array();
  if(isset($parameters['email']) && !empty($parameters['email'])){
    if(is_email($parameters['email'])){
      if(email_exists($parameters['email'])){
        $is_error = 1;
        $error_msg['email'] = 'Email already exist.';
      }else{
        $user_email = $parameters['email'];
      }
    }else{
      $is_error = 1;
      $error_msg['email'] = 'Please enter a valid Email.';
    }
  }else{
    $is_error = 1;
    $error_msg['email'] = 'Email is a required field.';
  }
  if(isset($parameters['password']) && !empty($parameters['password'])){
    if(strlen($parameters['password']) >= 6){
      $password = $parameters['password'];
    }else{
      $is_error = 1;
      $error_msg['password'] = 'Password must have 6 or more characters.';
    }
  }else{
    $is_error = 1;
    $error_msg['password'] = 'Password is a required field.';
  }
  if(isset($parameters['zip_code']) && !empty($parameters['zip_code'])){
    $zip = $parameters['zip_code'];
  }else{
    $is_error = 1;
    $error_msg['zip_code'] = 'Zip code is a required field.';
  }
  if(isset($is_error) && $is_error==1){
    $response = array('error'=>1,'msg'=>$error_msg);
  }else{
  	$zip_find_status = get_zip_info($zip);
		if($zip_find_status){
	    $user_login = esc_attr($user_email);
	    $user_email = esc_attr($user_email);
	    $sanitized_user_login = sanitize_user($user_login);
	    $user_email = apply_filters('user_registration_email', $user_email);
	    $user_pass = $password;
	    $user_id = wp_create_user($sanitized_user_login, $user_pass, $user_email);
	    if (!$user_id){
	      $response = array('error'=>1,'msg'=>$error_msg['Something goes wrong, please try again!']);
	    }else{
        update_user_meta($user_id, 'pmpro_bzipcode', $zip);
	      //save customer billing detail
	      update_user_meta($user_id, 'billing_email', $user_email);
	      update_user_meta($user_id, 'billing_postcode', $zip);
	      update_user_option($user_id, 'default_password_nag', true, true);
	      //add customer shipping address
	      update_user_meta($user_id, 'shipping_email', $user_email);
	      update_user_meta($user_id, 'shipping_postcode', $zip);
	      update_user_option($user_id, 'default_password_nag', true, true);
        //add user type
        $u_type = (isset($parameters['reqfrm']) && $parameters['reqfrm'] == 'jpfrm')?3:1;
        update_user_meta($user_id, 'user_type', $u_type);//1 = Normal, 2 = Affiliate, 3 = Juice
        update_user_meta($user_id, 'subscription_account_status', 0);//0 = Pending, 1 = Active, 2 = Cancelled
        //auto login user
        wp_set_current_user( $user_id, $sanitized_user_login );
        wp_set_auth_cookie( $user_id );
        $user_info = get_userdata($user_id);
        do_action( 'wp_login', $sanitized_user_login,$user_info );
        //send account activation email to user
	      //send_customer_new_account_mail($user_id,$user_pass);
        $redirecturl = site_url('/sign-up/plan-pricing/');
        if(isset($_SESSION['plan_id']) && $_SESSION['plan_id'] > 0){
         
          if(isset($_SESSION['pg_name'])){
             $redirecturl = pmpro_url("checkout", "?level=" . $_SESSION['plan_id'] . "&plan=" .@$_SESSION['pg_name'] , "https");
             unset($_SESSION['pg_name']);

          }else{
            $redirecturl = pmpro_url("checkout", "?level=" . $_SESSION['plan_id'] , "https");
          }
         
        
        }
        $response = array('error'=>0,'msg'=>'You have successfully registered.','redirecturl'=>$redirecturl);
	    }
		}else{
			$is_error = 1;
  		$error_msg['zip_code'] = 'Sorry, we currently do not deliver to your zip code.';
  		$response = array('error'=>1,'msg'=>$error_msg);
		}
  }
  echo json_encode($response);
  exit();
}

/*
* Method: function for get city, state and country by zipcode
*/

function get_zip_info($zip) 
{
  if(!empty($zip)){
    $zipcodesArr = get_option('zipcodes');
    if(!empty($zipcodesArr) && is_array($zipcodesArr) && in_array($zip,$zipcodesArr)){
      return true;
    }
  }
  return false;
}

/*
* Method: function for send partner user register notification email
*/

function send_welcome_email_to_partner_user($user_id,$user_pass = ''){
  if(isset($user_id) && !empty($user_id)){
    date_default_timezone_set("America/New_York");
    $user = get_userdata( $user_id );
    $uname = (isset($user->data->user_login) && !empty($user->data->user_login))?$user->data->user_login:'';
    $customer_subject = 'Your '.get_bloginfo( 'name' )." Account Details";
    $customer_message = '<div style="background-color:#f5f5f5;width:100%;margin:0;padding:15px 0 15px 0">
        <table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody>
            <tr>
              <td valign="top" align="center">
                <table width="800" cellspacing="0" cellpadding="0" border="0" style="border-radius:6px!important;background-color:#fdfdfd;border:1px solid #dcdcdc;border-radius:6px!important">
                  <tbody>
                    <tr>
                      <td valign="top" align="center">
                        <table width="800" cellspacing="0" cellpadding="0" border="0" bgcolor="#3C5DAE" style="background-color:#3C5DAE;color:#ffffff;border-top-left-radius:6px!important;border-top-right-radius:6px!important;border-bottom:0;font-family:Arial;font-weight:bold;line-height:100%;vertical-align:middle">
                          <tbody>
                            <tr>
                              <td>
                                 <h1 style="color:#ffffff;margin:0;padding:28px 24px;display:block;font-family:Arial;font-size:30px;font-weight:bold;text-align:center;line-height:150%">'.get_bloginfo( 'name' ).' Account Login Details</h1>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top" align="center">
                        <table width="800" cellspacing="0" cellpadding="5" border="0">
                          <tbody>
                            <tr>
                              <td valign="top" style="background-color:#fdfdfd;border-radius:6px!important">
                                <table width="100%" cellspacing="0" cellpadding="5" border="0" style="color:#737373;font-family:Arial;font-size:14px;text-align:left">
                                  <tbody>
                                    <tr>
                                      <td valign="top">
                                          <p>Hello,</p>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td valign="top">
                                          Thanks for creating account on '.get_bloginfo('name').'.
                                      </td>
                                    </tr>
                                    <tr>
                                      <td valign="top">
                                        <div style="line-height:80%;">
                                          <p>Your '. get_bloginfo( 'name' ).' Login Details Are:</p>
                                          <p><strong>Username:</strong> '.$uname.'</p>
                                         </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td valign="top">
                                        <div style="line-height:150%;">
                                          You can access your account area to view your orders and change your password here: '.site_url('/sign-in').'
                                         </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td valign="top">
                                        <div style="line-height:50%;">
                                          <p>Regards,</p>
                                          <p>'.get_bloginfo('name').' Team</p>
                                         </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top" align="center">
                        <table width="600" cellspacing="0" cellpadding="10" border="0" style="border-top:0">
                          <tbody>
                            <tr>
                              <td valign="top">
                                <table width="100%" cellspacing="0" cellpadding="10" border="0">
                                  <tbody>
                                    <tr>
                                      <td valign="middle" style="border:0;color:#3C5DAE;font-family:Arial;font-size:12px;line-height:100%;text-align:center" colspan="2">
                                        <p>'.get_bloginfo( 'name' ).'</p>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </div>' ;
    $customer_html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    $customer_html .='<html xmlns="http://www.w3.org/1999/xhtml">';
    $customer_html .='<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
    $customer_html .='';
    $customer_html .='<title>' . $customer_subject . '</title>';
    $customer_html .='<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
    $customer_html .='</head>';
    $customer_html .='<body style="margin: 0; padding: 0;">';
    $customer_html .= $customer_message;
    $customer_html .='</body>';
    $customer_html .='</html>';
    $headers = 'From: ' . get_bloginfo('name') . ' <' . get_bloginfo('admin_email') . '>';
    add_filter('wp_mail_content_type','wpse27856_set_content_type');
    $email = (isset($user->data->user_email) && !empty($user->data->user_email))?$user->data->user_email:'';
    @wp_mail($email, $customer_subject, $customer_html,$headers);
  }
}

/*
* Method: function for send partner information to admin
*/

function send_partner_request_to_admin($parameters){
  if(!empty($parameters)){
    date_default_timezone_set("America/New_York");
    // account information
    $pi_fname = $parameters['pi_fname'];
    $pi_lname = $parameters['pi_lname'];
    $pi_address1 = $parameters['pi_address1'];
    $pi_address2 = (isset($parameters['pi_address2']) && !empty($parameters['pi_address2'])) ? $parameters['pi_address2'] : '';
    $pi_city = $parameters['pi_city'];
    $pi_state = $parameters['pi_state'];
    $pi_zipcode = $parameters['pi_zipcode'];
    $pi_phone = $parameters['pi_phone'];
    //gym information
    $gym_business_name = $parameters['gym_business_name'];
    $gym_business_phone = $parameters['gym_business_phone'];
    $gym_address1 = $parameters['gym_address1'];
    $gym_address2 = (isset($parameters['gym_address2']) && !empty($parameters['gym_address2']) ) ? $parameters['gym_address2'] : '';
    $gym_city = $parameters['gym_city'];
    $gym_state = $parameters['gym_state'];
    $gym_zipcode = $parameters['gym_zipcode'];
    $gym_members = $parameters['gym_members'];
    $gym_locations = $parameters['gym_locations'];
    $gym_business_website_url = $parameters['gym_business_website_url'];
    $admin_email_subject = "Partner (".ucfirst($pi_fname)." ".ucfirst($pi_lname).") Request";
    $admin_message = '<div style="background-color:#f5f5f5;width:100%;margin:0;padding:15px 0 15px 0">
        <table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody>
            <tr>
              <td valign="top" align="center">
                <table width="800" cellspacing="0" cellpadding="0" border="0" style="border-radius:6px!important;background-color:#fdfdfd;border:1px solid #dcdcdc;border-radius:6px!important">
                  <tbody>
                    <tr>
                      <td valign="top" align="center">
                        <table width="800" cellspacing="0" cellpadding="0" border="0" bgcolor="#3C5DAE" style="background-color:#3C5DAE;color:#ffffff;border-top-left-radius:6px!important;border-top-right-radius:6px!important;border-bottom:0;font-family:Arial;font-weight:bold;line-height:100%;vertical-align:middle">
                          <tbody>
                            <tr>
                              <td>
                                 <h1 style="color:#ffffff;margin:0;padding:28px 24px;display:block;font-family:Arial;font-size:30px;font-weight:bold;text-align:center;line-height:150%">'.get_bloginfo( 'name' ).' Partner Request</h1>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top" align="center">
                        <table width="800" cellspacing="0" cellpadding="5" border="0">
                          <tbody>
                            <tr>
                              <td valign="top" style="background-color:#fdfdfd;border-radius:6px!important">
                                <table width="100%" cellspacing="0" cellpadding="5" border="0" style="font-family:Arial;font-size:14px;text-align:left">
                                  <tbody>
                                    <tr>
                                      <td valign="top">
                                          <p>Hello Adminstrator,</p>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td valign="top">
                                          One Partner has sent Request. Below are the details-
                                      </td>
                                    </tr>
                                    <tr>
                                      <td valign="top">
                                        <div style="line-height:80%;">
                                          <table width="100%">
                                            <tr>
                                              <td colspan="2" style="text-align:left;"><strong>Personal Information:</strong></td>
                                            </tr>
                                            <tr></tr>
                                            <tr>
                                              <td style="text-align:left;" width="25%">Name</td>
                                              <td width="75%">'.ucfirst($pi_fname).' '.ucfirst($pi_lname).'</td>
                                            </tr>
                                            <tr>
                                              <td style="text-align:left;" width="25%">Address line 1</td>
                                              <td width="75%">'.ucfirst($pi_address1).'</td>
                                            </tr>';
                                            if(!empty($pi_address2)){
                                              $admin_message .= '<tr>
                                                <td style="text-align:left;" width="25%">Address line 2</td>
                                                <td width="75%">'.ucfirst($pi_address2).'</td>
                                              </tr>';
                                            }
                                            $admin_message .= '<tr>
                                              <td style="text-align:left;" width="25%">City</td>
                                              <td width="75%">'.ucfirst($pi_city).'</td>
                                            </tr>
                                            <tr>
                                              <td style="text-align:left;" width="25%">State</td>
                                              <td width="75%">'.ucfirst($pi_state).'</td>
                                            </tr>
                                            <tr>
                                              <td style="text-align:left;" width="25%">Zipcode</td>
                                              <td width="75%">'.$pi_zipcode.'</td>
                                            </tr>
                                            <tr>
                                              <td style="text-align:left;" width="25%">Phone</td>
                                              <td width="75%">'.$pi_phone.'</td>
                                            </tr>
                                            <tr>
                                              <td colspan="2" style="text-align:left;padding-top: 1em;"><strong>Gym Information:<strong></td>
                                            </tr>
                                            <tr></tr>
                                            <tr>
                                              <td style="text-align:left;" width="25%">Business Name</td>
                                              <td width="75%">'.ucfirst($gym_business_name).'</td>
                                            </tr>
                                            <tr>
                                              <td style="text-align:left;" width="25%">Business Phone</td>
                                              <td width="75%">'.$gym_business_phone.'</td>
                                            </tr>
                                            <tr>
                                              <td style="text-align:left;" width="25%">Address line 1</td>
                                              <td width="75%">'.ucfirst($gym_address1).'</td>
                                            </tr>';
                                            if(!empty($gym_address2)){
                                              $admin_message .= '<tr>
                                                <td style="text-align:left;" width="25%">Address line 2</td>
                                                <td width="75%">'.ucfirst($gym_address2).'</td>
                                              </tr>';
                                            }
                                            $admin_message .= '<tr>
                                              <td style="text-align:left;" width="25%">City</td>
                                              <td width="75%">'.ucfirst($gym_city).'</td>
                                            </tr>
                                            <tr>
                                              <td style="text-align:left;" width="25%">State</td>
                                              <td width="75%">'.ucfirst($gym_state).'</td>
                                            </tr>
                                            <tr>
                                              <td style="text-align:left;" width="25%">Zipcode</td>
                                              <td width="75%">'.$gym_zipcode.'</td>
                                            </tr>
                                            <tr>
                                              <td style="text-align:left;" width="25%">How many members?</td>
                                              <td width="75%">'.$gym_members.'</td>
                                            </tr>
                                            <tr>
                                              <td style="text-align:left;" width="25%">How many locations?</td>
                                              <td width="75%">'.$gym_locations.'</td>
                                            </tr>
                                            <tr>
                                              <td style="text-align:left;" width="25%">Business Website Url</td>
                                              <td width="75%">'.$gym_business_website_url.'</td>
                                            </tr>
                                            <tr></tr>
                                            <tr></tr>
                                          </table>
                                         </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td valign="top">
                                        <div style="line-height:50%;">
                                          <p>Regards,</p>
                                          <p>'.get_bloginfo('name').' Team</p>
                                         </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top" align="center">
                        <table width="600" cellspacing="0" cellpadding="10" border="0" style="border-top:0">
                          <tbody>
                            <tr>
                              <td valign="top">
                                <table width="100%" cellspacing="0" cellpadding="10" border="0">
                                  <tbody>
                                    <tr>
                                      <td valign="middle" style="border:0;color:#3C5DAE;font-family:Arial;font-size:12px;line-height:100%;text-align:center" colspan="2">
                                        <p>'.get_bloginfo( 'name' ).'</p>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </div>';
    $admin_html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    $admin_html .='<html xmlns="http://www.w3.org/1999/xhtml">';
    $admin_html .='<head>';
    $admin_html .='';
    $admin_html .='<title>' . $admin_email_subject . '</title>';
    $admin_html .='<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
    $admin_html .='</head>';
    $admin_html .='<body style="margin: 0; padding: 0;">';
    $admin_html .= $admin_message;
    $admin_html .='</body>';
    $admin_html .='</html>';
    $headers = 'From: ' . get_bloginfo('name') . ' <' . get_bloginfo('admin_email') . '>';
    add_filter('wp_mail_content_type','wpse27856_set_content_type');
    $email = get_bloginfo('admin_email');
    @wp_mail($email, $admin_email_subject, $admin_html,$headers);
  }
}

function wpse27856_set_content_type(){
  return "text/html";
}

/*
* Method: function for get membership groups
*/

function get_membership_group(){
  $plan_groups_arr = array();
  $term_args = array(
    'orderby' => 'id',
    'order' => 'ASC',
    'hide_empty' => false,
  );
  $plan_groups = get_terms('membershipgroup',$term_args);
  if(isset($plan_groups) && !empty($plan_groups)){
    foreach ($plan_groups as $pgkey => $pgval) {
      $_status_key = 'status_' . $pgval->term_id;
      $_status = get_option($_status_key);
      if($_status == 1){
        $plan_groups_arr[$pgval->term_id] = $pgval->name;
      }
    }
  }
  return $plan_groups_arr;
}

/*
* Method: function for get number of meal(s) per day
*/

function get_number_of_meal_per_day(){
  return array('1' => '1 meal per day','2' => '2 meals per day','3' => '3 meals per day');
}

/*
* Method: function for add new fields on membership level add/edit page
*/

function pmprorh_pmpro_membership_level_after_other_settings()
{
  date_default_timezone_set("America/New_York");
  $levelid = $_REQUEST['edit'];
  $planGroupArr = get_membership_group();
  $mealPerDayArr = get_number_of_meal_per_day();
  $plan_group = $mpd = '';
  if(!empty($levelid) && $levelid > 0){
    global $wpdb;
    $plan_group = $wpdb->get_var("SELECT meta_value from $wpdb->pmpro_membership_levelmeta WHERE pmpro_membership_level_id = $levelid AND meta_key = '_membership_group'");
    $mpd = $wpdb->get_var("SELECT meta_value from $wpdb->pmpro_membership_levelmeta WHERE pmpro_membership_level_id = $levelid AND meta_key = '_meal_per_day'");
    $delivery_fee = $wpdb->get_var("SELECT meta_value from $wpdb->pmpro_membership_levelmeta WHERE pmpro_membership_level_id = $levelid AND meta_key = '_delivery_fee'");
    $onboarding_welcome_screen_text = $wpdb->get_var("SELECT meta_value from $wpdb->pmpro_membership_levelmeta WHERE pmpro_membership_level_id = $levelid AND meta_key = '_onboarding_welcome_screen_text'");
    $onboarding_welcome_screen_video_url = $wpdb->get_var("SELECT meta_value from $wpdb->pmpro_membership_levelmeta WHERE pmpro_membership_level_id = $levelid AND meta_key = '_onboarding_welcome_screen_video_url'");
  }
  ?>
  <h3 class="topborder">Membership Detail</h3>
  <table class="form-table">
      <tbody>
        <tr>
          <th scope="row" valign="top"><label>Select Membership Group:</label></th>
          <td>
            <select id="membership_group" name="membership_group">
              <option value="">Select Group</option>
              <?php
                foreach ($planGroupArr as $pg_key => $pg_val) {
                  $slcted = (!empty($plan_group) && $plan_group == $pg_key)?'selected':'';
                  ?>
                  <option value="<?php echo $pg_key; ?>" <?php echo $slcted; ?>><?php echo $pg_val; ?></option>
                  <?php
                }
              ?>
            <select>
          </td>
        </tr>
        <tr>
          <th scope="row" valign="top"><label>Select Number Of Meal(s) Per Day:</label></th>
          <td>
            <select id="meal_per_day" name="meal_per_day">
              <?php
                foreach ($mealPerDayArr as $mpd_key => $mpd_val) {
                  $slcted = (!empty($mpd) && $mpd == $mpd_key)?'selected':'';
                  ?>
                  <option value="<?php echo $mpd_key; ?>" <?php echo $slcted; ?>><?php echo $mpd_val; ?></option>
                  <?php
                }
              ?>
            <select>
          </td>
        </tr>
        <tr>
          <th scope="row" valign="top"><label>Delivery Fee:</label></th>
          <td>
            <input type="text" name="delivery_fee" id="delivery_fee" placeholder="Enter Delivery Fee" value="<?php echo (isset($delivery_fee) && !empty($delivery_fee))?$delivery_fee:0; ?>">
          </td>
        </tr>
        <tr>
          <th scope="row" valign="top"><label>Onboarding Welcome Screen Text:</label></th>
          <td>
            <textarea name="onboarding_welcome_screen_text" id="onboarding_welcome_screen_text" placeholder="Enter Onboarding Welcome Screen Text" rows="4" cols="140"><?php echo (isset($onboarding_welcome_screen_text) && !empty($onboarding_welcome_screen_text))?$onboarding_welcome_screen_text:''; ?></textarea>
          </td>
        </tr>
        <tr>
          <th scope="row" valign="top"><label>Onboarding Welcome Screen Video URL:</label></th>
          <td>
            <input type="text" name="onboarding_welcome_screen_video_url" id="onboarding_welcome_screen_video_url" placeholder="Enter Onboarding Welcome Screen Video URL" value="<?php echo (isset($onboarding_welcome_screen_video_url) && !empty($onboarding_welcome_screen_video_url))?$onboarding_welcome_screen_video_url:''; ?>" style="width:75%;">
          </td>
        </tr>
      </tbody>
    </table>
  <?php
  //}
}
add_action("pmpro_membership_level_after_other_settings", "pmprorh_pmpro_membership_level_after_other_settings");

/*
* Method: function for save new fields value with membership level on pmpro_membership_levelmeta database table
*/

function pmprorh_pmpro_save_membership_level_meta($levelid){
  global $wpdb;
  date_default_timezone_set("America/New_York");
  $_membership_group = $wpdb->get_var("SELECT meta_value from $wpdb->pmpro_membership_levelmeta WHERE pmpro_membership_level_id = $levelid AND meta_key = '_membership_group'");
  if(!empty($_membership_group)){
    $_mg_data = array('meta_value'=>$_REQUEST['membership_group']);
    $_mg_where = array('pmpro_membership_level_id' => $levelid, 'meta_key' => '_membership_group');
    $wpdb->update( $wpdb->pmpro_membership_levelmeta, $_mg_data, $_mg_where );
  }else{
    if(!empty($_REQUEST['membership_group'])){
      $wpdb->insert($wpdb->pmpro_membership_levelmeta, array('pmpro_membership_level_id'=>$levelid,'meta_key'=>'_membership_group','meta_value'=>$_REQUEST['membership_group']));
    }
  }
  $_meal_per_day = $wpdb->get_var("SELECT meta_value from $wpdb->pmpro_membership_levelmeta WHERE pmpro_membership_level_id = $levelid AND meta_key = '_meal_per_day'");
  if(!empty($_meal_per_day)){
    $_mpd_data = array('meta_value'=>$_REQUEST['meal_per_day']);
    $_mpd_where = array('pmpro_membership_level_id' => $levelid, 'meta_key' => '_meal_per_day');
    $wpdb->update( $wpdb->pmpro_membership_levelmeta, $_mpd_data, $_mpd_where );
  }else{
    if(!empty($_REQUEST['meal_per_day'])){
      $wpdb->insert($wpdb->pmpro_membership_levelmeta, array('pmpro_membership_level_id'=>$levelid,'meta_key'=>'_meal_per_day','meta_value'=>$_REQUEST['meal_per_day']));
    }
  }
  if(isset($_REQUEST['delivery_fee'])){
    $df = (!empty($_REQUEST['delivery_fee']) && $_REQUEST['delivery_fee'] > 0)?$_REQUEST['delivery_fee']:0;
    $_delivery_fee = $wpdb->get_var("SELECT meta_value from $wpdb->pmpro_membership_levelmeta WHERE pmpro_membership_level_id = $levelid AND meta_key = '_delivery_fee'");
    if($_delivery_fee != NULL && $_delivery_fee >= 0){
      $_di_data = array('meta_value'=>$df);
      $_di_where = array('pmpro_membership_level_id' => $levelid, 'meta_key' => '_delivery_fee');
      $wpdb->update( $wpdb->pmpro_membership_levelmeta, $_di_data, $_di_where );
    }else{
      $wpdb->insert($wpdb->pmpro_membership_levelmeta, array('pmpro_membership_level_id'=>$levelid,'meta_key'=>'_delivery_fee','meta_value'=>$df));
    }
  }
  $_onboarding_welcome_screen_text = $wpdb->get_var("SELECT meta_value from $wpdb->pmpro_membership_levelmeta WHERE pmpro_membership_level_id = $levelid AND meta_key = '_onboarding_welcome_screen_text'");
  if(!empty($_onboarding_welcome_screen_text)){
    $_obwstxt_data = array('meta_value'=>$_REQUEST['onboarding_welcome_screen_text']);
    $_obwstxt_where = array('pmpro_membership_level_id' => $levelid, 'meta_key' => '_onboarding_welcome_screen_text');
    $wpdb->update( $wpdb->pmpro_membership_levelmeta, $_obwstxt_data, $_obwstxt_where );
  }else{
    if(!empty($_REQUEST['onboarding_welcome_screen_text'])){
      $wpdb->insert($wpdb->pmpro_membership_levelmeta, array('pmpro_membership_level_id'=>$levelid,'meta_key'=>'_onboarding_welcome_screen_text','meta_value'=>$_REQUEST['onboarding_welcome_screen_text']));
    }
  }
  $_onboarding_welcome_screen_video_url = $wpdb->get_var("SELECT meta_value from $wpdb->pmpro_membership_levelmeta WHERE pmpro_membership_level_id = $levelid AND meta_key = '_onboarding_welcome_screen_video_url'");
  if(!empty($_onboarding_welcome_screen_video_url)){
    $_obwsvurl_data = array('meta_value'=>$_REQUEST['onboarding_welcome_screen_video_url']);
    $_obwsvurl_where = array('pmpro_membership_level_id' => $levelid, 'meta_key' => '_onboarding_welcome_screen_video_url');
    $wpdb->update( $wpdb->pmpro_membership_levelmeta, $_obwsvurl_data, $_obwsvurl_where );
  }else{
    if(!empty($_REQUEST['onboarding_welcome_screen_video_url'])){
      $wpdb->insert($wpdb->pmpro_membership_levelmeta, array('pmpro_membership_level_id'=>$levelid,'meta_key'=>'_onboarding_welcome_screen_video_url','meta_value'=>$_REQUEST['onboarding_welcome_screen_video_url']));
    }
  }
  //manage membership plan revision
  $mem_pln_sql = "SELECT * FROM ".$wpdb->prefix."pmpro_membership_levels WHERE id = $levelid";
  $mem_pln_data = $wpdb->get_row($mem_pln_sql, ARRAY_A);
  if(isset($mem_pln_data['id']) && $mem_pln_data['id'] > 0){
    $mem_pln_revision_data = $mem_pln_data;
    unset($mem_pln_revision_data['id']);
    $mem_pln_revision_data['reference_id'] = $mem_pln_data['id'];
    $mem_pln_revision_data['membership_group'] = $_REQUEST['membership_group'];
    $mem_pln_revision_data['meal_per_day'] = $_REQUEST['meal_per_day'];
    $mem_pln_revision_data['delivery_fee'] = $_REQUEST['delivery_fee'];
    $mem_pln_revision_data['onboarding_welcome_screen_text'] = $_REQUEST['onboarding_welcome_screen_text'];
    $mem_pln_revision_data['onboarding_welcome_screen_video_url'] = $_REQUEST['onboarding_welcome_screen_video_url'];
    $mem_pln_revision_data['create_by'] = get_current_user_id();
    $mem_pln_revision_data['ip_address'] = get_client_ip();
    $wpdb->insert($wpdb->prefix.'membership_plans_revision',$mem_pln_revision_data);
  }
}
add_action("pmpro_save_membership_level", "pmprorh_pmpro_save_membership_level_meta");

/*
* Method: function for save extra field value with customer
*/

function pmpro_update_extra_fields_after_checkout($user_id)
{
  if(!empty($user_id)){
    $sdi = (isset($_REQUEST['special_delivery_instructions']) && !empty($_REQUEST['special_delivery_instructions']))?$_REQUEST['special_delivery_instructions']:'';
    update_user_meta($user_id, "pmpro_special_delivery_instructions", $sdi);
    if(isset($_REQUEST['bemail']) && !empty($_REQUEST['bemail'])){
      update_user_meta($user_id, 'pmpro_bemail', $_REQUEST['bemail']);
    }
    if(isset($_REQUEST['delivery_or_pickup']) && !empty($_REQUEST['delivery_or_pickup'])){
      update_user_meta($user_id, "delivery_or_pickup", $_REQUEST['delivery_or_pickup']);
      if($_REQUEST['delivery_or_pickup'] == 2){
        if(isset($_REQUEST['pickup_location']) && !empty($_REQUEST['pickup_location'])){
          update_user_meta($user_id, "pickup_location", $_REQUEST['pickup_location']);
          $pl_detail = get_pickup_location_detail($_REQUEST['pickup_location']);
          if(!empty($pl_detail)){
            update_user_meta($user_id, "pickup_location_detail", $pl_detail);
          }
        }
        if(isset($_REQUEST['force_pickup_location_selected']) && !empty($_REQUEST['force_pickup_location_selected'])){
          update_user_meta($user_id, "force_pickup_location_selected", $_REQUEST['force_pickup_location_selected']);
        }
      }
    }
  }
}
add_action('pmpro_after_checkout', 'pmpro_update_extra_fields_after_checkout');

/*
* Method: function for add admin custom menus in admin section
*/

add_action('admin_menu', 'admin_custom_menus');
function admin_custom_menus() {
  add_menu_page('Manage Zipcodes','Manage Zipcodes', 'manage_options', 'zipcodes', 'zipcodes_list', 'dashicons-list-view');
  add_submenu_page('pmpro-membershiplevels', 'Memberships Groups', 'Memberships Groups', 'edit_posts', 'edit-tags.php?taxonomy=membershipgroup',false);
  if(isset($_GET['taxonomy']) && $_GET['taxonomy'] == 'membershipgroup'){
  ?>
    <style type="text/css">
      div.row-actions span.delete{display:none;}
      div.row-actions span.view{display:none;}
      div.row-actions span.inline{display:none;}
      select#bulk-action-selector-top option[value=delete] {display: none;}
    </style>
  <?php
  }
  add_submenu_page('pmpro-membershiplevels', 'Coupons', 'Coupons', 'edit_posts', 'edit.php?post_type=membership-coupons',false);
  add_menu_page('Locked Meals Orders','Locked Meals Orders', 'manage_options', 'locked-meals-orders', 'locked_meals_orders_list', 'dashicons-list-view');
  add_menu_page('Feedback', 'Feedback', 'manage_options', 'cancelled-accounts', 'cancelled_accounts_feedback_list', 'dashicons-list-view');
  add_submenu_page( 'cancelled-accounts', 'Cancelled accounts', 'Cancelled accounts',
      'manage_options', 'cancelled-accounts');
  add_submenu_page( 'cancelled-accounts', 'Meals ratings', 'Meals ratings', 'manage_options', 'meals-ratings','meals_ratings_list');
  add_menu_page('Live Menu Activation','Live Menu Activation', 'manage_options', 'live-menu-activation', 'live_menu_activation_page', 'dashicons-list-view');
  add_menu_page('Schedule days off', 'Schedule days off', 'manage_options', 'schedule-days-off', 'schedule_days_off_list', 'dashicons-list-view');
}

/*
* Method: function for display added zipcodes list in admin section
*/

function zipcodes_list() {
  date_default_timezone_set("America/New_York");
  if(isset($_GET['action']) && !empty($_GET['action'])){
    if($_GET['action'] == 1){//add new zip code
      add_zipcode();
    }elseif($_GET['action'] == 2){//update zip code
      if(isset($_GET['zc']) && !empty($_GET['zc'])){
        $zc = base64_decode($_GET['zc']);
        edit_zipcode($zc);
      }
    }
  }else{
    $data = get_option('zipcodes');
    ?>
    <div class="wrap">
      <h1 class="wp-heading-inline">Zip Codes Info</h1>
      <a href="<?php echo admin_url(); ?>admin.php?page=zipcodes&action=1" class="page-title-action">Add New</a>
      <hr class="wp-header-end">
    <?php
    $html='<div class="nosubsub">
        <div id="ajax-response"></div>
          <div id="col-container">
                <table class="wp-list-table widefat fixed striped tags" width="100%">
                  <thead>
                    <tr>
                      <th width="100%"><span>Zip Code</span></th>
                    </tr>
                  </thead>'; 
                  if (isset($data) && !empty($data)){
                    foreach ($data as $val) {
                      $editurl = admin_url(). "admin.php?page=zipcodes&action=2&zc=".base64_encode($val);
                      $html .= '<tbody id="the-list" data-wp-lists="list:tag">
                        <tr>
                          <td class="name column-name has-row-actions column-primary" data-colname="Name"><strong><a class="row-title" href="'.$editurl.'" title="Edit">'.$val.'</a></strong><br>
                             <div class="row-actions">'; 
                             $html .= '<span class="edit"><a href="'.$editurl.'">Edit</a></span>&nbsp;<span class="delete"><a href="javascript:void(0);" onclick="delete_zipcode('.$val.');">Delete</a></span></td>
                        </tr>';
                    }
                  }else{
                    $html .= '<tr><td colspan="8" align="center">No record found!</td></tr>';
                  }
                  $html .= '</tbody>
                  <tfoot>
                    <tr>
                      <th width="100%"><span>Zip Code</span></th>
                    </tr>
                  </tfoot>
                </table>
            </div>
        </div>';
    echo $html;
   ?>
    </div>
    <?php
  }
}

/*
* Method: function for add zipcode Info
*/

function add_zipcode() {
  echo '<div class="wrap">
          <div class="postbox" style="margin: 15px 0 10px;padding: 10px;">
            <h3 class="hndle ui-sortable-handle" style="margin-top:5px;"><span>Add New Zipcode</span></h3>
            <div style="margin: 5px;text-align: right;" class="inside">
              <form method="post" id="zipcode_frm" action="">
                <table width="100%">
                  <tr align="left">
                    <th width="10%"><span>Zip Code</span></th>
                    <td width="90%"><input type="text" name="zipcode" id="zipcode" placeholder="Enter Zipcode" value=""></td>
                  </tr>
                  <tr align="left"><td colspan="2">&nbsp;</td></tr>
                  <tr align="left">
                    <td colspan="2"><input type="button" id="save_zipcode" value="Save" class="button button-primary">&nbsp;<a type="button" href="'.admin_url(). "admin.php?page=zipcodes".'" class="button button-primary">Back</a></td>
                  </tr>
                </table>
              </form>
            </div>
          </div>
        </div>
      </div>';
}

/*
* Method: function for edit zipcode Info
*/

function edit_zipcode($zc) {
    $html = '<div class="wrap">
          <div class="postbox" style="margin: 15px 0 10px;padding: 10px;">
            <h3 class="hndle ui-sortable-handle" style="margin-top:5px;"><span>Edit Zipcode</span></h3>
            <div style="margin: 5px;text-align: right;" class="inside">
              <form method="post" id="zipcode_frm" action="">
              <table width="100%">';
      if(isset($zc) && !empty($zc)){
          $html .= '<tr align="left">
                    <th width="10%"><span>Zip Code</span></th>
                    <td width="90%">
                      <input type="hidden" name="existing_zipcode" id="existing_zipcode" value="'.$zc.'">
                      <input type="text" name="zipcode" id="zipcode" placeholder="Enter Zipcode" value="'.$zc.'">
                    </td>
                  </tr>
                  <tr align="left"><td colspan="2">&nbsp;</td></tr>
                  <tr align="left">
                    <td colspan="2"><input type="button" id="save_zipcode" value="Save" class="button button-primary">&nbsp;<a type="button" href="'.admin_url(). "admin.php?page=zipcodes".'" class="button button-primary">Back</a></td>
                  </tr>';
      }else{
          $html .= '<tr><td colspan="2"><span>No record found!</span></td></tr>
                  <tr align="left"><td colspan="2">&nbsp;</td></tr>
                  <tr align="left">
                    <td colspan="2"><a type="button" href="'.admin_url(). "admin.php?page=zipcodes".'" class="button button-primary">Back</a></td>
                  </tr>';
      }
    $html .= '</table>
              </form>
            </div>
          </div>
        </div>
      </div>';
  echo $html;
}

/*
 * Method: function for save zipcode info
 */

function save_zipcode_info(){
  $parameters = array();
  parse_str($_POST['fdata'], $parameters);
  $is_error = 0;
  $error_msg = array();
  date_default_timezone_set("America/New_York");
  if(isset($parameters['zipcode']) && !empty($parameters['zipcode'])){
      $zipcode = $parameters['zipcode'];
  }else{
      $is_error = 1;
      $error_msg['zipcode'] = 'Zipcode field is required!';
  }
  if(isset($is_error) && $is_error==1){
    $response = array('error'=>1,'msg'=>$error_msg);
  }else{
    $zipcodesArr = get_option('zipcodes');
    if(isset($parameters['existing_zipcode']) && !empty($parameters['existing_zipcode'])){
      //edit zipcode
      if(!empty($zipcodesArr) && in_array($zipcode, $zipcodesArr) && $parameters['existing_zipcode'] != $zipcode){
        $response = array('error'=>1,'msg'=>array('zipcode'=>'Zipcode already exist.'));
      }else{
        $existing_zipcode_ind = array_search($parameters['existing_zipcode'],$zipcodesArr);
        $zipcodesArr[$existing_zipcode_ind] = $zipcode;
        update_option('zipcodes',$zipcodesArr);
        $response = array('error'=>0,'msg'=>'Zipcode info successfully saved.','redirecturl'=>admin_url()."admin.php?page=zipcodes");
      }
    }else{
      //add zipcode
      $_zipcode_ind = array_search($zipcode,$zipcodesArr);
      if(!empty($zipcodesArr) && in_array($zipcode, $zipcodesArr)){
        $response = array('error'=>1,'msg'=>array('zipcode'=>'Zipcode already exist.'));
      }else{
        $zipcodesArr[] = $zipcode;
        update_option('zipcodes',$zipcodesArr);
        $response = array('error'=>0,'msg'=>'Zipcode info successfully saved.','redirecturl'=>admin_url()."admin.php?page=zipcodes");
      }
    }
  }
  echo json_encode($response);
  exit();
}

/*
 * Method: function for remove zipcode
 */

function remove_zipcode(){
  if(isset($_POST['zc']) && !empty($_POST['zc'])){
    $zipcodesArr = get_option('zipcodes');
    $zipcode_ind = array_search($_POST['zc'],$zipcodesArr);
    unset($zipcodesArr[$zipcode_ind]);  
    update_option('zipcodes',$zipcodesArr);
    $response = array('error'=>0,'msg'=>'Zipcode successfully removed.','redirecturl'=>admin_url()."admin.php?page=zipcodes");
  }else{
    $response = array('error'=>1,'msg'=>'Something goes wrong, please try again later!','redirecturl'=>admin_url()."admin.php?page=zipcodes");
  }
  echo json_encode($response);
  exit();
}


/*
* Method: Function for display meal ratings given bu user
*/

function show_meal_ratings(){
  global $wpdb, $post;
  $meal_id = (int) $post->ID;
  $sql = "SELECT um.id,um.meal_date,um.rating,p.ID,um.user_id FROM wp_users_meals as um JOIN $wpdb->posts p ON (p.ID = um.meal_id) JOIN $wpdb->users u ON (u.ID = um.user_id) WHERE meal_id = $meal_id AND um.rating > 0";
  $_meals = $wpdb->get_results($sql);
  wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/css/font-awesome.min.css', array(),'4.7.0');
?>
  <div>
      <div class="wrap">
        <div class="meta-box-sortables ui-sortable">
          <div class="" style="padding: 8px;">
            <div id="menu_meta_msg"></div>
            <table style="width: 100%;" class="meal-user-rating">
              <tbody>
                <tr>
                  <td width="25%" class="fnt-bold">User</td>
                  <td width="25%" class="fnt-bold">Date(Day)</td>
                  <td width="50%" class="fnt-bold">Rating</td>
                </tr>
                <?php if(!empty($_meals) && count($_meals)>0){
                        foreach ($_meals as $mkey => $mval) {
                          $user_name = $day_full_name = '';
                          if(isset($mval->user_id) && $mval->user_id > 0){
                            $first_name = get_user_meta( $mval->user_id, 'first_name', true );
                            $last_name = get_user_meta( $mval->user_id, 'last_name', true );
                            $user_name = ((!empty($first_name))?$first_name:'').((!empty($last_name))?' '.$last_name:'');
                          }
                          if(isset($mval->meal_date) && !empty($mval->meal_date)){
                            $day_full_name = $mval->meal_date.' ('.ucfirst(date('l', strtotime($mval->meal_date))).')';  
                          }
                ?>
                          <tr>
                            <td><?php echo $user_name; ?></td>
                            <td><?php echo $day_full_name; ?></td>
                            <td>
                              <div class="starRating">
                                <?php
                                for($i=1;$i<=5;$i++){
                                    $act_cls = ($i <= $mval->rating)?'active':'';
                                ?>
                                    <i class="fa fa-star-o <?php echo $act_cls; ?>" aria-hidden="true"></i>
                                <?php } ?>
                                </div>
                            </td>
                          </tr>
                    <?php }
                  }else{ ?>
                    <tr>
                      <td colspan="3" align="center"><strong>No Record Found!</strong></td>
                    </tr>
                  <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  </div>
 <?php    
}

/*
* Method: Function for add custom taxonomy (group) for membership
*/

add_action( 'init', 'create_membership_groups_taxonomy', 0 );
function create_membership_groups_taxonomy() {
    register_taxonomy(
        'membershipgroup',
        'pmpro-membershiplevels',
        array(
            'labels' => array(
                'name' => 'Group',
                'add_new_item' => 'Add New Group',
                'new_item_name' => "New Group"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true
        )
    );
}

function get_status_list(){
  return array('1' => 'Active','0' => 'Inactive');
}

add_action( 'membershipgroup_add_form_fields', 'membershipgroup_taxonomy_custom_field_add', 10 );
function membershipgroup_taxonomy_custom_field_add( $taxonomy ) {
  $statusArr = get_status_list();
?>
  <div class="form-field">
    <label for="group_status">Status</label>
    <select id="group_status" name="group_status">
    <?php
      foreach ($statusArr as $gs_key => $gs_val) {
        ?>
        <option value="<?php echo $gs_key; ?>"><?php echo $gs_val; ?></option>
        <?php
      }
    ?>
    <select>
  </div>
<?php }

add_action( 'membershipgroup_edit_form_fields', 'membershipgroup_taxonomy_custom_field_edit', 10, 2 );
function membershipgroup_taxonomy_custom_field_edit( $tag, $taxonomy ) {
  $statusArr = get_status_list();
  $_status_key = 'status_' . $tag->term_id;
  $_status = get_option($_status_key);
?>
  <tr class="form-field">
    <th scope="row" valign="top"><label for="group_status">Status</label></th>
    <td>
      <select id="group_status" name="group_status">
      <?php
        foreach ($statusArr as $gs_key => $gs_val) {
          $slcted = ($_status == $gs_key)?'selected':'';
          ?>
          <option value="<?php echo $gs_key; ?>" <?php echo $slcted; ?>><?php echo $gs_val; ?></option>
        <?php
        }
      ?>
    <select>
    </td>
  </tr>
  <style type="text/css">
    div.edit-tag-actions span#delete-link{display:none;}
  </style>
<?php
}
 
/** Save Custom Field Of Category Form */
add_action( 'created_membershipgroup', 'membershipgroup_custom_field_save', 10, 2 ); 
add_action( 'edited_membershipgroup', 'membershipgroup_custom_field_save', 10, 2 );
 
function membershipgroup_custom_field_save( $term_id, $tt_id ) {
  if ( isset( $_POST['group_status'] ) ) {           
    $option_name = 'status_' . $term_id;
    update_option( $option_name, $_POST['group_status'] );
  }
}

/*
* Method: function for save TAX RATE in general setting page
*/

add_action('admin_init', 'my_general_section');  
function my_general_section() {  
  add_settings_section(  
    'my_settings_section', // Section ID 
    'Tax Setting', // Section Title
    'dd_section_options_callback', // Callback
    'general' // What Page?  This makes the section show up on the General Settings Page
  );
  add_settings_field( // Option 1
    'option_1', // Option ID
    'Tax Rate(%)', // Label
    'dd_textbox_callback', // !important - This is where the args go!
    'general', // Page it will be displayed (General Settings)
    'my_settings_section', // Name of our section
    array( // The $args
      'tax_rate' // Should match Option ID
    )  
  ); 
  register_setting('general','tax_rate', 'esc_attr');

}

function dd_section_options_callback() { // Section Callback
  echo '<p>Please enter Tax Rate in percentage. It will apply to meal plan throughout the website.</p>';
}

function dd_textbox_callback($args) { // Textbox Callback
  $tax_rate = get_option($args[0]);
  echo '<input type="text" id="'. $args[0] .'" name="'. $args[0] .'" value="' . $tax_rate . '" />';
  if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == true) {
    //manage tax rate revision
    global $wpdb;
    $tr_sql = "SELECT tax_rate FROM ".$wpdb->prefix."tax_rate_revision order by id DESC limit 1";
    $tr_data = $wpdb->get_row($tr_sql, ARRAY_A);
    if(isset($tr_data['tax_rate']) && $tr_data['tax_rate'] != $tax_rate){
      $tax_rate_revision_data = array(
        'tax_rate' => $tax_rate,
        'create_by' => get_current_user_id(),
        'ip_address'=> get_client_ip()
      );
      $wpdb->insert($wpdb->prefix.'tax_rate_revision',$tax_rate_revision_data);
    }
  }
}

/*
* Method: function for register membership coupons
*/

add_action( 'init', 'register_membership_coupons');
function register_membership_coupons(){
  $labels = array(
    'name'               => __( 'Coupons', 'toughcookies'),
    'singular_name'      => __( 'Coupon', 'toughcookies' ),
    'add_new'            => __( 'Add New Coupon', 'toughcookies' ),
    'add_new_item'       => __( 'Add Coupon', 'toughcookies' ),
    'edit_item'          => __( 'Edit Coupon', 'toughcookies' ),
    'new_item'           => __( 'New Coupon', 'toughcookies' ),
    'view_item'          => __( 'View Coupon', 'toughcookies' ),
    'search_items'       => __( 'Search Coupon', 'toughcookies' ),
    'not_found'          => __( 'No Coupon found', 'toughcookies' ),
    'not_found_in_trash' => __( 'No Coupon found in Trash', 'toughcookies' ),
    'parent_item_colon'  => ''
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds Coupon related data.',
    'public' => true,
    'show_in_menu' => false,
    'supports'      => array( 'title', 'editor'),
    'menu_icon' => 'dashicons-media-spreadsheet',
    'has_archive'   => true,
  );
  register_post_type('membership-coupons', $args );
}

/*
* Method: Function for display coupon meta box content
*/

function add_membership_coupon_meta_box(){
  global $post;
  date_default_timezone_set("America/New_York");
  $coupon_types = get_coupon_types();
  $_coupon_type = get_post_meta($post->ID,'_coupon_type',true);
  $coupon_categories = get_coupon_category_list();
  $_coupon_category = get_post_meta($post->ID,'_coupon_category',true);
  $_amount = get_post_meta($post->ID,'_amount',true);
  $_expiry_date = get_post_meta($post->ID,'_expiry_date',true);
  $_usage_limit_per_coupon = get_post_meta($post->ID,'_usage_limit_per_coupon',true);
  $_usage_limit_per_user = get_post_meta($post->ID,'_usage_limit_per_user',true);
  $_one_time_or_recurring = get_post_meta($post->ID,'_one_time_or_recurring',true);
  $nbc_cls = ($_one_time_or_recurring == 'recurring')?'':'dnone';
  $_number_of_billing_cycles = get_post_meta($post->ID,'_number_of_billing_cycles',true);
  $_linked_affiliate_user = get_post_meta($post->ID,'_linked_affiliate_user',true);
  $slcted_affiliate_usr = '';
  if($_linked_affiliate_user > 0){
    $usr = get_userdata($_linked_affiliate_user);
    $slcted_affiliate_usr = (!empty($usr->user_login))?$usr->user_login.' ('.$usr->user_email.')':'';
  }
  //$_link_affiliate_cls = (!empty($_coupon_category) && $_coupon_category == 'gift_card')?'dnone':'';
  $_coupon_fields_cls = (!empty($_coupon_category) && $_coupon_category == 'gift_card')?'dnone':'';
  $is_add_rule_2 = get_post_meta($post->ID,'_is_add_rule_2',true);
  $rule_2_box_cls = ($is_add_rule_2 == 1)?'':'dnone';
  $rule_2_coupon_type = get_post_meta($post->ID,'_rule_2_coupon_type',true);
  $rule_2_coupon_amount = get_post_meta($post->ID,'_rule_2_coupon_amount',true);
  $rule_2_number_of_billing_cycles = get_post_meta($post->ID,'_rule_2_number_of_billing_cycles',true);
  $force_pickup_location = get_post_meta($post->ID,'_force_pickup_location',true);
  $fpll_cls = ($force_pickup_location == 'yes')?'':'dnone';
  $location_link = get_post_meta($post->ID,'_slcted_location_link',true);
  $slcted_ppl = '';
  if($location_link > 0){
    $slcted_ppl = get_the_title($location_link);
  }
  $apply_to_first_billing_period = get_post_meta($post->ID,'_apply_to_first_billing_period',true);
?>
  <div>
      <div class="wrap">
        <div class="meta-box-sortables ui-sortable">
          <div class="">
            <div id="coupon_meta_msg"></div>
            <table style="width: 100%;" cellspacing="15">
              <tbody>
                <tr>
                  <th width="15%"><label for="coupon_category" class="fleft">Category <span class="required">*</span></label></th>
                  <td width="85%">
                    <select id="coupon_category" name="coupon_category">
                      <?php foreach ($coupon_categories as $cc_key => $cc_val) {
                        $slcted = (isset($_coupon_category) && $_coupon_category == $cc_key)?'selected':'';
                      ?>
                        <option value="<?php echo $cc_key; ?>" <?php echo $slcted; ?>><?php echo $cc_val; ?></option>
                      <?php } ?>
                    </select>
                  </td>
                </tr>
                <?php $screen = get_current_screen();
                if( 'add' == $screen->action ){ ?>
                <tr>
                  <th width="15%">&nbsp;</th>
                  <td width="85%">
                    <input type="button" name="generate_coupon_code" id="generate_coupon_code" class="button" value="Generate Coupon Code">
                    <input type="button" name="generate_gift_card_code" id="generate_gift_card_code" class="button dnone" value="Generate Gift Card Code">
                  </td>
                </tr>
              <?php } ?>
                <tr class="fld-bg-color">
                  <th width="15%"><label for="coupon_type" class="fleft">Coupon Type <span class="required">*</span></label></th>
                  <td width="85%">
                    <select id="coupon_type" name="coupon_type">
                      <?php foreach ($coupon_types as $ct_key => $ct_val) {
                        $slcted = (isset($_coupon_type) && $_coupon_type == $ct_key)?'selected':'';
                      ?>
                        <option value="<?php echo $ct_key; ?>" <?php echo $slcted; ?>><?php echo $ct_val; ?></option>
                      <?php } ?>
                    </select>
                  </td>
                </tr>
                <tr class="fld-bg-color">
                  <th width="15%"><label for="coupon_amount" class="fleft">Coupon amount <span class="required">*</span></label></th>
                  <td width="85%">
                    <input type="text" class="isonlynumeric" name="coupon_amount" id="coupon_amount" placeholder="Coupon amount" value="<?php echo (!empty($_amount))?$_amount:1; ?>" />
                  </td>
                </tr>
                <tr class="fld-bg-color">
                  <th width="15%"><label for="coupon_one_time_or_recurring" class="fleft">One Time or Recurring? <span class="required">*</span></label></th>
                  <td width="85%">
                    <input type="radio" name="coupon_one_time_or_recurring" value="one_time" checked> One Time &nbsp;
                    <input type="radio" name="coupon_one_time_or_recurring" value="recurring" <?php echo ($_one_time_or_recurring == 'recurring')?'checked':'';?>> Recurring<br>
                  </td>
                </tr>
                <tr id="coupon_billing_cycles" class="fld-bg-color <?php echo $nbc_cls; ?>">
                  <th width="15%"><label for="number_of_billing_cycles" class="fleft">Number of billing cycles <span class="required">*</span></label></th>
                  <td width="85%">
                    <input type="number" class="isonlyinteger" min="1" name="number_of_billing_cycles" id="number_of_billing_cycles" placeholder="Number of billing cycles" value="<?php echo (!empty($_number_of_billing_cycles))?$_number_of_billing_cycles:1; ?>"/>
                  </td>
                </tr>
                <tr class="fld-bg-color fields-for-coupon <?php echo $_coupon_fields_cls; ?> <?php echo ($is_add_rule_2 == 1)?'dnone':''; ?>">
                  <th width="15%">&nbsp;</th>
                  <td width="85%">
                    <input type="button" name="add_rule_2" id="add_rule_2" class="button" value="+ ADD RULE">
                    <input type="hidden" name="is_add_rule_2" id="is_add_rule_2" value="<?php echo $is_add_rule_2; ?>" />
                  </td>
                </tr>
                <tr class="fld-bg-color coupon-rule-2 <?php echo $rule_2_box_cls; ?>">
                  <th width="15%">&nbsp;</th>
                  <td width="85%">
                    <div class="rule-2-box">RULE 2</div>
                    <div class="pmpro_lite">Discount can not overlap. Rule 2 will apply once Rule 1 is completed.</div>
                  </td>
                </tr>
                <tr class="fld-bg-color coupon-rule-2 <?php echo $rule_2_box_cls; ?>">
                  <th width="15%"><label for="rule_2_coupon_type" class="fleft">Coupon Type <span class="required">*</span></label></th>
                  <td width="85%">
                    <select id="rule_2_coupon_type" name="rule_2_coupon_type">
                      <?php foreach ($coupon_types as $r2_ct_key => $r2_ct_val) {
                        $r2_slcted = (isset($rule_2_coupon_type) && $rule_2_coupon_type == $r2_ct_key)?'selected':'';
                      ?>
                        <option value="<?php echo $r2_ct_key; ?>" <?php echo $r2_slcted; ?>><?php echo $r2_ct_val; ?></option>
                      <?php } ?>
                    </select>
                  </td>
                </tr>
                <tr class="fld-bg-color coupon-rule-2 <?php echo $rule_2_box_cls; ?>">
                  <th width="15%"><label for="rule_2_coupon_amount" class="fleft">Fixed Value <span class="required">*</span></label></th>
                  <td width="85%">
                    <input type="text" class="isonlynumeric" name="rule_2_coupon_amount" id="rule_2_coupon_amount" placeholder="Coupon amount" value="<?php echo (!empty($rule_2_coupon_amount))?$rule_2_coupon_amount:1; ?>" />
                  </td>
                </tr>
                <tr class="fld-bg-color coupon-rule-2 <?php echo $rule_2_box_cls; ?>">
                  <th width="15%"><label for="rule_2_number_of_billing_cycles" class="fleft">Number of billing cycles <span class="required">*</span></label></th>
                  <td width="85%">
                    <input type="number" class="isonlyinteger" min="1" name="rule_2_number_of_billing_cycles" id="rule_2_number_of_billing_cycles" placeholder="Number of billing cycles" value="<?php echo (!empty($rule_2_number_of_billing_cycles))?$rule_2_number_of_billing_cycles:1; ?>"/>
                    <div class="remove-rule-2-inner">
                      <input type="button" name="remove_rule_2" id="remove_rule_2" class="button" value="x REMOVE RULE">
                    </div>
                </tr>
                <tr>
                  <th width="15%"><label for="coupon_expiry_date" class="fleft">Coupon expiry date <span class="required">*</span></label></th>
                  <td width="85%">
                    <input type="text" class="datepicker" name="coupon_expiry_date" id="coupon_expiry_date" placeholder="yyyy-mm-dd" value="<?php echo (!empty($_expiry_date))?$_expiry_date:''; ?>" readonly="true" />
                  </td>
                </tr>
                <tr>
                  <th width="15%"><label for="usage_limit_per_coupon" class="fleft">Usage limit per coupon?</label></th>
                  <td width="85%">
                    <input type="number" class="isonlyinteger" min="0" name="usage_limit_per_coupon" id="usage_limit_per_coupon" placeholder="Unlimited usage" value="<?php echo (!empty($_usage_limit_per_coupon))?$_usage_limit_per_coupon:''; ?>"/>
                    <span class="txt-color-red">(How many times a coupon can be used by all customers before being invalid?)</span>
                  </td>
                </tr>
                <tr>
                  <th width="15%"><label for="usage_limit_per_user" class="fleft">Usage limit per user?</label></th>
                  <td width="85%">
                    <input type="number" class="isonlyinteger" min="0" name="usage_limit_per_user" id="usage_limit_per_user" placeholder="Unlimited usage" value="<?php echo (!empty($_usage_limit_per_user))?$_usage_limit_per_user:''; ?>"/>
                    <span class="txt-color-red">(How many times a coupon can be used by each customer before being invalid for that customer?)</span>
                  </td>
                </tr>
                <tr class="fields-for-coupon <?php echo $_coupon_fields_cls; ?>">
                  <th width="15%">&nbsp;</th>
                  <td width="85%"><hr/></td>
                </tr>
                <tr class="fields-for-coupon link-affiliate <?php echo $_coupon_fields_cls; ?>">
                  <th width="15%"><label for="affiliate_discount" class="fleft">Affiliate Discount?</label></th>
                  <td width="85%">
                    <input type="text" class="txt-fid-widt-50" name="affiliate_discount" id="affiliate_discount" value="<?php echo $slcted_affiliate_usr; ?>"/>
                    <input type="hidden" name="slcted_affiliate_usr" id="slcted_affiliate_usr" value="<?php echo ($_linked_affiliate_user > 0)?$_linked_affiliate_user:''; ?>"/>
                    <small class="pmpro_lite">If you would like to connect this discount to an affiliate, enter the name of the affiliate it belongs to.</small>
                  </td>
                </tr>
                <tr class="fields-for-coupon <?php echo $_coupon_fields_cls; ?>">
                  <th width="15%"><label for="force_pickup_location" class="fleft">Force pickup location? <span class="required">*</span></label></th>
                  <td width="85%">
                    <input type="radio" name="force_pickup_location" value="no" checked> No
                    <input type="radio" name="force_pickup_location" value="yes" <?php echo ($force_pickup_location == 'yes')?'checked':''; ?>> Yes
                  </td>
                </tr>
                <tr id="location_link_list" class="fields-for-coupon <?php echo $_coupon_fields_cls; ?> <?php echo $fpll_cls; ?>">
                  <th width="15%"><label for="location_link" class="fleft">Location link <span class="required">*</span></label></th>
                  <td width="85%">
                    <input type="text" class="txt-fid-widt-50" name="location_link" id="location_link" value="<?php echo $slcted_ppl; ?>"/>
                    <input type="hidden" name="slcted_location_link" id="slcted_location_link" value="<?php echo ($location_link > 0)?$location_link:''; ?>"/>
                    <small class="pmpro_lite">If you would like to connect this discount to an pickup location, enter the name of location or address.</small>
                  </td>
                </tr>
                <tr class="fields-for-coupon <?php echo $_coupon_fields_cls; ?>">
                  <th width="15%"><label for="apply_to_first_billing_period" class="fleft">Apply to first billing period? <span class="required">*</span></label></th>
                  <td width="85%">
                    <input type="radio" name="apply_to_first_billing_period" value="no" checked> No
                    <input type="radio" name="apply_to_first_billing_period" value="yes" <?php echo ($apply_to_first_billing_period == 'yes')?'checked':''; ?>> Yes
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  </div>
 <?php    
}

/*
* Method: function for coupon types
*/

function get_coupon_types(){
  return array('fixed_cart'=>'Fixed cart discount','percentage'=>'Percentage discount');
}

/*
* Method: function for add custom category to custom post ingredients.
*/

function add_custom_category_to_ingredients() {
  register_taxonomy('ingredients_categories', 'ingredients', array(
    'hierarchical' => true,
    'labels' => array(
      'name' => _x( 'Ingredients Category', 'taxonomy general name' ),
      'singular_name' => _x( 'Ingredient Category', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Ingredient Category' ),
      'all_items' => __( 'All Ingredients Category' ),
      'parent_item' => __( 'Parent Ingredients Category' ),
      'parent_item_colon' => __( 'Parent Ingredient Category:' ),
      'edit_item' => __( 'Edit Ingredient Category' ),
      'update_item' => __( 'Update Ingredient Category' ),
      'add_new_item' => __( 'Add New Ingredient Category' ),
      'new_item_name' => __( 'New Ingredient Category Name' ),
      'menu_name' => __( 'Categories' ),
    ),
    'rewrite' => array(
      'slug' => 'ingredients_categories',
      'with_front' => false,
      'hierarchical' => true
    ),
  ));
}
add_action( 'init', 'add_custom_category_to_ingredients', 0 );

/*
* Method: founction for add custom fields with ingredient category.
*/

add_action('ingredients_categories_edit_form_fields','add_custom_fields_with_ingredient_category'); 
add_action('ingredients_categories_add_form_fields','add_custom_fields_with_ingredient_category');
function add_custom_fields_with_ingredient_category($term) {
  date_default_timezone_set("America/New_York");
  $termid = (isset($term->term_id) && !empty($term->term_id))?$term->term_id:'';
  $_status = get_term_meta($termid,'_status',true);
  $status_list = get_status_list();
?>
  <tr class="form-field">
      <th valign="top" scope="row">
          <label for="status"><?php _e('Status', 'toughcookies'); ?></label>
      </th>
      <td>
        <select name="status" id="status">
          <?php
            foreach ($status_list as $sl_key => $sl_val) {
              $_slcted = ($_status == $sl_key)?'selected':'';
          ?>
              <option value="<?php echo $sl_key; ?>" <?php echo $_slcted; ?>><?php echo $sl_val; ?></option>
          <?php } ?>
        </select>
        <p>Status of Ingredients Category.</p>
      </td>
  </tr>
<?php 
}

/*
* Method: founction for save custom fields with ingredient category.
*/

add_action ('edited_ingredients_categories', 'save_custom_fileds_for_ingredient_category');
add_action('created_ingredients_categories','save_custom_fileds_for_ingredient_category');
function save_custom_fileds_for_ingredient_category( $term_id ){
  if(isset($_POST['status'])) {
    update_term_meta($term_id, '_status', $_POST['status']);
  }
}

/*
* Method: function for add custom category(units) to custom post ingredients.
*/

function add_custom_units_to_ingredients() {
  register_taxonomy('ingredients_units', 'ingredients', array(
    'hierarchical' => true,
    'labels' => array(
      'name' => _x( 'Ingredient Units', 'toughcookies' ),
      'singular_name' => _x( 'Ingredient Unit', 'toughcookies' ),
      'search_items' =>  __( 'Search Ingredient Unit', 'toughcookies' ),
      'all_items' => __( 'All Ingredient Unit', 'toughcookies' ),
      'parent_item' => __( 'Parent Ingredient Unit', 'toughcookies' ),
      'parent_item_colon' => __( 'Parent Ingredient Unit:', 'toughcookies' ),
      'edit_item' => __( 'Edit Ingredient Unit', 'toughcookies' ),
      'update_item' => __( 'Update Ingredient Unit', 'toughcookies' ),
      'add_new_item' => __( 'Add New Ingredient Unit', 'toughcookies' ),
      'new_item_name' => __( 'New Ingredient Unit Name', 'toughcookies' ),
      'menu_name' => __( 'Units' ),
    ),
    'rewrite' => array(
      'slug' => 'ingredients_units', 
      'with_front' => false,
      'hierarchical' => true
    ),
  ));
}
add_action( 'init', 'add_custom_units_to_ingredients', 0 );

/*
* Method: function for add extra fields to custom category(units).
*/

add_action('ingredients_units_edit_form_fields','add_extra_fields_with_ing_unit_page'); 
add_action('ingredients_units_add_form_fields','add_extra_fields_with_ing_unit_page'); 
function add_extra_fields_with_ing_unit_page ($term) {
  date_default_timezone_set("America/New_York");
  $termid = (isset($term->term_id) && !empty($term->term_id))?$term->term_id:'';
  $_status = get_term_meta($termid,'_status',true);
  $status_list = get_status_list();
  $iu_group = get_term_meta($termid,'_group',true);
  $iu_list = get_ingredients_groups();
  $iu_abbreviation = get_term_meta($termid,'_abbreviation',true);
?>
  <tr class="form-field">
      <th valign="top" scope="row">
          <label for="iu_status"><?php _e('Status', 'toughcookies'); ?></label>
      </th>
      <td>
        <select name="iu_status" id="iu_status">
          <?php
            foreach ($status_list as $sl_key => $sl_val) {
              $_slcted = ($_status == $sl_key)?'selected':'';
          ?>
              <option value="<?php echo $sl_key; ?>" <?php echo $_slcted; ?>><?php echo $sl_val; ?></option>
          <?php } ?>
        </select>
      <p>Status of Ingredient Unit.</p>
      </td>
  </tr>
  <tr class="form-field">
      <th valign="top" scope="row">
          <label for="group"><?php _e('Group', 'toughcookies'); ?></label>
      </th>
      <td>
         <select name="iu_group" id="iu_group">
          <?php
            foreach ($iu_list as $iu_key => $iu_val) {
              $_slcted = ($iu_group == $iu_key)?'selected':'';
          ?>
              <option value="<?php echo $iu_key; ?>" <?php echo $_slcted; ?>><?php echo $iu_val; ?></option>
          <?php } ?>
        </select>
      <p>Group of Ingredient Unit.</p>
      </td>
  </tr>
  <tr class="form-field">
    <th valign="top" scope="row">
      <label for="iu_abbreviation"><?php _e('Abbreviation','toughcookies'); ?></label>
    </th>
    <td>
        <input type="text" name="iu_abbreviation" id="iu_abbreviation" value="<?php echo $iu_abbreviation; ?>" />
        <p id="abbr_unit_error" class="txt-color-red"></p>
      <p>Abbreviation of Ingredient Unit.</p>
      </td>
    </tr>
    <?php 
}

/*
* Method: function for check unit abbreviation is unique or not
*/

function check_unit_abbreviation(){
  if(isset($_POST['val']) && !empty($_POST['val'])){
    date_default_timezone_set("America/New_York");
    $abbr = strtolower($_POST['val']);
    $grp = strtolower($_POST['group']);
    global $wpdb;
    $sql = "SELECT t.term_id,tm1.meta_value as uabr,tm2.meta_value as ugroup FROM ".$wpdb->prefix."terms as t JOIN ".$wpdb->prefix."termmeta as tm1 ON (tm1.term_id = t.term_id AND tm1.meta_key='_abbreviation' AND tm1.meta_value = '".$abbr."') JOIN ".$wpdb->prefix."termmeta as tm2 ON (tm2.term_id = t.term_id AND tm2.meta_key='_group' AND tm2.meta_value = '".$grp."')";
    $abbreviation = $wpdb->get_row($sql);
    if(isset($abbreviation->term_id) && !empty($abbreviation->term_id)){
      if(isset($_POST['termid']) && $_POST['termid'] == $abbreviation->term_id){
        $response = array('error'=>0,'msg'=>'Success');
      }else{
        $response = array('error'=>1,'msg'=>'Abbreviation already exist with other unit/group, please enter an unique value.');
      }
    }else{
      $response = array('error'=>0,'msg'=>'Success');
    }
  }else{
    $response = array('error'=>1,'msg'=>'Abbreviation field is required.');
  }
  echo json_encode($response);
  exit();
}

/*
* Method: function for get ingredients groups
*/
function get_ingredients_groups(){
  return array("dry"=>"Dry","liquid"=>"Liquid","weight"=>"Weight");
}

/*
* Method: function for add extra fields of ingredients units
*/

add_action('created_ingredients_units','add_custom_fileds_for_ingredient_units');
add_action('edited_ingredients_units', 'update_custom_fileds_for_ingredient_units');
function add_custom_fileds_for_ingredient_units($term_id){
  if ( isset( $_POST['iu_status'] ) ) {
    update_term_meta($term_id, '_status', $_POST['iu_status']);
  }
  if ( isset( $_POST['iu_group'] ) ) {
    update_term_meta($term_id, '_group', $_POST['iu_group']);
  }
  if ( isset( $_POST['iu_abbreviation'] ) ) {
    global $wpdb;
    date_default_timezone_set("America/New_York");
    $sql = "SELECT t.term_id,tm1.meta_value as uabr,tm2.meta_value as ugroup FROM ".$wpdb->prefix."terms as t JOIN ".$wpdb->prefix."termmeta as tm1 ON (tm1.term_id = t.term_id AND tm1.meta_key='_abbreviation' AND tm1.meta_value = '".strtolower($_POST['iu_abbreviation'])."') JOIN ".$wpdb->prefix."termmeta as tm2 ON (tm2.term_id = t.term_id AND tm2.meta_key='_group' AND tm2.meta_value = '".strtolower($_POST['iu_group'])."')";
    $abbreviation = $wpdb->get_row($sql);
    if(isset($abbreviation->term_id) && !empty($abbreviation->term_id)){
      wp_delete_term($term_id,'ingredients_units');
    }else{
      update_term_meta($term_id, '_abbreviation', strtolower($_POST['iu_abbreviation']));
    }
  }
}

/*
* Method: function for update extra fields of ingredients units
*/ 

function update_custom_fileds_for_ingredient_units($term_id) {
  if ( isset( $_POST['iu_status'] ) ) {
    update_term_meta($term_id, '_status', $_POST['iu_status']);
  }
  if(isset( $_POST['iu_abbreviation'])) {
    global $wpdb;
    date_default_timezone_set("America/New_York");
    $sql = "SELECT t.term_id,tm1.meta_value as uabr,tm2.meta_value as ugroup FROM ".$wpdb->prefix."terms as t JOIN ".$wpdb->prefix."termmeta as tm1 ON (tm1.term_id = t.term_id AND tm1.meta_key='_abbreviation' AND tm1.meta_value = '".strtolower($_POST['iu_abbreviation'])."') JOIN ".$wpdb->prefix."termmeta as tm2 ON (tm2.term_id = t.term_id AND tm2.meta_key='_group' AND tm2.meta_value = '".strtolower($_POST['iu_group'])."')";
    $abbreviation = $wpdb->get_row($sql);
    $is_error = 1;
    if(isset($abbreviation->term_id) && !empty($abbreviation->term_id)){
      if($abbreviation->term_id == $term_id){
        $is_error = 0;
      }
    }else{
      $is_error = 0;
    }
    if($is_error == 0){
      if ( isset( $_POST['iu_group'] ) ) {
        update_term_meta($term_id, '_group', $_POST['iu_group']);
      }
      update_term_meta($term_id, '_abbreviation', strtolower($_POST['iu_abbreviation']));
    }
  }
} 

/*
* Method: function for add category meta box on ingredients page.
*/

function add_ingredient_category_meta_box() {
  $ing_cat = get_post_meta(get_the_ID(),'_category',true);
  $ing_categories = get_terms(array(
    'taxonomy' => 'ingredients_categories',
    'orderby' => 'name',
    'order' => 'ASC',
    'hide_empty' => false,
    'meta_query' => array(
      array(
        'key'       => '_status',
        'value'     => 1,
        'compare'   => '='
      )
    )
  ));
  ?>
  <select name="ing_category" id="ing_category">
  <?php
    if(isset($ing_categories) && !empty($ing_categories)){
      foreach($ing_categories as $ic_key => $ic_val) {
        $slcted = ($ing_cat == $ic_val->term_id)?'selected':'';
        ?>
          <option value="<?php echo $ic_val->term_id;?>" <?php echo $slcted; ?>><?php echo $ic_val->name; ?></option>
      <?php
    }
  }
  ?>
  </select>
  <?php
}

/*
* Method: function for add group meta box on ingredients page.
*/

function add_ingredient_group_meta_box() {
  $_group = get_post_meta(get_the_ID(),'_group',true);
  $ig_list = get_ingredients_groups();
  ?>
  <select name="ig_group" id="ig_group">
    <?php
      foreach ($ig_list as $ig_key => $ig_val) {
        $_slcted = ($_group == $ig_key)?'selected':'';
    ?>
        <option value="<?php echo $ig_key; ?>" <?php echo $_slcted; ?>><?php echo $ig_val; ?></option>
    <?php } ?>
  </select>
  <?php
}

/*
* Method: function for remove screen options
*/
add_action( 'in_admin_header', 'admin_header_screen_options');
function admin_header_screen_options() {
  global $wp_meta_boxes;
  //get_current_screen()->id => to get current screen id
  unset($wp_meta_boxes['ingredients']['side']['core']['ingredients_categoriesdiv']);
  unset($wp_meta_boxes['ingredients']['side']['core']['ingredients_unitsdiv']);
  unset($wp_meta_boxes['menu-items']['side']['core']['menu-items-categorydiv']);
}

/*
* Method: function for get fractions list
*/

function get_fractions_list(){
  return array("0.0625"=>"1/16","0.125"=>"1/8","0.25"=>"1/4","0.3334"=>"1/3","0.5"=>"1/2","0.6667"=>"2/3","0.75"=>"3/4");
}

/*
* Method: function for get ingredients list
*/

function get_ingredients_list(){
  if(isset($_POST['enter_val']) && !empty($_POST['enter_val'])){
    global $wpdb;
    $ing_sql = "select ID, post_title from ".$wpdb->prefix."posts where post_type ='ingredients' AND post_status ='publish' AND post_title like '%".$_POST['enter_val']."%'";
    if(isset($_POST['slcted_items']) && !empty($_POST['slcted_items'])){
      $ing_sql .= " AND ID NOT IN (".$_POST['slcted_items'].")";
    }
    $ingredients = $wpdb->get_results($ing_sql);
    $ingData = array();
    if(isset($ingredients) && !empty($ingredients)){
      foreach ($ingredients as $ingredient){
        $ingData[] = array('ing_name'=>$ingredient->post_title,'ing_id'=>$ingredient->ID);
      }
      $response = array('error'=>0,'ingData'=>$ingData);
    }else{
      $response = array('error'=>1,'ingData'=>array());
    }
  }else{
    $response = array('error'=>1,'ingData'=>array());
  }
  echo json_encode($response);
  exit();
}

/*
* Method: function for get ingredient units
*/

function get_ing_unit_list(){
  if(isset($_POST['ing_val']) && !empty($_POST['ing_val'])){
    global $wpdb;
    date_default_timezone_set("America/New_York");
    $ig_sql = "select pm.meta_value as grp from ".$wpdb->prefix."posts as p join ".$wpdb->prefix."postmeta as pm on p.ID = pm.post_id AND meta_key ='_group' where post_id=".$_POST['ing_val'];
    $ing_group = $wpdb->get_row($ig_sql);
    if(isset($ing_group->grp) && !empty($ing_group->grp)){
      $ing_units = $wpdb->get_results("select t.term_id as iu_id, t.name as iu_name, tm2.meta_value as unit from ".$wpdb->prefix."terms as t join ".$wpdb->prefix."termmeta as tm1 on t.term_id = tm1.term_id join ".$wpdb->prefix."termmeta as tm2 on tm2.term_id = tm1.term_id where tm1.meta_value ='".$ing_group->grp."' and tm2.meta_key='_abbreviation' order by t.name ASC");
      $response = array('error'=>0,'rslt'=>$ing_units);
    }else{
      $response = array('error'=>1,'rslt'=>array());
    }
  }else{
    $response = array('error'=>1,'rslt'=>array());
  }
  echo json_encode($response);
  exit(0);
}

/*
* Method: to get post id by slug.
*/

function get_post_id_by_slug($post_title) {
  global $wpdb;
  $ingre_data = $wpdb->get_results("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_title = '" . trim($post_title) . "' AND post_type = 'ingredients' AND post_status = 'publish'", ARRAY_A);
  if(count($ingre_data) == 1 && $ingre_data[0]['ID'] > 0){
    return $ingre_data[0]['ID'];
  }else{
    $post_slug = sanitize_title($post_title);
    return $wpdb->get_var("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_name = '" . trim($post_slug) . "' AND post_type = 'ingredients' AND post_status = 'publish'");
  }
  //return $wpdb->get_var("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_name = '" . trim($post_slug) . "'");
}

/*
* Method: to get post_id by title.
*/

function get_post_id_by_title($posttitle) {
  global $wpdb;
  $posttitle = sanitize_title($posttitle);
  echo $posttitle;
  return $wpdb->get_var("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_title = '" . trim($posttitle) . "'");
}

/*
* Method: to get term_id by name.
*/

function get_term_id_by_name($termname) {
  global $wpdb;
  return $wpdb->get_var("SELECT term_id FROM ".$wpdb->prefix."termmeta WHERE meta_value = '" . $termname . "' and meta_key='_abbreviation'");
}

/*
* Method: to insert multiple rows by single query.
*/

function common_batch_insert($row_arrays = array(), $wp_table_name) {
  global $wpdb;
  date_default_timezone_set("America/New_York");
  $wp_table_name = esc_sql($wp_table_name);
  // Setup arrays for Actual Values, and Placeholders
  $values = array();
  $place_holders = array();
  $query = "";
  $query_columns = "";
  $query .= "INSERT INTO {$wp_table_name} (";
      if(isset($row_arrays) && !empty($row_arrays)){
            foreach($row_arrays as $count => $row_array){
                foreach($row_array as $key => $value) {
                    if($count == 0) {
                        if($query_columns) {
                        $query_columns .= ",".$key."";
                        } else {
                        $query_columns .= "".$key."";
                        }
                    }
                    $values[] =  $value;
                    if(is_int($value)) {
                        if(isset($place_holders[$count])) {
                        $place_holders[$count] .= ", '%d'";
                        } else {
                        $place_holders[$count] .= "( '%d'";
                        }
                    }else if(is_float($value)) {
                        if(isset($place_holders[$count])) {
                        $place_holders[$count] .= ", '%f'";
                        } else {
                        $place_holders[$count] .= "( '%f'";
                        }
                    }else {
                        if(isset($place_holders[$count])) {
                        $place_holders[$count] .= ", '%s'";
                        } else {
                        $place_holders[$count] .= "( '%s'";
                        }
                    }
                }
                  // mind closing the GAP
                  $place_holders[$count] .= ")";
            }
          }
    $query .= " $query_columns ) VALUES ";
    $query .= implode(', ', $place_holders);
    if($wpdb->query($wpdb->prepare($query, $values))){
        return true;
    }else{
        return false;
    }
}

/*
 * Method: function for check record exist or not
 */

function check_record_exist($table, $where){
  if(!empty($table) && !empty($where)){
    global $wpdb;
    $rslt = $wpdb->get_row("SELECT * FROM $table WHERE $where", ARRAY_A);
    return (!empty($rslt))?$rslt:false;
  }
  return false;
}

/*
* Method: function for add locked meals orders menu in admin section
*/

function amp_url_bugfix($link) {
  return str_replace('#038;', '&', $link);
}
add_filter('paginate_links', 'amp_url_bugfix');

/*
* Method: function for display list of all locked meal orders, filter by selected week and generate spreetsheets
*/

function locked_meals_orders_list() {
  global $wpdb;
  date_default_timezone_set("America/New_York");
  if(isset($_GET['action']) && $_GET['action'] == 1 && isset($_GET['moid']) && !empty($_GET['moid'])){
    view_locked_meal_order(base64_decode($_GET['moid']));
  }else{
    $maxlmt = 999999999;
    $paged = (isset($_GET['paged']) && $_GET['paged'] > 0)?absint($_GET['paged']):1;
    $lmtstr = 0; 
    $lmtend = 500;
    if($paged > 1){
      $lmtstr = ($lmtend*$paged)-$lmtend;
    }
    $filter_dates = get_filter_dates();
    $prms = array();
    $filter_btn_cls = 'dnone';
    $dt_flt = (isset($_POST['filter_by_date']))?$_POST['filter_by_date']:$_GET['fltr-date'];
    if($dt_flt > 0){
      $slcted_wk_order_sunday = date('Y-m-d', $dt_flt);
      $slcted_wk_order_saturday = date('Y-m-d', strtotime('next saturday', strtotime($slcted_wk_order_sunday)));
      $prms['whr'] = array('wk_start_date'=>$slcted_wk_order_sunday,'wk_end_date'=>$slcted_wk_order_saturday);
    }
    if(isset($_POST['filter_by_customer']) && $_POST['filter_by_customer'] > 0){
      $prms['whr']['user_id'] = $_POST['filter_by_customer'];
      $lmtstr = 0; $lmtend = 500;
    }
    if(isset($_POST['filter_by_plan']) && $_POST['filter_by_plan'] > 0){
      $prms['whr']['plan_id'] = $_POST['filter_by_plan'];
    }
    $total_lmo_records = get_locked_meals_orders($filter_dates,$prms);
    if(!empty($total_lmo_records) && count($total_lmo_records) <= 10){
      $lmtstr = 0; $lmtend = 500;
    }
    $totalpages = ceil(count($total_lmo_records)/$lmtend);
    $prms['lmt_start'] = $lmtstr;
    $prms['lmt_end'] = $lmtend;
    $lmo_data = get_locked_meals_orders($filter_dates,$prms);
    $pln_lst = get_membership_group();
    if (isset($lmo_data) && count($lmo_data) > 0 && (isset($_POST['filter_by_date']) && $_POST['filter_by_date'] > 0)){
      $filter_btn_cls = '';
    }
    //generate order sheet
    if(isset($_POST['generation_order_sheet']) && !empty($_POST['generation_order_sheet'])){
      //$prms['all'] = true;
      //$prms['lmt_start'] = $prms['lmt_end'] = '';
      $all_locked_orders = get_locked_meals_orders($filter_dates,$prms);
      $filtered_array = $p_arr =  array();
      foreach ($all_locked_orders as $all_lo_key => $all_lo_val) {
        $meal_status_sql = "SELECT status FROM wp_users_meals WHERE user_id = ".$all_lo_val['usr_id']." AND week_start_date = '".$all_lo_val['week_start_date']."' AND week_end_date = '".$all_lo_val['week_end_date']."' GROUP BY week_start_date, week_end_date";
        $meal_status_arr =  $wpdb->get_results($meal_status_sql, ARRAY_A);
        if(isset($meal_status_arr[0]['status']) && $meal_status_arr[0]['status'] != 4){
          $_locked_orders = get_all_locked_meals(array('user_id'=>$all_lo_val['user_id'],'week_start_date'=>$all_lo_val['week_start_date'],'week_end_date'=>$all_lo_val['week_end_date']));
          $or_ml_data = (isset($all_lo_val['meal_data']) && !empty($all_lo_val['meal_data']))?unserialize($all_lo_val['meal_data']):'';
          if(!empty($or_ml_data) && is_array($or_ml_data)){
            foreach ($or_ml_data as $or_ml_key => $or_ml_val) {
              $om_data[$or_ml_key] = $or_ml_val['meal_name'];
            }
          }
          if(!empty($_locked_orders) && count($_locked_orders) > 0){
            foreach ($_locked_orders as $lokey => $loval) {
              if(strtotime($loval['week_start_date']) == strtotime($all_lo_val['week_start_date']) && strtotime($loval['week_end_date']) == strtotime($all_lo_val['week_end_date'])){
                if(in_array($loval['meal_plan_group'],$p_arr)){
                  $filtered_array[$loval['meal_plan_group']][] = $loval;
                }else{
                  $p_arr[] = $loval['meal_plan_group'];
                  $filtered_array[$loval['meal_plan_group']][] = $loval;
                }
              }
            }
          }
        }
      }
      if(isset($filtered_array) && !empty($filtered_array)){
        $filter_arr = array();
        foreach ($filtered_array as $fa_key => $fa_value) {
          $ua_arr = array();
          foreach ($fa_value as $key => $value) {
            $plan_group_id = isset($value['meal_plan_group']) ? $value['meal_plan_group'] : '';
            if(isset($value['usr_id']) && !empty($value['usr_id'])){
              $user_meta = get_user_meta($value['usr_id']);
              $allergy = (isset($user_meta['allergies'][0]) && !empty($user_meta['allergies'][0]))?unserialize($user_meta['allergies'][0]):'';
              $alr_blok_seq = 1;
              if(!empty($allergy[0])){
                switch($allergy[0]){
                  case 'gluten_free':
                    $alr_blok_seq = 2;
                  break;
                  case 'dairy_free':
                    $alr_blok_seq = 3;
                  break;
                  case 'gluten_dairy_free':
                    $alr_blok_seq = 4;
                  break;
                }
                if(in_array($allergy[0], $ua_arr)){
                  $filter_arr[$plan_group_id][$alr_blok_seq.$allergy[0]][] = $value;
                }else{
                  $ua_arr[] = $allergy[0];
                  $filter_arr[$plan_group_id][$alr_blok_seq.$allergy[0]][] = $value;
                }
              }else{
                //plain
                $filter_arr[$plan_group_id][$alr_blok_seq.'plain'][] = $value;
              }
            }
          }
        }
      }
      // sort array according to asc order of key
      ksort($filter_arr);
      if(!empty($filter_arr) && is_array($filter_arr)){
        $meal_cat_arr = $m_cat_arr = $meal_arr = $user_meals_arr = array();
        $meal_count = 0;
        foreach ($filter_arr as $filter_key => $filter_val) {
          $cat_lst_arr = $ml_lst_arr = array();
          foreach ($filter_val as $new_filter_key => $new_filter_val) {
            foreach ($new_filter_val as $all_lo_key => $all_lo_val) {
              $categories = get_the_terms($all_lo_val['meal_id'], 'menu-items-category');
              if(isset($categories) && !empty($categories)){
                foreach ($categories as $cat_key => $cat_val) {
                  $meal_count++;
                  if(!in_array($cat_val->term_id, $cat_lst_arr)){

                    $m_cat_arr[$filter_key][$cat_val->term_id] = $cat_val->name;
                    $cat_lst_arr[] = $cat_val->term_id;
                  }
                  //if(!in_array($all_lo_val['meal_id'], $ml_lst_arr)){
                  if(isset($om_data[$all_lo_val['meal_id']]) && !empty($om_data[$all_lo_val['meal_id']])){
                    $meal_arr[$filter_key][$cat_val->term_id][$all_lo_val['meal_id']] = $om_data[$all_lo_val['meal_id']];
                  }else{
                    $meal_arr[$filter_key][$cat_val->term_id][$all_lo_val['meal_id']] = $all_lo_val['meal_name'];
                  }
                  $ml_lst_arr[] = $all_lo_val['meal_id'];
                  //}
                  $user_meals_arr[$filter_key][$new_filter_key][$all_lo_val['usr_id']][$cat_val->term_id][$all_lo_val['meal_id']][] = $all_lo_val;
                }
              }
            }
          }
        }
        ksort($m_cat_arr);
        $m_cat_arr = sortArrayByKeyAsc($m_cat_arr);
        $user_meals_arr = sortArrayByKeyAsc($user_meals_arr);
        $user_meals_arr['total_meals'] = $meal_count;
        if(!empty($user_meals_arr) && is_array($user_meals_arr)){
          require_once "third_party/PHPExcel/Classes/PHPExcel.php";
          $excel_obj = new PHPExcel();
          $count = 0;
          $arr_count = count($user_meals_arr);
          foreach ($user_meals_arr as $uma_key => $uma_value) {
            $excel_obj->setActiveSheetIndex($count);
            $sheet_nm = 'Total Orders';
            if(isset($uma_key) && !empty($uma_key) && $uma_key != 'total_meals'){
              $cat_meal_link_arr = array();
              foreach ($m_cat_arr[$uma_key] as $mc_key => $mc_val) {
                $meal_cat_arr[$mc_key] = array(
                  'cat_id' => $mc_key,
                  'cat_name' => $mc_val,
                  'total_meals' => count($meal_arr[$uma_key][$mc_key]),
                  'cat_meals' => $meal_arr[$uma_key][$mc_key]
                );
                $cat_meal_link_arr[$mc_key] = array_keys($meal_arr[$mc_key]);
              }
              $meal_cat_arr = sortArrayByKeyAsc($meal_cat_arr);
              $plan_nm = '';
              if(isset($uma_key) && !empty($uma_key) && $uma_key != 'total_meals'){
                $plan_grp = get_term_by('id', $uma_key, 'membershipgroup');
                $sheet_nm = $plan_nm = (isset($plan_grp->name))?ucwords($plan_grp->name):'';
              }
              if(!empty($sheet_nm)){
                $excel_obj->getActiveSheet()->setTitle($sheet_nm);
                if(($count+1) != $arr_count){
                  $excel_obj->getActiveSheet()->mergeCells('A1:A2');
                  $excel_obj->getActiveSheet()->setCellValue('A1', 'Week of '.date('m/d/y',strtotime($prms['whr']['wk_start_date'])).' - '.date('m/d/y',strtotime($prms['whr']['wk_end_date'])));
                  //$excel_obj->getActiveSheet()->setCellValue('A1', 'Week of '.date('m/d/y',$date_range_arr[0]).' - '.date('m/d/y',$date_range_arr[1]));
                  $excel_obj->getActiveSheet()->getColumnDimension('A')->setWidth(25);
                  $excel_obj->getActiveSheet()->getStyle('A1:A'.$excel_obj->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
                  $excel_obj->getActiveSheet()->getStyle('A1')->applyFromArray(array(
                    'font'  => array(
                      'bold'  => true,
                      'color' => array('rgb' => '000000'),
                      'size'  => 14,
                      'name'  => ''
                    )));
                  $excel_obj->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
                  $excel_obj->getActiveSheet()->getDefaultRowDimension()->setRowHeight(25);
                  $curr_cat_col = 1;
                  $ml_ids = array();
                  foreach($meal_cat_arr as $mkey => $top_col_val){
                    $row = 1;
                    $strt_col = get_excel_col_name_by_index($curr_cat_col).$row;
                    if($top_col_val['total_meals'] > 1){
                      $end_col = get_excel_col_name_by_index($curr_cat_col + ($top_col_val['total_meals'] - 1)).$row;
                      $excel_obj->getActiveSheet()->mergeCells($strt_col.':'.$end_col);
                    }
                    $excel_obj->getActiveSheet()->setCellValueByColumnAndRow($curr_cat_col, 1, $top_col_val['cat_name']);
                    $excel_obj->getActiveSheet()->getStyleByColumnAndRow($curr_cat_col, 1)->applyFromArray(array(
                        'font'  => array(
                          'bold'  => true,
                          'size'  => 14
                        )
                      )
                    );
                    $curr_meal_col = $curr_cat_col;
                    foreach ($top_col_val['cat_meals'] as $ml_id => $mc_val) {
                      if(!in_array($ml_id, $ml_ids[$mkey])){
                        $ml_ids[$mkey][] = $ml_id;
                      }
                      $excel_obj->getActiveSheet()->setCellValueByColumnAndRow($curr_meal_col, 2, $mc_val);
                      $excel_obj->getActiveSheet()->getStyleByColumnAndRow($curr_meal_col, 2)->getFont()->setBold(true);
                      $start_col = get_excel_col_name_by_index($curr_meal_col);
                      $excel_obj->getActiveSheet()->getStyle($start_col.'2:'.$start_col.$excel_obj->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
                      $excel_obj->getActiveSheet()->getRowDimension($curr_meal_col)->setRowHeight(30);
                      $curr_meal_col++;
                    }
                    $curr_cat_col = $curr_cat_col + $top_col_val['total_meals'];
                  }
                  $ti_col_nm = get_excel_col_name_by_index($curr_cat_col);
                  $excel_obj->getActiveSheet()->mergeCells($ti_col_nm.'1:'.$ti_col_nm.'2');
                  $excel_obj->getActiveSheet()->setCellValue($ti_col_nm.'1', 'TOTAL ITEMS');
                  $excel_obj->getActiveSheet()->getColumnDimension($ti_col_nm.'1')->setWidth(10);
                  $excel_obj->getActiveSheet()->getStyle($ti_col_nm.'1')->getAlignment()->setWrapText(true);
                  $excel_obj->getActiveSheet()->getStyle($ti_col_nm.'1')->applyFromArray(array(
                    'font'  => array(
                      'bold'  => true,
                      'color' => array('rgb' => 'FF0000'),
                      'size'  => 14,
                      'name'  => ''
                    )));
                  $col_nm = get_excel_col_name_by_index($curr_cat_col);
                  $user_meals_arr = sortArrayByKeyAsc($user_meals_arr);
                }
              }
              $row_cnt = 3;
              $ii = 1;
              $tmp_uma = array();
              foreach ($uma_value as $alr_key => $alr_val) {
                foreach ($alr_val as $cust_id => $cust_mc) {
                  foreach ($cust_mc as $cat_id => $cust_mc_mls) {
                    foreach ($cust_mc_mls as $ml_id => $ml_val) {
                      $tmp_uma[$alr_key][$cust_id][$cat_id][$ml_id] = count($ml_val);
                    }
                    $diff_cat_mls = array_diff($ml_ids[$cat_id], array_keys($tmp_uma[$alr_key][$cust_id][$cat_id]));
                    if(!empty($diff_cat_mls)){
                      foreach ($diff_cat_mls as $dcm_key => $dcm_val) {
                        $tmp_uma[$alr_key][$cust_id][$cat_id][$dcm_val] = '';
                      }
                    }
                  }
                  $diff_cats = array_diff(array_keys($ml_ids), array_keys($cust_mc));
                  if(!empty($diff_cats)){
                    foreach ($diff_cats as $dc_key => $dc_val) {
                      $tmp_uma[$alr_key][$cust_id][$dc_val] = '';
                    }
                  }
                }
              }
              if(!empty($tmp_uma) && count($tmp_uma) > 0){
                foreach ($tmp_uma as $alr_key => $alr_val) {
                  $alr = '';
                  switch($alr_key){
                    case '1plain':
                      $alr = $plan_nm.' Plan';
                    break;
                    case '2gluten_free':
                      $alr = $plan_nm.': Gluten Free';
                    break;
                    case '3dairy_free':
                      $alr = $plan_nm.': Dairy Free';
                    break;
                    case '4gluten_dairy_free':
                      $alr = $plan_nm.': Gluten/Dairy Free';
                    break;
                  }
                  $excel_obj->getActiveSheet()->setCellValue('A'.$row_cnt, $alr);
                  $excel_obj->getActiveSheet()->mergeCells('A'.$row_cnt.':'.$col_nm.$row_cnt);
                  $excel_obj->getActiveSheet()->getStyle('A'.$row_cnt)->applyFromArray(array(
                      'font'  => array(
                        'bold'  => true,
                        'color' => array('rgb' => 'BF9000'),
                        'size'  => 12,
                        'name'  => ''
                      )));
                  $excel_obj->getActiveSheet()->getStyle('A'.$row_cnt)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFEEC');
                  $excel_obj->getActiveSheet()->getStyle('A'.$row_cnt.':'.'A'.$row_cnt)->applyFromArray(array('borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM))));
                  $cal_rw = $row_cnt;
                  $row_cnt++;
                  foreach ($alr_val as $av_key => $av_val) {
                    $um_user_meta = get_user_meta($av_key);
                    $um_f_name = (isset($um_user_meta['first_name'][0]) && !empty($um_user_meta['first_name'][0]))?$um_user_meta['first_name'][0]:'';
                    $um_l_name = (isset($um_user_meta['last_name'][0]) && !empty($um_user_meta['last_name'][0]))?$um_user_meta['last_name'][0]:'';
                    $excel_obj->getActiveSheet()->setCellValue('A'.$row_cnt, ucfirst($um_f_name.' '.$um_l_name));
                    $excel_obj->getActiveSheet()->getStyle('A'.$row_cnt)->getFont()->setBold(true);
                    $excel_obj->getActiveSheet()->getStyle('A'.$row_cnt.':'.'A'.$row_cnt)->applyFromArray(array('borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
                    $col_tot = 1;
                    $_tot_meals = 0;
                    $av_val = sortArrayByKeyAsc($av_val);
                    foreach ($av_val as $cat_key => $cat_val) {
                      $cat_tot_mls = $meal_cat_arr[$cat_key]['total_meals'];
                      $ml_cnt = 0;
                      $cat_val = sortArrayByKeyAsc($cat_val);
                      foreach ($cat_val as $m_key => $m_val) {
                        $xl_col_name = get_excel_col_name_by_index($col_tot);
                        $excel_obj->getActiveSheet()->setCellValue($xl_col_name.$row_cnt, $m_val);
                        $excel_obj->getActiveSheet()->getStyle($xl_col_name.$row_cnt.':'.$xl_col_name.$row_cnt)->applyFromArray(array('borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
                        $_tot_meals = $_tot_meals + $m_val;
                        $col_tot++;
                        $ml_cnt++;
                      }
                      $nxt_cat_col_idx = $cat_tot_mls - $ml_cnt;
                      $col_tot = $col_tot + $nxt_cat_col_idx;
                    }
                    $excel_obj->getActiveSheet()->setCellValue($ti_col_nm.$row_cnt, $_tot_meals);
                    $excel_obj->getActiveSheet()->getStyle($ti_col_nm.$row_cnt.':'.$ti_col_nm.$row_cnt)->applyFromArray(array('borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
                    $excel_obj->getActiveSheet()->getRowDimension($row_cnt)->setRowHeight(25);
                    if($row_cnt%2 == 0){
                      $excel_obj->getActiveSheet()->getStyle('A'.$row_cnt.':'.$col_nm.$row_cnt)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('EFEFEF');
                    }
                    $row_cnt++;
                  }
                  //display Total row
                  $r_cnt = $row_cnt;                  
                  $excel_obj->getActiveSheet()->setCellValue('A'.$r_cnt,'TOTAL:');
                  $excel_obj->getActiveSheet()->getStyle('A'.$r_cnt)->getFont()->setBold(true);
                  $excel_obj->getActiveSheet()->getStyle('A'.$r_cnt.':'.'A'.$r_cnt)->applyFromArray(array('borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
                  $excel_obj->getActiveSheet()->getStyle('A'.$r_cnt.':'.$col_nm.$r_cnt)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CCCCCC');
                  $highestColIdx = get_excel_col_index_by_name($col_nm);
                  $ij = ($ii > 1)?($cal_rw+1):4;
                  for($i=1; $i <= $highestColIdx; $i++) {
                    $col_name = get_excel_col_name_by_index($i);
                    $excel_obj->getActiveSheet()->setCellValue($col_name.$r_cnt,  '=SUM('.$col_name.$ij.':'.$col_name.($r_cnt - 1).')');
                    $excel_obj->getActiveSheet()->getStyle($col_name.$r_cnt)->getFont()->setBold(true);
                    $excel_obj->getActiveSheet()->getStyle($col_name.$r_cnt.':'.$col_name.$r_cnt)->applyFromArray(array('borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
                  }
                  $r_cnt++;
                  //display black row
                  if($ii < count($uma_value)){
                    $excel_obj->getActiveSheet()->getStyle('A'.$r_cnt.':'.$col_nm.$r_cnt)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('000000');
                    $excel_obj->getActiveSheet()->getRowDimension($r_cnt)->setRowHeight(15);
                  }
                  $row_cnt = $r_cnt+1;
                  $ii++;
                }
              }
            }else{
              $excel_obj->getActiveSheet()->setTitle($sheet_nm);
              $excel_obj->getActiveSheet()->getColumnDimension('A')->setWidth(40);
              $excel_obj->getActiveSheet()->getColumnDimension('A')->setWidth(40);
              $excel_obj->getActiveSheet()->getStyle('A2')->applyFromArray(array(
                'font'  => array(
                  'bold'  => true,
                  'color' => array('rgb' => '000000'),
                  'size'  => 14,
                  'name'  => ''
                )));
              $excel_obj->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
              $excel_obj->getActiveSheet()->setCellValue('A2', 'TOTAL AMOUNT OF ORDERS');
              $excel_obj->getActiveSheet()->setCellValue('B2',$uma_value);
              $excel_obj->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
              $excel_obj->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
              $excel_obj->getActiveSheet()->getStyle('A1:B3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('EFEFEF');
              $excel_obj->getActiveSheet()->getStyle('A1:A3')->applyFromArray(array('borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),'bottom' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM))));
              $excel_obj->getActiveSheet()->getStyle('B1:B3')->applyFromArray(array('borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),'bottom' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM))));
            }
            if(($count+1) != $arr_count){
              $excel_obj->createSheet();
            }
            $count++;
          }
          $filename = 'users-orders-sheet'.time().'.xls';
          header('Content-Type: application/vnd.ms-excel');
          header('Content-Disposition: attachment;filename="'.$filename.'"');
          header('Cache-Control: max-age=0');
          $objWriter = PHPExcel_IOFactory::createWriter($excel_obj, 'Excel5');  
          ob_clean(); ob_start();
          //force user to download the Excel file without writing it to server's HD
          $objWriter->save('php://output');
        }
      }
    }
    //generate grocery sheet
    if(isset($_POST['generate_grocery_sheet']) && !empty($_POST['generate_grocery_sheet'])){
      $prms['all'] = true;
      $prms['lmt_start'] = $prms['lmt_end'] = '';
      $all_locked_orders = get_locked_meals_orders($filter_dates,$prms);
      if(isset($all_locked_orders) && !empty($all_locked_orders)){
        $all_meals_arr = $all_meals_ids = $all_meal_ingredients = array();
        foreach ($all_locked_orders as $lo_key => $lo_val) {
          $gml_status_sql = "SELECT status FROM wp_users_meals WHERE user_id = ".$lo_val['usr_id']." AND week_start_date = '".$lo_val['week_start_date']."' AND week_end_date = '".$lo_val['week_end_date']."' GROUP BY week_start_date, week_end_date";
          $gml_status_arr =  $wpdb->get_results($gml_status_sql, ARRAY_A);
          if(isset($gml_status_arr[0]['status']) && $gml_status_arr[0]['status'] != 4){
            $grocery_locked_orders = get_all_locked_meals(array('user_id'=>$lo_val['user_id'],'week_start_date'=>$lo_val['week_start_date'],'week_end_date'=>$lo_val['week_end_date']));
            if(!empty($grocery_locked_orders) && count($grocery_locked_orders) > 0){
              foreach ($grocery_locked_orders as $glmkey => $glmval) {
                if(strtotime($glmval['week_start_date']) == strtotime($lo_val['week_start_date']) && strtotime($glmval['week_end_date']) == strtotime($lo_val['week_end_date'])){
                  if(in_array($glmval['meal_id'], $all_meals_ids)){
                    $all_meals_arr[$glmval['meal_id']]['total_meals'] = $all_meals_arr[$glmval['meal_id']]['total_meals'] + 1;
                  }else{
                    $all_meals_arr[$glmval['meal_id']] = array('meal_name'=>$glmval['meal_name'],'total_meals'=>1);
                    $all_meals_ids[] = $glmval['meal_id'];
                  }
                }
              }
            }
          }
        }
        if(isset($all_meals_arr) && !empty($all_meals_arr)){
          $meal_ingredients = array();
          $meal_ids = array_keys($all_meals_arr);
          $meal_ingredients = get_meal_ingredients($meal_ids);
          sortArrayByValueAsc($meal_ingredients,"ingredient_name");
          if(!empty($meal_ingredients) && count($meal_ingredients) > 0){
            foreach ($meal_ingredients as $mi_key => $mi_val) {
              $all_meal_ingredients[$mi_val['ingredient_cat']][$mi_val['ingredient_id']][] = $mi_val;
            }
          }
        }
        
        //generate excel sheet
        require_once "third_party/PHPExcel/Classes/PHPExcel.php";
        $excel_obj = new PHPExcel();
        $excel_obj->setActiveSheetIndex(0);
        //name the worksheet
        $excel_obj->getActiveSheet()->setTitle('Grocery List');
        $excel_obj->getActiveSheet()->setCellValue('A1', 'ITEM');
        $excel_obj->getActiveSheet()->setCellValue('B1', 'QTY');
        $excel_obj->getActiveSheet()->setCellValue('C1', 'UNIT');
        $excel_obj->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
        $excel_obj->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $excel_obj->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $excel_obj->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        //merge cell A2 until C2
        $excel_obj->getActiveSheet()->mergeCells('A2:C2');
        /*if(isset($date_range_arr[0]) && isset($date_range_arr[1])){
          $excel_obj->getActiveSheet()->setCellValue('A2', date('m/d/y',$date_range_arr[0]) .' - '.date('m/d/y',$date_range_arr[1]));
        }*/
        $excel_obj->getActiveSheet()->setCellValue('A1', 'Week of '.date('m/d/y',strtotime($prms['whr']['wk_start_date'])).' - '.date('m/d/y',strtotime($prms['whr']['wk_end_date'])));
        $excel_obj->getActiveSheet()->getRowDimension(2)->setRowHeight(25);
        $styleArray = array(
          'font'  => array(
            'bold'  => false,
            'color' => array('rgb' => '6B6D6C'),
            'size'  => 12,
            'name'  => ''
          ));
        $excel_obj->getActiveSheet()->getDefaultStyle()->applyFromArray($styleArray);
        $exceldata = array();
        //calculate ingredient's count
        if(!empty($all_meal_ingredients) && count($all_meal_ingredients) > 0){
          $cat_row_cnt = 3;
          foreach ($all_meal_ingredients as $ming_cat => $ming_val) {
            $ing_cat_term = get_term_by('id', $ming_cat, 'ingredients_categories');
            $ing_cat_name = (isset($ing_cat_term->name))?$ing_cat_term->name:'';
            $excel_obj->getActiveSheet()->mergeCells('A'.$cat_row_cnt.':C'.$cat_row_cnt);
            $excel_obj->getActiveSheet()->getStyle('A'.$cat_row_cnt)->getFont()->setBold(true);
            $exceldata[] = array(html_entity_decode($ing_cat_name));
            $ing_row_cnt = $cat_row_cnt + 1;
            foreach ($ming_val as $ing_key => $ing_val) {
              $convert_to = ingredient_group_units_convert_to($ing_val[0]['ingredient_grp']);
              $converted_unit_value = ingredient_units_us_measurements(strtolower($ing_val[0]['unit_abbreviation'].'-'.$convert_to));            
              $converted_item_qty = $ct_item_total_qty = $other_item_total_qty = 0;
              $ing_arr = array();
              foreach ($ing_val as $item_key => $item_val) {
                $item_qty = 0;
                $item_qty = $item_val['quantity'] + $item_val['fraction_qty'];
                if(isset($item_val['unit_abbreviation']) && strtolower($item_val['unit_abbreviation']) == 'ct'){
                  if(isset($all_meals_arr[$item_val['meal_id']]['total_meals']) && $all_meals_arr[$item_val['meal_id']]['total_meals'] > 0){
                    $ct_item_total_qty = $ct_item_total_qty + ($item_qty * $all_meals_arr[$item_val['meal_id']]['total_meals']);
                  }else{
                    $ct_item_total_qty = $ct_item_total_qty + $item_qty;
                  }
                  $ing_arr['ct_ing'] = $ct_item_total_qty;
                }else{
                  if(isset($all_meals_arr[$item_val['meal_id']]['total_meals']) && $all_meals_arr[$item_val['meal_id']]['total_meals'] > 0){
                    $other_item_total_qty = $other_item_total_qty + ($item_qty * $all_meals_arr[$item_val['meal_id']]['total_meals']);
                  }else{
                    $other_item_total_qty = $other_item_total_qty + $item_qty;
                  }
                  $ing_arr['other_ing'] = $other_item_total_qty;
                }
              }
              $excel_obj->getActiveSheet()->getStyle('A'.$ing_row_cnt.':C'.$ing_row_cnt)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
              if(isset($ing_arr['ct_ing']) && !empty($ing_arr['ct_ing'])){
                $converted_item_qty = round($ing_arr['ct_ing'],4);
                if(!empty($converted_item_qty)){
                  $fractional_arr = explode('.', $converted_item_qty);
                  if(isset($fractional_arr[1]) && !empty($fractional_arr[1])){
                    $item_fractional = get_fractional_part('0.'.$fractional_arr[1]);
                    if(!empty($item_fractional)){
                      if($fractional_arr[0] > 0){
                        $converted_item_qty = $fractional_arr[0].' '.$item_fractional;
                      }else{
                        $converted_item_qty = $item_fractional;
                      }
                    }
                  }
                }
                $exceldata[] = array(html_entity_decode($ing_val[0]['ingredient_name']), $converted_item_qty, 'ct');
                $excel_obj->getActiveSheet()->getRowDimension($ing_row_cnt)->setRowHeight(25);
                $ing_row_cnt++;
              }

              if(isset($ing_arr['other_ing']) && !empty($ing_arr['other_ing'])){
                $converted_item_qty = round(($ing_arr['other_ing']*$converted_unit_value),4);
                if(!empty($converted_item_qty)){
                  $fractional_arr = explode('.', $converted_item_qty);
                  if(isset($fractional_arr[1]) && !empty($fractional_arr[1])){
                    $item_fractional = get_fractional_part('0.'.$fractional_arr[1]);
                    if(!empty($item_fractional)){
                      if($fractional_arr[0] > 0){
                        $converted_item_qty = $fractional_arr[0].' '.$item_fractional;
                      }else{
                        $converted_item_qty = $item_fractional;
                      }
                    }
                  }
                }
                $exceldata[] = array(html_entity_decode($ing_val[0]['ingredient_name']), $converted_item_qty, $convert_to);
                $excel_obj->getActiveSheet()->getRowDimension($ing_row_cnt)->setRowHeight(25);
                $ing_row_cnt++;
              }
            }
            $excel_obj->getActiveSheet()->getStyle('A'.$cat_row_cnt.':C'.$cat_row_cnt)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('EFEFEF');
            $excel_obj->getActiveSheet()->getStyle('A'.$cat_row_cnt.':C'.$cat_row_cnt)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $excel_obj->getActiveSheet()->getRowDimension($cat_row_cnt)->setRowHeight(25);
            $cat_row_cnt = $ing_row_cnt;
          }
        }
        
        $excel_obj->getActiveSheet()->fromArray($exceldata, null, 'A3');
        $excel_obj->getActiveSheet()->getStyle('A2:C2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');
        $excel_obj->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $excel_obj->getActiveSheet()->getStyle('A2:C2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $excel_obj->getActiveSheet()->getStyle('B1:B'.$excel_obj->getActiveSheet()->getHighestRow())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $dr_filename = 'grocery-list'.time().'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$dr_filename.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($excel_obj, 'Excel5');  
        ob_clean(); ob_start();
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
      }
    }
    //generate delivery route sheet
    if(isset($_POST['generate_delivery_route']) && !empty($_POST['generate_delivery_route'])){
      require_once "third_party/PHPExcel/Classes/PHPExcel.php";
      $excel_obj = new PHPExcel();
      $excel_obj->setActiveSheetIndex(0);
      //name the worksheet
      $excel_obj->getActiveSheet()->setTitle('Delivery Route');
      $exceldata = array();
      $row_cnt = 1;
      foreach ($total_lmo_records as $gdrs_key => $gdrs_val){
        $gdrs_status_sql = "SELECT status FROM wp_users_meals WHERE user_id = ".$gdrs_val['usr_id']." AND week_start_date = '".$gdrs_val['week_start_date']."' AND week_end_date = '".$gdrs_val['week_end_date']."' GROUP BY week_start_date, week_end_date";
        $gdrs_status_arr =  $wpdb->get_results($gdrs_status_sql, ARRAY_A);
        if(isset($gdrs_status_arr[0]['status']) && $gdrs_status_arr[0]['status'] != 4){
          $usr_id = (isset($gdrs_val['usr_id']) && !empty($gdrs_val['usr_id']))?$gdrs_val['usr_id']:'';
          //$user_meta = get_user_meta($usr_id);
          $user_meta = (isset($gdrs_val['user_data']) && !empty($gdrs_val['user_data']))?unserialize($gdrs_val['user_data']):array();
          $first_name = (isset($user_meta['first_name']) && !empty($user_meta['first_name']))?$user_meta['first_name']:'';
          $last_name = (isset($user_meta['last_name']) && !empty($user_meta['last_name']))?$user_meta['last_name']:'';
          $phone = (isset($user_meta['pmpro_bphone']) && !empty($user_meta['pmpro_bphone']))?$user_meta['pmpro_bphone']:'';
          $email = (isset($user_meta['pmpro_bemail']) && !empty($user_meta['pmpro_bemail']))?$user_meta['pmpro_bemail']:'';
          $_address = '';
          if(isset($user_meta['pmpro_baddress1']) && !empty($user_meta['pmpro_baddress1'])){
            $_address .= $user_meta['pmpro_baddress1'];
          }
          if(isset($user_meta['pmpro_baddress2']) && !empty($user_meta['pmpro_baddress2'])){
            $_address .= ', '.$user_meta['pmpro_baddress2'];
          }
          if(isset($user_meta['pmpro_bcity']) && !empty($user_meta['pmpro_bcity'])){
            $_address .= ', '.$user_meta['pmpro_bcity'];
          }
          if(isset($user_meta['pmpro_bstate']) && !empty($user_meta['pmpro_bstate'])){
            $_address .= ', '.$user_meta['pmpro_bstate'];
          }
          if(isset($user_meta['pmpro_bzipcode']) && !empty($user_meta['pmpro_bzipcode'])){
            $_address .= ', '.$user_meta['pmpro_bzipcode'];
          }
          $delivery_notes = (isset($user_meta['pmpro_special_delivery_instructions']) && !empty($user_meta['pmpro_special_delivery_instructions']))?$user_meta['pmpro_special_delivery_instructions']:'';
          $exceldata[] = array('Delivery',$first_name.' '.$last_name,$phone,$email,$delivery_notes,'',$_address);
          if($row_cnt%2 == 0){
            $excel_obj->getActiveSheet()->getStyle('A'.$row_cnt.':G'.$row_cnt)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('EFEFEF');
          }
          $excel_obj->getActiveSheet()->getStyle('A'.$row_cnt.':G'.$row_cnt)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $row_cnt++;
        }
      }
      $excel_obj->getActiveSheet()->fromArray($exceldata, null, 'A1');
      $excel_obj->getActiveSheet()->getDefaultColumnDimension()->setWidth(30);
      $excel_obj->getActiveSheet()->getDefaultRowDimension()->setRowHeight(25);
      $dr_filename = 'users-delivery-route'.time().'.xls';
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$dr_filename.'"');
      header('Cache-Control: max-age=0');
      $objWriter = PHPExcel_IOFactory::createWriter($excel_obj, 'Excel5');  
      ob_clean(); ob_start();
      $objWriter->save('php://output');
    }
    //generate label sheet 
    if(isset($_POST['generate_label_sheet']) && !empty($_POST['generate_label_sheet'])){
      global $wpdb;
      require_once "third_party/PHPExcel/Classes/PHPExcel.php";
      $excel_obj = new PHPExcel();
      $excel_obj->setActiveSheetIndex(0);
      //name the worksheet
      $excel_obj->getActiveSheet()->setTitle('Label Sheet');
      $excel_obj->getActiveSheet()->setCellValue('A2', 'Meal Name');
      $excel_obj->getActiveSheet()->setCellValue('B2', 'Calories');
      $excel_obj->getActiveSheet()->setCellValue('C2', 'Carbs');
      $excel_obj->getActiveSheet()->setCellValue('D2', 'Fat');
      $excel_obj->getActiveSheet()->setCellValue('E2', 'Protein');
      $excel_obj->getActiveSheet()->setCellValue('F2', 'Category');
      $excel_obj->getActiveSheet()->setCellValue('G2', 'Plan');
      $excel_obj->getActiveSheet()->setCellValue('H2', 'Allergy');
      $excel_obj->getActiveSheet()->setCellValue('I2', 'Ingredients');
      $excel_obj->getActiveSheet()->setCellValue('J2', 'Directions');
      $excel_obj->getActiveSheet()->mergeCells('A1:J1');
      //$excel_obj->getActiveSheet()->setCellValue('A1', 'Week of '.date('m/d/y',$date_range_arr[0]) .' - '.date('m/d/y',$date_range_arr[1]));
      $excel_obj->getActiveSheet()->setCellValue('A1', 'Week of '.date('m/d/y',strtotime($prms['whr']['wk_start_date'])).' - '.date('m/d/y',strtotime($prms['whr']['wk_end_date'])));
      $excel_obj->getActiveSheet()->getColumnDimension('A')->setWidth(40);
      $excel_obj->getActiveSheet()->getColumnDimension('B')->setWidth(10);
      $excel_obj->getActiveSheet()->getColumnDimension('C')->setWidth(10);
      $excel_obj->getActiveSheet()->getColumnDimension('D')->setWidth(10);
      $excel_obj->getActiveSheet()->getColumnDimension('E')->setWidth(10);
      $excel_obj->getActiveSheet()->getColumnDimension('G')->setWidth(20);
      $excel_obj->getActiveSheet()->getColumnDimension('H')->setWidth(20);
      $excel_obj->getActiveSheet()->getColumnDimension('I')->setWidth(20);
      $excel_obj->getActiveSheet()->getColumnDimension('J')->setWidth(20);
      $excel_obj->getActiveSheet()->getStyle('A1:A'.$excel_obj->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
      $excel_obj->getActiveSheet()->getStyle('A1')->applyFromArray(array(
        'font'  => array(
          'bold'  => true,
          'color' => array('rgb' => 'FF0000'),
          'size'  => 12,
          'name'  => ''
        )));
      foreach ($total_lmo_records as $lbl_key => $lbl_val){
        $lbl_status_sql = "SELECT status FROM wp_users_meals WHERE user_id = ".$lbl_val['usr_id']." AND week_start_date = '".$lbl_val['week_start_date']."' AND week_end_date = '".$lbl_val['week_end_date']."' GROUP BY week_start_date, week_end_date";
        $lbl_status_arr =  $wpdb->get_results($lbl_status_sql, ARRAY_A);
        if(isset($lbl_status_arr[0]['status']) && $lbl_status_arr[0]['status'] != 4){
          $ou_data = (isset($lbl_val['user_data']) && !empty($lbl_val['user_data']))?unserialize($lbl_val['user_data']):'';
          //$om_data = (isset($lbl_val['meal_data']) && !empty($lbl_val['meal_data']))?unserialize($lbl_val['meal_data']):'';
          if(isset($lbl_val['usr_id']) && !empty($lbl_val['usr_id'])){
            //$user_meta = get_user_meta($lbl_val['usr_id']);
            //$allergies[$lbl_val['usr_id']] = (isset($user_meta['allergies'][0]) && !empty($user_meta['allergies'][0]))?unserialize($user_meta['allergies'][0]):'';
            $om_data[$lbl_val['usr_id']] = (isset($lbl_val['meal_data']) && !empty($lbl_val['meal_data']))?unserialize($lbl_val['meal_data']):'';
            $allergies[$lbl_val['usr_id']] = (isset($ou_data['allergies']) && !empty($ou_data['allergies']))?unserialize($ou_data['allergies']):'';
          }
          $usr_id[] = (isset($lbl_val['usr_id']) && !empty($lbl_val['usr_id']))?$lbl_val['usr_id']:'';
          $week_start_date = (isset($lbl_val['week_start_date']) && !empty($lbl_val['week_start_date'])) ? $lbl_val['week_start_date'] : '';
          $week_end_date = (isset($lbl_val['week_end_date']) && !empty($lbl_val['week_end_date'])) ? $lbl_val['week_end_date'] : '';
        }
      }

      $um_table = $wpdb->prefix.'users_meals';
      $post_table = $wpdb->prefix.'posts';
      if(!empty($usr_id) && is_array($usr_id)){
        $usr_id = implode(",",$usr_id);
      }
      $lmo_sql = "SELECT um.user_id,um.meal_id,meal.post_title as meal_name FROM $um_table as um JOIN $post_table as meal on meal.ID = um.meal_id WHERE um.user_id IN(".$usr_id.") AND um.week_start_date = '".$week_start_date."' AND um.week_end_date = '".$week_end_date."' order by meal.post_title ASC";
      $filtered_meals =  $wpdb->get_results($lmo_sql, ARRAY_A);
      if(!empty($filtered_meals) && is_array($filtered_meals)){
        $final_cat_sorted_arr = array();
        $final_cat_sorted_arr = filter_locked_meal_arr($filtered_meals);
        foreach ($final_cat_sorted_arr as $fm_key => $fm_value) {
          $meal_id = (isset($fm_value['meal_id']) && !empty($fm_value['meal_id'])) ? $fm_value['meal_id'] : '';
          $user_id = (isset($fm_value['user_id']) && !empty($fm_value['user_id'])) ? $fm_value['user_id'] : 0;
          $o_m_data = (isset($om_data[$user_id][$meal_id]) && !empty($om_data[$user_id][$meal_id])) ? $om_data[$user_id][$meal_id] : array();
          $o_meal_name = (isset($o_m_data['meal_name']) && !empty($o_m_data['meal_name'])) ? $o_m_data['meal_name'] : '';
          $meal_name = (isset($fm_value['meal_name']) && !empty($fm_value['meal_name'])) ? $fm_value['meal_name'] : '';
          $meal_name = (!empty($o_meal_name)) ? $o_meal_name : $meal_name;
          $o_m_cat = (isset($o_m_data['meal_cat'][0]) && !empty($o_m_data['meal_cat'][0])) ? $o_m_data['meal_cat'][0]->name : '';
          $category = '';
          $category_arr = get_the_terms($meal_id,'menu-items-category');
          if(!empty($category_arr) && is_array($category_arr)){
            $count = 1;
            $cat_count = count($category_arr);
            foreach ($category_arr as $key => $value) {
              $category .= isset($value->name) ? $value->name : '';
              if($cat_count != $count){
                $category .= ', ';
              }
              $count++;
            }
          }
          $category = (!empty($o_m_cat)) ? $o_m_cat : $category;
          $meta_data = get_post_meta($meal_id);
          $o_m_calories = (isset($o_m_data['calories']) && !empty($o_m_data['calories'])) ? $o_m_data['calories'] : 0;
          $calories = (isset($meta_data['_calories'][0]) && !empty($meta_data['_calories'][0])) ? $meta_data['_calories'][0] : '';
          $calories = (!empty($o_m_calories)) ? $o_m_calories : $calories;
          $o_m_carbs = (isset($o_m_data['carbs']) && !empty($o_m_data['carbs'])) ? $o_m_data['carbs'] : 0;
          $carbs = (isset($meta_data['_carbs'][0]) && !empty($meta_data['_carbs'][0])) ? $meta_data['_carbs'][0] : '';
          $carbs = (!empty($o_m_carbs)) ? $o_m_carbs : $carbs;
          $o_m_fat = (isset($o_m_data['fat']) && !empty($o_m_data['fat'])) ? $o_m_data['fat'] : 0;
          $fat = (isset($meta_data['_fat'][0]) && !empty($meta_data['_fat'][0])) ? $meta_data['_fat'][0] : '';
          $fat = (!empty($o_m_fat)) ? $o_m_fat : $fat;
          $o_m_protein = (isset($o_m_data['protein']) && !empty($o_m_data['protein'])) ? $o_m_data['protein'] : 0;
          $protein = (isset($meta_data['_protein'][0]) && !empty($meta_data['_protein'][0])) ? $meta_data['_protein'][0] : '';
          $protein = (!empty($o_m_protein)) ? $o_m_protein : $protein;
          $o_m_plan_id = (isset($o_m_data['plan']) && !empty($o_m_data['plan'])) ? $o_m_data['plan'] : 0;
          $plan_id = (isset($meta_data['_plan'][0]) && !empty($meta_data['_plan'][0])) ? $meta_data['_plan'][0] : '';
          $plan_id = (!empty($o_m_plan_id)) ? $o_m_plan_id : $plan_id;
          $plan_grp = get_term_by('id', $plan_id, 'membershipgroup');
          $plan = (isset($plan_grp->name))?$plan_grp->name:'';
          $_allergies = (isset($allergies[$user_id][0]) && !empty($allergies[$user_id][0])) ? $allergies[$user_id][0] : '';
          $_allergies = ucfirst(str_replace('_', ' ', $_allergies));

          $ingredients = '';
          $ingredients_arr = get_meal_ingredients($meal_id);
          if(!empty($ingredients_arr) && is_array($ingredients_arr)){
            $count = 1;
            $ing_count = count($ingredients_arr);
            foreach ($ingredients_arr as $key => $value) {
              $ingredients .= isset($value['ingredient_name']) ? $value['ingredient_name'] : '';
              if($ing_count != $count){
                $ingredients .= ', ';
              }
              $count++;
            }
          }
          $directions = (isset($meta_data['_directions'][0]) && !empty($meta_data['_directions'][0])) ? $meta_data['_directions'][0] : '';
          $exceldata[] = array($meal_name, $calories, $carbs, $fat, $protein, $category, $plan, $_allergies,$ingredients,$directions);
          $excel_obj->getActiveSheet()->getStyle('A2:J2')->getFont()->setBold( true );
          $row_cnt++;
        }
      }
      
      $excel_obj->getActiveSheet()->fromArray($exceldata, null, 'A3');
      $excel_obj->getActiveSheet()->getDefaultColumnDimension()->setWidth(30);
      $excel_obj->getActiveSheet()->getDefaultRowDimension()->setRowHeight(25);
      $dr_filename = 'label-sheet'.time().'.xls';
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$dr_filename.'"');
      header('Cache-Control: max-age=0');
      $objWriter = PHPExcel_IOFactory::createWriter($excel_obj, 'Excel5');  
      ob_clean(); ob_start();
      //force user to download the Excel file without writing it to server's HD
      $objWriter->save('php://output');
    }
    //generate pickup location sheet 
    if(isset($_POST['generate_pickup_location_sheet']) && !empty($_POST['generate_pickup_location_sheet'])){
      global $wpdb;
      require_once "third_party/PHPExcel/Classes/PHPExcel.php";
      $excel_obj = new PHPExcel();
      $is_pl_fnd = 0;
      foreach ($total_lmo_records as $pl_key => $pl_val){
        if(isset($pl_val['pickup_location']) && $pl_val['pickup_location'] > 0){
          $is_pl_fnd = 1;
          $pl_status_sql = "SELECT status FROM wp_users_meals WHERE user_id = ".$pl_val['usr_id']." AND week_start_date = '".$pl_val['week_start_date']."' AND week_end_date = '".$pl_val['week_end_date']."' GROUP BY week_start_date, week_end_date";
          $pl_status_arr =  $wpdb->get_results($pl_status_sql, ARRAY_A);
          if(isset($pl_status_arr[0]['status']) && $pl_status_arr[0]['status'] != 4){
            $ou_data = (isset($pl_val['user_data']) && !empty($pl_val['user_data']))?unserialize($pl_val['user_data']):'';
            $first_name = (isset($ou_data['first_name']) && !empty($ou_data['first_name']))?$ou_data['first_name']:'';
            $last_name = (isset($ou_data['last_name']) && !empty($ou_data['last_name']))?$ou_data['last_name']:'';
            $phone = (isset($ou_data['pmpro_bphone']) && !empty($ou_data['pmpro_bphone']))?$ou_data['pmpro_bphone']:'';
            $email = (isset($ou_data['pmpro_bemail']) && !empty($ou_data['pmpro_bemail']))?$ou_data['pmpro_bemail']:'';
            $pickup_loc_detail = get_pickup_location_detail($pl_val['pickup_location']);
            if(!empty($pickup_loc_detail)){
              $pl_nm = (isset($pickup_loc_detail['name']) && !empty($pickup_loc_detail['name']))?ucwords($pickup_loc_detail['name']):'';
              $pl_adr = (isset($pickup_loc_detail['address']) && !empty($pickup_loc_detail['address']))?$pickup_loc_detail['address']:'';
              $exceldata[] = array('Pickup',$first_name.' '.$last_name, $phone, $email, $pl_nm, $pl_adr);
              $excel_obj->getActiveSheet()->getStyle('A2:F2')->getFont()->setBold( true );
            }
          }
        }
      }
      if($is_pl_fnd == 1){
        $excel_obj->setActiveSheetIndex(0);
        //name the worksheet
        $excel_obj->getActiveSheet()->setTitle('Pickup Location Sheet');
        $excel_obj->getActiveSheet()->setCellValue('B2', 'Customer Name');
        $excel_obj->getActiveSheet()->setCellValue('C2', 'Customer Phone');
        $excel_obj->getActiveSheet()->setCellValue('D2', 'Customer Email');
        $excel_obj->getActiveSheet()->setCellValue('E2', 'Pickup Name');
        $excel_obj->getActiveSheet()->setCellValue('F2', 'Pickup Address');
        $excel_obj->getActiveSheet()->mergeCells('A1:F1');
        $excel_obj->getActiveSheet()->setCellValue('A1', 'Week of '.date('m/d/y',strtotime($prms['whr']['wk_start_date'])).' - '.date('m/d/y',strtotime($prms['whr']['wk_end_date'])));
        $excel_obj->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $excel_obj->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $excel_obj->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $excel_obj->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $excel_obj->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $excel_obj->getActiveSheet()->getColumnDimension('F')->setWidth(50);
        $excel_obj->getActiveSheet()->getStyle('A1:A'.$excel_obj->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
        $excel_obj->getActiveSheet()->getStyle('A1')->applyFromArray(array(
          'font'  => array(
          'bold'  => true,
          'color' => array('rgb' => 'FF0000'),
          'size'  => 12,
          'name'  => ''
        )));
        $excel_obj->getActiveSheet()->fromArray($exceldata, null, 'A3');
        $excel_obj->getActiveSheet()->getDefaultColumnDimension()->setWidth(30);
        $excel_obj->getActiveSheet()->getDefaultRowDimension()->setRowHeight(25);
        $dr_filename = 'pickup-location-sheet'.time().'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$dr_filename.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($excel_obj, 'Excel5');  
        ob_clean(); ob_start();
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
      }else{
        echo '<script type="text/javascript">
              jQuery(document).ready(function() {
                alert("No record found.");
              });
            </script>';
      }
    }
    //generate tookan delivery sheet
    if(isset($_POST['generate_tookan_delivery_sheet']) && !empty($_POST['generate_tookan_delivery_sheet'])){
      date_default_timezone_set("America/New_York");
      require_once "third_party/PHPExcel/Classes/PHPExcel.php";
      $excel_obj = new PHPExcel();
      $excel_obj->setActiveSheetIndex(0);
      //name the worksheet
      $excel_obj->getActiveSheet()->setTitle('Tookan Delivery');
      $excel_obj->getActiveSheet()->setCellValue('A1', 'Pickup or delivery');
      $excel_obj->getActiveSheet()->setCellValue('B1', 'Task Description');
      $excel_obj->getActiveSheet()->setCellValue('C1', 'Customer Email');
      $excel_obj->getActiveSheet()->setCellValue('D1', 'Customer Name');
      $excel_obj->getActiveSheet()->setCellValue('E1', 'Street Level Address');
      $excel_obj->getActiveSheet()->setCellValue('F1', 'City');
      $excel_obj->getActiveSheet()->setCellValue('G1', 'Zipcode/Pincode');
      $excel_obj->getActiveSheet()->setCellValue('H1', 'Country');
      $excel_obj->getActiveSheet()->setCellValue('I1', 'Latitude');
      $excel_obj->getActiveSheet()->setCellValue('J1', 'Longitude');
      $excel_obj->getActiveSheet()->setCellValue('K1', 'Customer Phone Number');
      $excel_obj->getActiveSheet()->setCellValue('L1', 'Delivery Date and Time (MM/DD/YYYY) (HH:MM:SS)');
      $excel_obj->getActiveSheet()->getColumnDimension('A')->setWidth(20);
      $excel_obj->getActiveSheet()->getColumnDimension('B')->setWidth(50);
      $excel_obj->getActiveSheet()->getColumnDimension('C')->setWidth(35);
      $excel_obj->getActiveSheet()->getColumnDimension('D')->setWidth(25);
      $excel_obj->getActiveSheet()->getColumnDimension('E')->setWidth(35);
      $excel_obj->getActiveSheet()->getColumnDimension('F')->setWidth(15);
      $excel_obj->getActiveSheet()->getColumnDimension('G')->setWidth(15);
      $excel_obj->getActiveSheet()->getColumnDimension('H')->setWidth(15);
      $excel_obj->getActiveSheet()->getColumnDimension('I')->setWidth(10);
      $excel_obj->getActiveSheet()->getColumnDimension('J')->setWidth(10);
      $excel_obj->getActiveSheet()->getColumnDimension('K')->setWidth(20);
      $excel_obj->getActiveSheet()->getColumnDimension('L')->setWidth(30);
      $tmp_array = $filtered_array = $p_arr =  array();
      foreach ($total_lmo_records as $lo_key => $lo_val) {
        $meal_status_sql = "SELECT status FROM wp_users_meals WHERE user_id = ".$lo_val['usr_id']." AND week_start_date = '".$lo_val['week_start_date']."' AND week_end_date = '".$lo_val['week_end_date']."' GROUP BY week_start_date, week_end_date";
        $meal_status_arr = $wpdb->get_results($meal_status_sql, ARRAY_A);
        if(isset($meal_status_arr[0]['status']) && $meal_status_arr[0]['status'] != 4){
          $user_meta = (isset($lo_val['user_data']) && !empty($lo_val['user_data']))?unserialize($lo_val['user_data']):array();
          $last_name = (isset($user_meta['last_name']) && !empty($user_meta['last_name']))?$user_meta['last_name']:'';
          $is_allergies = (isset($user_meta['is_allergies'][0]) && !empty($user_meta['is_allergies'][0]))?$user_meta['is_allergies'][0]:'';
          $allergies = (isset($user_meta['allergies'][0]) && !empty($user_meta['allergies'][0]))?unserialize($user_meta['allergies'][0]):'';
          $allergy = '';
          if($is_allergies == 'yes' && !empty($allergies[0])){
            switch ($allergies[0]) {
              case 'gluten_free':
                $allergy = '1gluten_free';
              break;
              case 'dairy_free':
                $allergy = '2dairy_free';
              break;
              case 'gluten_dairy_free':
                $allergy = '3gluten_dairy_free';
              break;
            }
          }
          $lo_val['last_name'] = $last_name;
          $lo_val['allergy'] = $allergy;
          if(in_array($lo_val['meal_plan_group'],$p_arr)){
            $tmp_array[$lo_val['meal_plan_group']][] = $lo_val;
          }else{
            $p_arr[] = $lo_val['meal_plan_group'];
            $tmp_array[$lo_val['meal_plan_group']][] = $lo_val;
          }
        }
      }
      ksort($tmp_array);
      if(!empty($tmp_array) && count($tmp_array) > 0){
        foreach ($tmp_array as $tmp_ky => $tmp_vl) {
          $filtered_array[$tmp_ky] = array_msort($tmp_vl, array('last_name'=>SORT_ASC,'allergy'=>SORT_ASC));
        }
      }
      global $pmpro_countries;
      $exceldata = array();
      $row_cnt = 1;
      foreach ($filtered_array as $meal_pln => $fltr_val){
        foreach ($fltr_val as $gdrs_key => $gdrs_val){
          $gdrs_status_sql = "SELECT status FROM ".$wpdb->prefix."users_meals WHERE user_id = ".$gdrs_val['usr_id']." AND week_start_date = '".$gdrs_val['week_start_date']."' AND week_end_date = '".$gdrs_val['week_end_date']."' GROUP BY week_start_date, week_end_date";
          $gdrs_status_arr =  $wpdb->get_results($gdrs_status_sql, ARRAY_A);
          if(isset($gdrs_status_arr[0]['status']) && $gdrs_status_arr[0]['status'] != 4){
            $usr_id = (isset($gdrs_val['usr_id']) && !empty($gdrs_val['usr_id']))?$gdrs_val['usr_id']:'';
            $user_meta = (isset($gdrs_val['user_data']) && !empty($gdrs_val['user_data']))?unserialize($gdrs_val['user_data']):array();
            $first_name = (isset($user_meta['first_name']) && !empty($user_meta['first_name']))?$user_meta['first_name']:'';
            $last_name = (isset($user_meta['last_name']) && !empty($user_meta['last_name']))?$user_meta['last_name']:'';
            $phone = (isset($user_meta['pmpro_bphone']) && !empty($user_meta['pmpro_bphone']))?$user_meta['pmpro_bphone']:'';
            $email = (isset($user_meta['pmpro_bemail']) && !empty($user_meta['pmpro_bemail']))?$user_meta['pmpro_bemail']:'';
            $street_level_address = '';
            if(isset($user_meta['pmpro_baddress1']) && !empty($user_meta['pmpro_baddress1'])){
              $street_level_address .= $user_meta['pmpro_baddress1'];
            }
            if(isset($user_meta['pmpro_baddress2']) && !empty($user_meta['pmpro_baddress2'])){
              $street_level_address .= ', '.$user_meta['pmpro_baddress2'];
            }
            $city = (isset($user_meta['pmpro_bcity']) && !empty($user_meta['pmpro_bcity']))?$user_meta['pmpro_bcity']:'';
            $country = (isset($user_meta['pmpro_bcountry']) && !empty($user_meta['pmpro_bcountry']))?$pmpro_countries[$user_meta['pmpro_bcountry']]:'';
            $zipcode = (isset($user_meta['pmpro_bzipcode']) && !empty($user_meta['pmpro_bzipcode']))?" ".$user_meta['pmpro_bzipcode']:'';
            $delivery_notes = (isset($user_meta['pmpro_special_delivery_instructions']) && !empty($user_meta['pmpro_special_delivery_instructions']))?$user_meta['pmpro_special_delivery_instructions']:'';
            $delivery_date = date('m/d/Y', strtotime('next sunday', strtotime($gdrs_val['week_start_date'])));
            $delivery_date_time = '('.$delivery_date.') (20:30:00)';

            $insulated_bag_odrs = get_user_purchased_insulated_bag_products_by_user_id($usr_id);
            $pickup_or_delivery = (!empty($insulated_bag_odrs) && is_array($insulated_bag_odrs) && count($insulated_bag_odrs) > 0)?'Insulated delivery':'Delivery';

            $exceldata[] = array($pickup_or_delivery,$delivery_notes,$email,$first_name.' '.$last_name,$street_level_address,$city,$zipcode,$country,'','',$phone,$delivery_date_time);
            if($row_cnt%2 == 0){
              $excel_obj->getActiveSheet()->getStyle('A'.$row_cnt.':L'.$row_cnt)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('EFEFEF');
            }
            $excel_obj->getActiveSheet()->getStyle('A'.$row_cnt.':L'.$row_cnt)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $row_cnt++;
          }
        }
      }
      
      $excel_obj->getActiveSheet()->getStyle('A1:L1'.$excel_obj->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
      $excel_obj->getActiveSheet()->getStyle('A1:L1')->applyFromArray(array(
        'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '000000'),
        'size'  => 11,
        'name'  => ''
      )));
      $excel_obj->getActiveSheet()->fromArray($exceldata, null, 'A2');
      $excel_obj->getActiveSheet()->getDefaultColumnDimension()->setWidth(30);
      $excel_obj->getActiveSheet()->getDefaultRowDimension()->setRowHeight(35);
      $lastrow = $excel_obj->getActiveSheet()->getHighestRow();
      $excel_obj->getActiveSheet()->getStyle('F1:F'.$lastrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
      $tds_filename = 'tookan-delivery-sheet'.time().'.xls';
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$tds_filename.'"');
      header('Cache-Control: max-age=0');
      $objWriter = PHPExcel_IOFactory::createWriter($excel_obj, 'Excel5');  
      ob_clean(); ob_start();
      $objWriter->save('php://output');
    }
    ?>
    <div class="wrap">
      <h1 class="wp-heading-inline">Locked Meals Orders</h1>
      <a href="<?php echo site_url(); ?>/list_of_users_who_have_only_preselected_meals_in_current_week_order.php" target="_blank">List of users who have auto selected, hand selected and partial selected meals of locked week order</a>
      <hr class="wp-header-end">
    <?php
    $html = '<div class="nosubsub">
        <div id="ajax-response"></div>
          <div id="col-container">
            <div class="tablenav top">
              <div class="alignleft actions">
              <form action="" method="post">
                <label for="filter-by-date" class="screen-reader-text">Filter by date</label>
                <select name="filter_by_date" id="filter_by_date">
                  <option selected="selected" value="0">Filter delivery date</option>';
                  if(isset($filter_dates) && !empty($filter_dates)){
                    foreach ($filter_dates as $fd_key => $fd_val) {
                      $slcted = ($dt_flt == strtotime($fd_val))?'selected':'';
                      //$slcted = ((isset($_POST['filter_by_date']) && $_POST['filter_by_date'] == strtotime($fd_val)) || (isset($_GET['fltr-date']) && $_GET['fltr-date'] == strtotime($fd_val)))?'selected':'';
                      $html .= '<option value="'.strtotime($fd_val).'" '.$slcted.'>'.ucfirst(date("F j, Y",strtotime($fd_val))).'</option>';
                    }
                  }
                $html.='</select>';
                $flr_by_customer = (isset($_POST['flr_by_customer']) && !empty($_POST['flr_by_customer']))?$_POST['flr_by_customer']:'';
                $fltr_by_cust = (isset($_POST['filter_by_customer']) && !empty($_POST['filter_by_customer']))?$_POST['filter_by_customer']:'';
                $html.='<input type="text" class="" name="flr_by_customer" id="flr_by_customer" value="'.$flr_by_customer.'" placeholder="Enter user email"/>
                    <input type="hidden" name="filter_by_customer" id="filter_by_customer" value="'.$fltr_by_cust.'"/>';
                $html.='<select name="filter_by_plan" id="filter_by_plan">
                  <option selected="selected" value="">Filter by plan</option>';
                  if(!empty($pln_lst) && count($pln_lst)){
                    foreach ($pln_lst as $pln_id => $pln_val) {
                      $slcted = '';
                      if(isset($_POST['filter_by_plan'])){
                        if($_POST['filter_by_plan'] == $pln_id){
                          $slcted = 'selected';
                        }
                      }else if(isset($_GET['fltr-pln']) && $_GET['fltr-pln'] == $pln_id){
                        $slcted = 'selected';
                      }
                      $html .= '<option value="'.$pln_id.'" '.$slcted.'>'.$pln_val.'</option>';
                    }
                  }
                $html.='</select>
                <input type="submit" name="filter_action" id="filter_action" class="button" value="Filter">
                <span id="sheet_generate_btn_sec" class="'.$filter_btn_cls.'">
                  <input type="submit" name="generation_order_sheet" id="generation_order_sheet" class="button" value="Generation order sheet">
                  <input type="submit" name="generate_grocery_sheet" id="generate_grocery_sheet" class="button" value="Generate grocery sheet">
                  <input type="submit" name="generate_delivery_route" id="generate_delivery_route" class="button" value="Generate delivery route">
                  <input type="submit" name="generate_label_sheet" id="generate_label_sheet" class="button" value="Generate label sheet">
                  <input type="submit" name="generate_pickup_location_sheet" id="generate_pickup_location_sheet" class="button" value="Generate pickup location sheet">
                  <input type="submit" name="generate_tookan_delivery_sheet" id="generate_tookan_delivery_sheet" class="button" value="Generate tookan delivery sheet">
                </span>
              </form>
              </div>
              <div class="tablenav-pages one-page adm-pagination">
                <span class="displaying-num">'.count($total_lmo_records).' items</span>
                '.paginate_links(array(
                  'base' => str_replace($maxlmt, '%#%', esc_url(get_pagenum_link($maxlmt))),
                  'format' => '?paged=%#%',
                  'add_args' => array('fltr-date' => $_POST['filter_by_date'],'fltr-pln' => $_POST['filter_by_plan'],'fltr-cust' => $_POST['filter_by_customer']),
                  'current' => max(1, $paged),
                  'mid-size' => 1,
                  'prev_next' => true,
                    'prev_text' => __('«'),
                    'next_text' => __('»'),
                  'total' => $totalpages
                )).'
              </div>
              <br class="clear">
            </div>
            <table class="widefat" width="100%">
              <thead>
                <tr>
                  <th width="15%"><span>Order</span></th>
                  <th width="18%"><span>Ship to</span></th>
                  <th width="5%"><span>Payment Status</span></th>
                  <th width="10%"><span>Order Date</span></th>
                  <th width="12%"><span>Plan</span></th>
                  <th width="10%"><span>Days</span></th>
                  <th width="10%"><span>Meals Per Day</span></th>
                  <th width="10%"><span>Week Status</span></th>
                  <th width="10%"><span>Total</span></th>
                </tr>
              </thead>';
              if (isset($lmo_data) && !empty($lmo_data)){
                $ump_tbl = $wpdb->prefix.'users_membership_plans';
                foreach ($lmo_data as $lmo_key => $lmo_val){
                  $usr_id = (isset($lmo_val['usr_id']) && !empty($lmo_val['usr_id']))?$lmo_val['usr_id']:'';
                  $week_start_date = (isset($lmo_val['week_start_date']) && !empty($lmo_val['week_start_date'])) ? $lmo_val['week_start_date'] : '';
                  $week_end_date = (isset($lmo_val['week_end_date']) && !empty($lmo_val['week_end_date'])) ? $lmo_val['week_end_date'] : '';
                  if(!empty($usr_id) && !empty($week_start_date) && !empty($week_end_date)){
                    $range_sql = "SELECT selected_days_range FROM $ump_tbl WHERE user_id = $usr_id AND start_date = '".$week_start_date."' AND end_date = '".$week_end_date."'";
                    $date_range_arr =  $wpdb->get_results($range_sql, ARRAY_A);
                    if(!empty($date_range_arr)){
                      $sldrarr = explode('-', $date_range_arr[0]['selected_days_range']);
                      $start_day = (isset($sldrarr[0])) ? getDayFromIndex($sldrarr[0]) : '';
                      $end_day = (isset($sldrarr[1])) ? getDayFromIndex($sldrarr[1]) : '';
                    }
                    $wk_pln_sql = "SELECT week_meals_per_day, status FROM wp_users_meals WHERE user_id = $usr_id AND week_start_date = '".$week_start_date."' AND week_end_date = '".$week_end_date."' GROUP BY week_start_date, week_end_date";
                    $wk_pln_arr =  $wpdb->get_results($wk_pln_sql, ARRAY_A);
                  }
                  $meals_per_day = (isset($wk_pln_arr[0]['week_meals_per_day']) && !empty($wk_pln_arr[0]['week_meals_per_day'])) ? $wk_pln_arr[0]['week_meals_per_day'] : '';
                  $w_status = (isset($wk_pln_arr[0]['status']) && !empty($wk_pln_arr[0]['status']) && $wk_pln_arr[0]['status'] != 4) ? 'Ordered' : 'Skipped';
                  $moid = (isset($lmo_val['id']) && !empty($lmo_val['id']))?$lmo_val['id']:'';
                  $usr_plan = get_user_plan_by_user_id($usr_id);
                  $umo_user_data = unserialize($lmo_val['user_data']);
                  $first_name = (isset($umo_user_data['first_name']) && !empty($umo_user_data['first_name']))?$umo_user_data['first_name']:'';
                  $last_name = (isset($umo_user_data['last_name']) && !empty($umo_user_data['last_name']))?$umo_user_data['last_name']:'';
                  $order_date = (isset($lmo_val['order_date']) && !empty($lmo_val['order_date']))?ucfirst(date("F j, Y",strtotime($lmo_val['order_date']))):'';
                  $initial_payment = (isset($lmo_val['total_bill_amount']) && $lmo_val['total_bill_amount'] > 0)?$lmo_val['total_bill_amount']:0;
                  $mem_grp_name = $mem_grp_color = '';
                  if(isset($lmo_val['meal_plan_group']) && $lmo_val['meal_plan_group'] > 0){
                    $term_grp = get_term_by('id', $lmo_val['meal_plan_group'], 'membershipgroup');
                    $mem_grp_name = (isset($term_grp->name))?$term_grp->name:'';
                    if(isset($term_grp->slug) && !empty($term_grp->slug)){
                      switch ($term_grp->slug) {
                        case 'weight-loss':
                        case 'lose-weight':
                          $mem_grp_color = '#5B83BB';
                        break;
                        case 'balanced':
                          $mem_grp_color = '#D0AD69';
                        break;
                        case 'gain-muscle':
                          $mem_grp_color = '#D26B6B';
                        break;
                      }
                    }
                  }
                  $umordr_status = (isset($lmo_val['status']) && !empty($lmo_val['status']))?ucfirst($lmo_val['status']):'';
                  $umo_delivery_notes = (isset($umo_user_data['pmpro_special_delivery_instructions']) && !empty($umo_user_data['pmpro_special_delivery_instructions']))?$umo_user_data['pmpro_special_delivery_instructions']:'';
                  $ship_address = '';
                  if(isset($umo_user_data['pmpro_bfirstname']) && !empty($umo_user_data['pmpro_bfirstname'])){
                    $ship_address .= $umo_user_data['pmpro_bfirstname'];
                  }
                  if(isset($umo_user_data['pmpro_blastname']) && !empty($umo_user_data['pmpro_blastname'])){
                    $ship_address .= ' '.$umo_user_data['pmpro_blastname'];
                  }
                  if(isset($umo_user_data['pmpro_baddress1']) && !empty($umo_user_data['pmpro_baddress1'])){
                    $ship_address .= ', '.$umo_user_data['pmpro_baddress1'];
                  }
                  if(isset($umo_user_data['pmpro_baddress2']) && !empty($umo_user_data['pmpro_baddress2'])){
                    $ship_address .= ', '.$umo_user_data['pmpro_baddress2'];
                  }
                  if(isset($umo_user_data['pmpro_bcity']) && !empty($umo_user_data['pmpro_bcity'])){
                    $ship_address .= ', '.$umo_user_data['pmpro_bcity'];
                  }
                  if(isset($umo_user_data['pmpro_bstate']) && !empty($umo_user_data['pmpro_bstate'])){
                    $ship_address .= ', '.$umo_user_data['pmpro_bstate'];
                  }
                  if(isset($umo_user_data['pmpro_bzipcode']) && !empty($umo_user_data['pmpro_bzipcode'])){
                    $ship_address .= ', '.$umo_user_data['pmpro_bzipcode'];
                  }
                  $viewurl = admin_url(). "admin.php?page=locked-meals-orders&action=1&moid=".base64_encode($moid);
                  $html .= '<tbody>
                    <tr>
                      <td class="name column-name has-row-actions column-primary" data-colname="Name"><strong><a class="row-title" href="'.$viewurl.'" title="View">'.$first_name.' '.$last_name.'<br>'.$lmo_val["user_email"].'</a></strong><br>
                        <div class="row-actions">
                          <span class="view"><a href="'.$viewurl.'">View</a></span>
                        </div>
                      </td>
                      <td>'.$ship_address.'</td>
                      <td>'.$umordr_status.'</td>
                      <td>'.$order_date.'</td>
                      <td>';
                      if(!empty($mem_grp_name)){
                        $html .= '<span style="border-radius: 6px; border: 1px solid '.$mem_grp_color.';padding: 5px;color:'.$mem_grp_color.';">'.$mem_grp_name.'</span>';
                      }
                      $html .= '</td>
                      <td>'.$start_day.'-'.$end_day.'</td>
                      <td>'.$meals_per_day.'</td>
                      <td>'.$w_status.'</td>
                      <td>$'.$initial_payment.'<br>Via Stripe</td>
                    </tr>';
                }
              }else{
                $html .= '<tr><td colspan="9" align="center">No record found!</td></tr>';
              }
              $html .= '</tbody>
              <tfoot>
                <tr>
                  <th width="15%"><span>Order</span></th>
                  <th width="18%"><span>Ship to</span></th>
                  <th width="5%"><span>Payment Status</span></th>
                  <th width="10%"><span>Order Date</span></th>
                  <th width="12%"><span>Plan</span></th>
                  <th width="10%"><span>Days</span></th>
                  <th width="10%"><span>Meals Per Day</span></th>
                  <th width="10%"><span>Week Status</span></th>
                  <th width="10%"><span>Total</span></th>
                </tr>
              </tfoot>
            </table>
            <div class="tablenav bottom">
              <div class="tablenav-pages one-page adm-pagination">
                <span class="displaying-num">'.count($total_lmo_records).' items</span>
                '.paginate_links(array(
                  'base' => str_replace($maxlmt, '%#%', esc_url(get_pagenum_link($maxlmt))),
                  'format' => '?paged=%#%',
                  'add_args' => array('fltr-date' => $_POST['filter_by_date'],'fltr-pln' => $_POST['filter_by_plan'],'fltr-cust' => $_POST['filter_by_customer']),
                  'current' => max(1, $paged),
                  'mid-size' => 1,
                  'prev_next' => true,
                    'prev_text' => __('«'),
                    'next_text' => __('»'),
                  'total' => $totalpages
                )).'
              </div>
            </div>
          </div>
        </div>';
        $html .= '<script type="text/javascript">
          jQuery("select#filter_by_date").on("change", function (e) {
            jQuery("span#sheet_generate_btn_sec").addClass("dnone");
          });
          jQuery("select#filter_by_plan").on("change", function (e) {
            jQuery("span#sheet_generate_btn_sec").addClass("dnone");
          });
        </script>';
    echo $html;
   ?>
    </div>
    <?php
  }
}

function filter_locked_meal_arr($filtered_meals = array()){
  $temp_breakfast = $temp_lunch = $temp_dinner = $temp_snack = $temp_dessert = $temp_lst = array();
  foreach ($filtered_meals as $fm_key => $fm_value) {
    $meal_id = (isset($fm_value['meal_id']) && !empty($fm_value['meal_id'])) ? $fm_value['meal_id'] : '';
    $category_arr = get_the_terms($meal_id,'menu-items-category');
    $cat_order = get_term_meta($category_arr[0]->term_id,'_meal_cat_order',true);
    if($cat_order){
      if($cat_order == 1){
        $temp_breakfast[] = $fm_value;
      }elseif($cat_order == 2){
        $temp_lunch[] = $fm_value;
      }elseif($cat_order == 3){
        $temp_dinner[] = $fm_value;
      }elseif($cat_order == 4){
        $temp_snack[] = $fm_value;
      }elseif($cat_order == 5){
        $temp_dessert[] = $fm_value;
      }
    }else{
      $temp_lst[] = $fm_value;
    }
    $cat_break_lunch_arr = array_merge($temp_breakfast,$temp_lunch);
    $cat_br_lu_din_arr = array_merge($cat_break_lunch_arr,$temp_dinner);
    $cat_br_lu_din_snak_arr = array_merge($cat_br_lu_din_arr,$temp_snack);
    $cat_br_lu_din_snak_des_arr = array_merge($cat_br_lu_din_snak_arr,$temp_dessert);
    $final_cat_sorted_arr = array_merge($cat_br_lu_din_snak_des_arr,$temp_lst);
  }
  $temp_wt_ls = $temp_bal = $temp_gain_mus = $temp_keto = $temp_paleo = $final_plan_sorted_arr = array();
  foreach ($final_cat_sorted_arr as $fcs_key => $fcs_value) {
    $meal_id = (isset($fcs_value['meal_id']) && !empty($fcs_value['meal_id'])) ? $fcs_value['meal_id'] : '';
    $plan_id = get_post_meta($meal_id,'_plan',true);
    $plan_grp = get_term_by('id', $plan_id, 'membershipgroup');
    $plan_slug = (isset($plan_grp->slug))?$plan_grp->slug:'';
    if($plan_slug == 'weight-loss' || $plan_slug == 'lose-weight'){
      $temp_wt_ls[] = $fcs_value;
    }elseif($plan_slug == 'balanced'){
      $temp_bal[] = $fcs_value;
    }elseif($plan_slug == 'gain-muscle'){
      $temp_gain_mus[] = $fcs_value;
    }elseif($plan_slug == 'ketogenic'){
      $temp_keto[] = $fcs_value;
    }elseif($plan_slug == 'paleo'){
      $temp_paleo[] = $fcs_value;
    }
    $plan_sorted_arr_wl_bal = array_merge($temp_wt_ls,$temp_bal);
    $plan_sorted_arr_wl_bal_gm = array_merge($plan_sorted_arr_wl_bal,$temp_gain_mus);
    $plan_sorted_arr_wl_bal_gm_kt = array_merge($plan_sorted_arr_wl_bal_gm,$temp_keto);
    $final_plan_sorted_arr = array_merge($plan_sorted_arr_wl_bal_gm_kt,$temp_paleo);
  }
  return $final_plan_sorted_arr;
}

/*
* Method: function for view locked meal order
*/

function view_locked_meal_order($moid){
  if(!empty($moid)){
    global $wpdb;
    date_default_timezone_set("America/New_York");
    $umo_tbl = $wpdb->prefix.'users_meals_orders';
    $u_tbl = $wpdb->prefix.'users';
    $mo_sql = "SELECT umo.*, usr.ID as usr_id, usr.user_email FROM $umo_tbl as umo JOIN $u_tbl as usr on usr.ID = umo.user_id WHERE umo.id = $moid";
    $rslt = $wpdb->get_row($mo_sql, ARRAY_A);
    $week_start_date = (isset($rslt['week_start_date']) && !empty($rslt['week_start_date'])) ? $rslt['week_start_date'] : '';
    $week_end_date = (isset($rslt['week_end_date']) && !empty($rslt['week_end_date'])) ? $rslt['week_end_date'] : '';
    $discount_amount = (isset($rslt['discount_amount']) && !empty($rslt['discount_amount']))? $rslt['discount_amount']: 0;
    $tax_amount = (isset($rslt['tax_amount']) && !empty($rslt['tax_amount']))? $rslt['tax_amount']: 0;
    if(!empty($week_start_date) && !empty($week_end_date)){
      $strt_dt_frmt = str_replace('-', '', $week_start_date);
      $end_dt_frmt = str_replace('-', '', $week_end_date);
      $rec_bill_lst_dt_frmt = $strt_dt_frmt.'-'.$end_dt_frmt;
    }
    $umo_id = (isset($rslt['id']) && !empty($rslt['id']))?$rslt['id']:'';
    $usr_id = (isset($rslt['usr_id']) && !empty($rslt['usr_id']))?$rslt['usr_id']:'';
    if(!empty($usr_id)){
      $user_coupon_data = get_user_meta($usr_id,'subscription_plan_coupon_data', true);
      $recurring_billing_list = (isset($user_coupon_data['recurring_billing_list']) && !empty($user_coupon_data['recurring_billing_list'])) ? $user_coupon_data['recurring_billing_list'] : array();
      if(!empty($recurring_billing_list) && in_array($rec_bill_lst_dt_frmt,$recurring_billing_list)){
        $coupon_code = (isset($user_coupon_data['coupon_code']) && !empty($user_coupon_data['coupon_code'])) ? $user_coupon_data['coupon_code'] : '';
        $coupon_amount = $discount_amount;
      }else{
        $coupon_amount = 0;
      }
    }
    $umo_user_data = (isset($rslt['user_data']) && !empty($rslt['user_data']))?unserialize($rslt['user_data']):'';
    $f_name = (isset($umo_user_data['first_name']) && !empty($umo_user_data['first_name']))?ucfirst($umo_user_data['first_name']):'';
    $l_name = (isset($umo_user_data['last_name']) && !empty($umo_user_data['last_name']))?ucfirst($umo_user_data['last_name']):'';
    $user_email = (isset($rslt['user_email']) && !empty($rslt['user_email']))?$rslt['user_email']:'';
    $tot_bill_amount = (isset($rslt['total_bill_amount']) && !empty($rslt['total_bill_amount'])) ?$rslt['total_bill_amount'] : 0; 
    $umo_date = (isset($rslt['order_date']) && !empty($rslt['order_date']))?$rslt['order_date']:'';
    $umo_ip_addr = (isset($rslt['ip_address']) && !empty($rslt['ip_address']))?$rslt['ip_address']:'';
    $umo_status = (isset($rslt['status']) && !empty($rslt['status']))?ucfirst($rslt['status']):'';
    $umo_transaction_data = (isset($rslt['transaction_data']) && !empty($rslt['transaction_data']))?unserialize($rslt['transaction_data']):'';
    $umo_invoice_data = (isset($umo_transaction_data['invoice_data']) && !empty($umo_transaction_data['invoice_data']))?$umo_transaction_data['invoice_data']:'';
    $umo_charge_data = (isset($umo_transaction_data['charge_data']) && !empty($umo_transaction_data['charge_data']))?$umo_transaction_data['charge_data']:'';
    $payment_source = (isset($umo_charge_data['source']['funding']) && !empty($umo_charge_data['source']['funding']))?ucfirst($umo_charge_data['source']['funding']):'';
    $trns_key = (isset($umo_invoice_data['charge']) && !empty($umo_invoice_data['charge']))?$umo_invoice_data['charge']:((isset($umo_invoice_data['invoice_id']) && !empty($umo_invoice_data['invoice_id']))?$umo_invoice_data['invoice_id']:'');
    $umo_delivery_notes = (isset($umo_user_data['pmpro_special_delivery_instructions']) && !empty($umo_user_data['pmpro_special_delivery_instructions']))?$umo_user_data['pmpro_special_delivery_instructions']:'NA';
    $pmpro_bfirstname = (isset($umo_user_data['pmpro_bfirstname']) && !empty($umo_user_data['pmpro_bfirstname']))?$umo_user_data['pmpro_bfirstname']:'';
    $pmpro_blastname = (isset($umo_user_data['pmpro_blastname']) && !empty($umo_user_data['pmpro_blastname']))?$umo_user_data['pmpro_blastname']:'';
    $pmpro_baddress1 = (isset($umo_user_data['pmpro_baddress1']) && !empty($umo_user_data['pmpro_baddress1']))?$umo_user_data['pmpro_baddress1']:'';
    $pmpro_baddress2 = (isset($umo_user_data['pmpro_baddress2']) && !empty($umo_user_data['pmpro_baddress2']))?$umo_user_data['pmpro_baddress2']:'';
    $pmpro_bcity = (isset($umo_user_data['pmpro_bcity']) && !empty($umo_user_data['pmpro_bcity']))?$umo_user_data['pmpro_bcity']:'';
    $pmpro_bstate = (isset($umo_user_data['pmpro_bstate']) && !empty($umo_user_data['pmpro_bstate']))?$umo_user_data['pmpro_bstate']:'';
    $pmpro_bzipcode = (isset($umo_user_data['pmpro_bzipcode']) && !empty($umo_user_data['pmpro_bzipcode']))?$umo_user_data['pmpro_bzipcode']:'';
    $pmpro_bcountry = (isset($umo_user_data['pmpro_bcountry']) && !empty($umo_user_data['pmpro_bcountry']))?$umo_user_data['pmpro_bcountry']:'';
    $pmpro_bphone = (isset($umo_user_data['pmpro_bphone']) && !empty($umo_user_data['pmpro_bphone']))?$umo_user_data['pmpro_bphone']:'';
    $pmpro_bemail = (isset($umo_user_data['pmpro_bemail']) && !empty($umo_user_data['pmpro_bemail']))?$umo_user_data['pmpro_bemail']:'';
    $mem_grp_name = $mem_grp_color = '';
    $total_amount = $tot_bill_amount;
    $sub_tot_amt = $tot_bill_amount;
    if(!empty($coupon_amount)){
      $sub_tot_amt = $sub_tot_amt + $coupon_amount;
    }
    $sub_tot_amt = $sub_tot_amt - $tax_amount;
    if(isset($rslt['meal_plan_group']) && $rslt['meal_plan_group'] > 0){
      $term_grp = get_term_by('id', $rslt['meal_plan_group'], 'membershipgroup');
      $mem_grp_name = (isset($term_grp->name))?$term_grp->name:'';
      if(isset($term_grp->slug) && !empty($term_grp->slug)){
        switch ($term_grp->slug) {
          case 'weight-loss':
          case 'lose-weight':
            $mem_grp_color = '#5B83BB';
          break;
          case 'balanced':
            $mem_grp_color = '#D0AD69';
          break;
          case 'gain-muscle':
            $mem_grp_color = '#D26B6B';
          break;
        }
      }
    }
    $week_start_date = (isset($rslt['week_start_date']) && !empty($rslt['week_start_date']))? $rslt['week_start_date']:'';
    $week_end_date = (isset($rslt['week_end_date']) && !empty($rslt['week_end_date']))? $rslt['week_end_date']:'';
    $meal_plan = (isset($rslt['meal_plan']) && !empty($rslt['meal_plan']))? $rslt['meal_plan']:'';
    if(!empty($meal_plan)){
      if(!empty($week_start_date) && !empty($week_end_date) && !empty($usr_id)){
        $psd_sql = "SELECT selected_days, used_credit_points, addon_meals_amount from {$wpdb->prefix}users_membership_plans WHERE start_date = '".$week_start_date."' AND end_date = '".$week_end_date."' AND user_id = ".$usr_id;
        $pln_data = $wpdb->get_row($psd_sql, ARRAY_A);
        $days_per_week = (!empty($pln_data['selected_days']))?$pln_data['selected_days']:0;
        $used_credit_points = (!empty($pln_data['used_credit_points']))?$pln_data['used_credit_points']:0;
        if($used_credit_points > 0){
          $total_amount = $total_amount - $used_credit_points;
        }
        $addon_meals_amount = (!empty($pln_data['addon_meals_amount']))?$pln_data['addon_meals_amount']:0;
        if($addon_meals_amount > 0){
          $total_amount = $total_amount + $pln_data['addon_meals_amount'];
        }
        $wk_mpd_sql = "SELECT week_meals_per_day, status FROM wp_users_meals WHERE user_id = $usr_id AND week_start_date = '".$week_start_date."' AND week_end_date = '".$week_end_date."' GROUP BY week_start_date, week_end_date";
        $wk_mpd_arr =  $wpdb->get_results($wk_mpd_sql, ARRAY_A);
        $meal_per_day = (isset($wk_mpd_arr[0]['week_meals_per_day']))?$wk_mpd_arr[0]['week_meals_per_day']:0;
        $wko_status = (isset($wk_mpd_arr[0]['status']) && !empty($wk_mpd_arr[0]['status']) && $wk_mpd_arr[0]['status'] != 4) ? 'Ordered' : 'Skipped';
      }
    }
    $um_table = $wpdb->prefix.'users_meals';
    $post_table = $wpdb->prefix.'posts';
    if(isset($rslt['week_start_date']) && !empty($rslt['week_start_date']) && isset($rslt['week_end_date']) && !empty($rslt['week_end_date'])){
      $mo_items_sql = "SELECT um.id, um.user_id, um.meal_id, meal.post_title as meal_name, COUNT(meal_id) as meal_cnt from $um_table as um join $post_table as meal on meal.ID = um.meal_id WHERE um.user_id = ".$rslt['user_id']." AND um.week_start_date = '".$rslt['week_start_date']."' AND um.week_end_date = '".$rslt['week_end_date']."' GROUP BY um.meal_id";
      $mo_items = $wpdb->get_results($mo_items_sql, ARRAY_A);
      $add_on_meals = 0;
      if(!empty($mo_items) && is_array($mo_items)){
        foreach ($mo_items as $mo_key => $mo_val) {
          $termArr = get_the_terms($mo_val['meal_id'],'menu-items-category');
          if(isset($termArr[0]->term_id) && !empty($termArr[0]->term_id)){
            $is_addon_cat = get_term_meta($termArr[0]->term_id,'_is_addon',true);
          }
          if(!empty($is_addon_cat)){
            $add_on_meals = $add_on_meals + $mo_val['meal_cnt'];
          }
        }
      }
    }
?>
<link rel='stylesheet' href='<?php echo site_url(); ?>/wp-content/plugins/woocommerce/assets/css/admin.css' type='text/css' media='all' />
<div class="wrap">
  <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-1">
      <div id="postbox-container-2" class="postbox-container">
        <div id="normal-sortables" class="meta-box-sortables ui-sortable">
          <div id="woocommerce-order-data" class="postbox ">
            <h2 class="hndle ui-sortable-handle"><span>Order data</span></h2>
            <div class="inside">
              <div class="panel-wrap woocommerce">
                <div id="order_data" class="panel">
                  <h2>Order #<?php echo $umo_id; ?> details</h2>
                  <p class="order_number">Payment via <?php echo $payment_source; ?> Card (Stripe) (<?php echo $trns_key; ?>) on <?php echo (!empty($umo_date))?date('F j, Y',strtotime($umo_date)):''; ?>. Customer IP: <?php echo $umo_ip_addr; ?></p>
                  <div class="order_data_column_container">
                    <div class="order_data_column">
                      <h3>General Details</h3>
                      <p class="form-field form-field-wide"><label for="order_date"><strong>Order date:</strong></label>
                        <?php echo (!empty($umo_date))?date('Y-m-d',strtotime($umo_date)):''; ?>
                      </p>
                      <p class="form-field form-field-wide"><label for="order_status"><strong>Order status:</strong></label>
                        <?php echo $wko_status; ?>
                      </p>
                      <p class="form-field form-field-wide"><label for="order_status"><strong>Payment status:</strong></label>
                        <?php echo $umo_status; ?>
                      </p>
                      <p class="form-field form-field-wide">
                        <label for="customer_user"><strong>Customer:</strong></label><a href="<?php echo site_url('/wp-admin/user-edit.php?user_id='.$usr_id) ?>">
                        <?php echo $f_name.' '.$l_name.' (#'.$usr_id.' - '.$user_email.')'; ?></a>
                      </p>
                    </div>
                    <div class="order_data_column">
                      <h3>Delivery details</h3>
                      <div class="address">
                        <p><strong>Address:</strong><?php echo $pmpro_bfirstname.' '.$pmpro_blastname; ?><br><?php echo $pmpro_baddress1; ?><br><?php echo $pmpro_bcity; ?> <?php echo (!empty($pmpro_bstate))?', '.$pmpro_bstate:''; ?> <?php echo (!empty($pmpro_bzipcode))?' '.$pmpro_bzipcode:''; ?><br><?php echo $pmpro_bcountry; ?></p>
                        <p><strong>Email address:</strong> <?php echo $pmpro_bemail; ?></p>
                        <p><strong>Phone:</strong> <?php echo $pmpro_bphone; ?></p>
                      </div>
                    </div>
                    <div class="order_data_column">
                      <h3>Delivery notes</h3>
                      <?php echo $umo_delivery_notes; ?>
                      <h3>Customer Plan</h3>
                      <div class="address">
                        <?php if(!empty($mem_grp_name)){ ?>
                          <p><span style="border-radius: 6px; border: 1px solid <?php echo $mem_grp_color; ?>;padding: 5px;color:<?php echo $mem_grp_color; ?>;"><?php echo $mem_grp_name; ?></span></p>
                        <?php } ?>
                        <?php
                        if(!empty($meal_per_day) && !empty($days_per_week)){ 
                          $main_meals = $meal_per_day * $days_per_week;
                          $total = $main_meals + $add_on_meals;
                        ?>
                          <table class="customer-plan-detail">
                            <tr>
                              <td style="text-align: right;">Meals per day :</td>
                              <td><?php echo $meal_per_day; ?></td>
                            </tr>
                            <tr>
                              <td style="text-align: right;">Days per week :</td>
                              <td><?php echo $days_per_week; ?></td>
                            </tr>
                            <?php if(!empty($add_on_meals)){ ?>
                            <tr>
                              <td style="text-align: right;">Main Meals :</td>
                              <td><?php echo $main_meals; ?></td>
                            </tr>
                            <tr>
                              <td style="text-align: right;">Add-on Meals :</td>
                              <td><?php echo $add_on_meals; ?></td>
                            </tr>
                            <?php } ?>
                            <tr>
                              <td style="text-align: right;">Total :</td>
                              <td><?php echo $total; ?></td>
                            </tr>
                          </table>
                        <?php }
                        ?>
                      </div>
                    </div>
                  </div>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
          </div>
          <?php
            $um_table = $wpdb->prefix.'users_meals';
            $post_table = $wpdb->prefix.'posts';
            if(isset($rslt['week_start_date']) && !empty($rslt['week_start_date']) && isset($rslt['week_end_date']) && !empty($rslt['week_end_date'])){
              $mo_items_sql = "SELECT um.id, um.user_id, um.meal_id, meal.post_title as meal_name, COUNT(meal_id) as meal_cnt from $um_table as um join $post_table as meal on meal.ID = um.meal_id WHERE um.user_id = ".$rslt['user_id']." AND um.week_start_date = '".$rslt['week_start_date']."' AND um.week_end_date = '".$rslt['week_end_date']."' GROUP BY um.meal_id";
              $mo_items = $wpdb->get_results($mo_items_sql, ARRAY_A);
              if(!empty($mo_items) && count($mo_items) > 0){
                $oml_data = (isset($rslt['meal_data']) && !empty($rslt['meal_data']))?unserialize($rslt['meal_data']):'';
          ?>
          <div id="woocommerce-order-items" class="postbox ">
            <h2 class="hndle ui-sortable-handle"><span>Items</span></h2>
            <div class="inside meal-order-detail">
              <div class="woocommerce_order_items_wrapper wc-order-items-editable">
                <table cellpadding="0" cellspacing="0" class="woocommerce_order_items order-meals-list">
                  <thead>
                    <tr>
                      <th colspan="2">Item</th>
                      <th colspan="2">Qty</th>
                    </tr>
                  </thead>
                  <tbody id="order_line_items">
                    <?php 
                      $meal_count = 0;
                      foreach ($mo_items as $moi_key => $moi_val) {
                        if(isset($moi_val['meal_cnt']) && !empty($moi_val['meal_cnt'])){
                          $meal_count = $meal_count + $moi_val['meal_cnt'];
                        }
                        $oid = (isset($moi_val['id']) && !empty($moi_val['id']))?$moi_val['id']:'';
                        $meal_name = $card_image_src = '';
                        if(isset($oml_data[$moi_val['meal_id']]['meal_name']) && !empty($oml_data[$moi_val['meal_id']]['meal_name'])){
                          $meal_name = $oml_data[$moi_val['meal_id']]['meal_name'];
                        }
                        if($meal_name == ''){
                          $meal_name = (isset($moi_val['meal_name']) && !empty($moi_val['meal_name']))?$moi_val['meal_name']:'';  
                        }
                        if(isset($oml_data[$moi_val['meal_id']]['thumbnail_id']) && !empty($oml_data[$moi_val['meal_id']]['thumbnail_id'])){
                          $thumb_image_arr = wp_get_attachment_image_src($oml_data[$moi_val['meal_id']]['thumbnail_id'],'tc-thumbnail-image');
                          $card_image_src = (isset($thumb_image_arr[0]) && !empty($thumb_image_arr[0]))?$thumb_image_arr[0]:'';
                        }
                        if($card_image_src == ''){
                          $card_image_src = get_the_post_thumbnail_url($moi_val['meal_id'],'tc-thumbnail-image');
                        }
                    ?>
                      <tr class="item" data-order_item_id="<?php echo $oid; ?>">
                        <td class="thumb" width="10%">
                          <div class="wc-order-item-thumbnail">
                            <img width="64" height="64" src="<?php echo (!empty($card_image_src))?$card_image_src:TOUGHCOOKIES_URL."images/no_image_found.png"; ?>" alt="<?php echo $meal_name; ?>" title="<?php echo $meal_name; ?>">
                          </div>
                        </td>
                        <td width="80%"><?php echo $meal_name; ?></td>
                        <td width="20%">
                          <div class="view"><?php echo (isset($moi_val['meal_cnt']) && !empty($moi_val['meal_cnt']))?$moi_val['meal_cnt']:''; ?></div>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <div class="wc-order-data-row wc-order-totals-items wc-order-items-editable">
 
                <table class="wc-order-totals meal-order-totals">
                  <tbody>
                    <tr>
                      <td class="label"><strong>Total meals:</strong></td>
                      <td width="1%"></td>
                      <td style="font-size: 18px;font-weight: bold;"><?php echo (!empty($meal_count))?$meal_count:0; ?></td>
                    </tr>
                    <tr class="subtotal_row">
                      <td class="label">Sub total:</td>
                      <td width="1%"></td>
                      <td class="subtotal">$<?php echo $sub_tot_amt; ?></td>
                    </tr>
                    <tr class="tax_row">
                      <td class="label">Tax:</td>
                      <td width="1%"></td>
                      <td class="tax">$<?php echo $tax_amount; ?></td>
                    </tr>
                    <?php if(!empty($addon_meals_amount)){ ?>
                      <tr>
                        <td class="label">Add-on Meals:</td>
                        <td width="1%"></td>
                        <td class="total">$<?php echo $addon_meals_amount; ?></td>
                      </tr>
                    <?php }
                    if(!empty($coupon_amount)){ ?>
                      <tr class="discount_row">
                        <td class="label">Coupon (<?php echo $coupon_code; ?>):</td>
                        <td width="1%"></td>
                        <td class="total">-$<?php echo $coupon_amount; ?></td>
                      </tr>
                    <?php } 
                    if($used_credit_points > 0){
                    ?>
                    <tr class="discount_row">
                      <td class="label">Credit Balance:</td>
                      <td width="1%"></td>
                      <td class="tax">-$<?php echo $used_credit_points; ?></td>
                    </tr>
                  <?php } ?>
                    <tr>
                      <td class="label">Total:</td>
                      <td width="1%"></td>
                      <td class="total">
                        <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $total_amount; ?></span>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div class="clear"></div>
              </div>
            </div>
          </div>
          <?php
            }
          }
          ?>
        </div>
        <div id="advanced-sortables" class="meta-box-sortables ui-sortable"></div>
      </div>
    </div>
    <br class="clear">
  </div>
</div>
<?php
  }
}

/*
* Method: Function for get all locked meals orders
*/

function get_locked_meals_orders($filter_dates, $params = array()){
  if(current_user_can('administrator') && !empty($filter_dates)) {
    global $wpdb;
    date_default_timezone_set("America/New_York");
    $curr_wk_sunday = current($filter_dates);
    $curr_wk_order_sunday = date('Y-m-d', strtotime($curr_wk_sunday));
    $curr_wk_order_saturday = date('Y-m-d', strtotime('next saturday', strtotime($curr_wk_order_sunday)));
    $umo_tbl = $wpdb->prefix.'users_meals_orders';
    $u_tbl = $wpdb->prefix.'users';
    $lmo_sql = "SELECT umo.*, usr.ID as usr_id, usr.user_email FROM $umo_tbl as umo JOIN $u_tbl as usr on usr.ID = umo.user_id WHERE umo.status = 'paid' AND umo.payment_status = 1";
    if(isset($params['whr']['wk_start_date']) && !empty($params['whr']['wk_start_date']) && isset($params['whr']['wk_end_date']) && !empty($params['whr']['wk_end_date'])){
      $lmo_sql .= " AND umo.week_start_date = '".$params['whr']['wk_start_date']."' AND umo.week_end_date = '".$params['whr']['wk_end_date']."'";
    }else{
      $lmo_sql .= " AND umo.week_end_date <= '$curr_wk_order_saturday'";
    }
    if(isset($params['whr']['user_id']) && !empty($params['whr']['user_id'])){
      $lmo_sql .= " AND umo.user_id = ".$params['whr']['user_id'];
    }
    if(isset($params['whr']['plan_id']) && !empty($params['whr']['plan_id'])){
      $lmo_sql .= " AND umo.meal_plan_group = ".$params['whr']['plan_id'];
    }
    $lmo_sql .= " order by umo.id DESC";
    if(isset($params['lmt_end']) && $params['lmt_end'] > 0){
      $lmo_sql .= " limit ".$params['lmt_start'].",".$params['lmt_end'];
    }
    $lmo_data = $wpdb->get_results($lmo_sql, ARRAY_A);
    return $lmo_data;
    $tmp_lmo_data = array();
    if(isset($lmo_data) && !empty($lmo_data) && count($lmo_data) > 0){
      foreach ($lmo_data as $lmod_key => $lmod_val) {
        $subscription_account_status = get_user_meta($lmod_val['usr_id'], 'subscription_account_status', true );
        if($subscription_account_status != 2){
          $tmp_lmo_data[] = $lmod_val;
        }
      }
    }
    return $tmp_lmo_data;
  }
}

/*
* Method: Function for get all locked meals for order
*/

function get_all_locked_meals($params = array()){
  if(current_user_can('administrator') && !empty($params)) {
    global $wpdb;
    date_default_timezone_set("America/New_York");
    $umo_tbl = $wpdb->prefix.'users_meals_orders';
    $um_tbl = $wpdb->prefix.'users_meals';
    $u_tbl = $wpdb->prefix.'users';
    $post_tbl = $wpdb->prefix.'posts';
    $lm_sql = "SELECT umo.*, usr.ID as usr_id, meal.ID as meal_id, meal.post_title as meal_name, usr.user_email, um.meal_date, umo.week_start_date, umo.week_end_date, um.plan_group_id FROM $umo_tbl as umo JOIN $um_tbl as um on (um.user_id = umo.user_id AND um.user_id = '".$params['user_id']."' AND um.week_start_date = '".$params['week_start_date']."' AND um.week_end_date = '".$params['week_end_date']."') JOIN $u_tbl as usr on usr.ID = umo.user_id join $post_tbl as meal on meal.ID = um.meal_id WHERE umo.status = 'paid'";
    return $wpdb->get_results($lm_sql, ARRAY_A);
  }
}

/*
* Method: function for get user's plan detail
*/

function get_user_plan_by_user_id($user_id){
  global $wpdb;
  date_default_timezone_set("America/New_York");
  $plan_detail = array();
  $user_membership_plan = pmpro_getMembershipLevelsForUser($user_id);
  if(isset($user_membership_plan[0]->id) && $user_membership_plan[0]->id > 0){
    $plan_id = $user_membership_plan[0]->id;
    $plan_initial_payment = $user_membership_plan[0]->initial_payment;
    $sdate = date("Y-m-d", strtotime($user_membership_plan[0]->start_date));
    $edate = date('Y-m-d', strtotime('next sunday', strtotime($user_membership_plan[0]->start_date)));
    $wh = "`user_id` = $user_id AND `start_date` = '$sdate' AND `end_date` = '$edate'";
    $_exist_plan = check_record_exist($wpdb->prefix.'users_membership_plans',$wh);
    if(isset($_exist_plan['membership_plan_id']) && !empty($_exist_plan['membership_plan_id'])){
      $plan_id = $_exist_plan['membership_plan_id'];
    }
  }
  $plan_detail['mem_group_id'] = $wpdb->get_var("SELECT meta_value from $wpdb->pmpro_membership_levelmeta WHERE pmpro_membership_level_id = $plan_id AND meta_key = '_membership_group'");
  $plan_detail['plan_id'] = $plan_id;
  $plan_detail['initial_payment'] = $user_membership_plan[0]->initial_payment;
  $plan_detail['billing_amount'] = $user_membership_plan[0]->billing_amount;
  return $plan_detail;
}

/*
* Method: function for get list of all filtered dates
*/

function get_filter_dates(){
  date_default_timezone_set("America/New_York");
  $dates = array();
  $loop_start_date = strtotime('2019-01-06');
  $loop_end_date = time();
  while($loop_start_date <= $loop_end_date) {
    $meal_delivered_date = date('Y-m-d', strtotime('next sunday', $loop_start_date));
    $dates[strtotime($meal_delivered_date)] = $meal_delivered_date;
    $loop_start_date = strtotime('+7 day', $loop_start_date);
  }
  $meal_delivered_date = date('Y-m-d', strtotime('next sunday', $loop_start_date));
  if(date("N", $loop_end_date) > 3){
    $dates[strtotime($meal_delivered_date)] = $meal_delivered_date;
  }elseif(date("N", $loop_end_date) == 3 && date('H') >= 12){
    $dates[strtotime($meal_delivered_date)] = $meal_delivered_date;
  }
  return array_reverse($dates);
}

/*
* Method: function for get excel column name by given excel column index
*/

function get_excel_col_name_by_index($col_index){
  $alphabet = range('A', 'Z');
  $alpha_flip = array_flip($alphabet);
  if($col_index <= 25){
    return $alphabet[$col_index];
  }
  elseif($col_index > 25){
    $dividend = ($col_index + 1);
    $alpha = '';
    $modulo;
    while ($dividend > 0){
      $modulo = ($dividend - 1) % 26;
      $alpha = $alphabet[$modulo] . $alpha;
      $dividend = floor((($dividend - $modulo) / 26));
    } 
    return $alpha;
  }
}

/*
* Method: function for get excel column index by given excel column name
*/

function get_excel_col_index_by_name($data){
  $alphabet = range('A', 'Z');
  $alpha_flip = array_flip($alphabet);
  $return_value = -1;
  $length = strlen($data);
  for ($i = 0; $i < $length; $i++) {
      $return_value +=
          ($alpha_flip[$data[$i]] + 1) * pow(26, ($length - $i - 1));
  }
  return $return_value;
}

/*
* Method: function for get count of all nested sub array of given array
*/

function count_nested_array_keys($a) {
  $i = 0;
  foreach ($a as $key => $value) {
    if (is_array($value)) {
      $i += count(array_filter($value));
    }
  }
  return $i;
}

/*
* Method: function for sort multidimensional array
*/

function sortArrayByKeyAsc($_params){
  if(is_array($_params)){
    uksort($_params, 'strnatcmp');
    foreach ($_params as $key => $value){
      if(is_array($value)){
        $_params[$key] = sortArrayByKeyAsc($value);
      }
    }
  }
  return $_params;
}

/*
* Method: function for get converted ingredient unit value by given unit paid for 1
*/

function ingredient_units_us_measurements($unit_pair){
  $ingredient_units_us_measurements_arr = array(
    'tsp-cup'=>0.02083,
    'tbsp-cup'=>0.0625,
    'oz-lbs'=>0.0625,
    'tsp-floz'=>0.1667,
    'tbsp-floz'=>0.5,
    'cup-floz'=>8
  );
  return (isset($ingredient_units_us_measurements_arr[$unit_pair]))?$ingredient_units_us_measurements_arr[$unit_pair]:1;
}

function ingredient_group_units_convert_to($ing_unit){
  $ingredient_group_units_convert_to_arr = array(
    'dry'=>'cup',
    'weight'=>'lbs',
    'liquid'=>'floz'
  );
  return $ingredient_group_units_convert_to_arr[$ing_unit];
}

function get_meal_ingredients($meal_ids){
  if(isset($meal_ids) && count($meal_ids) > 0){
    global $wpdb;
    date_default_timezone_set("America/New_York");
    if(count($meal_ids) > 1){
      $ml_ing_sql = "SELECT p.ID as ingredient_id, p.post_title as ingredient_name, mi.meal_id, mi.quantity, mi.fraction_qty, mi.unit_id, mi.unit_abbreviation, pm1.meta_value as ingredient_grp, pm2.meta_value as ingredient_cat FROM ".$wpdb->prefix."meals_ingredients as mi JOIN ".$wpdb->prefix."posts as p ON (p.ID = mi.ingredient_id AND p.post_type = 'ingredients' AND p.post_status = 'publish') JOIN ".$wpdb->prefix."postmeta as pm1 ON (pm1.post_id = p.ID AND pm1.meta_key ='_group') JOIN ".$wpdb->prefix."postmeta as pm2 ON (pm2.post_id = p.ID AND pm2.meta_key ='_category') WHERE mi.meal_id IN (". implode(',', $meal_ids).")";
      $ml_ingredients = $wpdb->get_results($ml_ing_sql, ARRAY_A);
    }else{
      $ml_ing_sql = "SELECT p.ID as ingredient_id, p.post_title as ingredient_name, mi.meal_id, mi.quantity, mi.fraction_qty, mi.unit_id, mi.unit_abbreviation, pm1.meta_value as ingredient_grp, pm2.meta_value as ingredient_cat FROM ".$wpdb->prefix."meals_ingredients as mi JOIN ".$wpdb->prefix."posts as p ON (p.ID = mi.ingredient_id AND p.post_type = 'ingredients' AND p.post_status = 'publish') JOIN ".$wpdb->prefix."postmeta as pm1 ON (pm1.post_id = p.ID AND pm1.meta_key ='_group') JOIN ".$wpdb->prefix."postmeta as pm2 ON (pm2.post_id = p.ID AND pm2.meta_key ='_category') WHERE mi.meal_id =".$meal_ids;
      $ml_ingredients = $wpdb->get_results($ml_ing_sql, ARRAY_A);
    }
    return $ml_ingredients;
  }
  return false;
}

function sortArrayByValueAsc (&$array, $key) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
}

/*
* Method: function for get fractions list
*/

function get_fractional_part($decimal_num){
  $fractional_part = array("0.0625"=>"1⁄16","0.125"=>"⅛","0.25"=>"¼","0.3334"=>"⅓","0.5"=>"½","0.6667"=>"⅔","0.75"=>"¾");
  return (isset($fractional_part[$decimal_num]))?$fractional_part[$decimal_num]:false;
}

/*
* Method: function for manage user's next billing amount, account credits and subscription plan on strip website
*/

function manage_users_next_billing_amt_acc_credits_subscription_plan(){
  //$users = get_users(array('orderby' => 'ID','order' => 'ASC'));
  $wp_user_query = new WP_User_Query(array('role' => 'subscriber'));
  $users = $wp_user_query->get_results();
  if(isset($users) && !empty($users) && is_array($users)){
    global $wpdb;
    date_default_timezone_set("America/New_York");
    $tax_rate = get_option('tax_rate');
    $tax_rate_id = get_tax_rate_id();
    $pmpro_stripe_secretkey = get_option('pmpro_stripe_secretkey');
    foreach ($users as $ukey => $user) {
    //if($user->ID == 1152){
      $user_role = $user->roles[0];
      if(isset($user_role) && !empty($user_role) && strtolower($user_role) != 'administrator'){
        $user_levels = pmpro_getMembershipLevelsForUser($user->ID,true);
        if(isset($user_levels) && !empty($user_levels) && count($user_levels)>0){
          foreach ($user_levels as $ul_key => $ul_val) {
            $subs_pln_start_date = ((isset($ul_val->status) && $ul_val->status == 'active') && (isset($ul_val->start_date) && !empty($ul_val->start_date)))?$ul_val->start_date:'';
          }
          $um_age = 0;
          if(isset($subs_pln_start_date) && !empty($subs_pln_start_date)){
            $datediff = time() - strtotime($subs_pln_start_date);
            $um_age = round($datediff / (60 * 60 * 24));
            $is_charge = 0;
            //echo $user->ID.' => $um_age -> '.$um_age.' => '.$subs_pln_start_date.'<br>';
            if($um_age >= 1){
              //echo ' >= 1 <br>';
              $is_charge = 1;
            }else{
              //echo ' < 1 <br>';
              $f_ord_tm = strtolower(date('H:i:s',strtotime($subs_pln_start_date)));
              //echo $f_ord_tm.' <br>';
              if($f_ord_tm <= '11:00:00'){
                $is_charge = 1;
              }
            }
            //echo '<br> $is_charge -> '.$is_charge.'<br>';
            if($is_charge == 1){
              $curr_wk_sunday = date('Y-m-d',strtotime('next sunday'));
              $curr_wk_saturday = date('Y-m-d', strtotime('next saturday', strtotime($curr_wk_sunday)));
              $affiliate_id = affwp_get_affiliate_id($user->ID);
              $available_credit_points = 0;
              if($affiliate_id > 0){
                $affiliate_data = affwp_get_affiliate($affiliate_id);
                //$total_earnings = 0;
                //$available_credit_points = (isset($affiliate_data->earnings) && $affiliate_data->earnings > 0)?$available_credit_points+$affiliate_data->earnings:0;
                $available_credit_points = (isset($affiliate_data->unpaid_earnings) && $affiliate_data->unpaid_earnings > 0)?$available_credit_points+$affiliate_data->unpaid_earnings:$available_credit_points;
                //$available_credit_points = get_user_total_credits(array('affiliate_id'=>$affiliate_id));
              }
              $user_meta = get_user_meta($user->ID);
              $diet_preferences = get_user_meta($user->ID,'diet_preferences',true);
              $preferences_meals = get_user_meta($user->ID,'preferences_meals',true);
        
        $pmpro_AccountNumber = get_user_meta($user->ID,'pmpro_AccountNumber',true);
        $pmpro_CardType = get_user_meta($user->ID,'pmpro_CardType',true);
        $pmpro_ExpirationMonth = get_user_meta($user->ID,'pmpro_ExpirationMonth',true);
        $pmpro_ExpirationYear = get_user_meta($user->ID,'pmpro_ExpirationYear',true);
        
              $prefer_categories = (!empty($preferences_meals))?unserialize($preferences_meals):'';
              $membership_plan_id = (isset($user_meta['pmpro_membership_id'][0]) && !empty($user_meta['pmpro_membership_id'][0]))?$user_meta['pmpro_membership_id'][0]:'';
              $slct_days = (isset($user_meta['slct_days'][0]) && !empty($user_meta['slct_days'][0]))?$user_meta['slct_days'][0]:'';
              $slct_days_range = (isset($user_meta['slct_days_range'][0]) && !empty($user_meta['slct_days_range'][0]))?$user_meta['slct_days_range'][0]:'';
              //get user's active plan
              $wh = "`user_id` = $user->ID AND `status` = 1";
              $_exist_plan = check_record_exist($wpdb->prefix.'users_membership_plans',$wh);
              $pickup_location_id = (isset($_exist_plan['pickup_location']) && !empty($_exist_plan['pickup_location']))?$_exist_plan['pickup_location']:0;
              $membership_plan_id = (isset($_exist_plan['membership_plan_id']) && !empty($_exist_plan['membership_plan_id']))?$_exist_plan['membership_plan_id']:$membership_plan_id;
              $slct_days = (isset($_exist_plan['selected_days']) && !empty($_exist_plan['selected_days']))?$_exist_plan['selected_days']:$slct_days;
              $slct_days_range = (isset($_exist_plan['selected_days_range']) && !empty($_exist_plan['selected_days_range']))?$_exist_plan['selected_days_range']:$slct_days_range;
              if(isset($membership_plan_id) && $membership_plan_id > 0){
                $stripe_customerid = (isset($user_meta['pmpro_stripe_customerid'][0]) && !empty($user_meta['pmpro_stripe_customerid'][0]))?$user_meta['pmpro_stripe_customerid'][0]:'';
                $subscription_transaction_id = (isset($user_meta['subscription_transaction_id'][0]) && !empty($user_meta['subscription_transaction_id'][0]))?$user_meta['subscription_transaction_id'][0]:'';
                $is_allergies = (isset($user_meta['is_allergies'][0]) && !empty($user_meta['is_allergies'][0]))?$user_meta['is_allergies'][0]:'';
                $allergies = (isset($user_meta['allergies'][0]) && !empty($user_meta['allergies'][0]))?unserialize($user_meta['allergies'][0]):'';
                $allergy_cost = 0;
                if($is_allergies == 'yes' && !empty($allergies[0])){
                  if($allergies[0] == 'gluten_dairy_free'){
                    $allergy_cost = 15;
                  }else{
                    $allergy_cost = 10;
                  }
                }
                $subscription_plan_coupon_data = (isset($user_meta['subscription_plan_coupon_data'][0]) && !empty($user_meta['subscription_plan_coupon_data'][0]))?unserialize($user_meta['subscription_plan_coupon_data'][0]):'';
                $wk_start_key = str_replace('-', '', $curr_wk_sunday);
                $wk_end_key = str_replace('-', '', $curr_wk_saturday);
                //check skip week status
                $user_wk_status = get_user_meta($user->ID,'user_weeks_status',true);
                $user_wk_status = (!empty($user_wk_status))?unserialize($user_wk_status):array();
                $usr_subscription_acc_status = get_user_meta($user->ID, 'subscription_account_status', true );
                $meal_status_sql = "SELECT status FROM wp_users_meals WHERE user_id = ".$user->ID." AND week_start_date = '".$curr_wk_sunday."' AND week_end_date = '".$curr_wk_saturday."' GROUP BY week_start_date, week_end_date";
                $meal_status_arr =  $wpdb->get_results($meal_status_sql, ARRAY_A);
                if(($usr_subscription_acc_status == 2) || (isset($meal_status_arr[0]['status']) && $meal_status_arr[0]['status'] == 4) || (isset($user_wk_status[$wk_start_key]['status']) && $user_wk_status[$wk_start_key]['status'] == 4)){
                  //upcoming week is skipped by user. Remove subscription plan on stripe website and stop billing
                  if(!empty($pmpro_stripe_secretkey) && !empty($subscription_transaction_id)){
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/subscriptions/".$subscription_transaction_id);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                    curl_setopt($ch, CURLOPT_USERPWD, $pmpro_stripe_secretkey . ":" . "");
                    json_decode(curl_exec($ch), true);
                    curl_close ($ch);
                  }
                }else{
                  //$te_date = date('Y-m-d H:i:s', strtotime('next thursday', strtotime($curr_wk_sunday)));
                  //$trial_end = strtotime($te_date."+ 12 hours");
                  //$trial_end = strtotime("+ 1 minutes");
                  $wh = "`user_id` = $user->ID AND `start_date` = '".$curr_wk_sunday."' AND `end_date` = '".$curr_wk_saturday."'";
                  $curr_wk_plan = check_record_exist($wpdb->prefix.'users_membership_plans',$wh);
                  $pickup_location_id = (isset($curr_wk_plan['pickup_location']) && !empty($curr_wk_plan['pickup_location']))?$curr_wk_plan['pickup_location']:$pickup_location_id;
                  if(!empty($curr_wk_plan)){
                    $total_bill_amt = $curr_wk_plan['billing_amount'];
                    $bill_amt = $curr_wk_plan['billing_amount'];
                    $ufwko_sql = "SELECT * FROM ".$wpdb->prefix."users_meals_orders WHERE user_id = ".$user->ID." AND week_start_date = '".$curr_wk_sunday."' AND week_end_date = '".$curr_wk_saturday."'";
                    $ufwho_data = $wpdb->get_row($ufwko_sql, ARRAY_A);
                    $ufwho_data_taxamount = 0;
                    if(!empty($ufwho_data)){
                      $total_bill_amt = $ufwho_data['total_bill_amount'];
                      $bill_amt = $ufwho_data['total_bill_amount'];
                      $ufwho_data_taxamount = $ufwho_data['tax_amount'];
                    }
                    $total_bill_amt = ($curr_wk_plan['addon_meals_amount'] > 0)?$total_bill_amt+$curr_wk_plan['addon_meals_amount']:$total_bill_amt;
                    $pln_delivery_fee = $curr_wk_plan['delivery_fee'];
                    $membership_plan_id = $curr_wk_plan['membership_plan_id'];
                    $slct_days = $curr_wk_plan['selected_days'];
                    $slct_days_range = $curr_wk_plan['selected_days_range'];
                    $ord_smry_data = array(
                      'user_id' => $user->ID,
                      'membership_plan_id' => $curr_wk_plan['membership_plan_id'],
                      'loked_wk_sunday' => $curr_wk_sunday,
                      'loked_wk_saturday' => $curr_wk_saturday,
                      'billing_amount' => $bill_amt,
                      'addon_meals_amount' => $curr_wk_plan['addon_meals_amount'],
                      'delivery_fee' => $curr_wk_plan['delivery_fee'],
                      'pickup_location' => $pickup_location_id,
                      'tax_amount'=> $ufwho_data_taxamount,
                      'pmpro_AccountNumber' => $pmpro_AccountNumber,
                      'pmpro_CardType' => $pmpro_CardType,
                      'pmpro_ExpirationMonth' => $pmpro_ExpirationMonth,
                      'pmpro_ExpirationYear' =>$pmpro_ExpirationYear
                    );
                  }else{
                    //user not customize their upcoming week plan. We will use user's active plan for current week
                    $mem_plan_detail = pmpro_getLevel($membership_plan_id);
                    if(isset($mem_plan_detail) && !empty($mem_plan_detail)){
                      $delivery_fee = get_pmpro_membership_level_meta($membership_plan_id,'_delivery_fee',true);
                      $delivery_fee = (isset($delivery_fee) && !empty($delivery_fee))?$delivery_fee:0;
                      $pln_delivery_fee = $delivery_fee;
                      $initial_price = (!empty($mem_plan_detail->initial_payment))?$mem_plan_detail->initial_payment:0;
                      $weekly_initial_amount = $initial_price;
                      if(isset($initial_price) && $initial_price>0){
                        $weekly_initial_amount = $initial_price*$slct_days;
                      }
                      $billing_amount = (!empty($mem_plan_detail->billing_amount))?$mem_plan_detail->billing_amount:0;
                      $weekly_billing_amount = $billing_amount;
                      if(isset($billing_amount) && $billing_amount>0){
                        $weekly_billing_amount = $billing_amount*$slct_days;
                      }
                      if(isset($allergy_cost) && $allergy_cost>0){
                        $weekly_initial_amount = $weekly_initial_amount+$allergy_cost;
                        $weekly_billing_amount = $weekly_billing_amount+$allergy_cost;
                      }
                      $initial_coupon_amt = $billing_coupon_amt = $applied_coupon_id = 0;
                      if(isset($subscription_plan_coupon_data) && !empty($subscription_plan_coupon_data)){
                        if(isset($subscription_plan_coupon_data['recurring_billing_list']) && in_array($wk_start_key.'-'.$wk_end_key, $subscription_plan_coupon_data['recurring_billing_list'])){//apply coupon rule 1
                          if($subscription_plan_coupon_data['coupon_type'] == 'fixed_cart'){
                            $initial_coupon_amt = $subscription_plan_coupon_data['amount'];
                            $billing_coupon_amt = $subscription_plan_coupon_data['amount'];
                          }elseif($subscription_plan_coupon_data['amount'] > 0){
                            $initial_coupon_amt = ($weekly_initial_amount*$subscription_plan_coupon_data['amount'])/100;
                            $billing_coupon_amt = ($weekly_billing_amount*$subscription_plan_coupon_data['amount'])/100;
                          }
                          $applied_coupon_id = $subscription_plan_coupon_data['coupon_id'];
                        }elseif(isset($subscription_plan_coupon_data['rule_2']['rule_2_recurring_billing_list']) && in_array($wk_start_key.'-'.$wk_end_key, $subscription_plan_coupon_data['rule_2']['rule_2_recurring_billing_list'])){//apply coupon rule 2
                          if($subscription_plan_coupon_data['rule_2']['rule_2_coupon_type'] == 'fixed_cart'){
                            $initial_coupon_amt = $subscription_plan_coupon_data['rule_2']['rule_2_coupon_amount'];
                            $billing_coupon_amt = $subscription_plan_coupon_data['rule_2']['rule_2_coupon_amount'];
                          }elseif($subscription_plan_coupon_data['rule_2']['rule_2_coupon_amount'] > 0){
                            $initial_coupon_amt = ($weekly_initial_amount*$subscription_plan_coupon_data['rule_2']['rule_2_coupon_amount'])/100;
                            $billing_coupon_amt = ($weekly_billing_amount*$subscription_plan_coupon_data['rule_2']['rule_2_coupon_amount'])/100;
                          }
                        }
                      }
                      if($weekly_initial_amount > $initial_coupon_amt){
                        $weekly_initial_amount = $weekly_initial_amount - $initial_coupon_amt;
                      }else{
                        $weekly_initial_amount = 0;
                      }
                      if($weekly_billing_amount > $billing_coupon_amt){
                        $weekly_billing_amount = $weekly_billing_amount - $billing_coupon_amt;
                      }else{
                        $weekly_billing_amount = 0;
                      }
                      if(isset($tax_rate) && $tax_rate>0){
                        $weekly_ini_tax_amt = ($weekly_initial_amount*$tax_rate)/100;
                        $weekly_initial_amount = $weekly_initial_amount+$weekly_ini_tax_amt;
                        $weekly_bill_tax_amt = ($weekly_billing_amount*$tax_rate)/100;
                        $weekly_billing_amount = $weekly_billing_amount+$weekly_bill_tax_amt;
                      }
                      if(isset($delivery_fee) && $delivery_fee>0){
                        $weekly_initial_amount = $weekly_initial_amount+$delivery_fee;
                        $weekly_billing_amount = $weekly_billing_amount+$delivery_fee;
                      }
                      $total_bill_amt = round($weekly_billing_amount,2);
                      $wk_initial_payment = round($weekly_initial_amount,2);
                    }
                    $ord_smry_data = array(
                      'user_id' => $user->ID,
                      'membership_plan_id' => $membership_plan_id,
                      'loked_wk_sunday' => $curr_wk_sunday,
                      'loked_wk_saturday' => $curr_wk_saturday,
                      'billing_amount' => $total_bill_amt,
                      'addon_meals_amount' => 0,
                      'delivery_fee' => $delivery_fee,
                      'pickup_location' => $pickup_location_id,
                      'tax_amount'=> (isset($weekly_bill_tax_amt) && $weekly_bill_tax_amt > 0)?round($weekly_bill_tax_amt,2):0,
                      'pmpro_AccountNumber' => $pmpro_AccountNumber,
                      'pmpro_CardType' => $pmpro_CardType,
                      'pmpro_ExpirationMonth' => $pmpro_ExpirationMonth,
                      'pmpro_ExpirationYear' =>$pmpro_ExpirationYear
                    );
                  }
                  $remaining_credit_points = 0;
                  if($available_credit_points > 0){
                    if($total_bill_amt >= $available_credit_points){
                      $total_bill_amt = $total_bill_amt - $available_credit_points;
                    }else{
                      $remaining_credit_points = $available_credit_points - $total_bill_amt;
                      $total_bill_amt = 0;
                    }
                  }
                  $total_bill_amt = number_format($total_bill_amt, 2, '.', '');
                  $stripe_pln_amt = str_replace('.', '', $total_bill_amt);
                  //$plan_sql = "SELECT pml.id, pml.name, pml_meta1.meta_value as membership_group FROM $wpdb->pmpro_membership_levels pml JOIN $wpdb->pmpro_membership_levelmeta pml_meta1 ON (pml_meta1.pmpro_membership_level_id = pml.id AND pml_meta1.meta_key = '_membership_group') WHERE pml.id = ".$membership_plan_id;
                  $plan_sql = "SELECT pml.id, pml.name, pml.initial_payment, pml.billing_amount, pml.cycle_number, pml.cycle_period, pml.trial_amount, pml.trial_limit, pml_meta1.meta_value as membership_group, pml_meta2.meta_value as meal_per_day, pml_meta3.meta_value as delivery_fee FROM $wpdb->pmpro_membership_levels pml JOIN $wpdb->pmpro_membership_levelmeta pml_meta1 ON (pml_meta1.pmpro_membership_level_id = pml.id AND pml_meta1.meta_key = '_membership_group') JOIN $wpdb->pmpro_membership_levelmeta pml_meta2 ON (pml_meta2.pmpro_membership_level_id = pml.id AND pml_meta2.meta_key = '_meal_per_day') LEFT JOIN $wpdb->pmpro_membership_levelmeta pml_meta3 ON (pml_meta3.pmpro_membership_level_id = pml.id AND pml_meta3.meta_key = '_delivery_fee') WHERE pml.id = ".$membership_plan_id;
                  $plan_data = $wpdb->get_row($plan_sql, ARRAY_A);
                  if(isset($plan_data) && !empty($plan_data)){
                    if(isset($plan_data['membership_group']) && !empty($plan_data['membership_group'])){
                      $mg_term = get_term($plan_data['membership_group'],'membershipgroup');
                      $grp_name = (isset($mg_term->name) && !empty($mg_term->name))?$mg_term->name:'tc-product';
                      $pln_name = (isset($plan_data['name']) && !empty($plan_data['name']))?$plan_data['name']:'tc-plan';
                      if(!empty($pmpro_stripe_secretkey)){
                        //create product, plan, subscription on strip website
                        $ch = curl_init();
                        // add new product
                        curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/products");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, "name=".$grp_name."&type=service");
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_USERPWD, $pmpro_stripe_secretkey . ":" . "");
                        $headers = array();
                        $headers[] = "Content-Type: application/x-www-form-urlencoded";
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        $product_rslt = curl_exec($ch);
                        $product_rslt = json_decode($product_rslt, true);
                        if(isset($product_rslt['id']) && !empty($product_rslt['id'])){
                          curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/plans");
                          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                          curl_setopt($ch, CURLOPT_POSTFIELDS, "currency=usd&interval=week&product=".$product_rslt['id']."&nickname=".$pln_name."&amount=".$stripe_pln_amt);
                          curl_setopt($ch, CURLOPT_POST, 1);
                          curl_setopt($ch, CURLOPT_USERPWD, $pmpro_stripe_secretkey . ":" . "");
                          $headers = array();
                          $headers[] = "Content-Type: application/x-www-form-urlencoded";
                          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                          $pln_result = curl_exec($ch);
                          $pln_rslt = json_decode($pln_result, true);
                          if(isset($pln_rslt['id']) && !empty($pln_rslt['id'])){
                            //create subscription
                            curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/subscriptions");
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, "customer=".$stripe_customerid."&items[0][plan]=".$pln_rslt['id']."&cancel_at_period_end=true");
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_USERPWD, $pmpro_stripe_secretkey . ":" . "");
                            $headers = array();
                            $headers[] = "Content-Type: application/x-www-form-urlencoded";
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            $subscription_rslt = curl_exec($ch);
                            $subscription_rslt = json_decode($subscription_rslt, true);
                          }
                        }
                        curl_close ($ch);
                      }
                    }
                  }
                  if(isset($subscription_rslt['id']) && !empty($subscription_rslt['id'])){
                    //update user's account credits
                    if($available_credit_points > 0){
                      $credit_points_data = array(
                        //'earnings' => round(($available_credit_points - $remaining_credit_points), 2),
                        'unpaid_earnings' => round($remaining_credit_points, 2)
                      );
                      $wpdb->update($wpdb->prefix.'affiliate_wp_affiliates', $credit_points_data, array('user_id' => $user->ID,'affiliate_id' => $affiliate_id));
                    }
                    //update user's current membership plan detail
                    $curr_active_wh = "`user_id` = $user->ID AND `status` = 1";
                    $curr_active_plan = check_record_exist($wpdb->prefix.'users_membership_plans',$curr_active_wh);
                    if(isset($curr_active_plan['id']) && !empty($curr_active_plan['id'])){
                      //update current week plan staus to expired
                      $curr_membership_plan_data = array(
                        'status' => 3,
                        'modify_date'=> date('Y-m-d H:i:s')
                      );
                      $wpdb->update($wpdb->prefix.'users_membership_plans', $curr_membership_plan_data, array('user_id' => $user->ID,'id' => $curr_active_plan['id']));
                    }
                    if(!empty($curr_wk_plan)){
                      //update account credit and next week plan data
                      $user_membership_plan_data = array(
                        'used_credit_points' => $available_credit_points - $remaining_credit_points,
                        //'billing_amount' => $total_bill_amt,
                        'delivery_fee' => (isset($pln_delivery_fee))?$pln_delivery_fee:0,
                        'week_original_initial_payment' => $curr_wk_plan['initial_payment'],
                        'week_original_billing_payment' => $curr_wk_plan['billing_amount'],
                        'status' => 1,
                        'modify_date'=> date('Y-m-d H:i:s')
                      );
                      $wpdb->update($wpdb->prefix.'users_membership_plans', $user_membership_plan_data, array('user_id' => $user->ID,'start_date' => $curr_wk_sunday, 'end_date' => $curr_wk_saturday));
                    }else{
                      //create new record
                      $user_membership_plan_data = array(
                        'user_id' => $user->ID,
                        'membership_plan_id' => $membership_plan_id,
                        'used_credit_points' => round(($available_credit_points - $remaining_credit_points), 2),
                        'initial_payment' => (isset($wk_initial_payment))?$wk_initial_payment:$total_bill_amt,
                        'billing_amount' => $total_bill_amt,
                        'coupon_id' => $applied_coupon_id,
                        'discount_amount' => ($billing_coupon_amt > 0)?round($billing_coupon_amt,2):0,
                        'delivery_fee' => (isset($pln_delivery_fee))?$pln_delivery_fee:0,
                        'week_original_initial_payment' => (isset($wk_initial_payment))?$wk_initial_payment:$total_bill_amt,
                        'week_original_billing_payment' => $total_bill_amt,
                        'selected_days' => $slct_days,
                        'selected_days_range' => $slct_days_range,
                        'start_date' => $curr_wk_sunday,
                        'end_date' => $curr_wk_saturday,
                        'status' => 1,//current
                        'tax_rate_id'=> $tax_rate_id,
                        'tax_amount'=> (isset($weekly_bill_tax_amt) && $weekly_bill_tax_amt > 0)?round($weekly_bill_tax_amt,2):0,
                        'create_date'=> date('Y-m-d H:i:s'),
                        'modify_date'=> date('Y-m-d H:i:s'),
                        'ip_address'=> get_client_ip()
                      );
                      $wpdb->insert($wpdb->prefix.'users_membership_plans',$user_membership_plan_data);
                    }
                    //get user's current week meals
                    $ucwm_sql = "SELECT um.meal_date, um.status, p.* FROM wp_users_meals as um JOIN $wpdb->posts p ON (p.ID = um.meal_id) WHERE um.user_id = $user->ID AND um.week_start_date = '".$curr_wk_sunday."' AND um.week_end_date = '".$curr_wk_saturday."'";
                    $usr_curr_meals = $wpdb->get_results($ucwm_sql);
                    if(empty($usr_curr_meals)){
                      //save user's current week's pre-selected meals
                      $pln_mpd = get_pmpro_membership_level_meta($membership_plan_id,'_meal_per_day',true);
                      $meal_data = array(
                        'user_id' => $user->ID,
                        'week_meals_per_day' =>$pln_mpd,
                        'status' => 2,
                        'is_pre_selected' => 1,
                        'created_date' => date('Y-m-d H:i:s'),
                        'modify_date' => date('Y-m-d H:i:s'),
                        'ip_address' => get_client_ip()
                      );
                      $insrt_arr = array();
                      $start_day = $end_day = '';
                      if(!empty($slct_days_range)){
                        $slct_days_range_arr = explode('-', $slct_days_range);
                        $start_day = (isset($slct_days_range_arr[0]))?$slct_days_range_arr[0]:'';
                        $end_day = (isset($slct_days_range_arr[1]))?$slct_days_range_arr[1]:'';
                      }
                      $week_days = array();
                      if((!empty($start_day) || $start_day == 0) && !empty($end_day)){
                        for($i = $start_day; $i<=$end_day; $i++){
                          $week_days[] = date('Y-m-d', strtotime('+'.$i.' day', strtotime($curr_wk_sunday)));
                        }
                      }
                      //get plan group id by plan id
                      $plan_grp_data = get_plan_group_by_plan_id($membership_plan_id);
                      $mealsArr = get_posts([
                        'post_type' => 'menu-items',
                        'post_status' => 'publish',
                        'posts_per_page' => '-1',
                        'meta_query' => array(
                          'relation' => 'AND',
                          array(
                            'key'     => '_appear_date',
                            'value'   => $curr_wk_sunday,
                            'compare' => '<=',
                            'type'    => 'DATE'
                          ),
                          array(
                            'key'     => '_expire_date',
                            'value'   => $curr_wk_sunday,
                            'compare' => '>=',
                            'type'    => 'DATE'
                          ),
                          array(
                            'key' => '_plan',
                            'value' => $plan_grp_data['id'],
                            'compare' => '='
                          )
                        ),
                      ]);
                      if(!empty($mealsArr) && count($mealsArr)>0){
                        $filtered_meals = array();
                        foreach ($mealsArr as $mkey => $mval) {
                          $meal_protein = get_post_meta($mval->ID,'_meal_protein',true);         
                          $meal_categories = get_the_terms($mval->ID, 'menu-items-category', true);
                          $meal_categories = json_decode(json_encode($meal_categories), true);
                          $meal_categories_arr = array_column($meal_categories, 'term_id');
                          $match_diet_preferences = 0;
                          $match_meal_cats = array();
                          if(!empty($diet_preferences) && !empty($meal_protein) && in_array($meal_protein, $diet_preferences)){
                            $match_diet_preferences = 1;
                          }
                          if(!empty($prefer_categories) && !empty($meal_categories_arr)){
                            $match_meal_cats = array_intersect($prefer_categories,$meal_categories_arr);
                          }
                          if($match_diet_preferences == 1 && !empty($match_meal_cats)){
                            $filtered_meals[] = $mval;
                          }
                        }
                        $rand_key_arr = array();
                        foreach ($week_days as $wd_key => $wd_val) {
                          $tmp = array();
                          if(count($filtered_meals) > $pln_mpd){
                              if($pln_mpd > 1){
                                $rand_keys = array_rand($filtered_meals, $pln_mpd);
                                for($mi = 0;$mi < $pln_mpd;$mi++){
                                  $pln_grp_id = get_post_meta($filtered_meals[$rand_keys[$mi]]->ID, '_plan', true);
                                  $tmp = array(
                                    'meal_id' => $filtered_meals[$rand_keys[$mi]]->ID,
                                    'plan_group_id' => ($pln_grp_id > 0)?$pln_grp_id:0,
                                    'meal_date' => $wd_val,
                                    'week_start_date' => $curr_wk_sunday,
                                    'week_end_date' => $curr_wk_saturday
                                  );
                                  $insrt_arr[] = array_merge($meal_data, $tmp);
                                }
                              }else{
                                $rand_key = array_rand($filtered_meals, $pln_mpd);
                                if(in_array($rand_key, $rand_key_arr)){
                                  $rand_key = array_rand($filtered_meals, $pln_mpd);
                                }else{
                                  $rand_key_arr[] = $rand_key;
                                }
                                $plan_grp_id = get_post_meta($filtered_meals[$rand_key]->ID, '_plan', true);
                                //$plan_grp_id = get_post_meta($filtered_meals[0]->ID, '_plan', true);
                                $tmp = array(
                                  'meal_id' => $filtered_meals[$rand_key]->ID,
                                  'plan_group_id' => ($plan_grp_id > 0)?$plan_grp_id:0,
                                  'meal_date' => $wd_val,
                                  'week_start_date' => $curr_wk_sunday,
                                  'week_end_date' => $curr_wk_saturday
                                );
                                $insrt_arr[] = array_merge($meal_data, $tmp);
                              }
                          }else{
                            $ml_cnt = 1;
                            $repeat_arr = array();
                            foreach ($filtered_meals as $mlkey => $mlval) {
                              $plan_group_id = get_post_meta($mlval->ID, '_plan', true);
                              $tmp = array(
                                'meal_id' => $mlval->ID,
                                'plan_group_id' => ($plan_group_id > 0)?$plan_group_id:0,
                                'meal_date' => $wd_val,
                                'week_start_date' => $curr_wk_sunday,
                                'week_end_date' => $curr_wk_saturday
                              );
                              if($ml_cnt == count($filtered_meals)){
                                $repeat_arr = $tmp;
                              }
                              $insrt_arr[] = array_merge($meal_data, $tmp);
                              $ml_cnt++;
                            }
                            if(!empty($repeat_arr)){
                              for ($i = count($filtered_meals); $i < $pln_mpd; $i++) {
                                $insrt_arr[] = array_merge($meal_data, $repeat_arr);
                              }
                            }
                          }
                        }
                      }
                      if(count($insrt_arr) > 0){
                        common_batch_insert($insrt_arr,$wpdb->prefix.'users_meals');
                      }
                    }
                    //update user's current plan detail
                    $curr_mem_plan_detail = pmpro_getLevel($membership_plan_id);
                    if(isset($curr_mem_plan_detail) && !empty($curr_mem_plan_detail)){
                      $curr_mem_plan_detail->slct_days = $slct_days;
                      $curr_mem_plan_detail->slct_days_range = $slct_days_range;
                      $curr_mem_plan_detail->allergies = $allergies;
                      $curr_mem_plan_detail->subscription_transaction_id = $subscription_rslt['id'];
                      update_user_meta($user->ID, "current_membership_plan_detail", $curr_mem_plan_detail);
                    }
                    if(!empty($pmpro_stripe_secretkey) && !empty($subscription_transaction_id)){
                      $ch = curl_init();
                      curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/subscriptions/".$subscription_transaction_id);
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                      curl_setopt($ch, CURLOPT_USERPWD, $pmpro_stripe_secretkey . ":" . "");
                      $cancel_subscription_rslt = json_decode(curl_exec($ch), true);
                      curl_close ($ch);
                    }
                    //update user's subscription data
                    update_user_meta($user->ID, "stripe_subscription_product_id", $product_rslt['id']);
                    update_user_meta($user->ID, "stripe_subscription_plan_id", $pln_rslt['id']);
                    update_user_meta($user->ID, "subscription_transaction_id", $subscription_rslt['id']);
                    //save user meals order's data
                    $charge_data = $invoice_data = array();
                    //get invoices by subscription id
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/invoices?subscription=".$subscription_rslt['id']);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                    curl_setopt($ch, CURLOPT_USERPWD, $pmpro_stripe_secretkey . ":" . "");
                    $sub_inv_rslt = json_decode(curl_exec($ch), true);
                    if (curl_errno($ch)) {
                        echo 'Error:' . curl_error($ch);
                    }
                    curl_close ($ch);
                    if(isset($sub_inv_rslt['data'][0]['charge']) && !empty($sub_inv_rslt['data'][0]['charge'])){
                      $ch = curl_init();
                      curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/charges/".$sub_inv_rslt['data'][0]['charge']);
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                      curl_setopt($ch, CURLOPT_USERPWD, $pmpro_stripe_secretkey . ":" . "");
                      $sub_ch_rslt = json_decode(curl_exec($ch), true);
                      if (curl_errno($ch)) {
                          echo 'Error:' . curl_error($ch);
                      }
                      curl_close ($ch);
                      if(!empty($sub_ch_rslt)){
                        $charge_data = array(
                          'charge_id' => (isset($sub_ch_rslt['id']) && !empty($sub_ch_rslt['id']))?$sub_ch_rslt['id']:'',
                          'amount' => (isset($sub_ch_rslt['amount']) && !empty($sub_ch_rslt['amount']))?$sub_ch_rslt['amount']:'',
                          'balance_transaction' => (isset($sub_ch_rslt['balance_transaction']) && !empty($sub_ch_rslt['balance_transaction']))?$sub_ch_rslt['balance_transaction']:'',
                          'currency' => (isset($sub_ch_rslt['currency']) && !empty($sub_ch_rslt['currency']))?$sub_ch_rslt['currency']:'',
                          'customer' => (isset($sub_ch_rslt['customer']) && !empty($sub_ch_rslt['customer']))?$sub_ch_rslt['customer']:'',
                          'invoice' => (isset($sub_ch_rslt['invoice']) && !empty($sub_ch_rslt['invoice']))?$sub_ch_rslt['invoice']:'',
                          'receipt_number' => (isset($sub_ch_rslt['receipt_number']) && !empty($sub_ch_rslt['receipt_number']))?$sub_ch_rslt['receipt_number']:'',
                          'source' => (isset($sub_ch_rslt['source']) && !empty($sub_ch_rslt['source']))?$sub_ch_rslt['source']:''
                        );
                      }
                    }
                    $invoice_data = array(
                      'invoice_id' => (isset($sub_inv_rslt['data'][0]['id']) && !empty($sub_inv_rslt['data'][0]['id']))?$sub_inv_rslt['data'][0]['id']:'',
                      'amount_due' => (isset($sub_inv_rslt['data'][0]['amount_due']) && !empty($sub_inv_rslt['data'][0]['amount_due']))?$sub_inv_rslt['data'][0]['amount_due']:'',
                      'amount_paid' => (isset($sub_inv_rslt['data'][0]['amount_paid']) && !empty($sub_inv_rslt['data'][0]['amount_paid']))?$sub_inv_rslt['data'][0]['amount_paid']:'',
                      'amount_remaining' => (isset($sub_inv_rslt['data'][0]['amount_remaining']) && !empty($sub_inv_rslt['data'][0]['amount_remaining']))?$sub_inv_rslt['data'][0]['amount_remaining']:'',
                      'charge' => (isset($sub_inv_rslt['data'][0]['charge']) && !empty($sub_inv_rslt['data'][0]['charge']))?$sub_inv_rslt['data'][0]['charge']:'',
                      'currency' => (isset($sub_inv_rslt['data'][0]['currency']) && !empty($sub_inv_rslt['data'][0]['currency']))?$sub_inv_rslt['data'][0]['currency']:'',
                      'customer' => (isset($sub_inv_rslt['data'][0]['customer']) && !empty($sub_inv_rslt['data'][0]['customer']))?$sub_inv_rslt['data'][0]['customer']:'',
                      'invoice_pdf' => (isset($sub_inv_rslt['data'][0]['invoice_pdf']) && !empty($sub_inv_rslt['data'][0]['invoice_pdf']))?$sub_inv_rslt['data'][0]['invoice_pdf']:'',
                      'lines' => (isset($sub_inv_rslt['data'][0]['lines']) && !empty($sub_inv_rslt['data'][0]['lines']))?$sub_inv_rslt['data'][0]['lines']:'',
                      'number' => (isset($sub_inv_rslt['data'][0]['number']) && !empty($sub_inv_rslt['data'][0]['number']))?$sub_inv_rslt['data'][0]['number']:'',
                      'receipt_number' => (isset($sub_inv_rslt['data'][0]['receipt_number']) && !empty($sub_inv_rslt['data'][0]['receipt_number']))?$sub_inv_rslt['data'][0]['receipt_number']:'',
                      'period_start' => (isset($sub_inv_rslt['data'][0]['period_start']) && !empty($sub_inv_rslt['data'][0]['period_start']))?$sub_inv_rslt['data'][0]['period_start']:'',
                      'period_end' => (isset($sub_inv_rslt['data'][0]['period_end']) && !empty($sub_inv_rslt['data'][0]['period_end']))?$sub_inv_rslt['data'][0]['period_end']:'',
                      'status' => (isset($sub_inv_rslt['data'][0]['status']) && !empty($sub_inv_rslt['data'][0]['status']))?$sub_inv_rslt['data'][0]['status']:'',
                      'subscription' => (isset($sub_inv_rslt['data'][0]['subscription']) && !empty($sub_inv_rslt['data'][0]['subscription']))?$sub_inv_rslt['data'][0]['subscription']:'',
                      'subtotal' => (isset($sub_inv_rslt['data'][0]['subtotal']) && !empty($sub_inv_rslt['data'][0]['subtotal']))?$sub_inv_rslt['data'][0]['subtotal']:'',
                      'tax' => (isset($sub_inv_rslt['data'][0]['tax']) && !empty($sub_inv_rslt['data'][0]['tax']))?$sub_inv_rslt['data'][0]['tax']:'',
                      'tax_percent' => (isset($sub_inv_rslt['data'][0]['tax_percent']) && !empty($sub_inv_rslt['data'][0]['tax_percent']))?$sub_inv_rslt['data'][0]['tax_percent']:'',
                      'total' => (isset($sub_inv_rslt['data'][0]['total']) && !empty($sub_inv_rslt['data'][0]['total']))?$sub_inv_rslt['data'][0]['total']:''
                    );
                    $user_data = array(
                      'id' => $user->ID,
                      'first_name' => (isset($user_meta['first_name'][0]) && !empty($user_meta['first_name'][0]))?$user_meta['first_name'][0]:'',
                      'last_name' => (isset($user_meta['last_name'][0]) && !empty($user_meta['last_name'][0]))?$user_meta['last_name'][0]:'',
                      'billing_email' => (isset($user_meta['billing_email'][0]) && !empty($user_meta['billing_email'][0]))?$user_meta['billing_email'][0]:'',
                      'billing_postcode' => (isset($user_meta['billing_postcode'][0]) && !empty($user_meta['billing_postcode'][0]))?$user_meta['billing_postcode'][0]:'',
                      'first_name' => (isset($user_meta['first_name'][0]) && !empty($user_meta['first_name'][0]))?$user_meta['first_name'][0]:'',
                      'shipping_email' => (isset($user_meta['shipping_email'][0]) && !empty($user_meta['shipping_email'][0]))?$user_meta['shipping_email'][0]:'',
                      'shipping_postcode' => (isset($user_meta['shipping_postcode'][0]) && !empty($user_meta['shipping_postcode'][0]))?$user_meta['shipping_postcode'][0]:'',
                      'user_type' => (isset($user_meta['user_type'][0]) && !empty($user_meta['user_type'][0]))?$user_meta['user_type'][0]:'',
                      'subscription_account_status' => (isset($user_meta['subscription_account_status'][0]) && !empty($user_meta['subscription_account_status'][0]))?$user_meta['subscription_account_status'][0]:'',
                      'pmpro_bfirstname' => (isset($user_meta['pmpro_bfirstname'][0]) && !empty($user_meta['pmpro_bfirstname'][0]))?$user_meta['pmpro_bfirstname'][0]:'',
                      'pmpro_blastname' => (isset($user_meta['pmpro_blastname'][0]) && !empty($user_meta['pmpro_blastname'][0]))?$user_meta['pmpro_blastname'][0]:'',
                      'pmpro_baddress1' => (isset($user_meta['pmpro_baddress1'][0]) && !empty($user_meta['pmpro_baddress1'][0]))?$user_meta['pmpro_baddress1'][0]:'',
                      'pmpro_baddress2' => (isset($user_meta['pmpro_baddress2'][0]) && !empty($user_meta['pmpro_baddress2'][0]))?$user_meta['pmpro_baddress2'][0]:'',
                      'pmpro_bcity' => (isset($user_meta['pmpro_bcity'][0]) && !empty($user_meta['pmpro_bcity'][0]))?$user_meta['pmpro_bcity'][0]:'',
                      'pmpro_bstate' => (isset($user_meta['pmpro_bstate'][0]) && !empty($user_meta['pmpro_bstate'][0]))?$user_meta['pmpro_bstate'][0]:'',
                      'pmpro_bzipcode' => (isset($user_meta['pmpro_bzipcode'][0]) && !empty($user_meta['pmpro_bzipcode'][0]))?$user_meta['pmpro_bzipcode'][0]:'',
                      'pmpro_bcountry' => (isset($user_meta['pmpro_bcountry'][0]) && !empty($user_meta['pmpro_bcountry'][0]))?$user_meta['pmpro_bcountry'][0]:'',
                      'pmpro_bphone' => (isset($user_meta['pmpro_bphone'][0]) && !empty($user_meta['pmpro_bphone'][0]))?$user_meta['pmpro_bphone'][0]:'',
                      'pmpro_bemail' => (isset($user_meta['pmpro_bemail'][0]) && !empty($user_meta['pmpro_bemail'][0]))?$user_meta['pmpro_bemail'][0]:'',
                      'pmpro_special_delivery_instructions' => (isset($user_meta['pmpro_special_delivery_instructions'][0]) && !empty($user_meta['pmpro_special_delivery_instructions'][0]))?$user_meta['pmpro_special_delivery_instructions'][0]:'',
                      'is_allergies' => (isset($user_meta['is_allergies'][0]) && !empty($user_meta['is_allergies'][0]))?$user_meta['is_allergies'][0]:'',
                      'allergies' => (isset($user_meta['allergies'][0]) && !empty($user_meta['allergies'][0]))?$user_meta['allergies'][0]:'',
                      'diet_preferences' => (isset($user_meta['diet_preferences'][0]) && !empty($user_meta['diet_preferences'][0]))?$user_meta['diet_preferences'][0]:'',
                      'preferences_meals' => (isset($user_meta['preferences_meals'][0]) && !empty($user_meta['preferences_meals'][0]))?$user_meta['preferences_meals'][0]:''
                    );
                    $uml_sql = "SELECT um.meal_id, p.post_title FROM wp_users_meals as um JOIN $wpdb->posts p ON (p.ID = um.meal_id) WHERE um.user_id = ".$user->ID." AND um.week_start_date = '".$curr_wk_sunday."' AND um.week_end_date = '".$curr_wk_saturday."'";
                    $uml_data = $wpdb->get_results($uml_sql, ARRAY_A);
                    $ml_data = $mlids = array();
                    if(!empty($uml_data) && count($uml_data)>0){
                      foreach ($uml_data as $uml_key => $uml_val) {
                        if(!in_array($uml_val['meal_id'], $mlids)){
                          $mlids[] = $uml_val['meal_id'];
                          $ml_meta_data = get_post_meta($uml_val['meal_id']);
                          $meal_cat = get_the_terms($uml_val['meal_id'], 'menu-items-category', true);
                          $ml_data[$uml_val['meal_id']] = array(
                            'meal_id'=>$uml_val['meal_id'],
                            'meal_name'=>(isset($uml_val['post_title']) && !empty($uml_val['post_title']))?$uml_val['post_title']:'',
                            'calories'=>(isset($ml_meta_data['_calories'][0]) && !empty($ml_meta_data['_calories'][0]))?$ml_meta_data['_calories'][0]:'',
                            'protein'=>(isset($ml_meta_data['_protein'][0]) && !empty($ml_meta_data['_protein'][0]))?$ml_meta_data['_protein'][0]:'',
                            'carbs'=>(isset($ml_meta_data['_carbs'][0]) && !empty($ml_meta_data['_carbs'][0]))?$ml_meta_data['_carbs'][0]:'',
                            'fat'=>(isset($ml_meta_data['_fat'][0]) && !empty($ml_meta_data['_fat'][0]))?$ml_meta_data['_fat'][0]:'',
                            'ingredients'=>(isset($ml_meta_data['_ingredients'][0]) && !empty($ml_meta_data['_ingredients'][0]))?$ml_meta_data['_ingredients'][0]:'',
                            'plan'=>(isset($ml_meta_data['_plan'][0]) && !empty($ml_meta_data['_plan'][0]))?$ml_meta_data['_plan'][0]:'',
                            'sub_title'=>(isset($ml_meta_data['_sub_title'][0]) && !empty($ml_meta_data['_sub_title'][0]))?$ml_meta_data['_sub_title'][0]:'',
                            'allergies_diets'=>(isset($ml_meta_data['_allergies_diets'][0]) && !empty($ml_meta_data['_allergies_diets'][0]))?$ml_meta_data['_allergies_diets'][0]:'',
                            'thumbnail_id'=>(isset($ml_meta_data['_thumbnail_id'][0]) && !empty($ml_meta_data['_thumbnail_id'][0]))?$ml_meta_data['_thumbnail_id'][0]:'',
                            'appear_date'=>(isset($ml_meta_data['_appear_date'][0]) && !empty($ml_meta_data['_appear_date'][0]))?$ml_meta_data['_appear_date'][0]:'',
                            'expire_date'=>(isset($ml_meta_data['_expire_date'][0]) && !empty($ml_meta_data['_expire_date'][0]))?$ml_meta_data['_expire_date'][0]:'',
                            'price'=>(isset($ml_meta_data['_price'][0]) && !empty($ml_meta_data['_price'][0]))?$ml_meta_data['_price'][0]:'',
                            'meal_protein'=>(isset($ml_meta_data['_meal_protein'][0]) && !empty($ml_meta_data['_meal_protein'][0]))?$ml_meta_data['_meal_protein'][0]:'',
                            'signal_tags'=>(isset($ml_meta_data['_signal_tags'][0]) && !empty($ml_meta_data['_signal_tags'][0]))?$ml_meta_data['_signal_tags'][0]:'',
                            'meal_cat'=>$meal_cat
                          );
                        }
                      }
                    }
                    $user_meals_order_data = array(
                      'user_id' => $user->ID,
                      'meal_plan' => $membership_plan_id,
                      'meal_plan_group' => (!empty($plan_data['membership_group']))?$plan_data['membership_group']:'',
                      'user_data' => serialize($user_data),
                      'plan_data' => serialize($plan_data),
                      'meal_data' => serialize($ml_data),
                      'transaction_data' => serialize(array('invoice_data' => $invoice_data,'charge_data' => $charge_data)),
                      'week_start_date' => $curr_wk_sunday,
                      'week_end_date' => $curr_wk_saturday,
                      'total_bill_amount' => (isset($total_bill_amt))?$total_bill_amt:0,
                      'delivery_fee' => (isset($pln_delivery_fee))?$pln_delivery_fee:0,
                      'status' => (isset($sub_inv_rslt['data'][0]['status']) && !empty($sub_inv_rslt['data'][0]['status']))?$sub_inv_rslt['data'][0]['status']:'',
                      'order_date' => date('Y-m-d H:i:s'),
                      'ip_address' => get_client_ip(),
                      'pickup_location' => $pickup_location_id,
                      'payment_status' => 1
                    );
                    $uo_sql = "SELECT * FROM ".$wpdb->prefix."users_meals_orders WHERE user_id = ".$user->ID." AND week_start_date = '".$curr_wk_sunday."' AND week_end_date = '".$curr_wk_saturday."'";
                    $uo_data = $wpdb->get_row($uo_sql, ARRAY_A);
                    if(!empty($uo_data) && $uo_data['id'] > 0){
                      $wpdb->update($wpdb->prefix.'users_meals_orders', $user_meals_order_data, array('id'=>$uo_data['id']));
                    }else{
                      $user_meals_order_data['tax_rate_id'] = $tax_rate_id;
                      $user_meals_order_data['tax_amount'] = (isset($weekly_bill_tax_amt) && $weekly_bill_tax_amt > 0)?round($weekly_bill_tax_amt,2):0;
                      $wpdb->insert($wpdb->prefix.'users_meals_orders',$user_meals_order_data);
                    }
                    //get linked affiliate user id
                    $usr_applied_coupons = get_user_meta($user->ID, "applied_coupons", true);
                    if(!empty($usr_applied_coupons) && is_array($usr_applied_coupons) && count($usr_applied_coupons) > 0){
                      $usr_last_applied_coupon = end($usr_applied_coupons);
                      if(!empty($usr_last_applied_coupon)){
                        //get coupon detail by coupon code
                        $applied_coupon_data = get_coupon_detail(array('column'=>'post_title','value'=>$usr_last_applied_coupon));
                        if(isset($applied_coupon_data['coupon_id']) && !empty($applied_coupon_data['coupon_id'])){
                          if(isset($applied_coupon_data['linked_affiliate_user']) && $applied_coupon_data['linked_affiliate_user'] > 0){
                            //get affiliate id by user id
                            $lnked_affiliate_id = affwp_get_affiliate_id($applied_coupon_data['linked_affiliate_user']);
                            if($lnked_affiliate_id > 0){
                              $aff_data = affwp_get_affiliate($lnked_affiliate_id);
                              //get affiliate user data
                              $aff_meta_arr = $wpdb->get_results("SELECT meta_key, meta_value FROM ".$wpdb->prefix."affiliate_wp_affiliatemeta WHERE affiliate_id = $lnked_affiliate_id", ARRAY_A);
                              $aff_recurring_disabled = $aff_recurring_rate_type = $aff_recurring_rate = $aff_recurring_referral_limit = '';
                              if(!empty($aff_meta_arr) && is_array($aff_meta_arr) && count($aff_meta_arr) > 0){
                                foreach ($aff_meta_arr as $add_ky => $aff_vl) {
                                  switch ($aff_vl['meta_key']) {
                                    case 'recurring_disabled':
                                      $aff_recurring_disabled = $aff_vl['meta_value'];
                                    break;
                                    case 'recurring_rate_type':
                                      $aff_recurring_rate_type = $aff_vl['meta_value'];
                                    break;
                                    case 'recurring_rate':
                                      $aff_recurring_rate = $aff_vl['meta_value'];
                                    break;
                                    case 'recurring_referral_limit':
                                      $aff_recurring_referral_limit = $aff_vl['meta_value'];
                                    break;
                                  }
                                }
                              }
                              if($aff_recurring_disabled == '' && $aff_recurring_rate_type != '' && $aff_recurring_rate != ''){
                                $aff_recurring_bonus_amt = 0;
                                if($aff_recurring_rate_type == 'flat'){
                                  $aff_recurring_bonus_amt = $aff_recurring_rate;
                                }elseif($total_bill_amt > 0 && $aff_recurring_rate > 0){
                                  $aff_recurring_bonus_amt = ($total_bill_amt*$aff_recurring_rate)/100;
                                }
                                if($aff_recurring_bonus_amt > 0){
                                  //$aff_ref_sql = "SELECT reference FROM ".$wpdb->prefix."affiliate_wp_referrals WHERE affiliate_id = ".$lnked_affiliate_id." AND context = 'pmp'";
                                  //$aff_ref_data = $wpdb->get_row($aff_ref_sql, ARRAY_A);
                                  //$aff_reference_id = (isset($aff_ref_data['reference']) && !empty($aff_ref_data['reference']))?$aff_ref_data['reference']:'';
                                  $cust_ref_sql = "SELECT id FROM ".$wpdb->prefix."pmpro_membership_orders WHERE user_id = ".$user->ID;
                                  $cust_ref_data = $wpdb->get_row($cust_ref_sql, ARRAY_A);
                                  $cust_ord_reference_id = (isset($cust_ref_data['id']) && !empty($cust_ref_data['id']))?$cust_ref_data['id']:'';
                                  //create referral
                                  $aff_recur_ref_arr = array(
                                    'affiliate_id' => $lnked_affiliate_id,
                                    'description' => (isset($plan_data['name']) && !empty($plan_data['name']))?$plan_data['name']:'tc-plan',
                                    'status' => 'unpaid',
                                    'amount' => round($aff_recurring_bonus_amt,2),
                                    'currency' => 'USD',
                                    'custom' => serialize(array('affiliate_id'=>$lnked_affiliate_id)),
                                    'context' => 'pmp',
                                    'campaign' => '',
                                    'reference' => $cust_ord_reference_id,
                                    'products' => '',
                                    'payout_id' => 0,
                                    'date' => date('Y-m-d H:i:s'),
                                    'customer_id' => $user->ID,
                                    'type' => 'sale'
                                  );
                                  $wpdb->insert($wpdb->prefix.'affiliate_wp_referrals',$aff_recur_ref_arr);
                                  $aff_unpaid_earnings = (isset($aff_data->unpaid_earnings) && $aff_data->unpaid_earnings > 0)?$aff_data->unpaid_earnings:0;
                                  $credit_points_data = array(
                                    'unpaid_earnings' => round(($aff_recurring_bonus_amt+$aff_unpaid_earnings),2)
                                  );
                                  $wpdb->update($wpdb->prefix.'affiliate_wp_affiliates', $credit_points_data, array('user_id' => $applied_coupon_data['linked_affiliate_user'],'affiliate_id' => $lnked_affiliate_id));
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                    //send meal order processed notification email
                   
                    send_meal_order_processed_notification($ord_smry_data);
                  }
                }
              }
            }
          }
        }
      }
    //die('end');
    //}
    }
  }
}

/*
* Method: founction for add custom field with meal category.
*/

add_action('menu-items-category_edit_form_fields','add_custom_fields_with_meal_category'); 
add_action('menu-items-category_add_form_fields','add_custom_fields_with_meal_category');
function add_custom_fields_with_meal_category($term) {
  $termid = (isset($term->term_id) && !empty($term->term_id))?$term->term_id:'';
  $display_on_onboarding_page = get_term_meta($termid,'_display_on_onboarding_page',true);
  $meal_cat_order = get_term_meta($termid,'_meal_cat_order',true);
  $is_add_on = get_term_meta($termid,'_is_addon',true);
?>
  <tr class="form-field">
    <th valign="top" scope="row">
      <label for="status"><?php _e('Display On Onboarding Page ', 'toughcookies'); ?></label>
    </th>
    <td>
      <select name="display_on_onboarding_page" id="display_on_onboarding_page">
        <option value="1" <?php echo ($display_on_onboarding_page == 1)?'selected':''; ?>>Yes</option>
        <option value="0" <?php echo ($display_on_onboarding_page == 0)?'selected':''; ?>>No</option>
      </select>
      <p>Please select if you want to display this category on onboarding page.</p>
    </td>
  </tr>
  <tr class="form-field">
    <th valign="top" scope="row">
      <label for="status"><?php _e('Category Order', 'toughcookies'); ?></label>
    </th>
    <td>
      <input type="text" name="meal_cat_order" id="meal_cat_order" value="<?php echo $meal_cat_order; ?>">
      <p>Please enter order number like 1, 2,3, ... in which order you want to display this category on onboarding page.</p>
    </td>
  </tr>
  <tr class="form-field">
    <th valign="top" scope="row">
      <label for="status"><?php _e('Is Add-on ', 'toughcookies'); ?></label>
    </th>
    <td>
      <select name="is_add_on" id="is_add_on">
        <option value="1" <?php echo ($is_add_on == 1)?'selected':''; ?>>Yes</option>
        <option value="0" <?php echo ($is_add_on == 0)?'selected':''; ?>>No</option>
      </select>
      <p>Please select if this category is for add-on meal.</p>
    </td>
  </tr>
<?php 
}

/*
* Method: founction for save custom fields with meal category.
*/

add_action ('edited_menu-items-category', 'save_custom_fileds_for_meal_category');
add_action('created_menu-items-category','save_custom_fileds_for_meal_category');
function save_custom_fileds_for_meal_category( $term_id ){
  if(isset($_POST['display_on_onboarding_page'])) {
    update_term_meta($term_id, '_display_on_onboarding_page', $_POST['display_on_onboarding_page']);
  }
  if(isset($_POST['display_on_onboarding_page'])) {
    update_term_meta($term_id, '_meal_cat_order', $_POST['meal_cat_order']);
  }
  if(isset($_POST['is_add_on'])){
    update_term_meta($term_id, '_is_addon', $_POST['is_add_on']);
  }
}

/*
* Method: founction for save user's upcoming 4 weeks pre-selected meals
*/

function save_user_upcoming_wk_pre_selected_meals($user_id){
  if(!empty($user_id)){
    global $wpdb;
    date_default_timezone_set("America/New_York");
    $diet_preferences = get_user_meta($user_id,'diet_preferences',true);
    $preferences_meals = get_user_meta($user_id,'preferences_meals',true);
    $prefer_categories = (!empty($preferences_meals))?unserialize($preferences_meals):'';
    $user_membership_levels = pmpro_getMembershipLevelsForUser($user_id);
    $subscription_start_date = (isset($user_membership_levels[0]->start_date))?$user_membership_levels[0]->start_date:'';
    $plan_id = (isset($user_membership_levels[0]->id))?$user_membership_levels[0]->id:0;
    if(!empty($subscription_start_date)){
      $wkd_name = strtolower(date("D", strtotime($subscription_start_date)));
      if(in_array($wkd_name, array('sun','mon','tue'))){
        $curr_wk_sunday = date("Y-m-d",strtotime('next sunday', strtotime($subscription_start_date)));
      }else{
        $reg_time = strtolower(date('H:i:s',strtotime($subscription_start_date)));
        if($wkd_name == 'wed' && $reg_time <= '11:00:00'){
          $curr_wk_sunday = date("Y-m-d",strtotime('next sunday', strtotime($subscription_start_date)));
        }else{
          $curr_wk_sunday = date("Y-m-d",strtotime('+2 sunday', strtotime($subscription_start_date))); 
        }
        //$curr_wk_sunday = date("Y-m-d",strtotime('+2 sunday', strtotime($subscription_start_date))); 
      }
      //$next_sunday = date('Y-m-d', strtotime('next sunday',strtotime($curr_wk_sunday)));
      $upcming_wks = array($curr_wk_sunday,
        date('Y-m-d',strtotime('+2 sunday', strtotime($curr_wk_sunday))),
        date('Y-m-d',strtotime('+3 sunday', strtotime($curr_wk_sunday))),
        date('Y-m-d',strtotime('+4 sunday', strtotime($curr_wk_sunday))),
        date('Y-m-d',strtotime('+5 sunday', strtotime($curr_wk_sunday)))
      );
      array_unshift($upcming_wks,$curr_wk_sunday);
      //$upcming_wks_settings = get_user_meta($user_id,'upcoming_weeks_settings',true);
      //$upcming_wks_settings = (!empty($upcming_wks_settings))?unserialize($upcming_wks_settings):array();
      $slct_days = get_user_meta($user_id,'slct_days',true);
      $slct_days_range = get_user_meta($user_id,'slct_days_range',true);
      $wk_slct_days_range = $slct_days_range;
      $wk_plan_id = $plan_id;
      $wh = "`user_id` = $user_id AND `status` = 1";
      $_curr_plan = check_record_exist($wpdb->prefix.'users_membership_plans',$wh);
      $wk_plan_id = (isset($_curr_plan['membership_plan_id']) && !empty($_curr_plan['membership_plan_id']))?$_curr_plan['membership_plan_id']:$wk_plan_id;
      $slct_days = (isset($_curr_plan['selected_days']) && !empty($_curr_plan['selected_days']))?$_curr_plan['selected_days']:$slct_days;
      $wk_slct_days_range = (isset($_curr_plan['selected_days_range']) && !empty($_curr_plan['selected_days_range']))?$_curr_plan['selected_days_range']:$wk_slct_days_range;
      $wk_days_meals = array();
      $upcming_wks = array_unique($upcming_wks);
      foreach ($upcming_wks as $uwkey => $uwval) {
        $wk_strt_key = str_replace('-', '', $uwval);
        //$wk_end_key = str_replace('-', '', date("Y-m-d",strtotime('next saturday', strtotime($uwval))));
        $wk_end_date = date("Y-m-d",strtotime('next saturday', strtotime($uwval)));
        $wk_end_key = str_replace('-', '', $wk_end_date);

        $wk_pln_wh = "`user_id` = $user_id AND `start_date` = '$uwval' AND `end_date` = '$wk_end_date'";
        $_wk_plan = check_record_exist($wpdb->prefix.'users_membership_plans',$wk_pln_wh);
        $wk_plan_id = (isset($_wk_plan['membership_plan_id']) && $_wk_plan['membership_plan_id'] > 0)?$_wk_plan['membership_plan_id']:$wk_plan_id;
        $slct_days = (isset($_wk_plan['selected_days']) && !empty($_wk_plan['selected_days']))?$_wk_plan['selected_days']:$slct_days;
        $wk_slct_days_range = (isset($_wk_plan['selected_days_range']) && !empty($_wk_plan['selected_days_range']))?$_wk_plan['selected_days_range']:$wk_slct_days_range;
        $wk_slct_days_range = (!empty($wk_slct_days_range))?$wk_slct_days_range:'';
        $start_day = $end_day = '';
        if(!empty($wk_slct_days_range)){
          $slct_days_range_arr = explode('-', $wk_slct_days_range);
          $start_day = (isset($slct_days_range_arr[0]))?$slct_days_range_arr[0]:'';
          $end_day = (isset($slct_days_range_arr[1]))?$slct_days_range_arr[1]:'';
        }
        $week_days = array();
        if((!empty($start_day) || $start_day == 0) && !empty($end_day)){
          for($i = $start_day; $i<=$end_day; $i++){
            $week_days[] = date('Y-m-d', strtotime('+'.$i.' day', strtotime($uwval)));
          }
        }
        $plan_sql = "SELECT pml.id, pml_meta1.meta_value as meal_per_day FROM $wpdb->pmpro_membership_levels pml JOIN $wpdb->pmpro_membership_levelmeta pml_meta1 ON (pml_meta1.pmpro_membership_level_id = pml.id AND pml_meta1.meta_key = '_meal_per_day') WHERE pml.id = $wk_plan_id";
        $plan_data = $wpdb->get_row($plan_sql, ARRAY_A);
        if(isset($plan_data) && !empty($plan_data)){
          $pln_mpd = $plan_data['meal_per_day'];
        }
        //get plan group id by plan id
        $plan_grp_data = get_plan_group_by_plan_id($wk_plan_id);
        $mealsArr = get_posts([
          'post_type' => 'menu-items',
          'post_status' => 'publish',
          'posts_per_page' => '-1',
          'meta_query' => array(
            'relation' => 'AND',
            array(
              'key'     => '_appear_date',
              'value'   => $uwval,
              'compare' => '<=',
              'type'    => 'DATE'
            ),
            array(
              'key'     => '_expire_date',
              'value'   => $uwval,
              'compare' => '>=',
              'type'    => 'DATE'
            ),
            array(
              'key' => '_plan',
              'value' => $plan_grp_data['id'],
              'compare' => '='
            )
          )
        ]);
        if(!empty($mealsArr) && count($mealsArr)>0){
          $filtered_meals = array();
          foreach ($mealsArr as $mkey => $mval) {
            $meal_protein = get_post_meta($mval->ID,'_meal_protein',true);
            $meal_categories = get_the_terms($mval->ID, 'menu-items-category', true);
            $meal_categories = json_decode(json_encode($meal_categories), true);
            $meal_categories_arr = array_column($meal_categories, 'term_id');
            $_price = get_post_meta($mval->ID,'_price',true);
            $match_diet_preferences = 0;
            $match_meal_cats = array();
            if(!empty($diet_preferences) && !empty($meal_protein) && in_array($meal_protein, $diet_preferences)){
              $match_diet_preferences = 1;//array_intersect($diet_preferences,$meal_allergies_diets);
            }
            if(!empty($prefer_categories) && !empty($meal_categories_arr)){
              $match_meal_cats = array_intersect($prefer_categories,$meal_categories_arr);
            }
            if($match_diet_preferences == 1 && !empty($match_meal_cats)){
              if(empty($_price) || $_price == 0){
                $filtered_meals[] = $mval;
              }
            }
          }
          $rand_key_arr = array();
          foreach ($week_days as $wdkey => $wdval) {
            $sql = "SELECT um.* FROM wp_users_meals as um JOIN $wpdb->posts p ON (p.ID = um.meal_id) WHERE um.user_id = $user_id AND um.meal_date = '".$wdval."' AND um.is_pre_selected = 1";
            $user_meals = $wpdb->get_results($sql);
            if(!empty($user_meals) && count($user_meals)>0){
              //nothing to do
              $wpdb->query("DELETE FROM wp_users_meals WHERE user_id = $user_id AND meal_date = '".$wdval."' AND is_pre_selected = 1");
            }
            if(count($filtered_meals) > $pln_mpd){
              if($pln_mpd > 1){
                $rand_keys = array_rand($filtered_meals, $pln_mpd);
                $tmp = array();
                for($mi = 0;$mi < $pln_mpd;$mi++){
                  $tmp[] = $filtered_meals[$rand_keys[$mi]];
                }
                $wk_days_meals[$uwval][$wdval] = $tmp;
              }else{
                $rand_key = array_rand($filtered_meals, $pln_mpd);
                if(in_array($rand_key, $rand_key_arr)){
                  $rand_key = array_rand($filtered_meals, $pln_mpd);
                }else{
                  $rand_key_arr[] = $rand_key;
                }
                $wk_days_meals[$uwval][$wdval] = array($filtered_meals[$rand_key]);
                //$wk_days_meals[$uwval][$wdval] = array($filtered_meals[0]);
              }
            }else{
              $cnt = 1;
              $repeat_arr = array();
              foreach ($filtered_meals as $mlkey => $mlval) {
                $wk_days_meals[$uwval][$wdval][] = $mlval;
                  if($cnt == count($filtered_meals)){
                    $repeat_arr = $mlval;
                  }
                  $cnt++;
              }
              if(!empty($repeat_arr)){
                for ($i = count($filtered_meals); $i < $pln_mpd; $i++) {
                  $wk_days_meals[$uwval][$wdval][] = $repeat_arr;
                }
              }
            }
          }
        }
      }
      if(!empty($wk_days_meals)){
        $wpdb->query("DELETE FROM wp_users_meals WHERE user_id = $user_id AND is_pre_selected = 1");
        foreach ($wk_days_meals as $wm_key => $wm_val) {
          foreach ($wm_val as $wdm_key => $wdm_val) {
            foreach ($wdm_val as $m_key => $m_val) {
              //add user's upcoming meals into wp_users_meals table
              $pln_grp_id = get_post_meta($m_val->ID, '_plan', true);
              $meal_data[] = array(
                'user_id'=> $user_id,
                'meal_id'=> $m_val->ID,
                'plan_group_id' => ($pln_grp_id > 0)?$pln_grp_id:0,
                'meal_date'=> $wdm_key,
                'week_meals_per_day'=>$pln_mpd,
                'week_start_date'=>$wm_key,
                'week_end_date'=> date('Y-m-d', strtotime('next saturday', strtotime($wm_key))),
                'status'=> 2,
                'is_pre_selected'=>1,
                'created_date'=> date('Y-m-d H:i:s'),
                'modify_date'=> date('Y-m-d H:i:s'),
                'ip_address'=> get_client_ip()
              );
            }
          }
        }
        common_batch_insert($meal_data,'wp_users_meals');
        $curwk_saturday = date('Y-m-d', strtotime('next saturday', strtotime($curr_wk_sunday)));
        $uml_sql = "SELECT um.meal_id, p.post_title FROM wp_users_meals as um JOIN $wpdb->posts p ON (p.ID = um.meal_id) WHERE um.user_id = ".$user_id." AND um.week_start_date = '".$curr_wk_sunday."' AND um.week_end_date = '".$curwk_saturday."'";
        $uml_data = $wpdb->get_results($uml_sql, ARRAY_A);
        $ml_data = $mlids = array();
        if(!empty($uml_data) && count($uml_data)>0){
          foreach ($uml_data as $uml_key => $uml_val) {
            if(!in_array($uml_val['meal_id'], $mlids)){
              $mlids[] = $uml_val['meal_id'];
              $ml_meta_data = get_post_meta($uml_val['meal_id']);
              $meal_cat = get_the_terms($uml_val['meal_id'], 'menu-items-category', true);
              $ml_data[$uml_val['meal_id']] = array(
                'meal_id'=>$uml_val['meal_id'],
                'meal_name'=>(isset($uml_val['post_title']) && !empty($uml_val['post_title']))?$uml_val['post_title']:'',
                'calories'=>(isset($ml_meta_data['_calories'][0]) && !empty($ml_meta_data['_calories'][0]))?$ml_meta_data['_calories'][0]:'',
                'protein'=>(isset($ml_meta_data['_protein'][0]) && !empty($ml_meta_data['_protein'][0]))?$ml_meta_data['_protein'][0]:'',
                'carbs'=>(isset($ml_meta_data['_carbs'][0]) && !empty($ml_meta_data['_carbs'][0]))?$ml_meta_data['_carbs'][0]:'',
                'fat'=>(isset($ml_meta_data['_fat'][0]) && !empty($ml_meta_data['_fat'][0]))?$ml_meta_data['_fat'][0]:'',
                'ingredients'=>(isset($ml_meta_data['_ingredients'][0]) && !empty($ml_meta_data['_ingredients'][0]))?$ml_meta_data['_ingredients'][0]:'',
                'plan'=>(isset($ml_meta_data['_plan'][0]) && !empty($ml_meta_data['_plan'][0]))?$ml_meta_data['_plan'][0]:'',
                'sub_title'=>(isset($ml_meta_data['_sub_title'][0]) && !empty($ml_meta_data['_sub_title'][0]))?$ml_meta_data['_sub_title'][0]:'',
                'allergies_diets'=>(isset($ml_meta_data['_allergies_diets'][0]) && !empty($ml_meta_data['_allergies_diets'][0]))?$ml_meta_data['_allergies_diets'][0]:'',
                'thumbnail_id'=>(isset($ml_meta_data['_thumbnail_id'][0]) && !empty($ml_meta_data['_thumbnail_id'][0]))?$ml_meta_data['_thumbnail_id'][0]:'',
                'appear_date'=>(isset($ml_meta_data['_appear_date'][0]) && !empty($ml_meta_data['_appear_date'][0]))?$ml_meta_data['_appear_date'][0]:'',
                'expire_date'=>(isset($ml_meta_data['_expire_date'][0]) && !empty($ml_meta_data['_expire_date'][0]))?$ml_meta_data['_expire_date'][0]:'',
                'price'=>(isset($ml_meta_data['_price'][0]) && !empty($ml_meta_data['_price'][0]))?$ml_meta_data['_price'][0]:'',
                'meal_protein'=>(isset($ml_meta_data['_meal_protein'][0]) && !empty($ml_meta_data['_meal_protein'][0]))?$ml_meta_data['_meal_protein'][0]:'',
                'signal_tags'=>(isset($ml_meta_data['_signal_tags'][0]) && !empty($ml_meta_data['_signal_tags'][0]))?$ml_meta_data['_signal_tags'][0]:'',
                'meal_cat'=>$meal_cat
              );
            }
          }
          $wpdb->update($wpdb->prefix.'users_meals_orders', array('meal_data' => serialize($ml_data)), array('user_id' => $user_id,'week_start_date' => $curr_wk_sunday, 'week_end_date' => $curwk_saturday));
        }
      }
    }
  }
}


/*
* Method: function to get meal status
*/

function get_meal_status(){
  return array('1' => 'Active','0' => 'Inactive');
}

/*
* Method: function to check meal status whether all fields were filled or not
*/

function check_meal_status($post_id){
  global $wpdb;
  date_default_timezone_set("America/New_York");
  $meal_data = get_post_meta($post_id);
  $title = get_the_title($post_id);
  $meal_category = get_the_terms($post_id, 'menu-items-category');
  //$ingredients = $wpdb->get_results("select ingredient_id, quantity, fraction_qty, unit_id, unit_abbreviation from ".$wpdb->prefix."meals_ingredients where meal_id= ".$post_id, ARRAY_A);
  $ingredients = $wpdb->get_results("SELECT ingredient_id, quantity, fraction_qty, unit_id, unit_abbreviation FROM ".$wpdb->prefix."meals_ingredients as m_ingr JOIN ".$wpdb->prefix."posts as ingr on ingr.ID = m_ingr.ingredient_id WHERE m_ingr.meal_id = ".$post_id." AND ingr.post_status = 'publish'", ARRAY_A);
  if(isset($meal_data['_calories'][0]) && !empty($meal_data['_calories'][0]) && isset($meal_data['_protein'][0]) && !empty($meal_data['_protein'][0]) && isset($meal_data['_carbs'][0]) && !empty($meal_data['_carbs'][0]) && isset($meal_data['_fat'][0]) && !empty($meal_data['_fat'][0]) && !empty($ingredients) && isset($meal_data['_plan'][0]) && !empty($meal_data['_plan'][0]) && isset($meal_data['_sub_title'][0]) && !empty($meal_data['_sub_title'][0]) && isset($meal_data['_appear_date'][0]) && !empty($meal_data['_appear_date'][0]) && isset($meal_data['_expire_date'][0]) && !empty($meal_data['_expire_date'][0]) && isset($meal_data['_meal_protein'][0]) && !empty($meal_data['_meal_protein'][0]) && isset($meal_data['_thumbnail_id'][0]) && !empty($meal_data['_thumbnail_id'][0]) && !empty($title) && !empty($meal_category)){
    return TRUE;
  }else{
    return FALSE;
  }
}

/*
* Method: function to adjust width of custom table 
*/
add_action('admin_head', 'meal_list_column_width');
function meal_list_column_width() {
  global $pagenow;
  if(isset($_GET['post_type']) && $_GET['post_type'] == 'menu-items'){
    echo '<style type="text/css">
        table.wp-list-table tr th{text-align:center;}
        #cb-select-all-1{vertical-align: middle !important; margin-left: 1.2em !important;}
        #cb-select-all-2{vertical-align: middle !important; margin-left: 1.2em !important;}
        table.wp-list-table tr th.column-meal_category{text-align:left;}
        .column-cb{ width:4% !important; overflow:hidden;}
        .column-meal_card_image{ text-align: center; width:6% !important; overflow:hidden;}
        .column-title{ width:25% !important; overflow:hidden;}
        .column-meal_category{width:10% !important; overflow:hidden;}
        .column-meal_plan{ text-align: center; width:12% !important; overflow:hidden;}
        .column-appear_date{text-align: center; width:9% !important;}
        .column-expire_date{text-align: center; width:9% !important;}
        .column-date{width:10% !important;}
        .column-status{text-align: center; width:5% !important;}
        .column-complete_incomplete{text-align: center; width:10% !important;}
    </style>';
  }elseif($pagenow == 'users.php'){
    echo '<style type="text/css">
      .column-username{width:20% !important; overflow:hidden;}
      .column-name{width:10% !important; overflow:hidden;}
      .column-email{width:20% !important; overflow:hidden;}
      .column-role{width:10% !important; overflow:hidden;}
      .column-posts{width:6% !important; overflow:hidden;}
      .manage-column column-pmpro_membership_level{width:14% !important; overflow:hidden;}
      .column-allergy{width:10% !important; overflow:hidden;}
      .column-subscription_acc_status{width:10% !important; overflow:hidden;}
    </style>';
  }
}
add_filter( 'parse_query', 'admin_meals_filter' );
add_action( 'restrict_manage_posts', 'filter_meals_by_meal_category' );
add_action( 'restrict_manage_posts', 'filter_meals_by_meal_plan' );
add_action( 'restrict_manage_posts', 'filter_meals_by_meal_status' );

/*
* Method: function for filtering meal by status, category and plan 
*/

function admin_meals_filter( $query ){
  global $pagenow;
  if ( is_admin() && $pagenow=='edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'menu-items'){
    if(isset($_GET['m_cat']) && $_GET['m_cat'] != ''){
      $args = array(
        array( 
          'taxonomy' => 'menu-items-category',
          'field' => 'id', 
          'terms' => array($_GET['m_cat']) 
        ) 
      );
      $query->query_vars['tax_query'] = $args;
    }
    if (isset($_GET['m_plan']) && $_GET['m_plan'] != ''){
      $query->query_vars['meta_key'] = '_plan';
      $query->query_vars['meta_value'] = $_GET['m_plan'];
    }
    if (isset($_GET['m_status']) && $_GET['m_status'] != ''){
      if($_GET['m_status'] == 1){
        $args = array( 
            array(
                'key' => '_appear_date',
                'value' => date('Y-m-d'),
                'compare' => '>=',
                'type' => 'DATE'
            )
        );
        $query->query_vars['meta_query'] = $args;
      }else{
        $args = array( 
            array(
                'key' => '_appear_date',
                'value' => date('Y-m-d'),
                'compare' => '<=',
                'type' => 'DATE'
            )
        );
        $query->query_vars['meta_query'] = $args;
      }
    }
  }
}

/*
* Method: function for showing dropdown to filter by meal category
*/

function filter_meals_by_meal_category(){ 
  if (isset($_GET['post_type']) && $_GET['post_type'] == 'menu-items'){
    $meal_category = get_categories(array('taxonomy' => 'menu-items-category'));
    ?>
    <select name="m_cat" id="m_cat">
      <option value=""><?php _e('Filter By Category', 'toughcookies'); ?></option>
    <?php
        if(!empty($meal_category) && is_array($meal_category)){
          $fltr_cat = isset($_GET['m_cat'])? $_GET['m_cat']:'';
          foreach ($meal_category as $cat_key => $cat_val) {
            $cat_name = isset($cat_val->name) ? $cat_val->name : '';
            $cat_id = isset($cat_val->term_taxonomy_id) ? $cat_val->term_taxonomy_id : 0;
            $cat_slcted = ($fltr_cat == $cat_id)?"selected":"";
            echo '<option value="'.$cat_id.'" '.$cat_slcted.'>'.$cat_name.'</option>';
          }
        }
    ?>
    </select>
<?php
  }
}

/*
* Method: function for showing dropdown to filter by meal plan
*/

function filter_meals_by_meal_plan(){ 
  if (isset($_GET['post_type']) && $_GET['post_type'] == 'menu-items'){
    $meal_plan = get_membership_group();
    ?>
    <select name="m_plan" id="m_plan">
      <option value=""><?php _e('Filter By Plan', 'toughcookies'); ?></option>
      <?php
        if(!empty($meal_plan) && is_array($meal_plan)){
          $fltr_pln = isset($_GET['m_plan'])? $_GET['m_plan']:'';
          foreach ($meal_plan as $pln_key => $pln_val) {
            $pln_name = !empty($pln_val)?$pln_val: '';
            $pln_id = !empty($pln_key)?$pln_key:0;
            $pln_slcted = ($fltr_pln == $pln_id)?"selected":"";
            echo '<option value="'.$pln_id.'" '.$pln_slcted.'>'.$pln_name.'</option>';
          }
        }
      ?>
    </select>
<?php
  }
}

/*
* Method: function for showing dropdown to filter by meal status
*/ 

function filter_meals_by_meal_status(){
  if (isset($_GET['post_type']) && $_GET['post_type'] == 'menu-items'){
    $meal_status = get_meal_status();
  ?>
    <select name="m_status" id="m_status">
      <option value=""><?php _e('Filter By Status', 'toughcookies'); ?></option>
      <?php
        if(!empty($meal_status) && is_array($meal_status)){
          $fltr_status = isset($_GET['m_status'])?$_GET['m_status']:'';
          foreach ($meal_status as $ms_key => $ms_val) {
            $ms_slcted = ($fltr_status!='' && $fltr_status == $ms_key)?'selected':'';
            echo '<option value="'.$ms_key.'" '.$ms_slcted.'>'.$ms_val.'</option>';
          }
        }
      ?>
    </select>
<?php
  }
}
add_action( 'init', 'add_custom_taxonomies_to_sliders', 0 );

/*
* Method: to add custom category for sliders custom post
* Parms:
*/

function add_custom_taxonomies_to_sliders() {
  register_taxonomy('sliders_category', 'sliders', array(
    'hierarchical' => true,
    'labels' => array(
      'name' => _x( 'Sliders Category', 'taxonomy general name' ),
      'singular_name' => _x( 'Slider Category', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Slider Category' ),
      'all_items' => __( 'All Sliders Category' ),
      'parent_item' => __( 'Parent Slider Category' ),
      'parent_item_colon' => __( 'Parent Sliders Category:' ),
      'edit_item' => __( 'Edit Slider Category' ),
      'update_item' => __( 'Update Slider Category' ),
      'add_new_item' => __( 'Add New Slider Category' ),
      'new_item_name' => __( 'New Slider Category Name' ),
      'menu_name' => __( 'Categories' ),
    ),
    'rewrite' => array(
      'slug' => 'sliders_category', // This controls the base slug that will display before each term
      'with_front' => false,
      'hierarchical' => true
    ),
  ));
}

add_action('sliders_category_edit_form_fields','add_fields_for_sliders'); 
add_action('sliders_category_add_form_fields','add_fields_for_sliders'); 
function add_fields_for_sliders ($tag) {
    $termid = isset($tag->term_id) ? $tag->term_id : '';  
?>
  <tr class="form-field">
      <th valign="top" scope="row">
          <label for="status"><?php _e('Status', ''); ?></label>
      </th>
      <td>
        <select name="slider_cat_status" id="slider_cat_status">
          <option value="on" <?php if(get_term_meta($termid,'slider_cat_status',true) == 'on'){echo 'selected';}?> >On</option>
          <option value="off" <?php if(get_term_meta($termid,'slider_cat_status',true) == 'off'){echo 'selected';}?> >Off</option>
      </select>
      </td>
      <p></p>
  </tr> 
  <?php
}

add_action ( 'edited_sliders_category', 'save_status_for_sliders');
add_action('created_sliders_category','save_status_for_sliders');

/*
* Method: save status for sliders
*/ 

function save_status_for_sliders($term_id) {
  if ( isset( $_POST['slider_cat_status'] ) ) {
    $status = get_term_meta($term_id);
    if ($status !== false ) {
      update_term_meta($term_id, 'slider_cat_status', $_POST['slider_cat_status']);
    }else{
      add_term_meta( $term_id, 'slider_cat_status', $_POST['slider_cat_status'], true );
    }
  }
}  

/*
* Method: remove status for sliders
*/ 

add_filter('deleted_term_taxonomy', 'remove_status_for_sliders');
function remove_status_for_sliders($term_id) {
  if($_POST['taxonomy'] == 'sliders_category'):
  if(get_term_meta($term_id))
      delete_term_meta($term_id);
  endif;
}

/*
* Method: Function for adding sliders data 
*/

function add_sliders_data(){
  global $post, $wpdb;
  $_slider_status = get_post_meta($post->ID,'_slider_status',true);
  ?>
  <div class="wrap">
    <label for="status"></label>
    <select name="slider_status" id="slider_status">
      <option value="on" <?php if(isset($_slider_status) && $_slider_status == 'on'){echo 'selected';}?> >On</option>
      <option value="off" <?php if(isset($_slider_status) && $_slider_status == 'off'){echo 'selected';}?> >Off</option>
    </select>
  </div>
<?php  
}

/*
* Method: function for get affiliate users list
*/

function get_affiliate_users(){
  if(isset($_POST['srch_val']) && !empty($_POST['srch_val'])){
    $af_user_list = array();
    $affiliate_users = affiliate_wp()->affiliates->get_affiliates(
      array('number' => -1, 'status' => 'active','fields' => 'user_id')
    );
    $af_args = array( 'include' => $affiliate_users );
    $af_args['search'] = '*'.mb_strtolower(htmlentities2(trim($_POST['srch_val']))) . '*';
    $found_users = get_users($af_args);
    if ($found_users) {
      foreach( $found_users as $user ) {
        $label = empty( $user->user_email ) ? $user->user_login : "{$user->user_login} ({$user->user_email})";
        $af_user_list[] = array(
          'lel'   => $label,
          'vl'   => $user->user_login,
          'usr_id' => $user->ID
        );
      }
      $response = array('error'=>0,'afusers'=>$af_user_list);
    }else{
      $response = array('error'=>1,'afusers'=>array());
    }
  }else{
    $response = array('error'=>1,'afusers'=>array());
  }
  echo json_encode($response);
  exit();
}

/*
* Date:
* Method: function for send coupon code to affiliate user
*/

function send_coupon_code_to_affiliate_user($affiliate_user_id,$coupon_id){
  if(!empty($affiliate_user_id) && !empty($coupon_id)){
    date_default_timezone_set("America/New_York");
    $aff_user = get_userdata( $affiliate_user_id );
    $uname = (isset($aff_user->data->user_login) && !empty($aff_user->data->user_login))?$aff_user->data->user_login:'';
    $first_name = get_user_meta( $affiliate_user_id, 'first_name', true );
    $last_name = get_user_meta( $affiliate_user_id, 'last_name', true );
    $af_user_name = ((!empty($first_name))?$first_name:'').((!empty($last_name))?' '.$last_name:'');
    $coupon_code = get_the_title($coupon_id);
    $_subject = get_bloginfo( 'name' )." promotional code";
    $_message = '<div style="background-color:#f5f5f5;width:100%;margin:0;padding:15px 0 15px 0">
        <table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody>
            <tr>
              <td valign="top" align="center">
                <table width="800" cellspacing="0" cellpadding="0" border="0" style="border-radius:6px!important;background-color:#fdfdfd;border:1px solid #dcdcdc;border-radius:6px!important">
                  <tbody>
                    <tr>
                      <td valign="top" align="center">
                        <table width="800" cellspacing="0" cellpadding="0" border="0" bgcolor="#3C5DAE" style="background-color:#3C5DAE;color:#ffffff;border-top-left-radius:6px!important;border-top-right-radius:6px!important;border-bottom:0;font-family:Arial;font-weight:bold;line-height:100%;vertical-align:middle">
                          <tbody>
                            <tr>
                              <td>
                                 <h1 style="color:#ffffff;margin:0;padding:28px 24px;display:block;font-family:Arial;font-size:30px;font-weight:bold;text-align:center;">'.get_bloginfo( 'name' ).' Promotional Code</h1>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top" align="center">
                        <table width="800" cellspacing="0" cellpadding="5" border="0">
                          <tbody>
                            <tr>
                              <td valign="top" style="background-color:#fdfdfd;border-radius:6px!important">
                                <table width="100%" cellspacing="0" cellpadding="5" border="0" style="color:#737373;font-family:Arial;font-size:14px;text-align:left">
                                  <tbody>
                                    <tr>
                                      <td valign="top">
                                          <p>Hello '.$af_user_name.',</p>
                                      </td>
                                    </tr>

                                    <tr>
                                      <td valign="top">
                                        Here is your '.get_bloginfo('name').' promotional code: <strong>'.$coupon_code.'</strong>.
                                      </td>
                                    </tr>

                                    <tr>
                                      <td valign="top">
                                        Please share this coupon code to your friends and when they signup & purchase meals from <a href="'.site_url().'">'.get_bloginfo('name').'</a> then your friend will get discount on first week billing and you also get commission.
                                      </td>
                                    </tr>

                                    <tr>
                                      <td valign="top">
                                        <div style="line-height:50%;">
                                          <p>Regards,</p>
                                          <p>'.get_bloginfo('name').' Team</p>
                                         </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top" align="center">
                        <table width="600" cellspacing="0" cellpadding="10" border="0" style="border-top:0">
                          <tbody>
                            <tr>
                              <td valign="top">
                                <table width="100%" cellspacing="0" cellpadding="10" border="0">
                                  <tbody>
                                    <tr>
                                      <td valign="middle" style="border:0;color:#3C5DAE;font-family:Arial;font-size:12px;line-height:100%;text-align:center" colspan="2">
                                        <p>'.get_bloginfo( 'name' ).'</p>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </div>';
    $mail_html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    $mail_html .='<html xmlns="http://www.w3.org/1999/xhtml">';
    $mail_html .='<head>';
    $mail_html .='';
    $mail_html .='<title>' . $_subject . '</title>';
    $mail_html .='<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
    $mail_html .='</head>';
    $mail_html .='<body style="margin: 0; padding: 0;">';
    $mail_html .= $_message;
    $mail_html .='</body>';
    $mail_html .='</html>';
    $headers = 'From: ' . get_bloginfo('name') . ' <' . get_bloginfo('admin_email') . '>';
    add_filter('wp_mail_content_type','wpse27856_set_content_type');
    $aff_email = (isset($aff_user->data->user_email) && !empty($aff_user->data->user_email))?$aff_user->data->user_email:'';
    //$aff_email = 'deepak.p@cisinlabs.com';
    @wp_mail($aff_email, $_subject, $mail_html,$headers);
  }
}

/*
* Date:
* Method: function for get customer & send reminder email for complete payment & purchase meal plan
*/

function send_reminder_to_users_complete_signup(){
 
  $users = get_users();
  if(isset($users) && !empty($users) && is_array($users)){

    foreach ($users as $key => $user) {
      $user_role = $user->roles[0];
     // if(isset($user_role) && !empty($user_role) && strtolower($user_role) != 'administrator'){
        $user_meta = get_user_meta($user->ID);
        $f_name = (isset($user_meta['first_name'][0]) && !empty($user_meta['first_name'][0]))?ucfirst($user_meta['first_name'][0]):'';
        $l_name = (isset($user_meta['last_name'][0]) && !empty($user_meta['last_name'][0]))?ucfirst($user_meta['last_name'][0]):'';
        $user_type = (isset($user_meta['user_type'][0]) && !empty($user_meta['user_type'][0]))?$user_meta['user_type'][0]:'';
        $subscription_account_status = (isset($user_meta['subscription_account_status'][0]) && !empty($user_meta['subscription_account_status'][0]))?$user_meta['subscription_account_status'][0]:'';
        $user_registered = (isset($user->data->user_registered) && !empty($user->data->user_registered))?$user->data->user_registered:'';
        $user_registered_date = new DateTime($user_registered);
        $today = new DateTime(date('Y-m-d h:i:s'));
        $diff = $today->diff($user_registered_date);
        $days_interval = $diff->days;
        if($user_type == 1 && $subscription_account_status == 0 && $days_interval == 3){
          echo "running";
          $remind_mail_subject = "You Forgot Something";
      $remind_mail_message = "<div style='background-color:#F9F9F9;'>
        <!-- Top Menu -->
        <!--[if mso | IE]>
          <table
           align='center' border='0' cellpadding='0' cellspacing='0' class='' style='width:480px;' width='480'
          >
          <tr>
            <td style='line-height:0px;font-size:0px;mso-line-height-rule:exactly;'>
          <![endif]-->
        <div style='background:#F9F9F9;background-color:#F9F9F9;margin:0px auto;max-width:480px;'>
          <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:#F9F9F9;background-color:#F9F9F9;width:100%;'>
          <tbody>
            <tr>
            <td style='direction:ltr;font-size:0px;padding:0;text-align:center;'>
              <!--[if mso | IE]>
                <table role='presentation' border='0' cellpadding='0' cellspacing='0'>

          <tr>

            <td
               class='' style='vertical-align:top;width:480px;'
            >
            <![endif]-->
              <div class='mj-column-per-100 mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'>
              <table border='0' cellpadding='0' cellspacing='0' role='presentation' width='100%'>
                <tbody>
                <tr>
                  <td style='vertical-align:top;padding:10px 0 0 0;'>
                  <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='' width='100%'>
                    <tr>
                    <td align='center' style='font-size:0px;padding:10px 25px;word-break:break-word;'>
                      <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:collapse;border-spacing:0px;'>
                      <tbody>
                        <tr>
                        <td style='width:90px;'> <a href='http://www.toughcookies.co/' target='_blank'>

          <img
           height='auto' src='https://toughcookies.co/wp-content/uploads/2020/12/Logo-Light-2020-Version-1.png' style='border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:14px;' width='90'
          />

          </a> </td>
                        </tr>
                      </tbody>
                      </table>
                    </td>
                    </tr>
                  </table>
                  </td>
                </tr>
                </tbody>
              </table>
              </div>
              <!--[if mso | IE]>
            </td>";

           $remind_mail_message .= ' <td
               class="" style="vertical-align:top;width:480px;"
            >
            <![endif]-->
              <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
              <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                <tr>
                <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                  <div style="font-family:acumin-pro, Helvetica, sans-serif;font-size:14px;font-weight:300;line-height:14px;text-align:center;color:#A0A0A0;">
                  <p><a style="text-decoration: none; color:#A0A0A0" href="https://toughcookies.co/how-it-works/">How it Works</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a style="text-decoration: none; color:#A0A0A0" href="https://toughcookies.co/on-the-menu/">On the Menu</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                    <a style="text-decoration: none; color:#A0A0A0" href="https://toughcookies.co/contact/">Contact</a> </p>
                  </div>
                </td>
                </tr>
              </table>
              </div>
              <!--[if mso | IE]>
            </td>';

         $remind_mail_message .= "</tr>

                </table>
              <![endif]-->
            </td>
            </tr>
          </tbody>
          </table>
        </div>
        <!--[if mso | IE]>
            </td>
          </tr>
          </table>
          <![endif]-->
        <!-- Image Header -->
        <!--[if mso | IE]>
          <table
           align='center' border='0' cellpadding='0' cellspacing='0' class='' style='width:480px;' width='480'
          >
          <tr>
            <td style='line-height:0px;font-size:0px;mso-line-height-rule:exactly;'>
          <![endif]-->
        <div style='background:#FFFFFF;background-color:#FFFFFF;margin:0px auto;max-width:480px;'>
          <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:#FFFFFF;background-color:#FFFFFF;width:100%;'>
          <tbody>
            <tr>
            <td style='direction:ltr;font-size:0px;padding:0 0 0 0;text-align:center;'>
              <!--[if mso | IE]>
                <table role='presentation' border='0' cellpadding='0' cellspacing='0'>

          <tr>

            <td
               class='' style='vertical-align:top;width:480px;'
            >
            <![endif]-->
              <div class='mj-column-per-100 mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'>
              <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'>
                <tr>
                <td align='center' style='font-size:0px;padding:0px 25px;padding-right:0px;padding-bottom:0px;padding-left:0px;word-break:break-word;'>
                  <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:collapse;border-spacing:0px;'>
                  <tbody>
                    <tr>
                    <td style='width:480px;'> <a href='".site_url('signup')."' target='_blank'><img height='auto' src='https://toughcookies.co/wp-content/uploads/2020/12/ForgotHeader@2x.jpg' style='border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:14px;' title='' width='480'
                      /> </a></td>
                    </tr>
                  </tbody>
                  </table>
                </td>
                </tr>
              </table>
              </div>
              <!--[if mso | IE]>
            </td>

          </tr>

                </table>
              <![endif]-->
            </td>
            </tr>
          </tbody>
          </table>
        </div>
        <!--[if mso | IE]>
            </td>
          </tr>
          </table>
          <![endif]-->
        <!-- Delivery Text -->
        <!--[if mso | IE]>
          <table
           align='center' border='0' cellpadding='0' cellspacing='0' class='' style='width:480px;' width='480'
          >
          <tr>
            <td style='line-height:0px;font-size:0px;mso-line-height-rule:exactly;'>
          <![endif]-->
        <div style='background:#104BCB;background-color:#104BCB;margin:0px auto;max-width:480px;'>
          <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:#104BCB;background-color:#104BCB;width:100%;'>
          <tbody>
            <tr>
            <td style='direction:ltr;font-size:0px;padding:18px 0 10px;text-align:center;'>
              <!--[if mso | IE]>
                <table role='presentation' border='0' cellpadding='0' cellspacing='0'>

          <tr>

            <td
               class='' style='vertical-align:top;width:432px;'
            >
            <![endif]-->
              <div class='mj-column-per-90 mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'>
              <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'>
                <tr>
                <td align='center' style='font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:10px;word-break:break-word;'>";
                 $remind_mail_message .= '<div style="font-family:acumin-pro, Helvetica, sans-serif;font-size:14px;font-weight:300;line-height:22px;text-align:center;color:#FFFFFF;"><a href='.site_url('free-meals').' style="text-decoration:none; color:#FFFFFF; display:block;" target="_blank">USE CODE: <span style="font-weight: 600;">FIRSTWEEK</span> TO GET 25$ OFF</div>';
                 $remind_mail_message .= "</a></td>
                </tr>
              </table>
              </div>
              <!--[if mso | IE]>
            </td>

          </tr>

                </table>
              <![endif]-->
            </td>
            </tr>
          </tbody>
          </table>
        </div>
        <!--[if mso | IE]>
            </td>
          </tr>
          </table>
          <![endif]-->
        <!-- Summary Text -->
        <!--[if mso | IE]>
          <table
           align='center' border='0' cellpadding='0' cellspacing='0' class='' style='width:480px;' width='480'
          >
          <tr>
            <td style='line-height:0px;font-size:0px;mso-line-height-rule:exactly;'>
          <![endif]-->
        <div style='background:#FFFFFF;background-color:#FFFFFF;margin:0px auto;max-width:480px;'>
          <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:#FFFFFF;background-color:#FFFFFF;width:100%;'>
          <tbody>
            <tr>
            <td style='direction:ltr;font-size:0px;padding:55px 5% 0;text-align:center;'>
              <!--[if mso | IE]>
                <table role='presentation' border='0' cellpadding='0' cellspacing='0'>

          <tr>

            <td
               class='' style='vertical-align:top;width:NaNpx;'
            >
            <![endif]-->
              <div class='mj-column-px-NaN mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'>
              <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'>
                <tr>
                <td align='center' style='font-size:0px;padding:10px 25px;padding-top:0;word-break:break-word;'>
                  <div style='font-family:Helvetica;font-size:18px;line-height:28px;text-align:center;color:#141414;'>You are just one step away from recieving your first delivery from Boston’s local meal delivery company. Featuring healthy and delicious meals, and convenient (FREE) delivery. ☺️</div>
                </td>
                </tr>
              </table>
              </div>
              <!--[if mso | IE]>
            </td>

          </tr>

                </table>
              <![endif]-->
            </td>
            </tr>
          </tbody>
          </table>
        </div>
        <!--[if mso | IE]>
            </td>
          </tr>
          </table>
          <![endif]-->
        <!-- How it Works Icons -->
        <!--[if mso | IE]>
          <table
           align='center' border='0' cellpadding='0' cellspacing='0' class='' style='width:480px;' width='480'
          >
          <tr>
            <td style='line-height:0px;font-size:0px;mso-line-height-rule:exactly;'>
          <![endif]-->
        <div style='background:#FFFFFF;background-color:#FFFFFF;margin:0px auto;max-width:480px;'>
          <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:#FFFFFF;background-color:#FFFFFF;width:100%;'>
          <tbody>
            <tr>
            <td style='direction:ltr;font-size:0px;padding:35px 5% 35px;text-align:center;'>
              <!--[if mso | IE]>
                <table role='presentation' border='0' cellpadding='0' cellspacing='0'>

          <tr>

            <td
               class='' style='vertical-align:top;width:423px;'
            >
            <![endif]-->
              <div class='mj-column-per-90 mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'>
              <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'>
                <tr>
                <td align='center' style='font-size:0px;padding:10px 25px;padding-bottom:30px;word-break:break-word;'>
                  <div style='font-family:acumin-pro, Helvetica, 'sans-serif';font-size:18px;font-weight:600;line-height:16px;text-align:center;color:#000000;'>YOU'RE ALWAYS IN CONTROL</div>
                </td>
                </tr>
              </table>
              </div>
              <!--[if mso | IE]>
            </td>
            <![endif]-->
              <!-- Skip -->
              <!--[if mso | IE]>
            <td
               class='' style='vertical-align:top;width:60px;'
            >
            <![endif]-->
              <div class='mj-column-px-60 mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'>
              <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'>
                <tr>
                <td align='center' style='font-size:0px;padding:10px 0;padding-right:0px;padding-left:0px;word-break:break-word;'>
                  <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:collapse;border-spacing:0px;'>
                  <tbody>
                    <tr>
                    <td style='width:60px;'> <img height='auto' src='https://toughcookies.co/wp-content/uploads/2020/12/SkipIcon@2x.jpg' style='border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:14px;' title='' width='60' /> </td>
                    </tr>
                  </tbody>
                  </table>
                </td>
                </tr>
              </table>
              </div>
              <!--[if mso | IE]>
            </td>";

             $remind_mail_message .= '<td
               class="" style="vertical-align:top;width:423px;"
            >
            <![endif]-->
              <div class="mj-column-per-90 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
              <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                <tr>
                <td align="center" style="font-size:0px;padding:0;word-break:break-word;">
                  <div style="font-family:acumin-pro, Helvetica, sans-serif;font-size:18px;font-weight:600;line-height:22px;text-align:center;color:#242424;">Skip anytime</div>
                </td>
                </tr>
                <tr>
                <td align="center" style="font-size:0px;padding:10px 25px;padding-bottom:45px;word-break:break-word;">
                  <div style="font-family:acumin-pro, Helvetica, sans-serif;font-size:15px;font-weight:300;line-height:22px;text-align:center;color:#696969;">You can skip a week whenever you need to. We get it, our schedules are unpredictable too.</div>
                </td>
                </tr>
              </table>
              </div>
              <!--[if mso | IE]>
            </td>
            <![endif]-->
              <!-- Reminder -->
              <!--[if mso | IE]>
            <td
               class="" style="vertical-align:top;width:70px;"
            >
            <![endif]-->
              <div class="mj-column-px-70 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
              <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                <tr>
                <td align="center" style="font-size:0px;padding:10px 0;padding-right:0px;padding-left:0px;word-break:break-word;">
                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
                  <tbody>
                    <tr>
                    <td style="width:70px;"> <img height="auto" src="https://toughcookies.co/wp-content/uploads/2020/12/ReminderIcon@2x.jpg" style="border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:14px;" title="" width="70"
                      /> </td>
                    </tr>
                  </tbody>
                  </table>
                </td>
                </tr>
              </table>
              </div>
              <!--[if mso | IE]>
            </td>

            <td
               class="" style="vertical-align:top;width:423px;"
            >
            <![endif]-->
              <div class="mj-column-per-90 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
              <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%"">
                <tr>
                <td align="center" style="font-size:0px;padding:0;word-break:break-word;">
                  <div style="font-family:acumin-pro, Helvetica, sans-serif;font-size:18px;font-weight:600;line-height:22px;text-align:center;color:#242424;">Weekly reminders</div>
                </td>
                </tr>
                <tr>
                <td align="center" style="font-size:0px;padding:10px 25px;padding-bottom:45px;word-break:break-word;">
                  <div style="font-family:acumin-pro, Helvetica, sans-serif;font-size:15px;font-weight:300;line-height:22px;text-align:center;color:#696969;"">We’ll always remind you before anything processes. No surprises here.</div>
                </td>
                </tr>
              </table>
              </div>
              <!--[if mso | IE]>
            </td>
            <![endif]-->
              <!-- Manage -->
              <!--[if mso | IE]>
            <td
               class="" style="vertical-align:top;width:80px;
            >
            <![endif]-->
              <div class="mj-column-px-80 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
              <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                <tr>
                <td align="center" style="font-size:0px;padding:10px 0;padding-right:0px;padding-left:0px;word-break:break-word;">
                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
                  <tbody>
                    <tr>
                    <td style="width:80px;"> <img height="auto" src="https://toughcookies.co/wp-content/uploads/2020/12/ManageWeekIcon@2x.jpg" style="border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:14px;" title="" width="80"
                      /> </td>
                    </tr>
                  </tbody>
                  </table>
                </td>
                </tr>
              </table>
              </div>
              <!--[if mso | IE]>
            </td>

            <td
               class="" style="vertical-align:top;width:423px;"
            >
            <![endif]-->
              <div class="mj-column-per-90 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
              <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                <tr>
                <td align="center" style="font-size:0px;padding:0;word-break:break-word;">
                  <div style="font-family:acumin-pro, Helvetica, sans-serif;font-size:18px;font-weight:600;line-height:22px;text-align:center;color:#242424;">Easy management</div>
                </td>
                </tr>
                <tr>
                <td align="center" style="font-size:0px;padding:10px 25px;padding-bottom:45px;word-break:break-word;">
                  <div style="font-family:acumin-pro, Helvetica, sans-serif;font-size:15px;font-weight:300;line-height:22px;text-align:center;color:#696969;">Effortlessly select your meals up to four weeks in advanced, through our Upcoming delivery planner.</div>
                </td>
                </tr>
              </table>
              </div>
              <!--[if mso | IE]>
            </td>

          </tr>

                </table>
              <![endif]-->
            </td>
            </tr>
          </tbody>
          </table>
        </div>
        <!--[if mso | IE]>
            </td>
          </tr>
          </table>
          <![endif]-->';
        $remind_mail_message .= "<!-- No Commitments Icon -->
        <!--[if mso | IE]>
          <table
           align='center' border='0' cellpadding='0' cellspacing='0' class='' style='width:480px;' width='480'
          >
          <tr>
            <td style='line-height:0px;font-size:0px;mso-line-height-rule:exactly;'>
          <![endif]-->
        <div style='background:#104BCB;background-color:#104BCB;margin:0px auto;max-width:480px;'>
          <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:#104BCB;background-color:#104BCB;width:100%;'>
          <tbody>
            <tr>
            <td style='direction:ltr;font-size:0px;padding:35px 5% 55px;text-align:center;'>
              <!--[if mso | IE]>
                <table role='presentation' border='0' cellpadding='0' cellspacing='0'>

          <tr>

            <td
               class='' style='vertical-align:top;width:150px;'
            >
            <![endif]-->
              <div class='mj-column-px-150 mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'>
              <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'>
                <tr>
                <td align='center' style='font-size:0px;padding:0px 25px;padding-right:0px;padding-bottom:0px;padding-left:0px;word-break:break-word;'>
                  <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:collapse;border-spacing:0px;'>
                  <tbody>
                    <tr>
                    <td style='width:150px;'> <img height='auto' src='https://toughcookies.co/wp-content/uploads/2020/12/SignatureLightBlue@2x.jpg' style='border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:14px;' title='' width='150'
                      /> </td>
                    </tr>
                  </tbody>
                  </table>
                </td>
                </tr>
              </table>
              </div>
              <!--[if mso | IE]>
            </td>

            <td
               class='' style='vertical-align:top;width:423px;'
            >
            <![endif]-->
              <div class='mj-column-per-90 mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'>
              <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'>
                <tr>
                <td align='center' style='font-size:0px;padding:0;word-break:break-word;'>";

                $remind_mail_message .=   '<div style="font-family:acumin-pro, Helvetica, sans-serif;font-size:20px;font-weight:600;line-height:22px;text-align:center;color:#FFFFFF;">No commitments</div>
                </td>
                </tr>
                <tr>
                <td align="center" style="font-size:0px;padding:10px 25px;padding-bottom:14px;word-break:break-word;">
                  <div style="font-family:acumin-pro, Helvetica, sans-serif;font-size:15px;font-weight:300;line-height:22px;text-align:center;color:#FFFFFF;">Skip weeks, swap meals, and cancel anytime - only order what and when you want. No commitments.</div>
                </td>
                </tr>';
               $remind_mail_message .= " <tr>
               <td align='center' vertical-align='middle' style='font-size:0px;padding:10px 25px;word-break:break-word;'>
                  <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:separate;line-height:100%;'>
                  <tr>
                    <td align='center' bgcolor='#FFFFFF' role='presentation' style='border:none;border-radius:30px;cursor:auto;mso-padding-alt:15px 25px;background:#FFFFFF;' valign='middle'> <a href='".site_url('free-meals')."' style='display:inline-block;background:#FFFFFF;color:#104BCB;font-family:Helvetica;font-size:12px;font-weight:600;line-height:120%;margin:0;text-decoration:none;text-transform:none;padding:15px 25px;mso-padding-alt:0px;border-radius:30px;'
                      target='_blank'>
              GET STARTED WITH $25 OFF
            </a> </td>
                  </tr>
                  </table>
                </td>
                </tr>
              </table>
              </div>
              <!--[if mso | IE]>
            </td>

          </tr>

                </table>
              <![endif]-->
            </td>
            </tr>
          </tbody>
          </table>
        </div>
        <!--[if mso | IE]>
            </td>
          </tr>
          </table>
          <![endif]-->
        <!-- Image CTA 1 -->
        <!--[if mso | IE]>
          <table
           align='center' border='0' cellpadding='0' cellspacing='0' class='' style='width:480px;' width='480'
          >
          <tr>
            <td style='line-height:0px;font-size:0px;mso-line-height-rule:exactly;'>
          <![endif]-->
        <div style='background:#FFFFF;background-color:#FFFFF;margin:0px auto;max-width:480px;'>
          <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:#FFFFF;background-color:#FFFFF;width:100%;'>
          <tbody>
            <tr>
            <td style='direction:ltr;font-size:0px;padding:0 0 0 0;text-align:center;'>
              <!--[if mso | IE]>
                <table role='presentation' border='0' cellpadding='0' cellspacing='0'>

          <tr>

            <td
               class='' style='vertical-align:top;width:480px;'
            >
            <![endif]-->
              <div class='mj-column-per-100 mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'>
              <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'>
                <tr>
                <td align='center' style='font-size:0px;padding:0px 25px;padding-right:0px;padding-bottom:0px;padding-left:0px;word-break:break-word;'>
                  <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:collapse;border-spacing:0px;'>
                  <tbody>
                    <tr>
                    <td style='width:480px;'><a href='".site_url('free-meals')."' target='_blank'> <img height='auto' src='https://toughcookies.co/wp-content/uploads/2020/12/Forgot-CTA@2x.jpg' style='border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:14px;' title='' width='480' /> </a>                             </td>
                    </tr>
                  </tbody>
                  </table>
                </td>
                </tr>
              </table>
              </div>
              <!--[if mso | IE]>
            </td>

          </tr>

                </table>
              <![endif]-->
            </td>
            </tr>
          </tbody>
          </table>
        </div>
        <!--[if mso | IE]>
            </td>
          </tr>
          </table>
          <![endif]-->
        <!-- Footer -->
        <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:#F9F9F9;background-color:#F9F9F9;width:100%;'>
          <tbody>
          <tr>
            <td>
            <!--[if mso | IE]>
          <table
           align='center' border='0' cellpadding='0' cellspacing='0' class='' style='width:480px;' width='480'
          >
          <tr>
            <td style='line-height:0px;font-size:0px;mso-line-height-rule:exactly;'>
          <![endif]-->
            <div style='margin:0px auto;max-width:480px;'>
              <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;'>
              <tbody>
                <tr>
                <td style='direction:ltr;font-size:0px;padding:30px 0 30px 0;text-align:center;'>
                  <!--[if mso | IE]>
                <table role='presentation' border='0' cellpadding='0' cellspacing='0'>

          <tr>

            <td
               class='' style='vertical-align:top;width:480px;'
            >
            <![endif]-->
                  <div class='mj-column-per-100 mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'>
                  <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'>
                    <tr>
                    <td align='center' style='font-size:0px;padding:0;word-break:break-word;'>
                      <!--[if mso | IE]>
          <table
           align='center' border='0' cellpadding='0' cellspacing='0' role='presentation'
          >
          <tr>

              <td>
            <![endif]-->
                      <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='float:none;display:inline-table;'>
                      <tr>
                        <td style='padding:4px;vertical-align:middle;'>
                        <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-radius:3px;width:18px;'>
                          <tr>
                          <td style='font-size:0;height:18px;vertical-align:middle;width:18px;'> <a href='https://facebook.com/ToughCookies/' target='_blank'>
                <img
                   height='18' src='https://toughcookies.co/wp-content/uploads/2019/10/FacebookIcon-1.png' style='border-radius:3px;display:block;' width='18'
                />
                </a> </td>
                          </tr>
                        </table>
                        </td>
                        <td style='vertical-align:middle;'> <a href='https://facebook.com/ToughCookies/' style='color:#333333;font-size:15px;font-family:Helvetica;line-height:22px;text-decoration:none;' target='_blank'>
              &nbsp;&nbsp;
            </a> </td>
                      </tr>
                      </table>
                      <!--[if mso | IE]>
              </td>

              <td>
            <![endif]-->
                      <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='float:none;display:inline-table;'>
                      <tr>
                        <td style='padding:4px;vertical-align:middle;'>
                        <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-radius:3px;width:18px;'>
                          <tr>
                          <td style='font-size:0;height:18px;vertical-align:middle;width:18px;'> <a href='https://instagram.com/toughcookiesco/' target='_blank'>
                <img
                   height='18' src='https://toughcookies.co/wp-content/uploads/2019/10/instagramIcon.png' style='border-radius:3px;display:block;' width='18'
                />
                </a> </td>
                          </tr>
                        </table>
                        </td>
                        <td style='vertical-align:middle;'> <a href='https://instagram.com/toughcookiesco/' style='color:#333333;font-size:15px;font-family:Helvetica;line-height:22px;text-decoration:none;' target='_blank'>
              &nbsp;&nbsp;
            </a> </td>
                      </tr>
                      </table>
                      <!--[if mso | IE]>
              </td>

              <td>
            <![endif]-->
                      <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='float:none;display:inline-table;'>
                      <tr>
                        <td style='padding:4px;vertical-align:middle;'>
                        <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-radius:3px;width:18px;'>
                          <tr>
                          <td style='font-size:0;height:18px;vertical-align:middle;width:18px;'> <a href='http://m.me/ToughCookies' target='_blank'>
                <img
                   height='18' src='https://toughcookies.co/wp-content/uploads/2019/10/MessengerIcon.png' style='border-radius:3px;display:block;' width='18'
                />
                </a> </td>
                          </tr>
                        </table>
                        </td>
                        <td style='vertical-align:middle;'> <a href='http://m.me/ToughCookies' style='color:#333333;font-size:15px;font-family:Helvetica;line-height:22px;text-decoration:none;' target='_blank'>
              &nbsp;
            </a> </td>
                      </tr>
                      </table>
                      <!--[if mso | IE]>
              </td>

            </tr>
          </table>
          <![endif]-->
                    </td>
                    </tr>
                  </table>
                  </div>
                  <!--[if mso | IE]>
            </td>";

           $remind_mail_message .=' <td
               class="" style="vertical-align:top;width:480px;"
            >
            <![endif]-->
                  <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                    <tr>
                    <td align="center" style="font-size:0px;padding:0;word-break:break-word;">
                      <div style="font-family:acumin-pro, Helvetica, sans-serif;font-size:14px;font-weight:300;line-height:30px;text-align:center;color:#7C7C7C;">
                      <p><a style="text-decoration: none; color:#777777; border-right: 1px solid #E6E6E6; padding:12px 15px 10px 15px;" href="tel:781-436-0235">Call us</a><a style="text-decoration: none; color:#777777; border-right: 1px solid #E6E6E6; padding:12px 15px 10px 15px;"
                        href="mailto:hello@toughcookies.co">Email us</a><a style="text-decoration: none; color:#777777; border-right: 1px solid #E6E6E6; padding:12px 15px 10px 15px;" href="sms:+17814360235">Text us</a><a style="text-decoration: none; color:#777777; padding:12px 15px 10px 15px;"
                        href="https://toughcookies.co/faqs/">FAQ</a></p>
                      </div>
                    </td>
                    </tr>
                  </table>
                  </div>
                  <!--[if mso | IE]>
            </td>';

            $remind_mail_message .=' <td
               class="" style="vertical-align:top;width:480px;"
            >
            <![endif]-->
                  <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                    <tr>
                    <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <div style="font-family:acumin-pro, Helvetica,sans-serif;font-size:12px;font-weight:300;line-height:20px;text-align:center;color:#7C7C7C;">Copyright © 2021 Tough Cookies, LLC | All Rights Reserved 46 Rockland St, Hanover, MA 02329</div>
                    </td>
                    </tr>
                  </table>
                  </div>
                  <!--[if mso | IE]>
            </td>

          </tr>

                </table>
              <![endif]-->
                </td>
                </tr>
              </tbody>
              </table>
            </div>
            <!--[if mso | IE]>
            </td>
          </tr>
          </table>
          <![endif]-->
            </td>
          </tr>
          </tbody>
        </table>';
        $remind_mail_message .=   '<!--[if mso | IE]>
          <table
           align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:480px;" width="480"
          >
          <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
          <![endif]-->
        <div style="margin:0px auto;max-width:480px;">
          <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
          <tbody>
            <tr>
            <td style="direction:ltr;font-size:0px;padding:0;text-align:center;">
              <!--[if mso | IE]>
                <table role="presentation" border="0" cellpadding="0" cellspacing="0">

          <tr>

            <td
               class="" style="vertical-align:top;width:480px;"
            >
            <![endif]-->
              <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
              <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                <tr>
                <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                  <div style="font-family:Helvetica;font-size:14px;line-height:1;text-align:center;color:#7C7C7C;">
                  <p>Having trouble viewing this email? <a href="#" style="font-weight: bold; text-decoration: none; color:#7C7C7C">View Online</a></p>
                  </div>
                </td>
                </tr>
              </table>
              </div>
              <!--[if mso | IE]>
            </td>

          </tr>

                </table>
              <![endif]-->
            </td>
            </tr>
          </tbody>
          </table>
        </div>
        <!--[if mso | IE]>
            </td>
          </tr>
          </table>
          <![endif]-->';

        $remind_mail_html  = '<!doctype html>';
        $remind_mail_html .= '<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">';
        $remind_mail_html .= '<head>';
        $remind_mail_html .= '  <title> </title>';
        $remind_mail_html .= '  <!--[if !mso]><!-- -->';
        $remind_mail_html .= '  <meta http-equiv="X-UA-Compatible" content="IE=edge">';
        $remind_mail_html .= '  <!--<![endif]-->';
        $remind_mail_html .= '  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
        $remind_mail_html .= '  <meta name="viewport" content="width=device-width, initial-scale=1">';
        $remind_mail_html .= '  <style type="text/css">';
        $remind_mail_html .= '  #outlook a {';
        $remind_mail_html .= '    padding: 0;';
        $remind_mail_html .= '  }';
        $remind_mail_html .= 'body {';
        $remind_mail_html .= '    margin: 0;';
        $remind_mail_html .= '    padding: 0;';
        $remind_mail_html .= '    -webkit-text-size-adjust: 100%;';
        $remind_mail_html .= '    -ms-text-size-adjust: 100%;';
        $remind_mail_html .= '  }';
        $remind_mail_html .= 'table,';
        $remind_mail_html .= '  td {';
        $remind_mail_html .= '    border-collapse: collapse;';
        $remind_mail_html .= '    mso-table-lspace: 0pt;';
        $remind_mail_html .= '    mso-table-rspace: 0pt;';
        $remind_mail_html .= '  }';
        $remind_mail_html .= 'img {';
        $remind_mail_html .= '    border: 0;';
        $remind_mail_html .= '    height: auto;';
        $remind_mail_html .= '    line-height: 100%;';
        $remind_mail_html .= '    outline: none;';
        $remind_mail_html .= '    text-decoration: none;';
        $remind_mail_html .= '    -ms-interpolation-mode: bicubic;';
        $remind_mail_html .= '  }';

        $remind_mail_html .= '  p {';
        $remind_mail_html .= '    display: block;';
        $remind_mail_html .= '    margin: 13px 0;';
        $remind_mail_html .= '  }';
        $remind_mail_html .= ' </style>';
        $remind_mail_html .= '  <!--[if mso]>';
        $remind_mail_html .= '    <xml>';
        $remind_mail_html .= '    <o:OfficeDocumentSettings>';
        $remind_mail_html .= '      <o:AllowPNG/>';
        $remind_mail_html .= '      <o:PixelsPerInch>96</o:PixelsPerInch>';
        $remind_mail_html .= '    </o:OfficeDocumentSettings>';
        $remind_mail_html .= '    </xml>';
        $remind_mail_html .= '    <![endif]-->';
        $remind_mail_html .= '  <!--[if lte mso 11]>';
        $remind_mail_html .= '    <style type="text/css">';
        $remind_mail_html .= '      .mj-outlook-group-fix { width:100% !important; }';
        $remind_mail_html .= '    </style>';
        $remind_mail_html .= '    <![endif]-->';
        $remind_mail_html .= '  <!--[if !mso]><!-->';
        $remind_mail_html .= '  <link href="https://use.typekit.net/alq3lif.css" rel="stylesheet" type="text/css">';
        $remind_mail_html .= '  <style type="text/css">';
        $remind_mail_html .= '  @import url(https://use.typekit.net/alq3lif.css);';
        $remind_mail_html .= '  </style>';
        $remind_mail_html .= '  <!--<![endif]-->';
        $remind_mail_html .= '  <style type="text/css">';
        $remind_mail_html .= '  @media only screen and (min-width:100px) {';
        $remind_mail_html .= '    .mj-column-per-100 {';
        $remind_mail_html .= '    width: 100% !important;';
        $remind_mail_html .= '    max-width: 100%;';
        $remind_mail_html .= '    }';
        $remind_mail_html .= '    .mj-column-per-90 {';
        $remind_mail_html .= '    width: 90% !important;';
        $remind_mail_html .= '    max-width: 90%;';
        $remind_mail_html .= '    }';
        $remind_mail_html .= '    .mj-column-px-NaN {';
        $remind_mail_html .= '    width: NaNfull-width !important;';
        $remind_mail_html .= '    max-width: NaNfull-width;';
        $remind_mail_html .= '    }';
        $remind_mail_html .= '    .mj-column-px-60 {';
        $remind_mail_html .= '    width: 60px !important;';
        $remind_mail_html .= '    max-width: 60px;';
        $remind_mail_html .= '    }';
        $remind_mail_html .= '    .mj-column-px-70 {';
        $remind_mail_html .= '    width: 70px !important;';
        $remind_mail_html .= '    max-width: 70px;';
        $remind_mail_html .= '    }';
        $remind_mail_html .= '    .mj-column-px-80 {';
        $remind_mail_html .= '    width: 80px !important;';
        $remind_mail_html .= '    max-width: 80px;';
        $remind_mail_html .= '    }';
        $remind_mail_html .= '    .mj-column-px-150 {';
        $remind_mail_html .= '    width: 150px !important;';
        $remind_mail_html .= '    max-width: 150px;';
        $remind_mail_html .= '    }';
        $remind_mail_html .= '  }';
        $remind_mail_html .= '  </style>';
        $remind_mail_html .= '  <style type="text/css">';
        $remind_mail_html .= '  @media only screen and (max-width:100px) {';
        $remind_mail_html .= '    table.mj-full-width-mobile {';
        $remind_mail_html .= '    width: 100% !important;';
        $remind_mail_html .= '    }';
        $remind_mail_html .= '    td.mj-full-width-mobile {';
        $remind_mail_html .= '    width: auto !important;';
        $remind_mail_html .= '    }';
        $remind_mail_html .= '  }';
        $remind_mail_html .= '  </style>';
        $remind_mail_html .= '</head>';
        
        $remind_mail_html .='<body style="background-color:#F9F9F9;">';
        $remind_mail_html .= $remind_mail_message;
        $remind_mail_html .='</body>';
        $remind_mail_html .='</html>';

      

          $headers = "From: " .  "Tough Cookies: Boston's Weekly Meal Prep - Keto & Paleo Plans"  . "< help@toughcookies.co >";
          


          add_filter('wp_mail_content_type','wpse27856_set_content_type');
          $usr_email = (isset($user->data->user_email) && !empty($user->data->user_email))?$user->data->user_email:'';
          @wp_mail($usr_email, $remind_mail_subject, $remind_mail_html,$headers);
        }
      }
   // }
  }
}

/*
* Method: function for get customer/client system's ip address
*/

function get_client_ip(){
  //whether ip is from share internet
  if(!empty($_SERVER['HTTP_CLIENT_IP'])){
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
  }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){//whether ip is from proxy
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
  }else{//whether ip is from remote address
    $ip_address = $_SERVER['REMOTE_ADDR'];
  }
  return !empty($ip_address) ? $ip_address : "";
}

/*
* Method: function for get locked orders users list
*/

function get_locked_orders_users(){
  if(isset($_POST['srch_val']) && !empty($_POST['srch_val'])){
    global $wpdb;
    date_default_timezone_set("America/New_York");
    $lcked_odr_end_date = time();
    $lst_ml_delvred_date = date('Y-m-d', strtotime('previous sunday', $lcked_odr_end_date));
    if(date("N", $loop_end_date) > 3){
      $lst_ml_delvred_date = date('Y-m-d', strtotime('next sunday', $lcked_odr_end_date));
    }elseif(date("N", $lcked_odr_end_date) == 3 && date('H') >= 12){
      $lst_ml_delvred_date = date('Y-m-d', strtotime('next sunday', $lcked_odr_end_date));
    }
    $srch_val = $_POST['srch_val'];
    $sql = "SELECT usr.* FROM ".$wpdb->prefix."users_meals_orders as umo JOIN ".$wpdb->prefix."users usr ON (usr.ID = umo.user_id) WHERE umo.week_end_date <= '".$lst_ml_delvred_date."' AND (usr.user_login LIKE '%".$srch_val."%' OR usr.user_url LIKE '%".$srch_val."%' OR usr.user_email LIKE '%".$srch_val."%' OR usr.user_nicename LIKE '%".$srch_val."%' OR usr.display_name LIKE '%".$srch_val."%')";
    $found_users = $wpdb->get_results($sql);
    if($found_users) {
      foreach($found_users as $user) {
        $label = empty( $user->user_email ) ? $user->user_login : "{$user->user_login} ({$user->user_email})";
        $lo_user_list[] = array(
          'lel'   => $label,
          'vl'   => $user->user_login,
          'usr_id' => $user->ID
        );
      }
      $response = array('error'=>0,'lo_users'=>$lo_user_list);
    }else{
      $response = array('error'=>1,'lo_users'=>array());
    }
  }else{
    $response = array('error'=>1,'lo_users'=>array());
  }
  echo json_encode($response);
  exit();
}

/*
* Method: function for hiding slug from membership group
*/
add_filter('edit_membershipgroup_slug','hide_slug_membershipgroup');
function hide_slug_membershipgroup(){
  echo '<style>'; ?>
    input#tag-slug{display:none;}
    label[for=tag-slug]{display:none !important;}
    #tag-slug + p {display:none !important;}
    input#slug{display:none;}
    label[for=slug]{display:none !important;}
    #slug + p {display:none !important;}
  <?php echo '</style>';
}

/*
* Method: function for saving zipcode and check if user is logged in and has entered zipcode * or not
*/
function check_zipcode_availability(){
  $user_id = get_current_user_id();
  if (isset($user_id) && !empty($user_id) && $user_id>0){
    $parameters = array();
    parse_str($_POST['fdata'], $parameters);
    $is_error = 0;
    $error_msg = array();
    if(isset($parameters['zip_code']) && !empty($parameters['zip_code'])){
      $zip = $parameters['zip_code'];
    }else{
      $is_error = 1;
      $error_msg['zip_code'] = 'Zip code is a required field.';
    }
    if(isset($is_error) && $is_error==1){
      $response = array('error'=>1,'msg'=>$error_msg);
    }else{
      $zip_find_status = get_zip_info($zip);
      if($zip_find_status){
        update_user_meta($user_id, 'billing_postcode', $zip);
        update_user_meta($user_id, 'shipping_postcode', $zip);
        update_user_meta($user_id, 'pmpro_bzipcode', $zip);
        $response = array('error'=>0,'msg'=>'Zipcode updated successfully.');
      }else{
        $is_error = 1;
        $error_msg['zip_code'] = 'Sorry, we currently do not deliver to your zip code.';
        $response = array('error'=>1,'msg'=>$error_msg);
      }
    }
  }else{
    $response = array('error'=>1,'msg'=>$error_msg['Something goes wrong, please try again!'],'redirecturl'=>site_url('/sign-up'));
  }
  echo json_encode($response);
  exit();
}

//add columns to User panel list page
function add_user_columns($column) {
  $column['allergy'] = 'Allergy';
  $column['subscription_acc_status'] = 'Subscription Account Status';
  return $column;
}
add_filter( 'manage_users_columns', 'add_user_columns' );

//add the data
function add_user_column_data( $val, $column_name, $user_id ) {
  switch ($column_name) {
    case 'allergy' :
      $slcted_allergy = get_user_meta( $user_id, 'allergies', true );
      return (isset($slcted_allergy[0]) && !empty($slcted_allergy[0]))?ucwords(str_replace('_', ' ', $slcted_allergy[0])):'';
      break;
    case 'subscription_acc_status' :
      if(is_super_admin($user_id)){
        $subscription_acc_status = '-';
      }else{
        $subscription_acc_status = get_user_meta($user_id, 'subscription_account_status', true );
        switch($subscription_acc_status){
          case '0':
            $subscription_acc_status = 'Pending';
            break;
          case '1':
            $subscription_acc_status = 'Active';
            break;
          case '2':
            $subscription_acc_status = 'Cancelled';
            break;
          default :
            $subscription_acc_status = 'Pending';
            break;
        }
      }
      return $subscription_acc_status;
    break;
    default:
  }
  return;
}
add_filter('manage_users_custom_column', 'add_user_column_data', 1, 3);

/*
* Function for send notification email to users for order processed
*/
function send_meal_order_processed_notification($ord_smry_data = ''){
  echo "running....";
  $order_processed_mail_html = "";
  if(!empty($ord_smry_data)){
    global $wpdb;
    date_default_timezone_set("America/New_York");
    $plan_grp_data = get_plan_group_by_plan_id($ord_smry_data['membership_plan_id']);
    $usr_pln_nm = (isset($plan_grp_data['name']) && !empty($plan_grp_data['name']))?$plan_grp_data['name']:'';
    $dlvry_date = (isset($ord_smry_data['loked_wk_sunday']) && !empty($ord_smry_data['loked_wk_sunday']))?date("l, F jS",strtotime($ord_smry_data['loked_wk_sunday'])):'';
    $sub_total_amt = (isset($ord_smry_data['billing_amount']) && !empty($ord_smry_data['billing_amount']))? $ord_smry_data['billing_amount'] : 0;
    $total_amt = $sub_total_amt;
  
  
    ////ADDED*

    $mem_pln_detail = pmpro_getLevel($ord_smry_data['membership_plan_id']);
    $pln_name = (isset($mem_pln_detail->name) && !empty($mem_pln_detail->name))?$mem_pln_detail->name:'';
    $pln_name= explode("-",$pln_name);
    $pln_description = (isset($mem_pln_detail->description) && !empty($mem_pln_detail->description))?$mem_pln_detail->description:'';
    $meal_per_day = get_pmpro_membership_level_meta($ord_smry_data['membership_plan_id'],'_meal_per_day',true);
    $meal_price = (isset($ord_smry_data['initial_payment']) && !empty($ord_smry_data['initial_payment']))?pmpro_formatPrice($ord_smry_data['initial_payment']):0;
    $selected_days = (isset($ord_smry_data['selected_days']) && !empty($ord_smry_data['selected_days']))?$ord_smry_data['selected_days']:0;
    $mpd_txt = ($meal_per_day == 1)?'meal per day':'meals per day';
   //$meals_per_week = $selected_days.' Days per week — '.$meal_per_day.' '.$mpd_txt;
    $meals_per_week = 'Mon-Fri -' . $pln_name[1]; //'Mon-Fri - ' .$selected_days.' days per week';//.$meal_per_day.' '.$mpd_txt;
    $first_delivry_date = date("l, F jS",strtotime($ord_smry_data['start_date']));
    $weekly_tax_amount = $ord_smry_data['tax_amount'];
    $pmpro_AccountNumber = $ord_smry_data['pmpro_AccountNumber'];
    $pmpro_CardType    = $ord_smry_data['pmpro_CardType'];
    $pmpro_ExpirationMonth = $ord_smry_data['pmpro_ExpirationMonth'];
    $pmpro_ExpirationYear = $ord_smry_data['pmpro_ExpirationYear']; 
    ///END ADDED*
  
  
  
    if(isset($ord_smry_data['addon_meals_amount']) && $ord_smry_data['addon_meals_amount'] > 0){
      $addon_amount = $ord_smry_data['addon_meals_amount'];
      $total_amt = $sub_total_amt + $ord_smry_data['addon_meals_amount'];
    }
    $delivery_fee = (!empty($ord_smry_data['delivery_fee'])) ? pmpro_formatPrice($ord_smry_data['delivery_fee']) : 'FREE';
    $user = get_userdata($ord_smry_data['user_id']);
    $user_meta = get_user_meta($ord_smry_data['user_id']);
    $loked_wk_sql = "SELECT um.meal_date,um.status,um.week_start_date,um.week_end_date,p.* FROM wp_users_meals as um JOIN $wpdb->posts p ON (p.ID = um.meal_id) WHERE p.post_status = 'publish' AND um.user_id = ".$ord_smry_data['user_id']." AND um.week_start_date = '".$ord_smry_data['loked_wk_sunday']."' AND um.week_end_date = '".$ord_smry_data['loked_wk_saturday']."'";
    $loked_wk_meals = $wpdb->get_results($loked_wk_sql);
    if(!empty($loked_wk_meals) && count($loked_wk_meals)>0){
      $loked_wk_meals = arrange_meals_in_cat_orders($loked_wk_meals);
      $addon_count = 0;
      foreach ($loked_wk_meals as $cmkey => $cmval) {
        $loked_wk_meals_arr[$cmval->meal_date][] = $cmval;
        $_price = get_post_meta($cmval->ID,'_price',true);
        if(!empty($_price) && $_price > 0){
          $addon_count++;
        }
      }
      ksort($loked_wk_meals_arr);
      $bloginfo = get_bloginfo('name');
      $order_processed_mail_subject = "Your Tough Cookies Weekly Order Summary";
      $order_processed_mail_message ="<div style='background-color:#F9F9F9;'>
    <!-- Top Menu -->
    <!--[if mso | IE]> 
      <table
         align='center' border='0' cellpadding='0' cellspacing='0' class='' style='width:480px;' width='480'
      >
        <tr>
          <td style='line-height:0px;font-size:0px;mso-line-height-rule:exactly;'>
      <![endif]-->
    <div style='background:#F9F9F9;background-color:#F9F9F9;margin:0px auto;max-width:480px;'>
      <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:#F9F9F9;background-color:#F9F9F9;width:100%;'>
        <tbody>
          <tr>
            <td style='direction:ltr;font-size:0px;padding:0;text-align:center;'>
              <!--[if mso | IE]>
                  <table role='presentation' border='0' cellpadding='0' cellspacing='0'>

        <tr>

            <td
               class='' style='vertical-align:top;width:480px;'
            >
          <![endif]-->
              <div class='mj-column-per-100 mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'>
                <table border='0' cellpadding='0' cellspacing='0' role='presentation' width='100%'>
                  <tbody>
                    <tr>
                      <td style='vertical-align:top;padding:10px 0 0 0;'>
                        <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='' width='100%'>
                          <tr>
                            <td align='center' style='font-size:0px;padding:10px 25px;word-break:break-word;'>
                              <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:collapse;border-spacing:0px;'>
                                <tbody>
                                  <tr>
                                    <td style='width:90px;'> <a href='http://www.toughcookies.co/' target='_blank'>
      <img
         height='auto' src='https://toughcookies.co/wp-content/uploads/2020/12/Logo-Light-2020-Version-1.png' style='border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:14px;' width='90'
      />

        </a> </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!--[if mso | IE]>
            </td>";

            $order_processed_mail_message .='<td
               class="" style="vertical-align:top;width:480px;"
            >
          <![endif]-->
              <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tr>
                    <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <div style="font-family:acumin-pro, Helvetica, sans-serif;font-size:14px;font-weight:300;line-height:14px;text-align:center;color:#A0A0A0;">
                        <p><a style="text-decoration: none; color:#A0A0A0" href="https://toughcookies.co/how-it-works/">How it Works</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a style="text-decoration: none; color:#A0A0A0" href="https://toughcookies.co/upcoming/">Upcoming</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                          <a style="text-decoration: none; color:#A0A0A0" href="https://toughcookies.co/contact/">Contact</a> </p>
                      </div>
                    </td>
                  </tr>
                </table>
              </div>
              <!--[if mso | IE]>
            </td>';

        $order_processed_mail_message .="</tr>

                  </table>
                <![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      <![endif]-->
    <!-- Image Header -->
    <!--[if mso | IE]>
      <table
         align='center' border='0' cellpadding='0' cellspacing='0' class='' style='width:480px;' width='480'
      >
        <tr>
          <td style='line-height:0px;font-size:0px;mso-line-height-rule:exactly;'>
      <![endif]-->
    <div style='background:#FFFAEC;background-color:#FFFAEC;margin:0px auto;max-width:480px;'>
      <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:#FFFAEC;background-color:#FFFAEC;width:100%;'>
        <tbody>
          <tr>
            <td style='direction:ltr;font-size:0px;padding:0 0 0 0;text-align:center;'>
              <!--[if mso | IE]>
                  <table role='presentation' border='0' cellpadding='0' cellspacing='0'>

        <tr>

            <td
               class='' style='vertical-align:top;width:480px;'
            >
          <![endif]-->
              <div class='mj-column-per-100 mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'>
                <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'>
                  <tr>
                    <td align='center' style='font-size:0px;padding:0px 25px;padding-right:0px;padding-bottom:0px;padding-left:0px;word-break:break-word;'>
                      <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:collapse;border-spacing:0px;'>
                        <tbody>
                          <tr>
                            <td style='width:480px;'> <img height='auto' src='https://toughcookies.co/wp-content/uploads/2020/12/Welcome-Header@2x.jpg' style='border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:14px;' title='' width='480'
                              /> </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </table>
              </div>
              <!--[if mso | IE]>
            </td>

        </tr>

                  </table>
                <![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      <![endif]-->
    <!-- Delivery Text -->
    <!--[if mso | IE]>
      <table
         align='center' border='0' cellpadding='0' cellspacing='0' class='' style='width:480px;' width='480'
      >
        <tr>
          <td style='line-height:0px;font-size:0px;mso-line-height-rule:exactly;'>
      <![endif]-->
    <div style='background:#18254A;background-color:#18254A;margin:0px auto;max-width:480px;'>
      <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:#18254A;background-color:#18254A;width:100%;'>
        <tbody>
          <tr>
            <td style='direction:ltr;font-size:0px;padding:40px 0 35px;text-align:center;'>
              <!--[if mso | IE]>
                  <table role='presentation' border='0' cellpadding='0' cellspacing='0'>

        <tr>

            <td
               class='' style='vertical-align:top;width:NaNpx;'
            >
          <![endif]-->
              <div class='mj-column-px-NaN mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'>
                <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'>
                   <tr>
                    <td align='center' style='font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;'>
                      <div style='font-family:acumin-pro, Helvetica, 'sans-serif';font-size:15px;line-height:30px;text-align:center;color:#FFFFFF;'><span style='font-size: 15px; line-height: 25px; font-weight: 400; color:#fff; margin-bottom:5; display:block;'>Your delivery arrives</span><span style='font-size: 18px; line-height: 20px; font-weight: bold; color:#fff; margin-bottom:5px; display:block; text-transform: uppercase;'>$dlvry_date</span></div>
                    </td>
                  </tr>
                </table>
              </div>
              <!--[if mso | IE]>
            </td>

        </tr>

                  </table>
                <![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      <![endif]-->";

    $order_processed_mail_message .= '<!-- Products -->
      <div style="background:#FFFFFF;background-color:#FFFFFF;margin:0px auto;max-width:480px;padding-top:40px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#FFFFFF;background-color:#FFFFFF;width:80%;">
          <tbody>
            <tr>
              <td align="center" style="font-size:0px;padding-top:0;padding-bottom:35px;word-break:break-word;">
                <div style="font-family:Helvetica;font-weight:bold;font-size:15px;line-height:1;text-align:center;color:#222222;">ORDER SUMMARY</div>
            </td>
      </tr>';
        if(!empty($loked_wk_meals_arr) && is_array($loked_wk_meals_arr)){
        $count = 1;
        foreach ($loked_wk_meals_arr as $lwma_key => $lwma_val) {
          $unixTimestamp = strtotime($lwma_key);
          $dayOfWeek = date("l", $unixTimestamp);
            $order_processed_mail_message .= "<tr>
              <td align='left' style='font-size:0px;padding-top:5px;padding-bottom:15px;word-break:break-word;'>
                <div style='font-family:Helvetica;font-weight:bold;font-size:13px;line-height:1;text-align:left;color:#222222;text-transform: uppercase;'>$dayOfWeek</div>
            </td>
      </tr>";
        if(!empty($lwma_val) && is_array($lwma_val)){
         foreach ($lwma_val as $lwma_v_key => $lwma_v_val) {
                $meal_nam = (isset($lwma_v_val->post_title) && !empty($lwma_v_val->post_title)) ? $lwma_v_val->post_title : '';
                $_card_image = wp_get_attachment_image_src( get_post_thumbnail_id($lwma_v_val->ID), array(55,55));
        
        $meal_img = (isset($_card_image[0]) && $_card_image[0]!='')? $_card_image[0]:TOUGHCOOKIES_URL.'images/no-image-found-354x170.png';
                $order_processed_mail_message .= "<tr>";
                $order_processed_mail_message .= "<td valign='top'> 
                                  <table align='center' border='0' cellpadding='0' cellspacing='0' class='product-row'
                                    width='100%' style='margin-bottom:20px; display:block;'>
                                    <tr>
                                     <td>
                                      <td valign='top'>
                                        <table align='left' border='0' cellpadding='0' cellspacing='0' class='device-width'
                                          style='width: 100%; max-width: 55px; padding-bottom:-10px;'>
                                          <tr>
                                            <td valign='top'><img alt='Tough Cookies Menu Item'
                                                  class='product-img'  src=$meal_img alt=$meal_nam
                                                  style='outline:none;text-decoration:none;border: none; border-radius: 5px; display: block;'
                                                  width='55' height='55'></td>
                                          </tr>
                                        </table>
                                      </td>
                                        <!--[if (gte mso 9)|(IE)]>
                                </td>
                                <td valign='top'>
                              <table width='75' align='left' cellpadding='0' cellspacing='0' border='0'>
                              <tr>
                              <td valign='top'>
                          <![endif]-->
                                      </td>
                                      <td valign='top'>
                                        <table border='0' cellpadding='0' cellspacing='0' class='product-descriptions'
                                          width='300'>
                                          <tr>
                                            <td style='cursor:auto;color:#000000;text-align:left;' valign='top'>
                                              <span style='font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:18px;padding-left:10px;'>
                                               $meal_nam
                                              </span>
                                            </td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>";
                }
      }
            $order_processed_mail_message .="<tr>
                                <td height='35' valign='top'></td>
                              </tr>";
                }
          }
            $order_processed_mail_message .="</tbody>
                          </table>
        </div>
                        <!--[if mso | IE]>
                              </td>
                            </tr>
                          </table>";
              
    $order_processed_mail_message .="<!-- RECEIPT -->";
  

  $order_processed_mail_message .='  <!-- RECEIPT -->
  <div style="background:#FFFFFF;background-color:#FFFFFF;margin:0px auto;max-width:480px;padding-top:0;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#FFFFFF;background-color:#FFFFFF;width:80%;">
      <tbody>
              <tr>
                <td valign="top" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell;">
                  <table border="0" width="100%" cellpadding="0" cellspacing="0" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse;">
                    <tr>
                      <td class="receipt__container" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell; border: 1px solid #ECECEC; padding: 25px;">
                        <table border="0" width="100%" cellpadding="0" cellspacing="0" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse;">
                          <tr>
                            <td width="80%" class="textAlignLeft" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell; text-align: left;">
                              <div class="text-list textColorNormal" style="font-family: Helvetica, Arial, sans-serif; font-weight: 400; font-size: 14px; line-height: 22px; color: rgb(79, 79, 101);">
                                Sub total
                              </div>
                            </td>
                            <td width="20%" align="right" class="textAlignRight" valign="top" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell; text-align: right;">
                              <div class="text-list textColorNormal" style="font-family: Helvetica, Arial, sans-serif; font-weight: 400; font-size: 14px; line-height: 22px; color: rgb(79, 79, 101);">
                                '.pmpro_formatPrice($sub_total_amt).'
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2" style="height: 12px; line-height: 12px; max-height: 12px; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell;"></td>
                          </tr>
                        </table>
                        <table border="0" width="100%" cellpadding="0" cellspacing="0" class="receipt__row" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-top: 1px solid #ECECEC;">
                          <tr>
                            <td colspan="2" style="height: 12px; line-height: 12px; max-height: 12px; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell;"></td>
                          </tr>
                          <tr>
                            <td width="80%" class="textAlignLeft" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell; text-align: left;">
                              <div class="text-list textColorNormal" style="font-family: Helvetica, Arial, sans-serif; font-weight: 400; font-size: 14px; line-height: 22px; color: rgb(79, 79, 101);">
                                Sales Tax
                              </div>
                            </td>
                            <td width="20%" align="right" class="textAlignRight" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell; text-align: right;">
                              <div class="text-list textColorNormal" style="font-family: Helvetica, Arial, sans-serif; font-weight: 400; font-size: 14px; line-height: 22px; color: rgb(79, 79, 101);">
                                '.$weekly_tax_amount.'
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2" style="height: 12px; line-height: 12px; max-height: 12px; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell;"></td>
                          </tr>
                        </table>
                        <table border="0" width="100%" cellpadding="0" cellspacing="0" class="receipt__row" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-top: 1px solid #ECECEC;">
                          <tr>
                            <td colspan="2" style="height: 12px; line-height: 12px; max-height: 12px; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell;"></td>
                          </tr>
                          <tr>
                            <td width="80%" class="textAlignLeft" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell; text-align: left;">
                              <div class="text-list text-bold textColorDark" style="font-family: Helvetica, Arial, sans-serif; font-weight: 400; font-size: 14px; line-height: 22px; font-weight: 600; color: rgb(35, 35, 62);">
                                Total
                              </div>
                            </td>
                            <td width="20%" align="right" class="textAlignRight" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell; text-align: right;">
                              <div class="text-list text-bold textColorDark" style="font-family: Helvetica, Arial, sans-serif; font-weight: 400; font-size: 14px; line-height: 22px; font-weight: 600; color: rgb(35, 35, 62);">
                                '.  pmpro_formatPrice($total_amt) .'
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2" style="height: 25px; line-height: 25px; max-height: 25px; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell;"></td>
                          </tr>
                          <tr style="padding: 10px;">
                            <td colspan="2" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell;">
                              <table border="0" width="100%" cellpadding="0px" cellspacing="0" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse;">
                                <tr>
                                  <td class="message" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell; background-color: #F9F9F9; padding: 18px;">
                                    <table border="0" width="100%" cellpadding="0px" cellspacing="0" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse;">
                                      <tr>
                                        <td colspan="2" padding="0px" class="textColorNormal" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell; font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: #222222;"> Paid with '.$pmpro_CardType .'&nbsp;'. $pmpro_AccountNumber . '&nbsp;('. $pmpro_ExpirationMonth .'/'. $pmpro_ExpirationYear .')'. '</td>
                                      </tr>
                                      <tr class="spacer">
                                        <td height="12px" colspan="2" style="font-size: 12px; line-height:12px; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell;">&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td class="textSmall textColorGrayDark" colspan="2" padding="0px" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell; font-family: Helvetica, Arial, sans-serif; font-weight: 400; font-size: 13px; color: #666666;">
                                        On your statement, this charge will appear as<br /> "Tough Cookies, LLC" </td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr class="spacer">
                <td height="18px" colspan="2" style="font-size: 18px; line-height:18px; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell;">&nbsp;</td>
              </tr>
              <tr class="spacer">
                <td height="18px" colspan="2" style="font-size: 18px; line-height:18px; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; display: table-cell;">&nbsp;</td>
              </tr>
            </tbody>
          </table>
        </div>
        <!--[if mso | IE]>
              </td>
            </tr>
          </table>
        <![endif]-->
<!-- EDIT RECEIPT -->';


 
   $order_processed_mail_message .= '<!-- Plan Summary Minimized -->
    <!--[if mso | IE]>
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:480px;" width="480"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
    <div style="background:#FFFFFF;background-color:#FFFFFF;margin:0px auto;max-width:480px;">
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#FFFFFF;background-color:#FFFFFF;width:100%;">
        <tbody>
          <tr>
            <td style="direction:ltr;font-size:0px;padding:0 5% 35px;text-align:center;">
              <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">

        <tr>

            <td
               class="" style="vertical-align:top;width:423px;"
            >
          <![endif]-->
              <div class="mj-column-per-90 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                  <tbody>
                    <tr>
                      <td style="background-color:#F9F9F9;vertical-align:top;padding:25px 10px 25px;">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="" width="100%">
                          <tr>
                            <td align="left" style="font-size:0px;padding:10px 25px;padding-top:5px;padding-bottom:0;word-break:break-word;">
                              <div style="font-family:Helvetica;font-size:14px;line-height:1;text-align:left;color:#222222;">Plan Type</div>
                            </td>
                          </tr>
                          <tr>
                            <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">';
                             $order_processed_mail_message .= "<div style='font-family:Helvetica;font-size:14px;line-height:1;text-align:left;color:#666666;'>" . $pln_name[0] ."</div>";
                          $order_processed_mail_message .= "</td>
                          </tr>
                          <tr>
                            <td align='left' style='font-size:0px;padding:10px 25px;padding-top:5px;padding-bottom:0;word-break:break-word;'>
                              <div style='font-family:Helvetica;font-size:14px;line-height:1;text-align:left;color:#222222;'>Meals per week</div>
                            </td>
                          </tr>
                          <tr>
                            <td align='left' style='font-size:0px;padding:10px 25px;word-break:break-word;'>
                              <div style='font-family:Helvetica;font-size:14px;line-height:1;text-align:left;color:#666666;'>$meals_per_week</div>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!--[if mso | IE]>
            </td>

        </tr>

                  </table>
                <![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      <![endif]-->
    <!-- Referral -->
    <!--[if mso | IE]>";
     $order_processed_mail_message .= '<table
         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:480px;" width="480"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
    <div style="background:#4D6CFF;background-color:#4D6CFF;margin:0px auto;max-width:480px;">
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#4D6CFF;background-color:#4D6CFF;width:100%;">
        <tbody>
          <tr>
            <td style="direction:ltr;font-size:0px;padding:0;text-align:center;">
              <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">

        <tr>

            <td
               class="" style="vertical-align:top;width:480px;"
            >
          <![endif]-->
              <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tr>
                    <td align="center" style="font-size:0px;padding:0px 25px;padding-right:0px;padding-bottom:0px;padding-left:0px;word-break:break-word;">
                      <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
                        <tbody>
                          <tr>
                            <td style="width:480px;"> <a href="'.site_url('free-meals').'" target="_blank"><img height="auto" src="https://toughcookies.co/wp-content/uploads/2020/12/Refer-Friend-Module@2x-1.jpg" style="border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:14px;" title="" width="480"
                              /></a> </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </table>
              </div>
              <!--[if mso | IE]>
            </td>

        </tr>

                  </table>
                <![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      <![endif]-->

        <!-- Problem with order? -->
      <!--[if mso | IE]>
        <table
           align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:480px;" width="480"
        >
          <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
        <![endif]-->
      <div style="background:#F9F9F9;background-color:#F9F9F9;margin:0px auto;max-width:480px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#F9F9F9;background-color:#F9F9F9;width:100%;">
          <tbody>
            <tr>
              <td style="direction:ltr;font-size:0px;padding:40px 0 0;text-align:center;">
                <!--[if mso | IE]>
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">

          <tr>

              <td
                 class="" style="vertical-align:top;width:NaNpx;"
              >
            <![endif]-->
                <div class="mj-column-px-NaN mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                    <tr>
                      <td align="center" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:10px;word-break:break-word;">
                        <div style="font-family:acumin-pro, Helvetica, sans-serif;font-size:18px;font-weight:600;line-height:24px;text-align:center;color:#5A5A5A;">Any problems with this order?<br/><span style="font-size: 14px; line-height: 18px; font-weight: 300;">Please let us know immediatly</span></div>
                      </td>
                    </tr>
                    <tr>
                      <td align="center" vertical-align="middle" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;line-height:100%;">
                          <tr>
                            <td align="center" bgcolor="#F9F9F9" role="presentation" style="border:none;border-radius:3px;cursor:auto;mso-padding-alt:0;background:#F9F9F9;" valign="middle"> <a href="'.site_url('upcoming').'" style="display:inline-block;background:#F9F9F9;color:#4D79D2;font-family:Helvetica;font-size:15px;font-weight:600;line-height:120%;margin:0;text-decoration:none;text-transform:none;padding:0;mso-padding-alt:0px;border-radius:3px;"
                                target="_blank">
                ORDER DETAILS
              </a> </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </div>
                <!--[if mso | IE]>
              </td>

          </tr>

                    </table>
                  <![endif]-->
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!--[if mso | IE]>
            </td>
          </tr>
        </table>
        <![endif]-->

    <!-- Footer -->
    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#F9F9F9;background-color:#F9F9F9;width:100%;">
      <tbody>
        <tr>
          <td>
            <!--[if mso | IE]>
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:480px;" width="480"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
            <div style="margin:0px auto;max-width:480px;">
              <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                <tbody>
                  <tr>
                    <td style="direction:ltr;font-size:0px;padding:30px 0 30px 0;text-align:center;">
                      <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">

        <tr>

            <td
               class="" style="vertical-align:top;width:480px;"
            >
          <![endif]-->
                      <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                          <tr>
                            <td align="center" style="font-size:0px;padding:0;word-break:break-word;">
                              <!--[if mso | IE]>
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
      >
        <tr>

              <td>
            <![endif]-->
                              <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="float:none;display:inline-table;">
                                <tr>
                                  <td style="padding:4px;vertical-align:middle;">
                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-radius:3px;width:18px;">
                                      <tr>
                                        <td style="font-size:0;height:18px;vertical-align:middle;width:18px;"> <a href="https://facebook.com/ToughCookies/" target="_blank">
                    <img
                       height="18" src="https://toughcookies.co/wp-content/uploads/2019/10/FacebookIcon-1.png" style="border-radius:3px;display:block;" width="18"
                    />
                  </a> </td>
                                      </tr>
                                    </table>
                                  </td>
                                  <td style="vertical-align:middle;"> <a href="https://facebook.com/ToughCookies/" style="color:#333333;font-size:15px;font-family:Helvetica;line-height:22px;text-decoration:none;" target="_blank">
              &nbsp;&nbsp;
            </a> </td>
                                </tr>
                              </table>
                              <!--[if mso | IE]>
              </td>

              <td>
            <![endif]-->
                              <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="float:none;display:inline-table;">
                                <tr>
                                  <td style="padding:4px;vertical-align:middle;">
                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-radius:3px;width:18px;">
                                      <tr>
                                        <td style="font-size:0;height:18px;vertical-align:middle;width:18px;"> <a href="https://instagram.com/toughcookiesco/" target="_blank">
                    <img
                       height="18" src="https://toughcookies.co/wp-content/uploads/2019/10/instagramIcon.png" style="border-radius:3px;display:block;" width="18"
                    />
                  </a> </td>
                                      </tr>
                                    </table>
                                  </td>
                                  <td style="vertical-align:middle;"> <a href="https://instagram.com/toughcookiesco/" style="color:#333333;font-size:15px;font-family:Helvetica;line-height:22px;text-decoration:none;" target="_blank">
              &nbsp;&nbsp;
            </a> </td>
                                </tr>
                              </table>
                              <!--[if mso | IE]>
              </td>

              <td>
            <![endif]-->
                              <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="float:none;display:inline-table;">
                                <tr>
                                  <td style="padding:4px;vertical-align:middle;">
                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-radius:3px;width:18px;">
                                      <tr>
                                        <td style="font-size:0;height:18px;vertical-align:middle;width:18px;"> <a href="http://m.me/ToughCookies" target="_blank">
                    <img
                       height="18" src="https://toughcookies.co/wp-content/uploads/2019/10/MessengerIcon.png" style="border-radius:3px;display:block;" width="18"
                    />
                  </a> </td>
                                      </tr>
                                    </table>
                                  </td>
                                  <td style="vertical-align:middle;"> <a href="http://m.me/ToughCookies" style="color:#333333;font-size:15px;font-family:Helvetica;line-height:22px;text-decoration:none;" target="_blank">
              &nbsp;
            </a> </td>
                                </tr>
                              </table>
                              <!--[if mso | IE]>
              </td>

          </tr>
        </table>
      <![endif]-->
                            </td>
                          </tr>
                        </table>
                      </div>
                      <!--[if mso | IE]>
            </td>

            <td
               class="" style="vertical-align:top;width:480px;"
            >
          <![endif]-->
                      <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                          <tr>
                            <td align="center" style="font-size:0px;padding:0;word-break:break-word;">
                              <div style="font-family:acumin-pro, Helvetica, sans-serif;font-size:14px;font-weight:300;line-height:30px;text-align:center;color:#7C7C7C;">
                                <p><a style="text-decoration: none; color:#777777; border-right: 1px solid #E6E6E6; padding:12px 15px 10px 15px;" href="tel:781-436-0235">Call us</a><a style="text-decoration: none; color:#777777; border-right: 1px solid #E6E6E6; padding:12px 15px 10px 15px;"
                                    href="mailto:hello@toughcookies.co">Email us</a><a style="text-decoration: none; color:#777777; border-right: 1px solid #E6E6E6; padding:12px 15px 10px 15px;" href="sms:+17814360235">Text us</a><a style="text-decoration: none; color:#777777; padding:12px 15px 10px 15px;"
                                    href="https://toughcookies.co/faqs/">FAQ</a></p>
                              </div>
                            </td>
                          </tr>
                        </table>
                      </div>
                      <!--[if mso | IE]>
            </td>

            <td
               class="" style="vertical-align:top;width:480px;"
            >
          <![endif]-->
                      <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                          <tr>
                            <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                              <div style="font-family:acumin-pro, Helvetica, sans-serif;font-size:12px;font-weight:300;line-height:20px;text-align:center;color:#7C7C7C;">Copyright © 2021 Tough Cookies, LLC | All Rights Reserved 46 Rockland St, Hanover, MA 02329</div>
                            </td>
                          </tr>
                        </table>
                      </div>
                      <!--[if mso | IE]>
            </td>

        </tr>

                  </table>
                <![endif]-->
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      <![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
    <!--[if mso | IE]>
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:480px;" width="480"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->

    <div style="margin:0px auto;max-width:480px;">
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
        <tbody>
          <tr>
            <td style="direction:ltr;font-size:0px;padding:0;text-align:center;">
              <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">

        <tr>

            <td
               class="" style="vertical-align:top;width:480px;"
            >
          <![endif]-->
              <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tr>
                    <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <div style="font-family:Helvetica;font-size:14px;line-height:1;text-align:center;color:#7C7C7C;">
                        <p>Having trouble viewing this email? <a href="#" style="font-weight: bold; text-decoration: none; color:#7C7C7C">View Online</a></p>
                      </div>
                    </td>
                  </tr>
                </table>
              </div>
              <!--[if mso | IE]>
            </td>

        </tr>

                  </table>
                <![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      <![endif]-->
  </div>';
   
    $order_processed_mail_html .='<!doctype html>
    <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
    <head>
      <title> </title>
      <!--[if !mso]><!-- -->
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!--<![endif]-->
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <style type="text/css">
      #outlook a {
        padding: 0;
      }

      body {
        margin: 0;
        padding: 0;
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
      }

      table,
      td {
        border-collapse: collapse;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
      }

      img {
        border: 0;
        height: auto;
        line-height: 100%;
        outline: none;
        text-decoration: none;
        -ms-interpolation-mode: bicubic;
      }

      p {
        display: block;
        margin: 13px 0;
      }
      </style>
      <!--[if mso]>
        <xml>
        <o:OfficeDocumentSettings>
          <o:AllowPNG/>
          <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
        </xml>
        <![endif]-->
      <!--[if lte mso 11]>
        <style type="text/css">
          .mj-outlook-group-fix { width:100% !important; }
        </style>
        <![endif]-->
      <!--[if !mso]><!-->
      <link href="https://use.typekit.net/alq3lif.css" rel="stylesheet" type="text/css">
      <style type="text/css">
      @import url(https://use.typekit.net/alq3lif.css);
      </style>
      <!--<![endif]-->
      <style type="text/css">
      @media only screen and (min-width:100px) {
        .mj-column-per-100 {
        width: 100% !important;
        max-width: 100%;
        }
        .mj-column-px-NaN {
        width: NaNfull-width !important;
        max-width: NaNfull-width;
        }
        .mj-column-per-90 {
        width: 90% !important;
        max-width: 90%;
        }
      }
      </style>
      <style type="text/css">
      @media only screen and (max-width:100px) {
        table.mj-full-width-mobile {
        width: 100% !important;
        }
        td.mj-full-width-mobile {
        width: auto !important;
        }
      }
      </style>
    </head>';


   
      $order_processed_mail_html .='</head>';
      $order_processed_mail_html .='<body style="background-color:#F9F9F9;">';
      $order_processed_mail_html .= $order_processed_mail_message;
      $order_processed_mail_html .='</body>';
      $order_processed_mail_html .='</html>';
       $bloginfo = get_bloginfo('name');
      //$headers = "From: Your Tough Cookies Weekly Order Summary " .  '<' . get_bloginfo('admin_email') . '>';
      $headers = "From: Your Tough Cookies Weekly Order Summary " .  '< help@toughcookies.co >';

      add_filter('wp_mail_content_type','wpse27856_set_content_type');
      $usr_email = (isset($user->data->user_email) && !empty($user->data->user_email))?$user->data->user_email:'';
      //$usr_email = array($usr_email,'rajat.l@cisinlabs.com');
      @wp_mail($usr_email, $order_processed_mail_subject, $order_processed_mail_html,$headers);
    }
  }
}

/*
* Function for remove view option from list of cutom post in admin area
*/

add_filter( 'post_row_actions', 'tc_remove_row_actions' );
function tc_remove_row_actions($action){
  if(isset($_GET['post_type']) && in_array($_GET['post_type'], array('membership-coupons','ingredients','sliders'))){
    unset($action['view']);
  }
  return $action;
}

/*
* Date: 23-04-2019
* Function for adding unique coupon error setting
*/

function unique_cpn_err($slug,$err){
  add_settings_error(
    $slug,
    $slug,
    $err,
    'error'
  );
  set_transient('cpn_err', get_settings_errors(), 30);
}

/*
* Function for add custom columns in coupons list
*/

add_filter( 'manage_membership-coupons_posts_columns', 'tc_coupons_list_custom_columns' );
function tc_coupons_list_custom_columns($columns) {
  unset($columns['date']);
  $columns['title'] = __( 'Code', 'toughcookies' );
  $columns['category'] = __( 'Category', 'toughcookies' );
  $columns['type'] = __( 'Type', 'toughcookies' );
  $columns['amount'] = __( 'Amount', 'toughcookies' );
  $columns['usage'] = __( 'Usage', 'toughcookies' );
  $columns['expiration_date'] = __( 'Expiration date', 'toughcookies' );
  $columns['date'] = __( 'Date', 'toughcookies' );
  return $columns;
}

/*
* Function for display value of custom columns in coupons list
*/

add_action( 'manage_membership-coupons_posts_custom_column' , 'tc_coupons_custom_column', 10, 2 );
function tc_coupons_custom_column( $column, $post_id ) {
  switch ( $column ) {
    case 'category' :
      $cat = ucwords(str_replace('_', ' ', get_post_meta($post_id , '_coupon_category' , true ))); 
      echo (!empty($cat))?$cat:'Coupon';
    break;
    case 'type' :
      echo ucwords(str_replace('_', ' ', get_post_meta($post_id , '_one_time_or_recurring' , true )));
      break;
    case 'amount' :
      echo get_post_meta( $post_id , '_amount' , true ); 
    break;
    case 'usage' :
      $tot_uc = get_post_meta( $post_id , 'total_usage_count' , true );
      $tot_ul = get_post_meta( $post_id , '_usage_limit_per_user' , true );
      echo (($tot_uc > 0)?$tot_uc:0).'/'.(($tot_ul == 0)?'∞':$tot_ul); 
    break;
    case 'expiration_date' :
      echo 'EXPIRE:<br>'.get_post_meta( $post_id , '_expiry_date' , true ); 
    break;
  }
}

/*
* Function for generate coupon code for add coupon page
*/

function generate_membership_coupon_code() {
  if(is_admin()){
    $generated_code = substr(str_shuffle('BCDFGHJKLMNPQRSTVWXYZ0123456789'), 0, 4).'-'.substr(str_shuffle('BCDFGHJKLMNPQRSTVWXYZ0123456789'), 0, 4);
    $response = array('error'=>0,'generated_code'=>$generated_code);
  }else{
    $response = array('error'=>1,'msg'=>'Something goes wrong, please try again!');
  }
  echo json_encode($response);
  exit();
}

/*
* Function for generate gift card code for add coupon page
*/

function generate_membership_gift_card_code() {
  if(is_admin()){
    //echo substr( str_shuffle( str_repeat( 'BCDFGHJKLMNPQRSTVWXYZ0123456789', 10 ) ), 0, 8 );
    $generated_code = 'TC'.substr(str_shuffle('BCDFGHJKLMNPQRSTVWXYZ0123456789'), 0, 2).'-'.substr(str_shuffle('BCDFGHJKLMNPQRSTVWXYZ0123456789'), 0, 4).'-'.substr(str_shuffle('BCDFGHJKLMNPQRSTVWXYZ0123456789'), 0, 4).'-'.substr(str_shuffle('BCDFGHJKLMNPQRSTVWXYZ0123456789'), 0, 4);
    $response = array('error'=>0,'generated_code'=>$generated_code);
  }else{
    $response = array('error'=>1,'msg'=>'Something goes wrong, please try again!');
  }
  echo json_encode($response);
  exit();
}

/*
* Method: function for coupon category list
*/

function get_coupon_category_list(){
  return array('coupon'=>'Coupon','gift_card'=>'Gift Card');
}

/*
* Date:
* Method: function for display user's feedback list after cancelled account in admin section
*/

function cancelled_accounts_feedback_list() {
  global $wpdb;
  date_default_timezone_set("America/New_York");
  if(isset($_GET['action']) && $_GET['action'] == 1 && isset($_GET['r_id']) && !empty($_GET['r_id'])){
    view_cancelled_accounts_feedback(base64_decode($_GET['r_id']));
  }else{
    $caf_sql = "SELECT * FROM ".$wpdb->prefix."users_feedback_data WHERE type = 1 ORDER BY id DESC";
    $tot_caf_rcords = $wpdb->get_results($caf_sql, ARRAY_A);
    $maxlmt = 999999999;
    $paged = (isset($_GET['paged']) && $_GET['paged'] > 0)?absint($_GET['paged']):1;
    $lmtstr = 0; 
    $lmtend = 20;
    if($paged > 1){
      $lmtstr = ($lmtend*$paged)-$lmtend;
    }
    $totalpages = ceil(count($tot_caf_rcords)/$lmtend);
    $caf_pg_sql = "SELECT * FROM ".$wpdb->prefix."users_feedback_data WHERE type = 1 ORDER BY id DESC limit ".$lmtstr.",".$lmtend;
    $caf_pg_data = $wpdb->get_results($caf_pg_sql, ARRAY_A);
    ?>
    <div class="wrap">
      <h1 class="wp-heading-inline">Cancellation Feedback</h1>
      <hr class="wp-header-end">
    <?php
      $html='<div class="nosubsub">
        <div id="ajax-response"></div>
          <div id="col-container">
            <div class="tablenav top">
              <div class="tablenav-pages one-page adm-pagination">
                <span class="displaying-num">'.count($tot_caf_rcords).' items</span>
                '.paginate_links(array(
                  'base' => str_replace($maxlmt, '%#%', esc_url(get_pagenum_link($maxlmt))),
                  'format' => '?paged=%#%',
                  'current' => max(1, $paged),
                  'mid-size' => 1,
                  'prev_next' => true,
                    'prev_text' => __('«'),
                    'next_text' => __('»'),
                  'total' => $totalpages
                )).'
              </div>
              <br class="clear">
            </div>
            <table class="wp-list-table widefat fixed striped tags" width="100%">
              <thead>
                <tr>
                  <th width="20%"><span>Customer</span></th>
                  <th width="15%"><span>Rating</span></th>
                  <th width="10%"><span>Plan</span></th>
                  <th width="55%"><span>Note</span></th>
                </tr>
              </thead>'; 
              if (!empty($caf_pg_data)){
                foreach ($caf_pg_data as $caf_ky => $caf_val) {
                  $r_id = $caf_val['id'];
                  $usr = get_user_by('id',$caf_val['user_id']);
                  $u_email = (isset($usr->data->user_email) && !empty($usr->data->user_email))?$usr->data->user_email:'';
                  $user_meta = get_user_meta($caf_val['user_id']);
                  $u_f_name = (isset($user_meta['first_name'][0]) && !empty($user_meta['first_name'][0]))?$user_meta['first_name'][0]:'';
                  $u_l_name = (isset($user_meta['last_name'][0]) && !empty($user_meta['last_name'][0]))?$user_meta['last_name'][0]:'';
                  $u_link = get_edit_user_link($caf_val['user_id']);
                  $rating = $note = $pln = '';
                  if(!empty($caf_val['data'])){
                    $_data = unserialize($caf_val['data']);
                    $rating = (isset($_data['rating']) && !empty($_data['rating']))?$_data['rating']:'';
                    $note = (isset($_data['message']) && !empty($_data['message']))?$_data['message']:'';
                    if(isset($_data['membership_plan']) && !empty($_data['membership_plan'])){
                      $meal_plan = get_plan_group_by_plan_id($_data['membership_plan']);
                      $mem_grp_color = '';
                      if(isset($meal_plan['slug']) && !empty($meal_plan['slug']) && isset($meal_plan['name']) && !empty($meal_plan['name'])){
                        switch($meal_plan['slug']){
                          case 'weight-loss':
                          case 'lose-weight':
                            $mem_grp_color = '#5B83BB';
                          break;
                          case 'balanced':
                            $mem_grp_color = '#D0AD69';
                          break;
                          case 'gain-muscle':
                            $mem_grp_color = '#D26B6B';
                          break;
                        }
                        $pln = '<span style="border-radius: 6px; border: 1px solid '.$mem_grp_color.';padding: 5px;color:'.$mem_grp_color.';">'.$meal_plan['name'].'</span>';
                      }
                    }
                  }
                  $supr_sad = $sad = $neutral = $happy = $supr_happy = '';
                  if(!empty($rating)){
                    switch ($rating) {
                      case 'super-sad':
                        $supr_sad = 'checked';
                      break;
                      case 'sad':
                        $sad = 'checked';
                      break;
                      case 'neutral':
                        $neutral = 'checked';
                      break;
                      case 'happy':
                        $happy = 'checked';
                      break;
                      case 'super-happy':
                        $supr_happy = 'checked';
                      break;
                    }
                  }
                  $viewurl = admin_url(). "admin.php?page=cancelled-accounts&action=1&r_id=".base64_encode($r_id);

                  $html .= '<tbody id="the-list" data-wp-lists="list:tag">
                    <tr>
                      <td><a href="'.$viewurl.'">'.$u_f_name.' '.$u_l_name.'<br>'.$u_email.'</a><br>
                          <div class="row-actions">
                            <span class="view"><a href="'.$viewurl.'">View</a></span>
                          </div>
                      </td>
                      <td>
                        <div class="adm-rating">
                          <label for="super-sad">
                            <input type="radio" name="super-sad'.$r_id.'" class="super-sad" id="super-sad'.$r_id.'" value="super-sad" '.$supr_sad.'/>
                              <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 34 34">
                                <g id="Angry" transform="translate(0.5 0.5)">
                                  <circle id="Oval" cx="16.5" cy="16.5" r="16.5" fill="#fff" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                  <g id="Face" transform="translate(6 9)">
                                      <path id="Path_2" data-name="Path 2" d="M0,2.4s3.687-4.8,7,0" transform="translate(7 11)" fill="none" stroke="#979797" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/>
                                      <circle id="Oval-2" data-name="Oval" cx="1.5" cy="1.5" r="1.5" transform="translate(16 4)" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                    <circle id="Oval-3" data-name="Oval" cx="1.5" cy="1.5" r="1.5" transform="translate(2 4)" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                      <path id="Path_15" data-name="Path 15" d="M-3.005,0l3,2" transform="translate(4.005)" fill="none" stroke="#979797" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/>
                                      <path id="Path_15-2" data-name="Path 15" d="M3.005,0l-3,2" transform="translate(17.005)" fill="none" stroke="#979797" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/>
                                  </g>
                                </g>
                              </svg>
                            </label>
                            <label for="sad">
                              <input type="radio" name="sad'.$r_id.'" class="sad" id="sad'.$r_id.'" value="sad" '.$sad.'/>
                                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 34 34">
                                  <g id="Sad" transform="translate(0.5 0.5)">
                                    <circle id="Oval" cx="16.5" cy="16.5" r="16.5" fill="#fff" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                    <g id="Face" transform="translate(8 13)">
                                      <path id="Path_2" data-name="Path 2" d="M0,2S3.687-2,7,2" transform="translate(5 7.401)" fill="none" stroke="#979797" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/>
                                      <circle id="Oval-2" data-name="Oval" cx="1.5" cy="1.5" r="1.5" transform="translate(14)" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                      <circle id="Oval-3" data-name="Oval" cx="1.5" cy="1.5" r="1.5" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                    </g>
                                </g>
                              </svg>
                          </label>
                          <label for="neutral">
                              <input type="radio" name="neutral'.$r_id.'" class="neutral" id="neutral'.$r_id.'" value="neutral" '.$neutral.' />
                              <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 34 34">
                                <g id="Group_3" data-name="Group 3" transform="translate(-73.5 0.5)">
                                  <g id="Meh" transform="translate(74)">
                                    <circle id="Oval" cx="16.5" cy="16.5" r="16.5" fill="#fff" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                    <g id="Face" transform="translate(8 13)">
                                      <circle id="Oval-2" data-name="Oval" cx="1.5" cy="1.5" r="1.5" transform="translate(14)" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                      <circle id="Oval-3" data-name="Oval" cx="1.5" cy="1.5" r="1.5" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                      <path id="Path_3" data-name="Path 3" d="M0,.5H7.5" transform="translate(4.5 8)" fill="none" stroke="#979797" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/>
                                    </g>
                                  </g>
                                </g>
                              </svg>
                          </label>
                          <label for="happy">
                              <input type="radio" name="happy'.$r_id.'" class="happy" id="happy'.$r_id.'" value="happy" '.$happy.'/>
                              <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 34 34">
                                <g id="Happy" transform="translate(0.5 0.5)">
                                  <circle id="Oval" cx="16.5" cy="16.5" r="16.5" fill="#fff" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                  <g id="Face" transform="translate(8 13)">
                                    <path id="Path_2" data-name="Path 2" d="M0-1.918s4.5,3.837,9,0" transform="translate(4 9.319)" fill="none" stroke="#979797" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/>
                                    <circle id="Oval-2" data-name="Oval" cx="1.5" cy="1.5" r="1.5" transform="translate(14)" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                    <circle id="Oval-3" data-name="Oval" cx="1.5" cy="1.5" r="1.5" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                  </g>
                                </g>
                              </svg>
                          </label>
                          <label for="super-happy">
                            <input type="radio" name="super-happy'.$r_id.'" class="super-happy" id="super-happy'.$r_id.'" value="super-happy" '.$supr_happy.'/>
                            <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 34 34">
                              <g id="Really_Happy" data-name="Really Happy" transform="translate(0.5 0.5)">
                                <circle id="Oval" cx="16.5" cy="16.5" r="16.5" fill="#fff" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                <g id="Face" transform="translate(8 13)">
                                  <circle id="Oval-2" data-name="Oval" cx="1.5" cy="1.5" r="1.5" transform="translate(14)" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                  <circle id="Oval-3" data-name="Oval" cx="1.5" cy="1.5" r="1.5" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                  <path id="Path_4" data-name="Path 4" d="M0,0H9A5.162,5.162,0,0,1,4.5,3,5.162,5.162,0,0,1,0,0Z" transform="translate(4 7)" fill="#979797" stroke="#979797" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1"/>
                                </g>
                              </g>
                            </svg>
                          </label>
                        </div>
                      </td>
                      <td>'.$pln.'</td>
                      <td>'.$note.'</td>
                    </tr>';
                }
              }else{
                $html .= '<tr><td colspan="4" align="center">No record found!</td></tr>';
              }
              $html .= '</tbody>
              <tfoot>
                  <tr>
                    <th width="20%"><span>Customer</span></th>
                    <th width="15%"><span>Rating</span></th>
                    <th width="10%"><span>Plan</span></th>
                    <th width="55%"><span>Note</span></th>
                </tr>
              </tfoot>
            </table>
            <div class="tablenav bottom">
              <div class="tablenav-pages one-page adm-pagination">
                <span class="displaying-num">'.count($tot_caf_rcords).' items</span>
                '.paginate_links(array(
                  'base' => str_replace($maxlmt, '%#%', esc_url(get_pagenum_link($maxlmt))),
                  'format' => '?paged=%#%',
                  'current' => max(1, $paged),
                  'mid-size' => 1,
                  'prev_next' => true,
                    'prev_text' => __('«'),
                    'next_text' => __('»'),
                  'total' => $totalpages
                )).'
              </div>
            </div>
          </div>
        </div>';
      echo $html;
     ?>
      </div>
      <?php
  }
}

/*
* Method: function for display meals rating list in admin section
* Update: Dev-1
*/

function meals_ratings_list() {
  global $wpdb;
  date_default_timezone_set("America/New_York");
  if(isset($_GET['action']) && $_GET['action'] == 1 && isset($_GET['fid']) && !empty($_GET['fid'])){
    view_meal_feedback_detail(base64_decode($_GET['fid']));
  }else{
    $tumf_sql = "SELECT * FROM ".$wpdb->prefix."users_feedback_data WHERE type = 2 ORDER BY id DESC";
    $tumf_data = $wpdb->get_results($tumf_sql, ARRAY_A);
    $maxlmt = 999999999;
    $paged = (isset($_GET['paged']) && $_GET['paged'] > 0)?absint($_GET['paged']):1;
    $lmtstr = 0; 
    $lmtend = 20;
    if($paged > 1){
      $lmtstr = ($lmtend*$paged)-$lmtend;
    }
    $totalpages = ceil(count($tumf_data)/$lmtend);
    $umf_sql = "SELECT * FROM ".$wpdb->prefix."users_feedback_data WHERE type = 2 ORDER BY id DESC limit ".$lmtstr.",".$lmtend;
    $umf_data = $wpdb->get_results($umf_sql, ARRAY_A);
    ?>
    <div class="wrap">
      <h1 class="wp-heading-inline">Meal Feedback</h1>
      <hr class="wp-header-end">
    <?php
      $html='<div class="nosubsub">
        <div id="ajax-response"></div>
          <div id="col-container">
            <div class="tablenav top">
              <div class="tablenav-pages one-page adm-pagination">
                <span class="displaying-num">'.count($tumf_data).' items</span>
                '.paginate_links(array(
                  'base' => str_replace($maxlmt, '%#%', esc_url(get_pagenum_link($maxlmt))),
                  'format' => '?paged=%#%',
                  'current' => max(1, $paged),
                  'mid-size' => 1,
                  'prev_next' => true,
                    'prev_text' => __('«'),
                    'next_text' => __('»'),
                  'total' => $totalpages
                )).'
              </div>
              <br class="clear">
            </div>
            <table class="wp-list-table widefat fixed striped tags" width="100%">
              <thead>
                <tr>
                  <th width="20%"><span>Customer</span></th>
                  <th width="10%"><span>Meal</span></th>
                  <th width="15%"><span>Rating</span></th>
                  <th width="55%"><span>Feedback Message</span></th>
                </tr>
              </thead>'; 
              if (!empty($umf_data)){
                foreach ($umf_data as $caf_ky => $umf_val) {
                  $fid = $umf_val['id'];
                  $usr = get_user_by('id',$umf_val['user_id']);
                  $u_email = (isset($usr->data->user_email) && !empty($usr->data->user_email))?$usr->data->user_email:'';
                  $user_meta = get_user_meta($umf_val['user_id']);
                  $u_f_name = (isset($user_meta['first_name'][0]) && !empty($user_meta['first_name'][0]))?$user_meta['first_name'][0]:'';
                  $u_l_name = (isset($user_meta['last_name'][0]) && !empty($user_meta['last_name'][0]))?$user_meta['last_name'][0]:'';
                  $rating = $fd_msg = $meal_id = $meal_name = '';
                  if(!empty($umf_val['data'])){
                    $_data = unserialize($umf_val['data']);
                    $rating = (isset($_data['rating']) && !empty($_data['rating']))?$_data['rating']:'';
                    //$fd_msg = (isset($_data['message']) && !empty($_data['message']))?$_data['message']:'';
                    $fd_msg = (isset($_data['message']) && strlen($_data['message']) > 190)?substr($_data['message'],0,190).'...':$_data['message'];
                    $meal_id = (isset($_data['meal_id']) && !empty($_data['meal_id']))?$_data['meal_id']:'';
                    if($meal_id!=''){
                      $meal_name = get_the_title($meal_id);
                    }
                  }
                  $viewurl = admin_url(). "admin.php?page=meals-ratings&action=1&fid=".base64_encode($fid);
                  $html .= '<tbody id="the-list" data-wp-lists="list:tag">
                    <tr>
                      <td><a href="'.$viewurl.'">'.$u_f_name.' '.$u_l_name.'<br>'.$u_email.'</a>
                          <div class="row-actions">
                            <span class="view"><a href="'.$viewurl.'">View</a></span>
                          </div>
                          </td>
                      <td>'.$meal_name.'</td>
                      <td>';
                        for($i=1;$i<=5;$i++){
                            $img_path = ($i <= $rating)?'star-fill.svg':'star-without-fill.svg';
                            $html .= '<i class="fa cursor-point" aria-hidden="true"><img src="'.get_template_directory_uri().'/images/'.$img_path.'" alt=""></i>';
                        }
                      $html .= '</td>';
                      $html .= '<td>'.$fd_msg.'</td>
                    </tr>';
                }
              }else{
                $html .= '<tr><td colspan="4" align="center">No record found!</td></tr>';
              }
              $html .= '</tbody>
              <tfoot>
                <tr>
                  <th width="20%"><span>Customer</span></th>
                  <th width="10%"><span>Meal</span></th>
                  <th width="15%"><span>Rating</span></th>
                  <th width="55%"><span>Feedback Message</span></th>
                </tr>
              </tfoot>
            </table>
            <div class="tablenav bottom">
              <div class="tablenav-pages one-page adm-pagination">
                <span class="displaying-num">'.count($tumf_data).' items</span>
                '.paginate_links(array(
                  'base' => str_replace($maxlmt, '%#%', esc_url(get_pagenum_link($maxlmt))),
                  'format' => '?paged=%#%',
                  'current' => max(1, $paged),
                  'mid-size' => 1,
                  'prev_next' => true,
                    'prev_text' => __('«'),
                    'next_text' => __('»'),
                  'total' => $totalpages
                )).'
              </div>
            </div>
          </div>
        </div>';
      echo $html;
     ?>
      </div>
    <?php
    }
}



/*
* Method: function for view meal feedback detail
*/

function view_meal_feedback_detail($fid){
  if(!empty($fid)){
    global $wpdb;
    date_default_timezone_set("America/New_York");
    $ufd_tbl = $wpdb->prefix.'users_feedback_data';
    $u_tbl = $wpdb->prefix.'users';
    $ufd_sql = "SELECT ufd.*, usr.ID as usr_id, usr.user_email FROM $ufd_tbl as ufd JOIN $u_tbl as usr on usr.ID = ufd.user_id WHERE ufd.id = $fid";
    $rslt = $wpdb->get_row($ufd_sql, ARRAY_A);
    $id = (isset($rslt['id']) && !empty($rslt['id'])) ? $rslt['id'] : 0;
    $user_id = (isset($rslt['user_id']) && !empty($rslt['user_id'])) ? $rslt['user_id'] : 0;
    $_data = (isset($rslt['data']) && !empty($rslt['data']))? unserialize($rslt['data']): array();
    $user_email = (isset($rslt['user_email']) && !empty($rslt['user_email']))? $rslt['user_email']: '';
    $usr = get_user_by('id',$user_id);
    $u_email = (isset($usr->data->user_email) && !empty($usr->data->user_email))?$usr->data->user_email:'';
    $user_meta = get_user_meta($user_id);
    $u_f_name = (isset($user_meta['first_name'][0]) && !empty($user_meta['first_name'][0]))?$user_meta['first_name'][0]:'';
    $u_l_name = (isset($user_meta['last_name'][0]) && !empty($user_meta['last_name'][0]))?$user_meta['last_name'][0]:'';
    $rating = (isset($_data['rating']) && !empty($_data['rating']))?$_data['rating']:'';
    $note = (isset($_data['message']) && !empty($_data['message']))?$_data['message']:'';
    $meal_id = (isset($_data['meal_id']) && !empty($_data['meal_id']))?$_data['meal_id']:'';
    if($meal_id!=''){
      $meal_name = get_the_title($meal_id);
    }
?>
<link rel='stylesheet' href='<?php echo site_url(); ?>/wp-content/plugins/woocommerce/assets/css/admin.css' type='text/css' media='all' />
<div class="wrap">
  <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-1">
      <div id="postbox-container-2" class="postbox-container">
        <div id="normal-sortables" class="meta-box-sortables ui-sortable">
          <div id="woocommerce-order-data" class="postbox ">
            <h2 class="hndle ui-sortable-handle"><span></span></h2>
            <div class="inside">
              <div class="panel-wrap woocommerce">
                <div id="order_data" class="panel">
                  <h2>Meal Feedback Detail<span class="fright" style="margin-top: -10px;"><a type="button" href="<?php echo admin_url(); ?>/admin.php?page=meals-ratings" class="button button-primary">Back</a></span></h2>
                  <div class="order_data_column_container">
                    <div class="order_data_column" style="width: 40%;">
                      <p class="form-field form-field-wide">
                        <label for="customer_user"><strong>Customer:</strong></label><a href="<?php echo site_url('/wp-admin/user-edit.php?user_id='.$user_id) ?>">
                        <?php echo $u_f_name.' '.$u_l_name.' (#'.$user_id.' - '.$u_email.')'; ?></a>
                      </p>
                      <p class="form-field form-field-wide"><label><strong>Rating:</strong></label>
                        <?php for($i=1;$i<=5;$i++){
                          $img_path = ($i <= $rating)?'star-fill.svg':'star-without-fill.svg';
                        ?>
                           <i class="fa cursor-point" aria-hidden="true"><img src="<?php echo get_template_directory_uri(); ?>/images/<?php echo $img_path; ?>" alt=""></i>
                        <?php } ?>
                      </p>  
                      <p class="form-field form-field-wide"><label><strong>Meal:</strong></label>
                        <?php echo $meal_name; ?>
                      </p> 
                    </div>
                    <div class="order_data_column" style="width: 58%;">
                      <p class="form-field form-field-wide"><label><strong>Feedback Message:</strong></label>
                        <?php echo $note; ?>
                      </p>
                    </div>
                  </div>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br class="clear">
  </div>
</div>
<?php
  }
}

/*
* Method: function for add Categoty filter drop-down on Coupon list page in admin panel
*/

add_action( 'restrict_manage_posts', 'tc_admin_posts_filter' );
function tc_admin_posts_filter(){
  $type = 'post';
  if (isset($_GET['post_type'])) {
      $type = $_GET['post_type'];
  }

  if ('membership-coupons' == $type){
      $values = array('Coupon'=>'coupon','Gift Card'=>'gift_card');
      ?>
      <select name="couponcat">
        <option value=""><?php _e('Select Category', 'toughcookies'); ?></option>
          <?php $current_v = isset($_GET['couponcat'])? $_GET['couponcat']:''; 
          foreach ($values as $label => $value) {
            printf(
              '<option value="%s"%s>%s</option>',
              $value,
              $value == $current_v? ' selected="selected"':'',
              $label
            );
        }
      ?>
      </select>
      <?php } 
} 

/*
* Method: function for filter Coupon list by Categoty filter drop-down in admin panel
*/

add_filter( 'parse_query', 'tc_filter_posts' ); 
function tc_filter_posts( $query ){ 
  global $pagenow; 
  $type = (isset($_GET['post_type']))?$_GET['post_type']:'post'; 
  if ( 'membership-coupons' == $type && is_admin() && $pagenow=='edit.php' && isset($_GET['couponcat']) && $_GET['couponcat'] != '') { 
    $query->query_vars['meta_key'] = '_coupon_category';
    $query->query_vars['meta_value'] = $_GET['couponcat'];
  }
}

/*
* Function for show order item meta in admin order detail page
*/

add_action( 'woocommerce_before_order_itemmeta', 'show_giftcard_order_item_meta', 10, 2 );
function show_giftcard_order_item_meta( $item_id, $item ) {
  $email_to_recipient = wc_get_order_item_meta( $item_id,'_email_to_recipient', true);
    echo '<div class="view">
      <table cellspacing="0" class="display_meta">
        <tbody>';
    if($email_to_recipient == 'on'){
      $sender_name = wc_get_order_item_meta( $item_id,'_sender_name', true);
      $recipient_name = wc_get_order_item_meta( $item_id,'_recipient_name', true);
      $recipient_email = wc_get_order_item_meta( $item_id,'_recipient_email', true);
      $message_for_gc_recipient = wc_get_order_item_meta( $item_id,'_message_for_gc_recipient', true);
    echo '<tr>
            <th>Email to recipient:</th>
            <td><p>Yes</p></td>
          </tr>
          <tr>
            <th>Sender Name:</th>
            <td><p>'.$sender_name.'</p></td>
          </tr>
          <tr>
            <th>Recipient Name:</th>
            <td><p>'.$recipient_name.'</p></td>
          </tr>
          <tr>
            <th>Recipient Email:</th>
            <td><p><a href="mailto:'.$recipient_email.'">'.$recipient_email.'</a></p></td>
          </tr>
          <tr>
            <th>Message:</th>
            <td><p>'.$message_for_gc_recipient.'</p></td>
          </tr>';
    }else{
      echo '<tr>
        <th>Email to recipient:</th>
        <td><p>No</p></td>
      </tr>';
    }
    echo '</tbody>
      </table>
    </div>';
  
}

/*
* Function for hide default order item meta in admin order detail page
*/

function custom_woocommerce_hidden_order_itemmeta($arr) {
  $arr = array('_email_to_recipient','_sender_name','_recipient_name','_recipient_email','_message_for_gc_recipient');
  return $arr;
}
add_filter('woocommerce_hidden_order_itemmeta', 'custom_woocommerce_hidden_order_itemmeta', 10, 1);

/*
* Function for add filter drop-down on users list page
*/

add_action('restrict_manage_users', 'filter_by_user_group');
function filter_by_user_group($which){
  $screen = get_current_screen();
  $fltr_arr = array(
    'registered_in_radius'=>'Registered (In radius)',
    'registered_outside_radius'=>'Registered (Outside radius)',
    'all_active_accounts'=>'All Active Accounts',
    'active_account_3rd_week_on'=>'Active Account (3rd week on)',
    'active_1st_week'=>'Active (1st week)',
    'active_2nd_week'=>'Active (2nd week)',
    'cancelled_account'=>'Cancelled Account'
  );
  $st = '<select id="user_group" name="user_group_%s" style="float:none;margin-left:10px;">
    <option value="">%s</option>%s</select>';
  $slcted_fltr = isset($_GET['user_group_top']) ? $_GET['user_group_top'] : '';
  //$bottom = isset($_GET['user_group_bottom']) ? $_GET['user_group_bottom'] : '';
  //$slcted_fltr = !empty($top) ? $top : $bottom;
  $optons = '';
  foreach ($fltr_arr as $fltr_key => $fltr_val) {
    $slcted = ($slcted_fltr == $fltr_key)?'selected':'';
    $optons .= '<option value="'.$fltr_key.'" '.$slcted.'>'.$fltr_val.'</option>';
  }
  $slct_box = sprintf( $st, $which, __( 'Select User Group' ), $optons );
  echo $slct_box;
  submit_button(__( 'Filter' ), null, $which, false);
  if (isset($screen->parent_file) && ('users.php' == $screen->parent_file)) {
    $fltred_users = get_fltred_users();
    if($fltred_users != '' && !empty($fltred_users)){
  ?>
    <input type="submit" name="export_user" class="button button-primary" value="Export">
  <?php } ?>
    <script type="text/javascript">
      jQuery(function() {
        jQuery(".tablenav.bottom select[name='user_group_bottom']").remove();
        jQuery(".tablenav.bottom input#bottom").remove();
        jQuery(".tablenav.bottom input#export_all_user").remove();
      });
    </script>
    <?php
  }
}

/*
* Function for apply filter on users list page
*/

add_filter('pre_get_users', 'filter_users_by_user_group_section');
function filter_users_by_user_group_section($query){
  global $wpdb, $pagenow;
  if (is_admin() && 'users.php' == $pagenow && isset($_GET['user_group_top']) && !empty($_GET['user_group_top'])) {
    $tot_usrs = $wpdb->get_results("SELECT COUNT(week_start_date) as week_count, o.week_start_date, o.week_end_date, o.user_id FROM ".$wpdb->prefix."users_meals_orders o INNER JOIN ".$wpdb->prefix."usermeta u ON o.user_id = u.user_id AND meta_key = 'subscription_account_status' AND meta_value = '1' GROUP BY o.user_id");
    switch ($_GET['user_group_top']) {
      case 'registered_in_radius':
        $meta_query = array (
          'relation' => 'AND',
          array (
            'key' => 'in_radius',
            'value' => 1,
            'compare' => '='
          ),
          array(
            'key'     => 'subscription_account_status',
            'value'   => 0,
            'compare' => '='
          )
        );
        $query->set('meta_query', $meta_query);
      break;
      case 'registered_outside_radius':
        $meta_query = array (
          'relation' => 'AND',
          array (
            'key' => 'in_radius',
            'value' => 0,
            'compare' => '='
          ),
          array(
            'key'     => 'subscription_account_status',
            'value'   => 0,
            'compare' => '='
          )
        );
        $query->set('meta_query', $meta_query);
      break;
      case 'all_active_accounts':
        $meta_query = array (array (
          'key' => 'subscription_account_status',
          'value' => 1,
          'compare' => '='
        ));
        $query->set('meta_query', $meta_query);
      break;
      case 'cancelled_account':
        $meta_query = array (array (
          'key' => 'subscription_account_status',
          'value' => 2,
          'compare' => '='
        ));
        $query->set('meta_query', $meta_query);
      break;
      case 'active_1st_week':
        if(!empty($tot_usrs) && count($tot_usrs) > 0){
          $wkd_name = strtolower(date("D", time()));
          if(in_array($wkd_name, array('sun','mon','tue','wed'))){
            $sdate = date('Y-m-d', strtotime('next sunday'));
          }else{
            if($wkd_name == 'wed' && date('H') < 12){
              $sdate = date('Y-m-d', strtotime('next sunday'));
            }else{
              $sdate = date('Y-m-d', strtotime('+2 sunday'));
            }
          }
          $edate = date('Y-m-d', strtotime('next saturday', strtotime($sdate)));
          foreach ($tot_usrs as $key => $val) {
            if($val->week_count == 1 && ($val->week_start_date == $sdate && $val->week_end_date == $edate)){
              $userids[] = $val->user_id;
            }
          }
          $query->set('include', $userids);
        }
        
      break;
      case 'active_2nd_week':
        if(!empty($tot_usrs) && count($tot_usrs) > 0){
          foreach ($tot_usrs as $key => $val) {
            if($val->week_count == 2){
              $userids[] = $val->user_id;
            }
          }
          $query->set('include', $userids);
        }
      break;
      case 'active_account_3rd_week_on':
        if(!empty($tot_usrs) && count($tot_usrs) > 0){
          foreach ($tot_usrs as $key => $val) {
            if($val->week_count >= 3){
              $userids[] = $val->user_id;
            }
          }
          $query->set('include', $userids);
        }
      break;
    }
  }
}

/*
* Function for generate CSV file for filtered users
*/

add_action( 'init', 'export_fltred_users' );
function export_fltred_users() {
  global $wpdb, $pagenow;
  if (is_admin() && 'users.php' == $pagenow && isset($_GET['export_user']) && !empty($_GET['export_user']) && (isset($_GET['user_group_top']) && !empty($_GET['user_group_top']))) {
    $users = get_fltred_users();
    if (!empty($users) && count($users) > 0) {
      header('Content-type: text/csv');
      header('Content-Disposition: attachment; filename="'.$_GET['user_group_top'].'_'.time().'.csv"');
      header('Pragma: no-cache');
      header('Expires: 0');
      $file = fopen('php://output', 'w');
      fputcsv($file, array('Name', 'First Name', 'Last Name', 'Email', 'Phone'));
      foreach ($users as $ky => $user) {
        $user_meta = get_user_meta($user->ID);
        $first_name = (isset($user_meta['first_name'][0]) && !empty($user_meta['first_name'][0]))?ucwords($user_meta['first_name'][0]):'';
        $last_name = (isset($user_meta['last_name'][0]) && !empty($user_meta['last_name'][0]))?ucwords($user_meta['last_name'][0]):'';
        $phone = (isset($user_meta['pmpro_bphone'][0]) && !empty($user_meta['pmpro_bphone'][0]))?preg_replace("/[^A-Za-z0-9]/", "", $user_meta['pmpro_bphone'][0]):0;
        $u_email = (isset($user->user_email) && !empty($user->user_email))?$user->user_email:'';
        fputcsv($file, array($first_name.' '.$last_name, $first_name, $last_name, $u_email, $phone));
      }
      exit();
    }
  }
}

/*
* Function for get filtered users
*/

function get_fltred_users() {
  global $wpdb;
  if (isset($_GET['user_group_top']) && !empty($_GET['user_group_top'])) {
    $args = $userids = array();
    $tot_usrs = $wpdb->get_results("SELECT COUNT(week_start_date) as week_count, o.user_id FROM ".$wpdb->prefix."users_meals_orders o INNER JOIN ".$wpdb->prefix."usermeta u ON o.user_id = u.user_id AND meta_key = 'subscription_account_status' AND meta_value = '1' GROUP BY o.user_id");
    switch ($_GET['user_group_top']) {
      case 'registered_in_radius':
        $args['meta_query'] = array (
          'relation' => 'AND',
          array (
            'key' => 'in_radius',
            'value' => 1,
            'compare' => '='
          ),
          array(
            'key'     => 'subscription_account_status',
            'value'   => 0,
            'compare' => '='
          )
        );
      break;
      case 'registered_outside_radius':
        $args['meta_query'] = array (
          'relation' => 'AND',
          array (
            'key' => 'in_radius',
            'value' => 0,
            'compare' => '='
          ),
          array(
            'key'     => 'subscription_account_status',
            'value'   => 0,
            'compare' => '='
          )
        );
      break;
      case 'all_active_accounts':
        $args['meta_query'] = array (array (
          'key' => 'subscription_account_status',
          'value' => 1,
          'compare' => '='
        ));
      break;
      case 'cancelled_account':
        $args['meta_query'] = array (array (
          'key' => 'subscription_account_status',
          'value' => 2,
          'compare' => '='
        ));
      break;
      case 'active_1st_week':
        if(!empty($tot_usrs) && count($tot_usrs) > 0){
          foreach ($tot_usrs as $key => $val) {
            if($val->week_count == 1){
              $userids[] = $val->user_id;
            }
          }
          $args['include'] = $userids;
        }
      break;
      case 'active_2nd_week':
        if(!empty($tot_usrs) && count($tot_usrs) > 0){
          foreach ($tot_usrs as $key => $val) {
            if($val->week_count == 2){
              $userids[] = $val->user_id;
            }
          }
          $args['include'] = $userids;
        }
      break;
      case 'active_account_3rd_week_on':
        if(!empty($tot_usrs) && count($tot_usrs) > 0){
          foreach ($tot_usrs as $key => $val) {
            if($val->week_count >= 3){
              $userids[] = $val->user_id;
            }
          }
          $args['include'] = $userids;
        }
      break;
    }
    $users = get_users( $args );
  }
  return (isset($users) && !empty($users))?$users:'';
}

/*
* Method: function for add/update contacts into sendinblue website
*/

function manage_contacts_on_sendinblue(){
  $wp_user_query = new WP_User_Query(array('role' => 'subscriber'));
  $users = $wp_user_query->get_results();
  if(isset($users) && !empty($users) && is_array($users)){
    require_once "third_party/sendinblue/autoload.php";
    $cnfg = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', TOUGHCOOKIES_SENDINBLUE_API_KEY);
    $apiInstance = new SendinBlue\Client\Api\ContactsApi(null,$cnfg);
    foreach ($users as $key => $user) {
      $user_meta = get_user_meta($user->ID);
      if(isset($user->data->user_email) && !empty($user->data->user_email)){
        $usr_email = $user->data->user_email;
        $f_name = (isset($user_meta['first_name'][0]) && !empty($user_meta['first_name'][0]))?ucfirst($user_meta['first_name'][0]):'';
        $l_name = (isset($user_meta['last_name'][0]) && !empty($user_meta['last_name'][0]))?ucfirst($user_meta['last_name'][0]):'';
        $in_out_radius = (isset($user_meta['in_radius'][0]) && $user_meta['in_radius'][0] == 1)?$user_meta['in_radius'][0]:0;
        $phone = (isset($user_meta['pmpro_bphone'][0]) && !empty($user_meta['pmpro_bphone'][0]))?preg_replace("/[^A-Za-z0-9]/", "", $user_meta['pmpro_bphone'][0]):0;
        $subscription_account_status = (isset($user_meta['subscription_account_status'][0]) && !empty($user_meta['subscription_account_status'][0]))?$user_meta['subscription_account_status'][0]:0;//0 = Pending, 1 = Active, 2 = Cancelled
        $lst_grp_ids = array();
        switch ($subscription_account_status) {
          case 0://pending account
            if($in_out_radius == 1){
              $lst_grp_ids[] = 19;
            }else{
              $lst_grp_ids[] = 18;
            }
          break;
          case 1://active account
            $lst_grp_ids[] = 20;
          break;
          case 2://cancelled acount
            $lst_grp_ids[] = 21;
          break;
        }
        try {
          //user found on sendinblue
          $rslt = $apiInstance->getContactInfo($usr_email);
          if(isset($rslt['id']) && $rslt['id'] > 0){
            $clistIds = $rslt['listIds'];
            $ullistIds = array_diff($clistIds,$lst_grp_ids);
            $updateContact = new \SendinBlue\Client\Model\UpdateContact();
            $updateContact['attributes'] = array('FIRSTNAME'=>$f_name,'LASTNAME'=>$l_name,'SMS'=>$phone);
            if(!empty($lst_grp_ids) && is_array($lst_grp_ids) && count($lst_grp_ids) > 0){
              $updateContact['listIds'] = $lst_grp_ids;
            }
            if(!empty($ullistIds) && is_array($ullistIds) && count($ullistIds) > 0){
              $updateContact['unlinkListIds'] = array_values($ullistIds);
            }
            $apiInstance->updateContact($usr_email, $updateContact);
          }
        } catch (Exception $e) {
          //user not found on sendinblue - need to add
          $createContact['email'] = $usr_email;
          $createContact['attributes'] = array('FIRSTNAME'=>$f_name,'LASTNAME'=>$l_name,'SMS'=>$phone);
          //$createContact['attributes'] = array('FIRSTNAME'=>$f_name,'LASTNAME'=>$l_name,'SMS'=>$phone);
          $createContact['listIds'] = $lst_grp_ids;
          try {
            $apiInstance->createContact($createContact);
          } catch (Exception $e) {
            //echo $e->getMessage();
          }
        }
      }
    }
  }
  die('hi');
}

/*
* Method: Function for save duplicate post custom data when admin create clone or add new draft
*/

add_action('transition_post_status', 'save_duplicate_post_custom_data', 10, 3);
function save_duplicate_post_custom_data($new_status, $old_status, $post) {
  if(isset($_GET['action']) && ($_GET['action'] == 'duplicate_post_save_as_new_post' || $_GET['action'] == 'duplicate_post_save_as_new_post_draft') && isset($_GET['post']) && $_GET['post'] > 0 && $new_status == 'draft' && $post->post_type === 'menu-items'){
    global $wpdb;
    $added_ingredients = $wpdb->get_results("select * from ".$wpdb->prefix."meals_ingredients where meal_id= ".$_GET['post'], ARRAY_A);
    if(isset($added_ingredients) && !empty($added_ingredients) && is_array($added_ingredients) && count($added_ingredients) > 0){
      $insrt_arr = array();
      foreach ($added_ingredients as $i_ky => $i_val) {
        $data = array(
          'meal_id' => $post->ID,
          'ingredient_id' => $i_val['ingredient_id'],
          'quantity' => $i_val['quantity'],
          'fraction_qty' => $i_val['fraction_qty'],
          'unit_id' => $i_val['unit_id'],
          'unit_abbreviation' => $i_val['unit_abbreviation'],
          'created_date' => date('Y-m-d h:i:s'),
          'modified_date' => date('Y-m-d h:i:s')             
        );
        $insrt_arr[] = $data;
      }
      if(count($insrt_arr) > 0){
        common_batch_insert($insrt_arr,$wpdb->prefix.'meals_ingredients');
      }
    }
  }
}
/*
* Method: function for update user's plan amount by updated tax rate
*/

function apply_new_tax_rate_on_users_upcoming_plans(){
  $wp_user_query = new WP_User_Query(array('role' => 'subscriber'));
  $users = $wp_user_query->get_results();
  if(isset($users) && !empty($users) && is_array($users)){
    global $wpdb;
    $tax_rate = get_option('tax_rate');
    $wkd_name = strtolower(date("D", time()));
    if(in_array($wkd_name, array('sun','mon','tue'))){
      $sdate = date('Y-m-d', strtotime('next sunday'));
    }else{
      if($wkd_name == 'wed'){
        $um_age = get_user_membership_age_in_days();
        if($um_age >= 1){
          if(strtotime('H:i:s') < '12:00:00'){
            $sdate = date('Y-m-d', strtotime('next sunday'));
          }else{
            $sdate = date('Y-m-d', strtotime('+2 sunday'));
          }
        }else{
          if(strtotime('H:i:s') <= '11:00:00'){
            $sdate = date('Y-m-d', strtotime('next sunday'));
          }else{
            $sdate = date('Y-m-d', strtotime('+2 sunday'));
          }
        }
      }else{
        $sdate = date('Y-m-d', strtotime('+2 sunday'));
      }
    }
    $upcming_wks_arr = array($sdate,
      date('Y-m-d',strtotime('+2 sunday', strtotime($sdate))),
      date('Y-m-d',strtotime('+3 sunday', strtotime($sdate))),
      date('Y-m-d',strtotime('+4 sunday', strtotime($sdate))),
      date('Y-m-d',strtotime('+5 sunday', strtotime($sdate)))
    );
    foreach ($users as $key => $user) {
      $user_id = $user->ID;
      //update user's upcoming weeks amount
      //if($user_id == 132){
      foreach ($upcming_wks_arr as $uwky => $wksdate) {
        $wkedate = date('Y-m-d', strtotime('next saturday', strtotime($wksdate)));
        $wkp_wh = "`user_id` = $user_id AND `start_date` = '$wksdate' AND `end_date` = '$wkedate'";
        $wk_plan_data = check_record_exist($wpdb->prefix.'users_membership_plans',$wkp_wh);
        if(!empty($wk_plan_data['id']) && $wk_plan_data['id'] > 0){
          $wk_start_key = str_replace('-', '', $wksdate);
          $wk_end_key = str_replace('-', '', $wkedate);
          $wk_plan_id = $wk_plan_data['membership_plan_id'];
          $wk_days = $wk_plan_data['selected_days'];
          $level = pmpro_getLevel($wk_plan_id);
          if(isset($level) && !empty($level)){
            $delivery_fee = get_pmpro_membership_level_meta($wk_plan_id,'_delivery_fee',true);
            $wk_meal_pr_day = get_pmpro_membership_level_meta($wk_plan_id,'_meal_per_day',true);
            $delivery_fee = (isset($delivery_fee) && !empty($delivery_fee))?$delivery_fee:0;
            $total_meals = $wk_days*$wk_meal_pr_day;
            $total_meals = (!empty($total_meals))?$total_meals:2;
            $initial_price = (!empty($level->initial_payment))?$level->initial_payment:0;
            $weekly_initial_amount = $initial_price;
            if(isset($initial_price) && $initial_price>0){
              $weekly_initial_amount = $initial_price*$wk_days;
            }
            $billing_amount = (!empty($level->billing_amount))?$level->billing_amount:0;
            $weekly_billing_amount = $billing_amount;
            if(isset($billing_amount) && $billing_amount>0){
              $weekly_billing_amount = $billing_amount*$wk_days;
            }
            $is_allergies = get_user_meta($user_id,'is_allergies',true);
            $allergies = get_user_meta($user_id,'allergies',true);
            $allergy_cost = 0;
            if($is_allergies == 'yes' && !empty($allergies[0])){
              if($allergies[0] == 'gluten_dairy_free'){
                $allergy_cost = 15;
              }else{
                $allergy_cost = 10;
              }
            }
            if(isset($allergy_cost) && $allergy_cost>0){
              $weekly_initial_amount = $weekly_initial_amount+$allergy_cost;
              $weekly_billing_amount = $weekly_billing_amount+$allergy_cost;
            }
            $initial_coupon_amt = $billing_coupon_amt = 0;
            $subscription_plan_coupon_data = get_user_meta($user_id,'subscription_plan_coupon_data',true);
            if(!empty($subscription_plan_coupon_data)){
              if(isset($subscription_plan_coupon_data['recurring_billing_list']) && in_array($wk_start_key.'-'.$wk_end_key, $subscription_plan_coupon_data['recurring_billing_list'])){//apply coupon rule 1
                if($subscription_plan_coupon_data['coupon_type'] == 'fixed_cart'){
                  $initial_coupon_amt = $subscription_plan_coupon_data['amount'];
                  $billing_coupon_amt = $subscription_plan_coupon_data['amount'];
                }elseif($subscription_plan_coupon_data['amount'] > 0){
                  $initial_coupon_amt = ($weekly_initial_amount*$subscription_plan_coupon_data['amount'])/100;
                  $billing_coupon_amt = ($weekly_billing_amount*$subscription_plan_coupon_data['amount'])/100;
                }
              }elseif(isset($subscription_plan_coupon_data['rule_2']['rule_2_recurring_billing_list']) && in_array($wk_start_key.'-'.$wk_end_key, $subscription_plan_coupon_data['rule_2']['rule_2_recurring_billing_list'])){//apply coupon rule 2
                if($subscription_plan_coupon_data['rule_2']['rule_2_coupon_type'] == 'fixed_cart'){
                  $initial_coupon_amt = $subscription_plan_coupon_data['rule_2']['rule_2_coupon_amount'];
                  $billing_coupon_amt = $subscription_plan_coupon_data['rule_2']['rule_2_coupon_amount'];
                }elseif($subscription_plan_coupon_data['rule_2']['rule_2_coupon_amount'] > 0){
                  $initial_coupon_amt = ($weekly_initial_amount*$subscription_plan_coupon_data['rule_2']['rule_2_coupon_amount'])/100;
                  $billing_coupon_amt = ($weekly_billing_amount*$subscription_plan_coupon_data['rule_2']['rule_2_coupon_amount'])/100;
                }
              }
            }
            if($weekly_initial_amount > $initial_coupon_amt){
              $weekly_initial_amount = $weekly_initial_amount - $initial_coupon_amt;
            }else{
              $weekly_initial_amount = 0;
            }
            if($weekly_billing_amount > $billing_coupon_amt){
              $weekly_billing_amount = $weekly_billing_amount - $billing_coupon_amt;
            }else{
              $weekly_billing_amount = 0;
            }
            if(isset($tax_rate) && $tax_rate>0){
              $weekly_ini_tax_amt = ($weekly_initial_amount*$tax_rate)/100;
              $weekly_initial_amount = $weekly_initial_amount+$weekly_ini_tax_amt;
              $weekly_bill_tax_amt = ($weekly_billing_amount*$tax_rate)/100;
              $weekly_billing_amount = $weekly_billing_amount+$weekly_bill_tax_amt;
            }
            if(isset($delivery_fee) && $delivery_fee>0){
              $weekly_initial_amount = $weekly_initial_amount+$delivery_fee;
              $weekly_billing_amount = $weekly_billing_amount+$delivery_fee;
            }
            $up_wk_pln_data = array(
              'initial_payment' => ($weekly_initial_amount > 0)?round($weekly_initial_amount,2):0,
              'billing_amount' => ($weekly_billing_amount > 0)?round($weekly_billing_amount,2):0,
              'discount_amount' => ($billing_coupon_amt > 0)?round($billing_coupon_amt,2):0,
              'delivery_fee' => ($delivery_fee > 0)?round($delivery_fee,2):0,
              'week_original_initial_payment' => $wk_plan_data['initial_payment'],
              'week_original_billing_payment' => $wk_plan_data['billing_amount'],
              'modify_date'=> date('Y-m-d H:i:s')
            );
            $wpdb->update($wpdb->prefix.'users_membership_plans', $up_wk_pln_data, array('user_id' => $user_id,'id' => $wk_plan_data['id']));
            //add revision
            $wk_pln_data = $wk_plan_data;
            $wk_pln_data['reference_id'] = $wk_plan_data['id'];
            unset($wk_pln_data['id']);
            $wpdb->insert($wpdb->prefix.'users_membership_plans_revision', $wk_pln_data);
            $ufwko_sql = "SELECT * FROM ".$wpdb->prefix."users_meals_orders WHERE user_id = ".$user_id." AND week_start_date = '".$wksdate."' AND week_end_date = '".$wkedate."'";
            $uwo_data = $wpdb->get_row($ufwko_sql, ARRAY_A);
            if(!empty($uwo_data) && $uwo_data['id'] > 0){
              //update first order amount after apply promo-code
              $uwmo_data = array(
                'total_bill_amount' => round($weekly_billing_amount,2),
                'discount_amount' => round($billing_coupon_amt,2),
              );
              $wpdb->update($wpdb->prefix.'users_meals_orders', $uwmo_data, array('id'=>$uwo_data['id']));
              //add revision
              $uwodata = $uwo_data;
              $uwodata['reference_id'] = $uwo_data['id'];
              unset($uwodata['id']);
              $wpdb->insert($wpdb->prefix.'users_meals_orders_revision', $uwodata);
            }
          }
          echo 'user_id -> '.$user_id.'<br>';
        }
      }
      //}
    }
  }
  die('hi');
}

/*
 * Method: function for add custom taxonomies for partners location
 */
add_action('init', 'add_custom_taxonomies_to_partners_location', 0);
function add_custom_taxonomies_to_partners_location() {
  register_taxonomy('partners_location_category', 'partners-location', array(
    'hierarchical' => true,
    'labels' => array(
      'name' => _x('Partners Location Groups', 'taxonomy general name'),
      'singular_name' => _x('Partner Location', 'taxonomy singular name'),
      'search_items' => __('Search'),
      'all_items' => __('All Categories'),
      'parent_item' => __('Parent'),
      'parent_item_colon' => __('Parent Partners Location Groups:'),
      'edit_item' => __('Edit Partner Location Category'),
      'update_item' => __('Update Partner Location Category'),
      'add_new_item' => __('Add New Category'),
      'new_item_name' => __('New Partner Location Category Name'),
      'menu_name' => __('Categories'),
    ),
    'rewrite' => array(
      'slug' => 'partners_location_category', // This controls the base slug that will display before each term
      'with_front' => false,
      'hierarchical' => true
    ),
  ));
}

/*
 * Method: Function for display custom post (partners pickup location) meta box content
 */

function add_partners_pickup_location_meta() {
  global $post;
  date_default_timezone_set("America/New_York");
  $_address = get_post_meta($post->ID, '_address', true);
  $_latitude = get_post_meta($post->ID, '_latitude', true);
  $_longitude = get_post_meta($post->ID, '_longitude', true);
  $_phone_number = get_post_meta($post->ID, '_phone_number', true);
  $_email = get_post_meta($post->ID, '_email', true);
  $_website = get_post_meta($post->ID, '_website', true);
  $_posted_day = get_post_meta($post->ID, '_posted_day', true);
  $_posted_hours = get_post_meta($post->ID, '_posted_hours', true);
  ?>
  <p>Partner pickup details and contact information</p>
  <div>
      <div class="wrap">
          <div class="meta-box-sortables ui-sortable">
              <div class="">
                  <table style="width: 100%;" cellspacing="15">
                      <tbody>
                          <tr>
                              <th width="15%"><label for="ppp_address" class="fleft">Address <span class="required">*</span></label></th>
                              <td width="85%">
                                  <input type="text" class="mi-field-width" name="ppp_address" id="ppp_address" placeholder="Start typing address..." value="<?php echo $_address; ?>" autocomplete="off" />
                                  <table style="width: 85%;">
                                      <tr>
                                          <td>Latitude</td>
                                          <td>Longitude</td>
                                      </tr>
                                      <tr>
                                          <td><input type="text" class="" name="ppp_latitude" id="ppp_latitude" value="<?php echo $_latitude; ?>" readonly="true"/></td>
                                          <td><input type="text" class="" name="ppp_longitude" id="ppp_longitude" value="<?php echo $_longitude; ?>" readonly="true"/></td>
                                      </tr>
                                  </table>

                              </td>
                          </tr>
                          <tr>
                              <th width="15%"><label for="ppp_phone_number" class="fleft">Phone <span class="required">*</span></label></th>
                              <td width="85%">
                                  <input type="text" class="mi-field-width" name="ppp_phone_number" id="ppp_phone_number" value="<?php echo $_phone_number; ?>" placeholder="Enter phone number" />
                              </td>
                          </tr>
                          <tr>
                              <th width="15%"><label for="ppp_email" class="fleft">Email <span class="required">*</span></label></th>
                              <td width="85%">
                                  <input type="email" class="mi-field-width" name="ppp_email" id="ppp_email" value="<?php echo $_email; ?>" placeholder="Enter email address" />
                              </td>
                          </tr>
                          <tr>
                              <th width="15%"><label for="ppp_website" class="fleft">Website <span class="required">*</span></label></th>
                              <td width="85%">
                                  <input type="text" class="mi-field-width" name="ppp_website" id="ppp_website" value="<?php echo $_website; ?>" placeholder="Website address" />
                              </td>
                          </tr>
                          <tr>
                              <th width="15%"><label for="ppp_posted_hours" class="fleft">Posted Hours <span class="required">*</span></label></th>
                              <td width="85%">
                                  <table style="width: 85%;" id="append_posted_days_hours">
                                      <tr>
                                          <td>Day</td>
                                          <td>Hours</td>
                                      </tr>
                                      <?php
                                      if (is_array($_posted_day) && is_array($_posted_hours) && count($_posted_day) > 0 && count($_posted_hours) > 0) {
                                          foreach ($_posted_day as $_posted_day_key => $_posted_day_val) {
                                              if ($_posted_day_key == 0) {
                                                  ?>
                                                  <tr>
                                                      <td><input type="text" class="" name="ppp_posted_day[]" id="ppp_posted_day" value="<?php echo $_posted_day[$_posted_day_key]; ?>" /></td>
                                                      <td><input type="text" class="" name="ppp_posted_hours[]" id="ppp_posted_hours" value="<?php echo $_posted_hours[$_posted_day_key]; ?>" />
                                                          <a class="ppp_add_more_button" id="ppp_add_more_button"><img class="cursor-point" alt="Add" src="<?php echo plugins_url('images/add.png', __FILE__); ?>"></a></td>
                                                  </tr>
                                              <?php } else {
                                                  ?>
                                                  <tr>
                                                      <td><input type="text" class="" name="ppp_posted_day[]" value="<?php echo $_posted_day[$_posted_day_key]; ?>" /></td>
                                                      <td>
                                                          <input type="text" class="" name="ppp_posted_hours[]" value="<?php echo $_posted_hours[$_posted_day_key]; ?>"/>
                                                          <a href="javascript:void(0);" class="ppp_remove_button"><img class="cursor-point" alt="Remove" src="<?php echo plugins_url('images/cross.jpeg', __FILE__); ?>"></a>
                                                      </td>
                                                  </tr>
                                                  <?php
                                              }
                                          }
                                      } else {
                                          ?>
                                          <tr>
                                              <td><input type="text" class="" name="ppp_posted_day[]" id="ppp_posted_day" /></td>
                                              <td><input type="text" class="" name="ppp_posted_hours[]" id="ppp_posted_hours" />
                                                  <a class="ppp_add_more_button" id="ppp_add_more_button"><img class="cursor-point" alt="Add" src="<?php echo plugins_url('images/add.png', __FILE__); ?>"></a></td>
                                          </tr>
                                      <?php } ?>
                                  </table>
                              </td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>
  <?php
  require_once "third_party/google-apis/address_autocomplete.php";
}

/*
* Method: function for get partners pickup locations list
*/

function get_partners_pickup_locations(){
  if(isset($_POST['srch_val']) && !empty($_POST['srch_val'])){
    global $wpdb;
    $ppl_sql = "select ID, post_title from ".$wpdb->prefix."posts where post_type ='partners-location' AND post_status ='publish' AND post_title like '%".$_POST['srch_val']."%'";
    $ppl_data = $wpdb->get_results($ppl_sql);
    $ppl_list = array();
    if(isset($ppl_data) && !empty($ppl_data)){
      foreach ($ppl_data as $ppl_ky => $ppl_vl){
        $ppl_list[] = array(
          'lel'   => !empty( $ppl_vl->post_title ) ? $ppl_vl->post_title : "",
          'ppl_id' => $ppl_vl->ID
        );
      }
      $response = array('error'=>0,'ppllst'=>$ppl_list);
    }else{
      $response = array('error'=>1,'ppllst'=>array());
    }
  }else{
    $response = array('error'=>1,'ppllst'=>array());
  }
  echo json_encode($response);
  exit();
}

/*
* Date:
* Method: function for get customer & send notification email to linked customer of pickup location
*/

function send_notification_email_to_linked_customers_of_pickup_location(){
  die('hi dude');
  global $wpdb;
  date_default_timezone_set("America/New_York");
  $bill_wk_sunday = date('Y-m-d',strtotime('next sunday'));
  $bill_wk_saturday = date('Y-m-d', strtotime('next saturday', strtotime($bill_wk_sunday)));
  $ppl_sql = "SELECT p.ID as ppl_id, p.post_title as ppl_name, umo.user_id, usr.user_email as user_email FROM ".$wpdb->prefix."posts as p JOIN ".$wpdb->prefix."users_meals_orders as umo ON (umo.pickup_location = p.ID) JOIN ".$wpdb->prefix."users as usr ON (usr.ID = umo.user_id) WHERE p.post_status = 'publish' AND umo.week_start_date = '".$bill_wk_sunday."' AND umo.week_end_date = '".$bill_wk_saturday."' AND umo.payment_status = 1";
  $ppl_data = $wpdb->get_results($ppl_sql, ARRAY_A);
  #print('<pre>');print_r($ppl_data);die;
  if(!empty($ppl_data) && count($ppl_data) > 0){
    foreach ($ppl_data as $ppl_ky => $ppl_vl) {
      $ppl_id = $ppl_vl['ppl_id'];
      $pl_meta = get_post_meta($ppl_id);
      $ppl_name = (isset($ppl_vl['ppl_name']) && !empty($ppl_vl['ppl_name']))?ucwords($ppl_vl['ppl_name']):'';
      $ppl_address = (isset($pl_meta['_address'][0]) && !empty($pl_meta['_address'][0]))?$pl_meta['_address'][0]:'';
      $ppl_phone = (isset($pl_meta['_phone_number'][0]) && !empty($pl_meta['_phone_number'][0]))?$pl_meta['_phone_number'][0]:'';
      $ppl_website = (isset($pl_meta['_website'][0]) && !empty($pl_meta['_website'][0]))?$pl_meta['_website'][0]:'';
      $ppl_thumbnail_url = get_the_post_thumbnail_url($ppl_vl['ppl_id'], 'thumbnail');
      $ppl_logo = ($ppl_thumbnail_url!='') ? $ppl_thumbnail_url : TOUGHCOOKIES_URL."images/no_image_found.png";
      if(!empty($ppl_vl['user_id']) && $ppl_vl['user_id'] > 0 && !empty($ppl_vl['user_email'])){
        $usr_data = get_user_meta($ppl_vl['user_id']);
        $usr_f_name = (isset($usr_data['first_name'][0]) && !empty($usr_data['first_name'][0]))?ucfirst($usr_data['first_name'][0]):'';
        $usr_address = '';
        if(isset($usr_data['pmpro_baddress1'][0]) && !empty($usr_data['pmpro_baddress1'][0])){
          $usr_address = $usr_data['pmpro_baddress1'][0];
        }
        if(isset($usr_data['pmpro_bzipcode'][0]) && !empty($usr_data['pmpro_bzipcode'][0])){
          $usr_address .= ' '.$usr_data['pmpro_bzipcode'][0];
        }
        if(isset($usr_data['pmpro_bcity'][0]) && !empty($usr_data['pmpro_bcity'][0])){
          $usr_address .= ', '.$usr_data['pmpro_bcity'][0];
        }
        if(isset($usr_data['pmpro_bstate'][0]) && !empty($usr_data['pmpro_bstate'][0])){
          $usr_address .= ', '.$usr_data['pmpro_bstate'][0];
        }
        if(isset($usr_data['pmpro_bcountry'][0]) && !empty($usr_data['pmpro_bcountry'][0])){
          $usr_address .= ', '.$usr_data['pmpro_bcountry'][0];
        }
        $ppl_map_lnk = urldecode('http://maps.google.com/maps?saddr='.$usr_address.'&daddr='.$ppl_address);
        $_subject = get_bloginfo( 'name' )." Order Delivery Notification";
        $mail_message = '<div style="background-color:#F9F9F9;">
        <div style="background:#F9F9F9;background-color:#F9F9F9;margin:0px auto;max-width:480px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
    style="background:#F9F9F9;background-color:#F9F9F9;width:100%;">
          <tbody>
            <tr>
            <td style="direction:ltr;font-size:0px;padding:0;text-align:center;">
              <div class="mj-column-per-100 mj-outlook-group-fix"
                  style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                    <tbody>
                      <tr>
                        <td style="vertical-align:top;padding:0px;">
                          <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                            <tr>
                              <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                                  style="border-collapse:collapse;border-spacing:0px;">
                                  <tbody>
                                    <tr>
                                      <td style="width:42px;"><a href="'.site_url().'" target="_blank"><img
                                            height="auto"
                                            src="'.get_template_directory_uri().'/images/email-logo-2x.png"
                                            style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;"
                                            width="42"></a></td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="mj-column-per-100 mj-outlook-group-fix"
                  style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;"
                    width="100%">
                    <tr>
                      <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                        <div
                          style="font-family:acumin-pro, Helvetica, sans-serif;font-size:14px;font-weight:300;line-height:14px;text-align:center;color:#A0A0A0;">
                          <p><a style="text-decoration: none; color:#A0A0A0" href="'.site_url("/how-it-works").'">How it Works</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a style="text-decoration: none; color:#A0A0A0" href="'.site_url("/faqs").'">FAQs</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <a
                              style="text-decoration: none; color:#A0A0A0" href="'.site_url("/contact-us").'">Contact</a></p>
                        </div>
                      </td>
                    </tr>
                  </table>
                </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>';
        $mail_message .= '<div style="background:#FFFAEC;background-color:#FFFAEC;margin:0px auto;max-width:480px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
          style="background:#FFFAEC;background-color:#FFFAEC;width:100%;">
          <tbody>
            <tr>
              <td style="direction:ltr;font-size:0px;padding:0 0 0 0;text-align:center;">
                <div class="mj-column-per-100 mj-outlook-group-fix"
                  style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;"
                    width="100%">
                    <tr>
                      <td align="center"
                        style="font-size:0px;padding:0px 25px;padding-right:0px;padding-bottom:0px;padding-left:0px;word-break:break-word;">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                          style="border-collapse:collapse;border-spacing:0px;">
                          <tbody>
                            <tr>
                              <td style="width:480px;"><img height="auto"
                                  src="'.get_template_directory_uri().'/images/email-pickup-header.jpg"
                                  style="border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;"
                                  width="480"></td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>';
      $mail_message .= '<div style="background:#FFFFFF;background-color:#FFFFFF;margin:0px auto;max-width:480px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
          style="background:#FFFFFF;background-color:#FFFFFF;width:100%;">
          <tbody>
            <tr>
              <td style="direction:ltr;font-size:0px;padding:60px 50px;text-align:center;">
                <div class="mj-column-px-NaN mj-outlook-group-fix"
                  style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;"
                    width="100%">
                    <tr>
                      <td align="left" style="font-size:0px;word-break:break-word;">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                          <tr>
                            <td style="font-size: 17px; font-weight: 500; color: #333333; text-align: center; font-family:acumin-pro, Helvetica, sans-serif;">
                              '.$usr_f_name.', your Tough Cookies order is set to be picked up tomorrow. Please call pickup location for any more information. Thank you!</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td align="left" style="font-size:0px;word-break:break-word;">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                          <tr>
                            <td style="height: 30px;"></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding: 15px; border: 1px solid #EDEDED; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                          <tr>
                            <td width="58" height="58px">
                              <img src="'.$ppl_logo.'" alt="'.$ppl_name.'" width="58" height="58px">
                            </td>
                            <td style="padding:0px 15px; vertical-align: top;">
                              <h3
                                style="font-size: 15px; color: #434343; margin: 0px; font-family:acumin-pro, Helvetica, sans-serif;">'.$ppl_name.'</h3>
                              <p
                                style="font-size: 15px; color: #434343; margin: 0px; font-family: acumin-pro, Helvetica, sans-serif;">'.$ppl_address.'</p>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td align="left" style="font-size:0px;word-break:break-word;">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                          <tr>
                            <td style="height: 30px;"></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                          <tr>
                            <td style="text-align: center;"><a href="tel:'.$ppl_phone.'" style="font-size: 22px; color: #4D79D2; font-weight: 500;; font-family:acumin-pro, Helvetica, sans-serif; display: block; width: 100%;">Call '.$ppl_phone.'</a>
                            </td>
                          </tr>
                          <tr>
                            <td style="text-align: center;"><a href="'.$ppl_map_lnk.'" target="_blank" style="font-size: 22px; color: #4D79D2; font-weight: 500;; font-family:acumin-pro, Helvetica, sans-serif; display: block; width: 100%;">Directions</a>
                            </td>
                          </tr>
                          <tr>
                            <td style="text-align: center;"><a href="'.$ppl_website.'" target="_blank" style="font-size: 22px; color: #4D79D2; font-weight: 500;; font-family:acumin-pro, Helvetica, sans-serif; display: block; width: 100%;">Website</a>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>';
      $mail_message .= '<div style="background:#ffffff;background-color:#ffffff;margin:0px auto;max-width:480px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
          style="background:#ffffff;background-color:#ffffff;width:100%;">
          <tbody>
            <tr>
              <td style="direction:ltr;font-size:0px;padding:0 0 0 0;text-align:center;">
                <div class="mj-column-per-100 mj-outlook-group-fix"
                  style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;"
                    width="100%">
                    <tr>
                      <td align="center" style="font-size:0px;padding:0px 25px;padding-right:0px;padding-bottom:0px;padding-left:0px;word-break:break-word;">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                          style="border-collapse:collapse;border-spacing:0px;">
                          <tbody>
                            <tr>
                              <td style="width:480px;"><a href="'.site_url('free-meals').'"><img height="auto"
                                  src="'.get_template_directory_uri().'/images/refer-friend-module.jpg"
                                  style="border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;"
                                  width="480"></a></td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>';
      $mail_message .= '<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
        style="background:#F9F9F9;background-color:#F9F9F9;width:100%;">
        <tbody>
          <tr>
            <td>
              <div style="margin:0px auto;max-width:480px;">
                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                  <tbody>
                    <tr>
                      <td style="direction:ltr;font-size:0px;padding:30px 0 30px 0;text-align:center;">
                        <div class="mj-column-per-100 mj-outlook-group-fix"
                          style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                          <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                            style="vertical-align:top;" width="100%">
                            <tr>
                              <td align="center" style="font-size:0px;padding:0;word-break:break-word;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
                                  style="float:none;display:inline-table;">
                                  <tr>
                                    <td style="padding:4px;">
                                      <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                                        style="border-radius:3px;width:18px;">
                                        <tr>
                                          <td style="font-size:0;height:18px;vertical-align:middle;width:18px;">
                                            <a href="'.get_theme_mod("tc_facebook").'" target="_blank"><img height="18" src="'.get_template_directory_uri().'/images/facebook-icon.png" alt="" style="border-radius:3px;display:block;" width="18"></a></td>
                                        </tr>
                                      </table>
                                    </td>
                                    <td style="vertical-align:middle;"><a href="'.get_theme_mod("tc_facebook").'"
                                        style="color:#333333;font-size:15px;font-family:Ubuntu, Helvetica, Arial, sans-serif;line-height:22px;text-decoration:none;"
                                        target="_blank">&nbsp;&nbsp;</a></td>
                                  </tr>
                                </table>
                                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
                                  style="float:none;display:inline-table;">
                                  <tr>
                                    <td style="padding:4px;">
                                      <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                                        style="border-radius:3px;width:18px;">
                                        <tr>
                                          <td style="font-size:0;height:18px;vertical-align:middle;width:18px;">
                                          <a href="'.get_theme_mod("tc_instagram").'" target="_blank"><img height="18" src="'.get_template_directory_uri().'/images/instagram-icon.png"
                                                style="border-radius:3px;display:block;" width="18" alt=""></a>
                                          </td>
                                        </tr>
                                      </table>
                                    </td>
                                    <td style="vertical-align:middle;"><a href="'.get_theme_mod("tc_instagram").'"
                                        style="color:#333333;font-size:15px;font-family:Ubuntu, Helvetica, Arial, sans-serif;line-height:22px;text-decoration:none;"
                                        target="_blank">&nbsp;&nbsp;</a></td>
                                  </tr>
                                </table>
                                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
                                  style="float:none;display:inline-table;">
                                  <tr>
                                    <td style="padding:4px;">
                                      <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                                        style="border-radius:3px;width:18px;">
                                        <tr>
                                          <td style="font-size:0;height:18px;vertical-align:middle;width:18px;"><a
                                              href="http://m.me/ToughCookies" target="_blank"><img height="18"
                                                src="https://toughcookies.co/wp-content/uploads/2019/10/MessengerIcon.png"
                                                style="border-radius:3px;display:block;" width="18"></a></td>
                                        </tr>
                                      </table>
                                    </td>
                                    <td style="vertical-align:middle;"><a href="http://m.me/ToughCookies"
                                        style="color:#333333;font-size:15px;font-family:Ubuntu, Helvetica, Arial, sans-serif;line-height:22px;text-decoration:none;"
                                        target="_blank">&nbsp;</a></td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </table>
                        </div>
                        <div class="mj-column-per-100 mj-outlook-group-fix"
                          style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                          <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                            style="vertical-align:top;" width="100%">
                            <tr>
                              <td align="center" style="font-size:0px;padding:0;word-break:break-word;">
                                <div
                                  style="font-family:acumin-pro, Helvetica, sans-serif;font-size:14px;font-weight:300;line-height:30px;text-align:center;color:#7C7C7C;">
                                  <p><a
                                      style="text-decoration: none; color:#777777; border-right: 1px solid #E6E6E6; padding:12px 15px 10px 15px;"
                                      href="tel:'.get_theme_mod("tc_tel_no").'">Call us</a><a
                                      style="text-decoration: none; color:#777777; border-right: 1px solid #E6E6E6; padding:12px 15px 10px 15px;"
                                      href="mailto:'.get_theme_mod("tc_help_email").'">Email us</a><a
                                      style="text-decoration: none; color:#777777; border-right: 1px solid #E6E6E6; padding:12px 15px 10px 15px;"
                                      href="sms:+'.get_theme_mod("tc_tel_no").'">Text us</a><a
                                      style="text-decoration: none; color:#777777; padding:12px 15px 10px 15px;"
                                      href="'.site_url("/faqs").'">FAQ</a></p>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </div>
                        <div class="mj-column-per-100 mj-outlook-group-fix"
                          style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                          <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                            style="vertical-align:top;" width="100%">
                            <tr>
                              <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                <div
                                  style="font-family:acumin-pro, Helvetica, sans-serif;font-size:12px;font-weight:300;line-height:20px;text-align:center;color:#7C7C7C;">
                                  Copyright © 2019 Tough Cookies, LLC | All Rights Reserved 46 Rockland St, Hanover, MA
                                  02329</div>
                              </td>
                            </tr>
                          </table>
                        </div>

                        <div class="mj-column-per-100 mj-outlook-group-fix"
                          style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                          <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                            style="vertical-align:top;" width="100%">
                            <tr>
                              <td align="center" style="font-size:0px;padding:0;word-break:break-word;">
                                <div
                                  style="font-family:acumin-pro, Helvetica, sans-serif;font-size:14px;font-weight:300;line-height:30px;text-align:center;color:#7C7C7C;">
                                  <p><a style="text-decoration: none; color:#777777; border-right: 1px solid #E6E6E6; padding:12px 15px 10px 15px;" href="[UNSUBSCRIBE]">unsubscribe</a><a style="text-decoration: none; color:#777777; padding:12px 15px 10px 15px;" href="{{ update_profile }}">subscription preferences</a></p>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </div>

                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </td>
          </tr>
        </tbody>
      </table>';
      $mail_message .= '</div>';
        $mail_html = '<!doctype html>';
        $mail_html .='<html>';
        $mail_html .='<head>';
        $mail_html .='<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet" type="text/css">
        <link href="https://use.typekit.net/alq3lif.css" rel="stylesheet" type="text/css">
        <style type="text/css">
          #outlook a {padding: 0;}
          body {margin: 0;padding: 0;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;}
          table,td {border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;}
          img {border: 0;height: auto;line-height: 100%;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;}
          p {display: block;margin: 13px 0;}
          @import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);
          @import url(https://use.typekit.net/alq3lif.css);
          @media only screen and (min-width:100px) {
            .mj-column-per-100 {width: 100% !important;max-width: 100%;}
            .mj-column-px-NaN {width: NaNfull-width !important;max-width: NaNfull-width;}
          }
          @media only screen and (max-width:100px) {
            table.mj-full-width-mobile {width: 100% !important;}
            td.mj-full-width-mobile {width: auto !important;}
          }
        </style>';
        $mail_html .='<title>' . $_subject . '</title>';
        $mail_html .='<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        $mail_html .='</head>';
        $mail_html .='<body style="background-color:#F9F9F9;">';
        $mail_html .= $mail_message;
        $mail_html .='</body>';
        $mail_html .='</html>';
        $headers = 'From: ' . get_bloginfo('name') . ' <' . get_bloginfo('admin_email') . '>';
        add_filter('wp_mail_content_type','wpse27856_set_content_type');
        @wp_mail('deepak.p@cisinlabs.com', $_subject, $mail_html,$headers);//$ump_vl['user_email']
        die('chk 2');
      }
    }
    die('chk 2');
    print('<pre>');print_r($ppl_data1);die('$ppl_data');
  }
}

/*
* Method: function for display page for Live Menu Activation
*/

function live_menu_activation_page() {
  $activated_live_menus = get_option('activated_live_menus');
  $html = '<div class="wrap">
    <div class="postbox" style="margin: 15px 0 10px;padding: 10px;">
      <h3 class="hndle ui-sortable-handle" style="margin-top:5px;"><span>Live Menu Activation</span></h3>
      <div class="inside">
        <ul class="lma-list">
        <li><h3 class="lma-hd">'.date('Y').'</h3></li>';
        for($m=1; $m<=12; ++$m){
          $mnth_val = date('Y-m', mktime(0, 0, 0, $m, 1));
          $is_chked = $is_disabled = '';
          if(!empty($activated_live_menus) && count($activated_live_menus) > 0 && in_array($mnth_val, $activated_live_menus)){
            $is_chked = 'checked';
            $is_disabled = 'disabled';
          }
          $html .= '<li><span class="lma-hd">'.date('F', mktime(0, 0, 0, $m, 1)).'</span>
          <label class="switch">
            <input type="checkbox" name="live_menu_month" value="'.$mnth_val.'" '.$is_chked.' '.$is_disabled.'>
            <span class="slider round"></span>
          </label>
          </li>';
        }
  $html .= '</ul>
          </div>
        </div>
      </div>
    </div>';
  echo $html;
}

/*
* Method: function for save activated live menus
*/

function save_activated_live_menus(){
  if(isset($_POST['val']) && !empty($_POST['val'])){
    date_default_timezone_set("America/New_York");
    $activated_live_menus = get_option('activated_live_menus');
    if(!empty($activated_live_menus) && is_array($activated_live_menus) && !in_array($_POST['val'], $activated_live_menus)){
      $activated_live_menus[] = $_POST['val'];
    }else{
      $activated_live_menus[] = $_POST['val'];
    }
    update_option('activated_live_menus',$activated_live_menus);
    $activated_mnth = date("F",strtotime($_POST['val']));
    $response = array('error'=>0,'msg'=>'Menu of '.$activated_mnth.' month successfully activated.');
  }else{
    $response = array('error'=>1,'msg'=>'Something goes wrong, please try again!');
  }
  echo json_encode($response);
  exit();
}

/*
* Method: function for display schedule days off list in admin section
*/

function schedule_days_off_list() {
  date_default_timezone_set("America/New_York");
  if(isset($_GET['action']) && !empty($_GET['action'])){
    if($_GET['action'] == 1){//add new week to schedule off
      add_schedule_day_off();
    }elseif($_GET['action'] == 2){//update week to schedule off
      if(isset($_GET['sdo']) && !empty($_GET['sdo'])){
        $sdo = base64_decode($_GET['sdo']);
        edit_schedule_day_off($sdo);
      }
    }
  }else{
    global $wpdb;
    $sdo_sql = "SELECT * FROM ".$wpdb->prefix."schedule_days_off ORDER BY id DESC";
    $tot_sdo_rcords = $wpdb->get_results($sdo_sql, ARRAY_A);
    $maxlmt = 999999999;
    $paged = (isset($_GET['paged']) && $_GET['paged'] > 0)?absint($_GET['paged']):1;
    $lmtstr = 0; 
    $lmtend = 20;
    if($paged > 1){
      $lmtstr = ($lmtend*$paged)-$lmtend;
    }
    $totalpages = ceil(count($tot_sdo_rcords)/$lmtend);
    $sdo_pg_sql = "SELECT * FROM ".$wpdb->prefix."schedule_days_off ORDER BY id DESC limit ".$lmtstr.",".$lmtend;
    $sdo_data = $wpdb->get_results($sdo_pg_sql, ARRAY_A);
    ?>
    <div class="wrap">
      <h1 class="wp-heading-inline">Schedule days off</h1>
      <a href="<?php echo admin_url(); ?>admin.php?page=schedule-days-off&action=1" class="page-title-action">Add New</a>
      <hr class="wp-header-end">
    <?php
      $html='<div class="nosubsub">
        <div id="ajax-response"></div>
          <div id="col-container">
            <div class="tablenav top">
              <div class="tablenav-pages one-page adm-pagination">
                <span class="displaying-num">'.count($tot_sdo_rcords).' items</span>
                '.paginate_links(array(
                  'base' => str_replace($maxlmt, '%#%', esc_url(get_pagenum_link($maxlmt))),
                  'format' => '?paged=%#%',
                  'current' => max(1, $paged),
                  'mid-size' => 1,
                  'prev_next' => true,
                    'prev_text' => __('«'),
                    'next_text' => __('»'),
                  'total' => $totalpages
                )).'
              </div>
              <br class="clear">
            </div>
            <table class="wp-list-table widefat fixed striped tags" width="100%">
              <thead>
                <tr>
                  <th width="15%"><span>Week Date</span></th>
                  <th width="35%"><span>Upcoming Page Message</span></th>
                  <th width="30%"><span>Pricing Page Message</span></th>
                  <th width="10%"><span>Week Status</span></th>
                  <th width="10%"><span>Create Date</span></th>
                </tr>
              </thead>'; 
              if (!empty($sdo_data)){
                foreach ($sdo_data as $sdo_ky => $sdo_val) {
                  $editurl = admin_url(). "admin.php?page=schedule-days-off&action=2&sdo=".base64_encode($sdo_val['id']);
                  $upm = (isset($sdo_val['upcoming_page_message']) && strlen($sdo_val['upcoming_page_message']) > 130)?substr($sdo_val['upcoming_page_message'],0,130).'...':$sdo_val['upcoming_page_message'];
                  $ppm = (isset($sdo_val['pricing_page_message']) && strlen($sdo_val['pricing_page_message']) > 120)?substr($sdo_val['pricing_page_message'],0,120).'...':$sdo_val['pricing_page_message'];
                  $html.='<tr>
                    <td class="name column-name has-row-actions column-primary"><a class="row-title" href="'.$editurl.'" title="Edit"><strong>'.ucfirst(date('D, F jS',strtotime($sdo_val['week_start_date']))).'</strong><br>'.$sdo_val['week_start_date'].' To '.$sdo_val['week_end_date'].'</a><br><div class="row-actions"><span class="edit"><a href="'.$editurl.'">Edit</a></span></div></td>
                    <td>'.$upm.'</td>
                    <td>'.$ppm.'</td>
                    <td><strong>'.ucwords($sdo_val['schedule_week_status']).'</strong></td>
                    <td>'.$sdo_val['create_date'].'</td>
                  </tr>';
                }
              }else{
                $html .= '<tr><td colspan="4" align="center">No record found!</td></tr>';
              }
              $html .= '</tbody>
              <tfoot>
                  <tr>
                    <th width="15%"><span>Week Date</span></th>
                    <th width="35%"><span>Upcoming Page Message</span></th>
                    <th width="30%"><span>Pricing Page Message</span></th>
                    <th width="10%"><span>Week Status</span></th>
                    <th width="10%"><span>Create Date</span></th>
                </tr>
              </tfoot>
            </table>
            <div class="tablenav bottom">
              <div class="tablenav-pages one-page adm-pagination">
                <span class="displaying-num">'.count($tot_sdo_rcords).' items</span>
                '.paginate_links(array(
                  'base' => str_replace($maxlmt, '%#%', esc_url(get_pagenum_link($maxlmt))),
                  'format' => '?paged=%#%',
                  'current' => max(1, $paged),
                  'mid-size' => 1,
                  'prev_next' => true,
                    'prev_text' => __('«'),
                    'next_text' => __('»'),
                  'total' => $totalpages
                )).'
              </div>
            </div>
          </div>
        </div>';
      echo $html;
     ?>
    </div>
  <?php
  }
}

/*
* Method: function for add day/week for off schedule
*/

function add_schedule_day_off() {
  date_default_timezone_set("America/New_York");
  $wkd_name = strtolower(date("D", time()));
  if(in_array($wkd_name, array('sun','mon','tue'))){
    $sdate = date('Y-m-d', strtotime('next sunday'));
  }else{
    if($wkd_name == 'wed' && strtotime(date('H:i:sa')) < strtotime('12pm')){
      $sdate = date('Y-m-d', strtotime('next sunday'));
    }else{
      $sdate = date('Y-m-d', strtotime('+2 sunday'));
    }
  }
  echo '<div class="wrap">
      <div class="postbox" style="margin: 15px 0 10px;padding: 10px;">
        <h3 class="hndle ui-sortable-handle" style="margin-top:5px;"><span>Add Week To Schedule Off</span></h3>
        <div style="margin: 5px;text-align: right;" class="inside">
          <form method="post" id="schedule_day_off_frm" action="">
            <table width="100%">
              <tr align="left">
                <th width="15%"><span>Schedule day</span></th>
                <td width="85%">
                  <input type="radio" name="schedule_day" value="off" checked> OFF&nbsp;
                  <input type="radio" name="schedule_day" value="on"> ON
                </td>
              </tr>
              <tr align="left">
                <th width="15%"><span>Week start date</span></th>
                <td width="85%">
                  <input type="text" class="datepicker sdo-week-start-date" name="week_start_date" id="week_start_date" value="" readonly="true" />
                </td>
              </tr>
              <tr align="left">
                <th width="15%"><span>Upcoming page message</span></th>
                <td width="85%">
                  <textarea name="upcoming_page_message" id="upcoming_page_message" placeholder="Enter upcoming page notification message" rows="4" cols="140"></textarea>
                </td>
              </tr>
              <tr align="left">
                <th width="15%"><span>Pricing page message</span></th>
                <td width="85%">
                  <textarea name="pricing_page_message" id="pricing_page_message" placeholder="Enter upcoming page notification message" rows="4" cols="140"></textarea>
                </td>
              </tr>
              <tr align="left"><td colspan="2">&nbsp;</td></tr>
              <tr align="left">
                <td colspan="2"><input type="button" id="save_schedule_day_off" value="Save" class="button button-primary">&nbsp;<a type="button" href="'.admin_url(). "admin.php?page=schedule-days-off".'" class="button button-primary">Back</a></td>
              </tr>
            </table>
          </form>
        </div>
      </div>
    </div>
  </div>';
  ?>
  <script type="text/javascript">
    jQuery(document).ready(function(){
      jQuery( ".sdo-week-start-date" ).datepicker({
        dateFormat : "yy-mm-dd",
        showOn: "button",
        buttonImage: "../wp-content/plugins/toughcookies/images/calendar.gif",
        buttonImageOnly: true,
        buttonText: "Select date",
        minDate: "<?php echo $sdate; ?>", 
        maxDate: "+60D",
        firstDay: 0,
        beforeShowDay: function (date) {
          if (date.getDay() == 0) {
            return [true, ''];
          } else {
            return [false, ''];
          }
        }
      });
    });
  </script>
  <?php
}

/*
* Method: function for edit day/week for off schedule
*/

function edit_schedule_day_off($sdoid) {
  global $wpdb;
  $sdo_pg_sql = "SELECT * FROM ".$wpdb->prefix."schedule_days_off WHERE id = ".$sdoid;
  $sdo_data = $wpdb->get_row($sdo_pg_sql, ARRAY_A);
  $sws_on = ($sdo_data['schedule_week_status'] == 'on')?'checked':'';
  $wksd = (isset($sdo_data['week_start_date']) && !empty($sdo_data['week_start_date']))?$sdo_data['week_start_date']:'';
  $upmsg = (isset($sdo_data['upcoming_page_message']) && !empty($sdo_data['upcoming_page_message']))?$sdo_data['upcoming_page_message']:'';
  $ppmsg = (isset($sdo_data['pricing_page_message']) && !empty($sdo_data['pricing_page_message']))?$sdo_data['pricing_page_message']:'';
  date_default_timezone_set("America/New_York");
  $wkd_name = strtolower(date("D", time()));
  if(in_array($wkd_name, array('sun','mon','tue'))){
    $sdate = date('Y-m-d', strtotime('next sunday'));
  }else{
    if($wkd_name == 'wed' && strtotime(date('H:i:sa')) < strtotime('12pm')){
      $sdate = date('Y-m-d', strtotime('next sunday'));
    }else{
      $sdate = date('Y-m-d', strtotime('+2 sunday'));
    }
  }
  $sv_btn_cls = (strtotime($wksd) < strtotime($sdate))?' dnone':'';
  echo '<div class="wrap">
      <div class="postbox" style="margin: 15px 0 10px;padding: 10px;">
        <h3 class="hndle ui-sortable-handle" style="margin-top:5px;"><span>Edit Week To Schedule Off</span><span class="fright" style="margin-top: -10px;"><a type="button" href="'.admin_url(). "admin.php?page=schedule-days-off".'" class="button button-primary">Back</a></span></h3>
        <div style="margin: 5px;text-align: right;" class="inside">
          <form method="post" id="schedule_day_off_frm" action="">
            <table width="100%">
              <tr align="left">
                <th width="15%"><span>Schedule day</span></th>
                <td width="85%">
                  <input type="radio" name="schedule_day" value="off" checked> OFF&nbsp;
                  <input type="radio" name="schedule_day" value="on" '.$sws_on.'> ON
                </td>
              </tr>
              <tr align="left">
                <th width="15%"><span>Week start date</span></th>
                <td width="85%">
                  <input type="text" class="datepicker" name="week_start_date" id="week_start_date" value="'.$wksd.'" readonly="true" />
                </td>
              </tr>
              <tr align="left">
                <th width="15%"><span>Upcoming page message</span></th>
                <td width="85%">
                  <textarea name="upcoming_page_message" id="upcoming_page_message" placeholder="Enter upcoming page notification message" rows="4" cols="140">'.$upmsg.'</textarea>
                </td>
              </tr>
              <tr align="left">
                <th width="15%"><span>Pricing page message</span></th>
                <td width="85%">
                  <textarea name="pricing_page_message" id="pricing_page_message" placeholder="Enter upcoming page notification message" rows="4" cols="140">'.$ppmsg.'</textarea>
                </td>
              </tr>
              <tr align="left"><td colspan="2">&nbsp;</td></tr>
              <tr align="left">
                <td colspan="2"><input type="button" id="save_schedule_day_off" value="Save" class="button button-primary'.$sv_btn_cls.'"></td>
              </tr>
            </table>
          </form>
        </div>
      </div>
    </div>
  </div>';
}

/*
 * Method: function for save schedule day off
*/

function save_schedule_day_off(){
  $parameters = array();
  parse_str($_POST['fdata'], $parameters);
  $is_error = 0;
  $error_msg = array();
  date_default_timezone_set("America/New_York");
  if(isset($parameters['schedule_day']) && !empty($parameters['schedule_day'])){
    $schedule_day = $parameters['schedule_day'];
  }else{
    $is_error = 1;
    $error_msg['schedule_day'] = 'Schedule day field is required!';
  }
  if(isset($parameters['week_start_date']) && !empty($parameters['week_start_date'])){
    $wsdate = $parameters['week_start_date'];
  }else{
    $is_error = 1;
    $error_msg['week_start_date'] = 'Week start date field is required!';
  }
  if(isset($parameters['upcoming_page_message']) && !empty($parameters['upcoming_page_message'])){
    $upcoming_page_message = $parameters['upcoming_page_message'];
  }else{
    $is_error = 1;
    $error_msg['upcoming_page_message'] = 'Upcoming page message field is required!';
  }
  if(isset($parameters['pricing_page_message']) && !empty($parameters['pricing_page_message'])){
    $pricing_page_message = $parameters['pricing_page_message'];
  }else{
    $is_error = 1;
    $error_msg['pricing_page_message'] = 'Pricing page message field is required!';
  }
  if(isset($is_error) && $is_error==1){
    $response = array('error'=>1,'msg'=>$error_msg);
  }else{
    global $wpdb;
    $wedate = date("Y-m-d",strtotime('next saturday', strtotime($wsdate)));
    $sdo_arr = array(
      'schedule_week_status'=>$schedule_day,
      'week_start_date'=>$wsdate,
      'week_end_date'=>$wedate,
      'upcoming_page_message'=>$upcoming_page_message,
      'pricing_page_message'=>$pricing_page_message
    );
    $sdo_wh = "week_start_date = '".$wsdate."' AND week_end_date = '".$wedate."'";
    $sdo_data = check_record_exist($wpdb->prefix.'schedule_days_off',$sdo_wh);
    if(isset($sdo_data['id']) && !empty($sdo_data['id'])){
      $wpdb->update($wpdb->prefix.'schedule_days_off',$sdo_arr,array('id'=>$sdo_data['id']));
      if(isset($sdo_data['schedule_week_status']) && $sdo_data['schedule_week_status'] == $schedule_day){
        $response = array('error'=>0,'msg'=>'Selected week already schedule for off.','redirecturl'=>admin_url()."admin.php?page=schedule-days-off");
      }else{
        $response = array('error'=>0,'msg'=>'Selected week successfully schedule for off.','redirecturl'=>admin_url()."admin.php?page=schedule-days-off");
        skip_unskip_given_week_meals_of_all_users(array('schedule_day_status'=>$schedule_day,'wsdate'=>$wsdate,'wedate'=>$wedate));
      }
    }else{
      $wpdb->insert($wpdb->prefix.'schedule_days_off',$sdo_arr);
      $response = array('error'=>0,'msg'=>'Selected week successfully schedule for off.','redirecturl'=>admin_url()."admin.php?page=schedule-days-off");
      skip_unskip_given_week_meals_of_all_users(array('schedule_day_status'=>$schedule_day,'wsdate'=>$wsdate,'wedate'=>$wedate));
    }
  }
  echo json_encode($response);
  exit();
}

/*
* Method: Function for skip-unskip given week meals of all users
*/

function skip_unskip_given_week_meals_of_all_users($params){
  global $wpdb;
  $schedule_day_status = $params['schedule_day_status'];
  $wsdate = $params['wsdate'];
  $wedate = $params['wedate'];
  if($schedule_day_status == 'off'){
    $old_status = 2;
    $nw_status = 4;
  }else{
    $old_status = 4;
    $nw_status = 2;
  }
  $ucwm_sql = "SELECT user_id FROM ".$wpdb->prefix."users_meals WHERE week_start_date = '".$wsdate."' AND week_end_date = '".$wedate."' AND status = ".$old_status." GROUP BY user_id";
  $usr_curr_meals = $wpdb->get_results($ucwm_sql,ARRAY_A);
  if(!empty($usr_curr_meals) && count($usr_curr_meals) > 0){
    //$usr_ids = array_column($usr_curr_meals, 'user_id');
    //$usr_ids_arr = array_unique($usr_ids, SORT_REGULAR);
    foreach ($usr_curr_meals as $usr_key => $usr) {
      if(isset($usr['user_id']) && $usr['user_id'] > 0){
        //get plan week_skip_by status - if it is 0 or 2 then we will update that user data
        $wk_pln_wh = "`user_id` = ".$usr['user_id']." AND `start_date` = '$wsdate' AND `end_date` = '$wedate'";
        $wk_plan_detail = check_record_exist($wpdb->prefix.'users_membership_plans',$wk_pln_wh);
        if(($nw_status == 2 && $wk_plan_detail['week_skip_by'] != 1) || $nw_status == 4){
          $meal_data = array('status'=>$nw_status,'modify_date'=>date('Y-m-d H:i:s'));
          $where = array('week_start_date'=>$wsdate,'week_end_date'=>$wedate,'status'=>$old_status,'user_id'=>$usr['user_id']);
          $wpdb->update($wpdb->prefix.'users_meals',$meal_data,$where);
          $user_wks_status = get_user_meta($usr['user_id'],'user_weeks_status',true);
          $user_wks_status = (!empty($user_wks_status))?unserialize($user_wks_status):array();
          $wk_sd_key = str_replace('-', '', $wsdate);
          if($nw_status == 2){
            unset($user_wks_status[$wk_sd_key]);
          }else if($nw_status == 4){
            $user_wks_status[$wk_sd_key] = array('status'=>$nw_status);
          }
          update_user_meta($usr['user_id'], 'user_weeks_status', serialize($user_wks_status));
          $user_membership_plan_data = array(
            'week_skip_by' => 2,
            'modify_date'=> date('Y-m-d H:i:s')
          );
          $wpdb->update($wpdb->prefix.'users_membership_plans', $user_membership_plan_data, array('user_id' => $usr['user_id'],'start_date' => $wsdate, 'end_date' => $wedate));
        }
      }
    }
  }
}

/*
* Method: Function for track user's activity when logout
*/

add_action('wp_logout','user_logout_activity');
function user_logout_activity(){
  user_log(4); // Logout user log
}

/*
* Method: Function for save logged-in user's activities
*/

function user_log($logActionId = 0, $userId = 0, $logReqData = array()) {
    global $wpdb;
    $userId = $userId != 0 ? $userId : get_current_user_id();
    if ($logActionId != 0 && $userId!=0) {        
        $log_msg = isset($logReqData['message']) ? $logReqData['message'] : '';
        switch ($logActionId) {
            case 1://Sign Up
                $log_msg = 'User register with the system.';
                break;
            case 2:// Sign In
                $log_msg = 'User login with the system.';
                break;
            case 3:// Upcoming Page
                $log_msg = 'User view upcoming page.';
                break;
            case 4:// Logout
                $log_msg = 'User logout successfully.';
                break;
            case 5:// Onboarding Page
                $log_msg = 'User view onboarding page.';
                break;
            case 6:// Account Page
                $log_msg = 'User view account page.';
                break;
            case 7:// Update Account Information
                $log_msg = 'User update basic account information from account info page.';
                break;
            case 8:// Delivery Setting Page
                $log_msg = 'User access delivery setting page.';
                break;
            case 9:// Nutrition Setting Page
                $log_msg = 'User access nutrition setting page.';
                break;
            case 10:// Payment Setting Page
                $log_msg = 'User access payment setting page.';
                break;
        }
        $user_log_data = array(
            'log_action_id' => $logActionId,
            'user_id' => $userId,
            'activity_date' => date('Y-m-d H:i:s'),
            'message' => $log_msg,
            'ip_address' => get_client_ip()
        );
        $wpdb->insert($wpdb->prefix . 'users_logs', $user_log_data);
        return true;
    }
    return false;
}


/*
* Method: function for view cancelled accounts feedback
*/

function view_cancelled_accounts_feedback($r_id){
  if(!empty($r_id)){
    global $wpdb;
    date_default_timezone_set("America/New_York");
    $ufd_tbl = $wpdb->prefix.'users_feedback_data';
    $u_tbl = $wpdb->prefix.'users';
    $ufd_sql = "SELECT ufd.*, usr.ID as usr_id, usr.user_email FROM $ufd_tbl as ufd JOIN $u_tbl as usr on usr.ID = ufd.user_id WHERE ufd.id = $r_id";
    $rslt = $wpdb->get_row($ufd_sql, ARRAY_A);
    $id = (isset($rslt['id']) && !empty($rslt['id'])) ? $rslt['id'] : 0;
    $user_id = (isset($rslt['user_id']) && !empty($rslt['user_id'])) ? $rslt['user_id'] : 0;
    $_data = (isset($rslt['data']) && !empty($rslt['data']))? unserialize($rslt['data']): array();
    $user_email = (isset($rslt['user_email']) && !empty($rslt['user_email']))? $rslt['user_email']: '';
    $usr = get_user_by('id',$user_id);
    $u_email = (isset($usr->data->user_email) && !empty($usr->data->user_email))?$usr->data->user_email:'';
    $user_meta = get_user_meta($user_id);
    $u_f_name = (isset($user_meta['first_name'][0]) && !empty($user_meta['first_name'][0]))?$user_meta['first_name'][0]:'';
    $u_l_name = (isset($user_meta['last_name'][0]) && !empty($user_meta['last_name'][0]))?$user_meta['last_name'][0]:'';
    $rating = (isset($_data['rating']) && !empty($_data['rating']))?$_data['rating']:'';
    $note = (isset($_data['message']) && !empty($_data['message']))?$_data['message']:'';
    $supr_sad = $sad = $neutral = $happy = $supr_happy = '';
    if(!empty($rating)){
      switch ($rating) {
        case 'super-sad':
          $supr_sad = 'checked';
        break;
        case 'sad':
          $sad = 'checked';
        break;
        case 'neutral':
          $neutral = 'checked';
        break;
        case 'happy':
          $happy = 'checked';
        break;
        case 'super-happy':
          $supr_happy = 'checked';
        break;
      }
    }
?>
<link rel='stylesheet' href='<?php echo site_url(); ?>/wp-content/plugins/woocommerce/assets/css/admin.css' type='text/css' media='all' />
<div class="wrap">
  <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-1">
      <div id="postbox-container-2" class="postbox-container">
        <div id="normal-sortables" class="meta-box-sortables ui-sortable">
          <div id="woocommerce-order-data" class="postbox ">
            <h2 class="hndle ui-sortable-handle"><span></span></h2>
            <div class="inside">
              <div class="panel-wrap woocommerce">
                <div id="order_data" class="panel">
                  <h2>Cancellation Feedback Detail<span class="fright" style="margin-top: -10px;"><a type="button" href="<?php echo admin_url(); ?>admin.php?page=cancelled-accounts&action" class="button button-primary">Back</a></span></h2>
                  <div class="order_data_column_container">
                    <div class="order_data_column" style="width: 48%;">
                      <p class="form-field form-field-wide">
                        <label for="customer_user"><strong>Customer:</strong></label><a href="<?php echo site_url('/wp-admin/user-edit.php?user_id='.$user_id) ?>">
                        <?php echo $u_f_name.' '.$u_l_name.' (#'.$user_id.' - '.$u_email.')'; ?></a>
                      </p>
                      <p class="form-field form-field-wide">
                        <strong>Rating:</strong>
                        <div class="adm-rating">
                          <label for="super-sad">
                            <input type="radio" name="super-sad<?php echo $r_id; ?>" class="super-sad" id="super-sad<?php echo $r_id; ?>" value="super-sad" <?php echo $supr_sad; ?> />
                              <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 34 34">
                                <g id="Angry" transform="translate(0.5 0.5)">
                                  <circle id="Oval" cx="16.5" cy="16.5" r="16.5" fill="#fff" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                  <g id="Face" transform="translate(6 9)">
                                      <path id="Path_2" data-name="Path 2" d="M0,2.4s3.687-4.8,7,0" transform="translate(7 11)" fill="none" stroke="#979797" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/>
                                      <circle id="Oval-2" data-name="Oval" cx="1.5" cy="1.5" r="1.5" transform="translate(16 4)" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                    <circle id="Oval-3" data-name="Oval" cx="1.5" cy="1.5" r="1.5" transform="translate(2 4)" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                      <path id="Path_15" data-name="Path 15" d="M-3.005,0l3,2" transform="translate(4.005)" fill="none" stroke="#979797" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/>
                                      <path id="Path_15-2" data-name="Path 15" d="M3.005,0l-3,2" transform="translate(17.005)" fill="none" stroke="#979797" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/>
                                  </g>
                                </g>
                              </svg>
                            </label>
                            <label for="sad">
                              <input type="radio" name="sad<?php echo $r_id; ?>" class="sad" id="sad<?php echo $r_id; ?>" value="sad" <?php echo $sad; ?>/>
                                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 34 34">
                                  <g id="Sad" transform="translate(0.5 0.5)">
                                    <circle id="Oval" cx="16.5" cy="16.5" r="16.5" fill="#fff" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                    <g id="Face" transform="translate(8 13)">
                                      <path id="Path_2" data-name="Path 2" d="M0,2S3.687-2,7,2" transform="translate(5 7.401)" fill="none" stroke="#979797" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/>
                                      <circle id="Oval-2" data-name="Oval" cx="1.5" cy="1.5" r="1.5" transform="translate(14)" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                      <circle id="Oval-3" data-name="Oval" cx="1.5" cy="1.5" r="1.5" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                    </g>
                                </g>
                              </svg>
                          </label>
                          <label for="neutral">
                              <input type="radio" name="neutral<?php echo $r_id; ?>" class="neutral" id="neutral<?php echo $r_id; ?>" value="neutral" <?php echo $neutral; ?> />
                              <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 34 34">
                                <g id="Group_3" data-name="Group 3" transform="translate(-73.5 0.5)">
                                  <g id="Meh" transform="translate(74)">
                                    <circle id="Oval" cx="16.5" cy="16.5" r="16.5" fill="#fff" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                    <g id="Face" transform="translate(8 13)">
                                      <circle id="Oval-2" data-name="Oval" cx="1.5" cy="1.5" r="1.5" transform="translate(14)" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                      <circle id="Oval-3" data-name="Oval" cx="1.5" cy="1.5" r="1.5" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                      <path id="Path_3" data-name="Path 3" d="M0,.5H7.5" transform="translate(4.5 8)" fill="none" stroke="#979797" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/>
                                    </g>
                                  </g>
                                </g>
                              </svg>
                          </label>
                          <label for="happy">
                              <input type="radio" name="happy<?php echo $r_id; ?>" class="happy" id="happy<?php echo $r_id; ?>" value="happy" <?php echo $happy; ?> />
                              <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 34 34">
                                <g id="Happy" transform="translate(0.5 0.5)">
                                  <circle id="Oval" cx="16.5" cy="16.5" r="16.5" fill="#fff" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                  <g id="Face" transform="translate(8 13)">
                                    <path id="Path_2" data-name="Path 2" d="M0-1.918s4.5,3.837,9,0" transform="translate(4 9.319)" fill="none" stroke="#979797" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/>
                                    <circle id="Oval-2" data-name="Oval" cx="1.5" cy="1.5" r="1.5" transform="translate(14)" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                    <circle id="Oval-3" data-name="Oval" cx="1.5" cy="1.5" r="1.5" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                  </g>
                                </g>
                              </svg>
                          </label>
                          <label for="super-happy">
                            <input type="radio" name="super-happy<?php echo $r_id; ?>" class="super-happy" id="super-happy<?php echo $r_id; ?>" value="super-happy" <?php echo $supr_happy; ?>/>
                            <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 34 34">
                              <g id="Really_Happy" data-name="Really Happy" transform="translate(0.5 0.5)">
                                <circle id="Oval" cx="16.5" cy="16.5" r="16.5" fill="#fff" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                <g id="Face" transform="translate(8 13)">
                                  <circle id="Oval-2" data-name="Oval" cx="1.5" cy="1.5" r="1.5" transform="translate(14)" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                  <circle id="Oval-3" data-name="Oval" cx="1.5" cy="1.5" r="1.5" fill="#979797" stroke="#979797" stroke-miterlimit="10" stroke-width="1"/>
                                  <path id="Path_4" data-name="Path 4" d="M0,0H9A5.162,5.162,0,0,1,4.5,3,5.162,5.162,0,0,1,0,0Z" transform="translate(4 7)" fill="#979797" stroke="#979797" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1"/>
                                </g>
                              </g>
                            </svg>
                          </label>
                        </div>
                      <p>
                    </div>
                    <div class="order_data_column" style="width: 48%;">
                      <p class="form-field form-field-wide"><label><strong>Feedback Message:</strong></label><?php echo $note; ?></p> 
                    </div>
                  </div>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br class="clear">
  </div>
</div>
<?php
  }
}

function array_msort($array, $cols){
  $colarr = array();
  foreach ($cols as $col => $order) {
    $colarr[$col] = array();
    foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
  }
  $eval = 'array_multisort(';
  foreach ($cols as $col => $order) {
    $eval .= '$colarr[\''.$col.'\'],'.$order.',';
  }
  $eval = substr($eval,0,-1).');';
  eval($eval);
  $ret = array();
  foreach ($colarr as $col => $arr) {
    foreach ($arr as $k => $v) {
      $k = substr($k,1);
      if (!isset($ret[$k])) $ret[$k] = $array[$k];
      $ret[$k][$col] = $array[$k][$col];
    }
  }
  return $ret;
}
/**
* pmpro general email issue
* stop expiring membership notifications
*/

function tc_pmpro_email_filter($email) {
  if($email->template == 'membership_expiring'){
    return false;
    //return $email;
  }
}
add_filter('pmpro_email_filter', 'tc_pmpro_email_filter');

function get_user_purchased_insulated_bag_products_by_user_id($user_id){
  $insulated_bag_odrs = array();
  $usr_all_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
    'meta_key'    => '_customer_user',
    'meta_value'  => $user_id,
    'post_type'   => wc_get_order_types( 'view-orders' ),
    'post_status' => array_keys( wc_get_order_statuses() ),
  )));
  if(!empty($usr_all_orders)){
    foreach ( $usr_all_orders as $odr_ky => $odr_vl ) {
      $odr_data = wc_get_order( $odr_vl );
      $odr_items = $odr_data->get_items();
      foreach ( $odr_items as $item ) {
        $product_id = $item->get_product_id();
      }
      if(isset($product_id) && $product_id > 0){
        $prod_cats = get_the_terms( $product_id, 'product_cat' );
        $prod_cat_slug = (isset($prod_cats[0]->slug) && !empty($prod_cats[0]->slug))?$prod_cats[0]->slug:'';
        if($prod_cat_slug == 'insulated-bags'){
          $insulated_bag_odrs[] = $odr_vl;
        }
      }
    }
  }
  return $insulated_bag_odrs;
}