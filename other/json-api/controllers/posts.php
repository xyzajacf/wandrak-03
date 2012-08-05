<?php
/*
Controller name: Posts
Controller description: Data manipulation methods for posts
*/

class JSON_API_Posts_Controller {

  public function delete_post() {
    nocache_headers();
    if (!current_user_can('edit_posts')) {
      $json_api->error("You need to login with a user capable of creating posts.");
    }

    wp_delete_post($_REQUEST['postId']);
    return array('post_id' => $_REQUEST['postId']);
  }

  public function create_post() {

	if ($_REQUEST['delete'] == 1) {
		return $this->delete_post();
	} else if ($_REQUEST['na'] == 1) {
//		http://wandrak.ideoci.eu/?json=create_post&status=publish&title=Test_lng_lang&content=Uvidime..2&lat=46.980252&lng=16.54541&parentId=9&na=na
//		http://192.168.192.128/?json=create_post&status=publish&type=page&title=PageAjax2Sub2&content=nieco2&na=1&parent_id=33
	    global $json_api;
	
	    if (!current_user_can('edit_posts')) {
	      $json_api->error("You need to login with a user capable of creating posts.");
	    }
//	    if (!$json_api->query->nonce) {
//	      $json_api->error("You must include a 'nonce' value to create posts. Use the `get_nonce` Core API method.");
//	    }
	    $nonce_id = $json_api->get_nonce_id('posts', 'create_post');
//	    if (!wp_verify_nonce($json_api->query->nonce, $nonce_id)) {
//	      $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
//	    }
	} else {
//  	http://wandrak.ideoci.eu/?json=create_post&status=publish&title=API2-post&content=nieco2&user=pista&pass=8sxiA6MjAKrj
	    if (!user_pass_ok( $_REQUEST['user'], $_REQUEST['pass'] ) ) {
	      $json_api->error("User / password do not match!");
	    }
	}
    return $this->createPost();
  }

  function createPost() {
    nocache_headers();

    $post = new JSON_API_Post();
    $id = $post->save($_REQUEST);
    if (empty($id)) {
      $json_api->error("Could not create post.");
    }
    if (update_post_meta($id, 'lat', $_REQUEST['lat'])) {
	    add_post_meta($id, 'lat', $_REQUEST['lat'], true);
    }
    if (update_post_meta($id, 'lng', $_REQUEST['lng'])) {
	    add_post_meta($id, 'lng', $_REQUEST['lng'], true);
	}
    return array(
      'post' => $post,
      'markerId' => $_REQUEST['markerId']
    );
  }

}

?>