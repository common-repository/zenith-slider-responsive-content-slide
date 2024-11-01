<?php

if( !class_exists( 'TF_Zenith_Interface' ) )
{
    class TF_Zenith_Interface
    {

    	protected static function hooks()
    	{
    		add_action( 'admin_menu', array( 'TF_Zenith_Interface', 'zt_replace_submit_box' ) );
			add_filter( "manage_edit-zenith_slider_columns", array( 'TF_Zenith_Interface', "zenith_post_columns" ) );
			add_action( "manage_zenith_slider_posts_custom_column", array( 'TF_Zenith_Interface', "zenith_custom_columns" ), 10, 2 );
            add_filter( 'post_row_actions', array( 'TF_Zenith_Interface', 'remove_view_link' ) );
            add_filter('enter_title_here', array( 'TF_Zenith_Interface','change_zenith_title' ) );
    	}

    	/**
    	* Replacing Default Submit Meta Box
    	*
    	*/
    	public static function zt_replace_submit_box()
    	{
    		remove_meta_box( 'submitdiv', 'zenith_slider', 'core' );
    		add_meta_box( 'submitdiv', 'Save Slider', array( 'TF_Zenith_Interface', 'zenith_submit' ), 'zenith_slider', 'side', 'low' );
    	}

    	/**
    	* custom edit of default wordpress publish box (callback function) 
		* code edited from wordpress/includes/metaboxes.php
		*
		* @global $action, $post
		* @since 1.0
		*/
    	public static function zenith_submit()
    	{
    	   global $action, $post;
	 
		   $post_type = $post->post_type;
		   $post_type_object = get_post_type_object($post_type);
		   $can_publish = current_user_can($post_type_object->cap->publish_posts);
		   ?>
		   <div class="submitbox" id="submitpost">
		   <div id="major-publishing-actions">
		   <?php
		   do_action( 'post_submitbox_start' );
		   ?>
		   <div id="delete-action">
		   <?php
		   if ( current_user_can( "delete_post", $post->ID ) ) {
		     if ( !EMPTY_TRASH_DAYS )
		          $delete_text = __('Delete Permanently');
		     else
		          $delete_text = __('Move to Trash');
		   ?>
		   <a class="submitdelete deletion" href="<?php echo get_delete_post_link($post->ID); ?>"><?php _e('Delete Slider'); ?></a><?php
		   } //if ?>
		   </div>
		   <div id="publishing-action">
		   <span class="spinner"></span>
		   <?php
		   if ( !in_array( $post->post_status, array('publish', 'future', 'private') ) || 0 == $post->ID ) {
		        if ( $can_publish ) : ?>
		          <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Save Slider') ?>" />
		          <?php submit_button( __( 'Save Slider' ), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' ) ); ?>
		   <?php   
		        endif; 
		   } else { ?>
		          <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Update Slider') ?>" />
		          <input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="<?php esc_attr_e('Update Slider') ?>" />
		   <?php
		   } //if ?>
		   </div>
		   <div class="clear"></div>
		   </div>
		   </div>
		  <?php
    	}

    	/**
    	* Add Custom Columns to Zenith Slider
    	* post edit screen
    	*
    	*/
    	public static function zenith_post_columns($cols)
    	{
    		$cols = array(
    			'cb' => '<input type="checkbox" />',
    			'title' => __('Slider Name', 'zenith'),
    			'layout' => __('Layout', 'zenith'),
    			'shortcode' => __('Shortcode', 'zenith')
    		);
    		return $cols;
    	}

    	//custom columns callback
    	public static function zenith_custom_columns( $column, $post_id )
    	{
    		switch( $column )
    		{
    			case 'layout':
    			  $layout = get_post_meta( $post_id, 'zenith_layout', true );	
    			  echo $layout[0];
    			break;
    			case 'shortcode':
    			  global $post;
				  $name = $post->post_name;
			      $shortcode = '<span style="border: solid 2px cornflowerblue; background:#fafafa; padding:2px 7px 5px; font-size:17px; line-height:40px;">[zenith slider="'.$name.'"]</strong>';
				  echo $shortcode; 
    			break;
    		}
    	}

        /**
        * Remove view post link from
        * post edit screen
        *
        * @param $action
        * @return $action
        * @since 1.0
        */
        public static function remove_view_link( $action )
        {
            unset ($action['view']);
            return $action;
        }

        /**
        * Add custom placeholder to the post title
        * field at post edit screen
        *
        * @param $title
        * @return string
        * @since 1.0
        */
        public static function change_zenith_title( $title )
        {
            $screen = get_current_screen();
            if( $screen->post_type == 'zenith_slider'){
                return 'Enter the slider name';
            }
        }

       
    	public static function init()
    	{
    		self::hooks();
    	}

    }//end TF_Zenith_Slider class
}//if !class_exists

?>