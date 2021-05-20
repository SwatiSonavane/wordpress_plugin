<?php
/*
 * To show the article on admin of wordpress dashboard
 */
 
 /* ------------------------------------------------- To add custom post type--------------------------------------------- */
 
//Hook  for pd_custom_post_article() </strong> to the init action hook

add_action( 'init', 'pd_custom_post_article' );

// The custom function to register  article post type

function pd_custom_post_article() {
	
		// Set the labels, this variable is used in the $args array
		$labels = array(
		'name'               => __( 'Articles' ),
		'singular_name'      => __( 'Article' ),
		'add_new'            => __( 'Add New  Article' ),
		'add_new_item'       => __( 'Add New  Article' ),
		'edit_item'          => __( 'Edit  Article' ),
		'new_item'           => __( 'New  Article' ),
		'all_items'          => __( 'All  Articles' ),
		'view_item'          => __( 'View  Article' ),
		'search_items'       => __( 'Search  Article' ),
		//'featured_image'     => 'Image',
		//'set_featured_image' => 'Add Image'
		);


		// The arguments for our post type, to be entered as parameter 2 of register_post_type()
		$args = array(
		'labels'            => $labels,
		'description'       => 'Holds our article post specific data',
		'public'            => true,
		'menu_position'     => 5,
		//'supports'          => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom-fields' ),
		'supports'          => array( 'title', 'editor', 'author'),
		'has_archive'       => true,
		'show_in_admin_bar' => true,
		'show_in_rest'      => true, 
		'show_in_nav_menus' => true,
		'menu_icon'           => 'dashicons-format-aside', // custom icon for the Articles 
		'query_var'         => true,
		);

		// Call the actual WordPress function
		// Parameter 1 is a name for the post type
		// Parameter 2 is the $args array

		register_post_type( 'article', $args);
}
/* --------------------------------------------------------To add custom fields  ---------------------------------------------------------------- */


add_action( 'show_user_profile', 'pd_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'pd_custom_user_profile_fields' );
add_action( 'user_new_form', 'pd_custom_user_profile_fields' );
 
function pd_custom_user_profile_fields( $user )
{
    echo '<h3 class="heading"> Address </h3>';
     //print_r($user->ID );
    ?>
    
    <table class="form-table">
	
	<tr>
        <th><label for="street">Street</label></th> 
	    <td><input type="text" class="input-text form-control" name="streetname" id="streetname" value="<?php echo esc_attr( get_the_author_meta( 'streetname', $user->ID ) ); ?>"  /></td>
 	</tr>
	<tr>
        <th><label for="suit">Suit</label></th> 
	    <td><input type="text" class="input-text form-control" name="suitname" id="suitname" value="<?php echo esc_attr( get_the_author_meta( 'suitname', $user->ID ) ); ?>"  /></td>
 	</tr>
	<tr>
        <th><label for="city">City</label></th> 
	    <td><input type="text" class="input-text form-control" name="cityname" id="cityname" value="<?php echo esc_attr( get_the_author_meta( 'cityname', $user->ID ) ); ?>"  /></td>
 	</tr>
	<tr>
        <th><label for="pincode">Pincode</label></th> 
	    <td><input type="text" class="input-text form-control" name="pincode" id="pincode" maxlength="4" value="<?php echo esc_attr( get_the_author_meta( 'pincode', $user->ID ) ); ?>"  /></td>
 	</tr>
		
	<tr>
        <th><label for="Phone">Phone</label></th> 
	    <td><input type="text" class="input-text form-control" name="phone" id="phone"  value="<?php echo esc_attr( get_the_author_meta( 'phone', $user->ID ) ); ?>" /></td>
 	</tr>
	<tr>
        <th><label for="company">Company Name</label></th> 
	    <td><input type="text" class="input-text form-control" name="companyname" id="companyname" value="<?php echo esc_attr( get_the_author_meta( 'companyname', $user->ID ) ); ?>"  /></td>
 	</tr>
	<tr>
        <th><label for="website">Website</label></th> 
	    <td><input type="text" class="input-text form-control" name="websitename" id="websitename" value="<?php echo esc_attr( get_the_author_meta( 'websitename', $user->ID ) ); ?>" /></td>
 	</tr>
    </table>
    
    <?php
}
 
	add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
	add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );
	add_action( 'user_register', 'save_extra_user_profile_fields' );
	add_action( 'user_new_form', "save_extra_user_profile_fields" );

function save_extra_user_profile_fields( $user_id ) {

   if ( !current_user_can( 'edit_user', $user_id ) ) { 
       return false;
    }

	update_user_meta( $user_id, 'streetname', $_POST['streetname'] );
	update_user_meta( $user_id, 'cityname', $_POST['cityname'] );
	update_user_meta( $user_id, 'suitname', $_POST['suitname'] );
	update_user_meta( $user_id, 'pincode', $_POST['pincode'] );
	update_user_meta( $user_id, 'phone', $_POST['phone'] );
	update_user_meta( $user_id, 'companyname', $_POST['companyname'] );
	update_user_meta( $user_id, 'websitename', $_POST['websitename'] );

} 


