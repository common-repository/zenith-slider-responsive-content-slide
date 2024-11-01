<?php

if( !class_exists( 'Zenith_Metaboxes' ) )
{
   class Zenith_Metaboxes
   {
      protected static function hooks()
      {
         add_action( 'cmb2_init', array( 'Zenith_Metaboxes', 'zenith_metabox_init' ) );
         add_action( 'add_meta_boxes', array( 'Zenith_Metaboxes', 'zt_general_opt' ) );
         add_action( 'save_post',  array( 'Zenith_Metaboxes', 'zenith_save_meta' ) );
      }

      /**
      * Create metaboxes for every slider layout + options
      *
      */
       public static function zenith_metabox_init()
       {
          $prefix = '_zt_';

          //default and hand layout
          $hgh = new_cmb2_box( array( 
              'id' => $prefix . 'highlights_box',
              'title' => __('Highlights', 'zenith'),
              'object_types' => array( 'zenith_slider' )
           ) );

          $hgh_group = $hgh->add_field( array( 
              'id'    => $prefix . 'highlight',
              'type'        => 'group',
              'description' => __( 'Add/Remove New Highlight', 'zenith' ),
              'options'     => array(
                  'group_title'   => __( 'Highlight {#}', 'cmb' ), 
                  'add_button'    => __( 'Add Another Highlight', 'cmb' ),
                  'remove_button' => __( 'Remove Highlight', 'cmb' ),
                  'sortable'      => true,
              ),
            ) 
          );

          $hgh->add_group_field( $hgh_group, array(
              'name' => '<span class="dashicons dashicons-edit"></span> ' . __('Title', 'zenith'),
              'id'   => $prefix . 'title',
              'type' => 'text',
          ) );

          $hgh->add_group_field( $hgh_group, array(
              'name' => '<span class="dashicons dashicons-visibility"></span> ' . __('Icon', 'zenith'),
              'id'   => $prefix . 'icon',
              'type' => 'select',
              'options' => zt_get_icons(),
              'row_classes' => 'zt_icon'
          ) );

          $hgh->add_group_field( $hgh_group, array(
              'name' => '<span class="dashicons dashicons dashicons-edit"></span> ' . __('Desription', 'zenith'),
              'id'   => $prefix . 'desc',
              'desc' => __('Highlight Content/Description.', 'zenith'),
              'type' => 'text',
          ) );

          $hgh->add_group_field( $hgh_group, array(
              'name' => '<span class="dashicons dashicons-format-image"></span> ' . __('Screen Image', 'zenith'),
              'id'   => $prefix . 'screen',
              'desc' => __('Screen representation of the highlight.', 'zenith'),
              'type' => 'file',
              'preview_size' => array( 170, 230 )
          ) );

          /**
          * Create action hook for adding custom element fields
          * @since 1.0.0
          */
          do_action( 'zenith_add_elements', $hgh, $hgh_group );

          

      }

      public static function zt_general_opt()
      {
         add_meta_box( 'zt_layout_opt', __('Layout', 'zenith'), array( 'Zenith_Metaboxes', 'zenith_layout_op_box' ), 'zenith_slider', 'side', 'high' );
         add_meta_box( 'zt_general_opt', __('General Options', 'zenith'), array( 'Zenith_Metaboxes', 'zenith_general_op_box' ), 'zenith_slider', 'side', 'high' );
      }      

      public static function zenith_layout_op_box($post)
      {
          $layout = get_post_meta( $post->ID, 'zenith_layout', true );

          $html = '<div id="layout_opt">';

          $html .= '<label class="img_slide_layout hgh_hand">';
          $html .= '<input type="radio" value="hand" id="hgh-2" class="toggle slide_layout" name="zenith_layout[]" '. ( !empty($layout) && 'hand' === $layout[0] ? 'checked="checked"' : '') .' />';
          $html .= '</label>';

          ob_start();

          /**
          * Create action hook for adding new layouts
          * @since 1.0.0
          */
          do_action( 'zenith_op_layouts', $layout );

          $new = ob_get_contents();
          ob_end_clean();
          $html .= $new;

          $html .= '</div>';

          echo $html;
      }


       /**
       * General Options Metabox
       *
       * This is a side metabox where
       * general options section will be placed 
       *
       * @param $post
       * @since 1.0
       */
       public static function zenith_general_op_box($post)
       {
          $index = get_post_meta( $post->ID, 'zt_index', true );
          $animation = get_post_meta( $post->ID, 'zt_animation', true );
          $bck = get_post_meta( $post->ID, 'zt_bck', true );
          $phone = get_post_meta( $post->ID, 'zt_phone', true );
          $direction = get_post_meta( $post->ID, 'zt_direction', true );
          $autoplay = get_post_meta( $post->ID, '_zt_autoplay', true );
          $delay = get_post_meta( $post->ID, 'zt_delay', true );
          $slideSpeed = get_post_meta( $post->ID, 'zt_speed', true );
          $autStop = get_post_meta( $post->ID, 'zt_autst', true );
          $iconStyle = get_post_meta( $post->ID, 'zt_icstyle', true );
          $iconColor = get_post_meta( $post->ID, 'zt_iclr', true ); 
          $iconBck = get_post_meta( $post->ID, 'zt_ibck', true ); 
          $iconAC = get_post_meta( $post->ID, 'zt_iconac', true ); 
          $bullets = get_post_meta( $post->ID, 'zt_bullets', true ); 
          $bulletsC = get_post_meta( $post->ID, 'zt_bulletsc', true ); 
          $bulletsAC = get_post_meta( $post->ID, 'zt_bulletsac', true );
          $arrows = get_post_meta( $post->ID, 'zt_arrows', true );
          ?>
          <div id="general_opt">
              <div class="zt_row">
                <div>
                    <label><?php _e('Select animation','zenith'); ?></label>
                </div>   
                <div> 
                    <select id="effect" name="zt_animation">
                      <option value="Slide" <?php if( $animation === 'Slide' ) echo 'selected="selected"' ; ?>>Slide</option>
                       <option value="Bounce" <?php if( $animation === 'Bounce' ) echo 'selected="selected"' ; ?>>Bounce</option>
                       <option value="Fade" <?php if( $animation === 'Fade' ) echo 'selected="selected"' ; ?>>Fade</option>
                       <option value="RotateDown" <?php if( $animation === 'RotateDown' ) echo 'selected="selected"' ; ?>>Rotate Down</option>
                       <option value="RotateUp" <?php if( $animation === 'RotateUp' ) echo 'selected="selected"' ; ?>>Rotate Up</option>
                       <option value="Zoom" <?php if( $animation === 'Zoom' ) echo 'selected="selected"' ; ?>>Zoom</option>
                       <option value="Flip" <?php if( $animation === 'Flip' ) echo 'selected="selected"' ; ?>>Flip</option>
                    </select>
                </div>
              </div>
              <div class="zt_row">
                <div>
                  <label><?php _e('Active Index'); ?></label><span class="fa fa-question"></span> 
                  <span class="question-bulb"><?php _e('This is screen/image that will be active/visible by default when page loads. (first slide/screen starts from 0)'); ?></span>
                </div>
                <div><input type="number" value="<?php echo !empty($index) ? $index : 0; ?>" name="zt_index" /></div>
              </div>
              <div class="zt_row">   
                <div>
                  <label><?php _e('Slide Speed'); ?></label><span class="fa fa-question"></span>
                  <span class="question-bulb"><?php _e('Slide stransition speed, determines how fast will slides animate/scroll.'); ?></span>
                </div>
                <div><span class="rng-val"></span><input type="range" step="100" value="<?php echo !empty($slideSpeed) ? $slideSpeed : 700; ?>" min="200" max="1500" name="zt_speed" /></div>
              </div>
              <div class="zt_row">
                <div>
                  <label><?php _e('Background Color'); ?></label> <span class="fa fa-question"></span>
                  <span class="question-bulb"><?php _e('This is a background color of the slider section.'); ?></span>
                </div>
                <div><input type="text" class="color" value="<?php echo !empty($bck) ? $bck : ''; ?>" name="zt_bck" /></div>
              </div>
              <div class="zt_row zt_hdn">
                <div><h3><?php _e('Autoplay Settings'); ?></h3></div>
              </div>
              <div class="zt_row">
                <div><label><?php _e('Autoplay'); ?></label></div>
                <div>
                  <label class="switch"><?php _e('On'); ?>
                    <input type="radio" class="toggle autp" value="on" id="auton" name="_zt_autoplay" <?php if( $autoplay === 'on' ) echo 'checked="checked"'; ?> />
                  </label>
                  <label class="switch"><?php _e('Off'); ?> 
                    <input type="radio" class="toggle autp" id="autoff" value="off" name="_zt_autoplay" <?php if( $autoplay === 'off' ) echo 'checked="checked"'; ?> />
                  </label>
                </div>
              </div>

              <div class="zt_row autoplay">
                <div>
                  <label><?php _e('Autoplay Delay'); ?></label><span class="fa fa-question"></span>
                  <span class="question-bulb"><?php _e('Delay between slides (in ms)'); ?></span>
                </div>
                <div><span class="rng-val"></span><input type="range" step="100" value="<?php echo !empty($delay) ? $delay : 3500; ?>" min="1000" max="10000" name="zt_delay" /></div>
              </div>
              <div class="zt_row autoplay">
                <div>
                  <label><?php _e('Autoplay Stop'); ?></label><span class="fa fa-question"></span>
                  <span class="question-bulb"><?php _e('Stop the autoplay when mouse enters the screen/phone area.'); ?></span>
                </div>
                <div>
                    <label class="switch"><?php _e('On'); ?>
                      <input type="radio" class="toggle" value="on" id="aston" name="zt_autst" <?php if( 'on' === $autStop ) echo 'checked="checked"'; ?> />
                    </label>
                    <label class="switch"><?php _e('Off'); ?> 
                      <input type="radio" class="toggle" value="off" id="astof" name="zt_autst" <?php if( 'off' === $autStop ) echo 'checked="checked"'; ?> />
                    </label>
                </div>
              </div>
              <div class="zt_row high_opt hgh-3">
                <div>
                  <label><?php _e('Slide Direction'); ?></label><span class="fa fa-question"></span>
                  <span class="question-bulb"><?php _e('Animation direction, determines if the slides are going to scroll to the left-right (horizontal) direction, of up-down (vertical) direction.'); ?></span>
                </div>
                <div>
                    <select name="zt_direction">
                      <option value="horizontal" <?php if( 'horizontal' === $direction ) echo 'selected="selected"'; ?> >Horizontal</option>
                      <option value="vertical" <?php if( 'vertical' === $direction ) echo 'selected="selected"'; ?>>Vertical</option>
                    </select>
                </div>
              </div>
              <div class="zt_row high_opt hgh-1 hgh-2 zt_hdn">
                <div><h3>Icon Optons</h3></div>
              </div>
              <div id="icon_style" class="zt_row high_opt hgh-1 hgh-2">
                <div><label><?php _e('Icon Style'); ?></label></div>
                <div>
                   <label class="img_icon" id="crcl">
                     <input type="radio" class="toggle" value="circle" name="zt_icstyle" <?php if( $iconStyle === 'circle' ) echo 'checked="checked"'; ?> />
                   </label>
                   <label class="img_icon" id="trngl">
                     <input type="radio" class="toggle" value="triangle" name="zt_icstyle" <?php if( $iconStyle === 'triangle' ) echo 'checked="checked"'; ?> />
                   </label>
                </div>
              </div>
              <div class="zt_row high_opt hgh-1 hgh-2">
                <div><label><?php _e('Icon Color'); ?></label></div>
                <div><input type="text" class="color" value="<?php echo !empty($iconColor) ? $iconColor : '#fefefe'; ?>" name="zt_iclr" /></div>
              </div>
              <div class="zt_row high_opt hgh-1 hgh-2">
                <div>
                  <label><?php _e('Icon Background Color'); ?></label><span class="fa fa-question"></span>
                  <span class="question-bulb"><?php _e('Background color of the circle/square that holds the icon.'); ?></span>
                </div>
                <div><input type="text" class="color" value="<?php echo !empty($iconBck) ? $iconBck : 'cornflowerblue'; ?>" name="zt_ibck" /></div>
              </div>
              <div class="zt_row high_opt hgh-1 hgh-2">
                <div>
                  <label><?php _e('Active Icon Background Color'); ?></label><span class="fa fa-question"></span>
                  <span class="question-bulb"><?php _e('Background color of the circle/square of currently active highlight.'); ?></span>
                </div>
                <div><input type="text" class="color" value="<?php echo !empty($iconAC) ? $iconAC : 'crimson'; ?>" name="zt_iconac" /></div>
              </div>
              <div id="phone_style" class="zt_row high_opt hgh-1 hgh-2">
                <div><label><?php _e('Phone Version'); ?></label></div>
                <div class="zt_phn">
                   <label class="img_icon phw">
                     <input type="radio" class="toggle" id="phw" value="wht" name="zt_phone" <?php if( $phone === 'wht' ) echo 'checked="checked"'; ?> />
                   </label>
                   <label class="img_icon phb">
                     <input type="radio" class="toggle" id="phb" value="black" name="zt_phone" <?php if( $phone === 'black' ) echo 'checked="checked"'; ?> />
                   </label>
                </div>
              </div>

              <div class="zt_row screen_opt hgh-3 zt_hdn">
                <div><h3><?php _e('Screen navs'); ?></h3></div>
              </div>
              <div class="zt_row screen_opt hgh-3">
                <div>
                  <label><?php _e('Bullet Navigation'); ?></label><span class="fa fa-question"></span>
                  <span class="question-bulb"><?php _e('Display or hide bullet-navigation.'); ?></span>
                </div>
                <div>
                  <label class="switch"><?php _e('On'); ?>
                    <input type="radio" class="toggle" value="on" id="bulon" name="zt_bullets" <?php if( 'on' === $bullets ) echo 'checked="checked"'; ?> />
                  </label>
                  <label class="switch"><?php _e('Off'); ?> 
                    <input type="radio" class="toggle" value="off" id="buloff" name="zt_bullets" <?php if( 'off' === $bullets ) echo 'checked="checked"'; ?> />
                  </label>
                </div>
              </div>
              <div class="zt_row screen_opt hgh-3 bullet">
                <div><label><?php _e('Bullets Color'); ?></label></div>
                <div><input type="text" class="color" value="<?php echo !empty($bulletsC) ? $bulletsC : '#fefefe'; ?>" name="zt_bulletsc" /></div>
              </div>
              <div class="zt_row screen_opt hgh-3 bullet">
                <div>
                  <label><?php _e('Active Bullet Color'); ?></label><span class="fa fa-question"></span>
                  <span class="question-bulb"><?php _e('Color of the currently active bullet.'); ?></span>
                </div>
                <div><input type="text" class="color" value="<?php echo !empty($bulletsAC) ? $bulletsAC : 'cornflowerblue'; ?>" name="zt_bulletsac" /></div>
              </div>
              <div class="zt_row screen_opt hgh-3">
                <div>
                  <label><?php _e('Arrows'); ?></label><span class="fa fa-question"></span>
                  <span class="question-bulb"><?php _e('Show/Hide navigational arrows.'); ?></span>
                </div>
                <div>
                  <label class="switch"><?php _e('On'); ?>
                    <input type="radio" class="toggle" value="on" id="aron" name="zt_arrows" <?php if( 'on' === $arrows ) echo 'checked="checked"'; ?> />
                  </label>
                  <label class="switch"><?php _e('Off'); ?> 
                    <input type="radio" class="toggle" value="off" id="arof" name="zt_arrows" <?php if( 'off' === $arrows ) echo 'checked="checked"'; ?> />
                  </label>
                </div>
              </div>
          </div>
         <?php
       }

       /**
       * Saving the metaboxes
       */
      public static function zenith_save_meta($post_id)
      {
          if(defined('DOING_AUTOSAVE') && 'DOING_AUTOSAVE') {
            return $post_id;
          }
          global $post;
          // Check the user's permissions.
          if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) ) {
              return;
            }
          } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
              return;
            }
          }

          $metadata['zenith_layout']  = ( isset( $_POST['zenith_layout'] ) ? $_POST['zenith_layout'] : '' ); 
          $metadata['zt_direction']  = ( isset( $_POST['zt_direction'] ) ? $_POST['zt_direction'] : '' );
          $metadata['zt_index']  = ( isset( $_POST['zt_index'] ) ? $_POST['zt_index'] : '' );
          $metadata['zt_animation']  = ( isset( $_POST['zt_animation'] ) ? $_POST['zt_animation'] : '' );
          $metadata['_zt_autoplay']  = ( isset( $_POST['_zt_autoplay'] ) ? sanitize_text_field( $_POST['_zt_autoplay'] ) : '' );
          $metadata['zt_delay']  = ( isset( $_POST['zt_delay'] ) ? sanitize_text_field( $_POST['zt_delay'] ) : '' );
          $metadata['zt_speed']  = ( isset( $_POST['zt_speed'] ) ? sanitize_text_field( $_POST['zt_speed'] ) : '' );
          $metadata['zt_icstyle']  = ( isset( $_POST['zt_icstyle'] ) ? sanitize_text_field( $_POST['zt_icstyle'] ) : '' );
          $metadata['zt_iclr']  = ( isset( $_POST['zt_iclr'] ) ? sanitize_text_field( $_POST['zt_iclr'] ) : '' );
          $metadata['zt_ibck']  = ( isset( $_POST['zt_ibck'] ) ? sanitize_text_field( $_POST['zt_ibck'] ) : '' );
          $metadata['zt_iconac']  = ( isset( $_POST['zt_iconac'] ) ? sanitize_text_field( $_POST['zt_iconac'] ) : '' );
          $metadata['zt_bullets']  = ( isset( $_POST['zt_bullets'] ) ? sanitize_text_field( $_POST['zt_bullets'] ) : '' );
          $metadata['zt_bulletsc']  = ( isset( $_POST['zt_bulletsc'] ) ? sanitize_text_field( $_POST['zt_bulletsc'] ) : '' );
          $metadata['zt_bulletsac']  = ( isset( $_POST['zt_bulletsac'] ) ? sanitize_text_field( $_POST['zt_bulletsac'] ) : '' );
          $metadata['zt_arrows']  = ( isset( $_POST['zt_arrows'] ) ? sanitize_text_field( $_POST['zt_arrows'] ) : '' ); 
          $metadata['zt_bck']  = ( isset( $_POST['zt_bck'] ) ? sanitize_text_field( $_POST['zt_bck'] ) : '' ); 
          $metadata['zt_phone']  = ( isset( $_POST['zt_phone'] ) ? sanitize_text_field( $_POST['zt_phone'] ) : '' ); 

          foreach( $metadata as $key => $value ){

            $current_value = get_post_meta($post_id, $key, true);
            if( $value && '' == $current_value ){
               add_post_meta( $post_id, $key, $value, true );
            } 
              elseif( $value && '' != $current_value ){
                update_post_meta( $post_id, $key, $value );
            } 
              elseif ( '' == $value &&  $current_value ){
               delete_post_meta( $post_id, $key, $current_value );
              }
           }
      }

      public static function init()
      {
        self::hooks();
      }


  }
}
?>