/*--------------------------------------- Fetch articles  -----------------------------------*/
// Hook  to fetch the article data 

add_action('admin_menu', 'add_fetch_article_submenu');

//admin_menu callback function

function add_fetch_article_submenu(){

     add_submenu_page(
                     'edit.php?post_type=article', //$parent_slug
                     'Fetch Articles Subpage',  //$page_title
                     'Fetch Articles',        //$menu_title
                     'manage_options',           //$capability
                     'article_subpage',//$menu_slug
                     'article_subpage_render_page'//$function
     );

}

//add_submenu_page callback function

function article_subpage_render_page() {

     echo '<h2> Article Data  </h2>';  
   
    //echo "test";
    //$current_page = ( ! empty( $_POST['current_page'] ) ) ? $_POST['current_page'] : 1;
    $articles = '';

    // Should return an array of objects
    $results = wp_remote_get( 'https://jsonplaceholder.typicode.com/posts?per_page=2' );
    //echo( $results);


    if ( is_wp_error( $results ) ) {
		return;
	}

    // turn it into a PHP array from JSON string
    $results_data = json_decode( wp_remote_retrieve_body( $results ));   
  
    // Exit if nothing is returned.
	if ( empty( $results_data ) ) {
		return;
	}
	else {
		
	  echo "<pre>";
      print_r($results_data);
	  echo "</pre>";
	  
	  foreach ( $results_data as $article ) {

		  $article_title=  $article->title; 
		  $article_description=  $article->body; 
		  
		 wp_insert_post( [
        'post_name' => $article->title,
        'post_title' =>$article->title,
		'post_content' =>$article->body,
        'post_type' => 'article',
        'post_status' => 'publish'
      ] );
		}
	}
	    
}
/*---------------------------------------Fetch users from API  -----------------------------------*/
// Hook  to fetch the users data 

add_action('admin_menu', 'add_fetch_users_submenu');

//admin_menu callback function

function add_fetch_users_submenu(){

     add_submenu_page(
                     'edit.php?post_type=article', //$parent_slug
                     'Fetch Users Subpage',  //$page_title
                     'Fetch Users',        //$menu_title
                     'manage_options',           //$capability
                     'users_subpage',//$menu_slug
                     'users_subpage_render_page'//$function
     );

}

//add_submenu_page callback function

function users_subpage_render_page() {

     echo '<h2> Users Data </h2>';   
	 
	 //echo "test";
     //$current_page = ( ! empty( $_POST['current_page'] ) ) ? $_POST['current_page'] : 1;
     $users = '';

    // Should return an array of objects
    $u_results = wp_remote_get( 'https://jsonplaceholder.typicode.com/users?per_page=2' );
    //echo( $u_results);

    if ( is_wp_error( $u_results ) ) {
		return;
	}

    // turn it into a PHP array from JSON string
    $u_results_data = json_decode( wp_remote_retrieve_body( $u_results ));   
  
    // Exit if nothing is returned.
	if ( empty( $u_results_data ) ) {
		return;
	}
	else {
		
	  echo "<pre>";
      print_r($u_results_data);
	  echo "</pre>";
	  
	  foreach ( $u_results_data as $users ) {

		   $user_name=  $users->username; 
		   $user_email=  $users->email; 
		   $user_display_name = $users->name;
		   $user_street = $users->address->street;
		   $user_suit = $users->address->suit;
		   $user_pincode = $users->zipcode;
		   $user_phone = $users->phone;
		   $user_company_name = $users->company->name;
		   $user_website = $users->website;
		   
		   $roles = array( 
		   'administrator',
		   'editor',
		   'author',
		   'contributor',
		   'subscriber');
		   $role = $roles[2];
		   
		   $user_id = wp_insert_user( array(
		  'user_login'    => $users->username,
		  'user_email'    => $users->email,
		  'first_name'    => $users->name,
		  'display_name'  => $users->name,
		  'streetname'    => $users->address->street,
		  'cityname'      => $users->address->city,
		  'suitname'      => $users->address->suit,
		  'pincode'       => $users->address->zipcode,
		  'phone'         => $users->phone,
		  'companyname'   => $users->company->name,
		  'websitename'   => $users->website,
 		  'role'          => $role
		));
		 //  add_user_meta( $user_id, 'streetname', $users->address->street, true  );
		   
		    update_user_meta( $user_id, 'streetname', $users->address->street );
			update_user_meta( $user_id, 'cityname', $users->address->city );
			update_user_meta( $user_id, 'suitname',  $users->address->suite );
			update_user_meta( $user_id, 'pincode', $users->address->zipcode );
			update_user_meta( $user_id, 'phone', $users->phone );
			update_user_meta( $user_id, 'companyname',  $users->company->name );
			update_user_meta( $user_id, 'websitename',  $users->website);
		}
	}

}
 
?>

