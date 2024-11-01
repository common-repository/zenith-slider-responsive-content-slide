<?php

if( !class_exists( 'Zenith_Shortcode' ) )
{
	class Zenith_Shortcode
	{
		function __construct()
		{
			add_shortcode( 'zenith', array( $this, 'zenith_shortcode' ) );
		}

		public function zenith_shortcode( $atts )
		{
        /**
          * Call post by name extracting the $name 
          * from the shortcode previously created
          * in custom post column
          */
          extract( shortcode_atts( array(
          		 'slider'  => ''
          	), $atts )
          );	
          
          $args = array('post_type' => 'zenith_slider', 'name' => $slider);
          $slides = get_posts( $args );
          $html = '';
          $di = 0;
          $ID = $slider;
          foreach( $slides as $slide )
          {	
          	setup_postdata($slide);	
	        //collect option values 
	        $layout = get_post_meta( $slide->ID, 'zenith_layout', true );
	        $bck = get_post_meta( $slide->ID, 'zt_bck', true );
	        $animation = get_post_meta( $slide->ID, 'zt_animation', true );
	        $phone = get_post_meta( $slide->ID, 'zt_phone', true );
	        $direction = get_post_meta( $slide->ID, 'zt_direction', true );
	        $autStop = get_post_meta( $slide->ID, 'zt_autst', true );
	        $index = get_post_meta( $slide->ID, 'zt_index', true );
	        $autoplay = get_post_meta( $slide->ID, '_zt_autoplay', true );
	        $autDelay = get_post_meta( $slide->ID, 'zt_delay', true );
	        $slideSpeed = get_post_meta( $slide->ID, 'zt_speed', true );
	        $iconStyle = get_post_meta( $slide->ID, 'zt_icstyle', true );
	        $iconColor = get_post_meta( $slide->ID, 'zt_iclr', true ); 
	        $iconBck = get_post_meta( $slide->ID, 'zt_ibck', true ); 
	        $iconAC = get_post_meta( $slide->ID, 'zt_iconac', true ); 
	        $bullets = get_post_meta( $slide->ID, 'zt_bullets', true ); 
	        $bulletsC = get_post_meta( $slide->ID, 'zt_bulletsc', true ); 
	        $bulletsAC = get_post_meta( $slide->ID, 'zt_bulletsac', true );
	        $arrows = get_post_meta( $slide->ID, 'zt_arrows', true );
	        $hgh_vals = get_post_meta( $slide->ID, '_zt_highlight', true ); 
	        $screens = get_post_meta( $slide->ID, '_zt_screens', true ); 
	        $content = get_post_meta( $slide->ID, '_zt_content', true );
	        $heading = get_post_meta( $slide->ID, '_zt_heading', true );
	        if( empty($animation) ) $animation = 'Slide';
	        /**
	        * Check for layout, and then call appropriate markup
	        *
	        */
	        if( 'default' === $layout[0] )
	        {
			  $html .= '<section id="zenith_'. $slide->ID .'" class="zenith_slider clearfix" data-layout="'. ( !empty( $layout[0] ) ? $layout[0] : 'default' ) .'" data-direction="'. ( !empty( $direction ) ? $direction : 'horizontal' ) .'" data-autoplay="'. ( !empty( $autoplay ) ? $autoplay : 'on' ) .'" data-animation="'. $animation .'">';
			   $html .= apply_filters( 'zenith_def_layout', $hgh_vals, $di );
			   $html .= '<input class="passed-values" type="hidden" data-index="'. ( !empty( $index ) ? $index : 0 ) .'" data-ic="'. (!empty( $iconColor ) ? $iconColor : '#fffff0') .'" data-ac="'. (!empty( $iconAC ) ? $iconAC : 'crimson') .'" data-aus="true" data-autsp="'. (!empty($autDelay ) ? $autDelay : '3500') .'" data-slds="'. (!empty($slideSpeed ) ? $slideSpeed : '600') .'" data-margin="'. (!empty($margin) ? $margin : '65') .'" data-cc="'. (!empty( $iconBck ) ? $iconBck : 'cornflowerblue') .'" data-bck="'. ( !empty( $bck ) ? $bck : 'transparent' ) .'" />';
	           $html .= '</section>';	
	        }
	        else if( 'hand' === $layout[0] )
	        { //second layout - Highlights Hand With Phone
	          $idx = 0;	
	          $ix = 0;
			  $html = '<section id="zenith_'. $slide->ID .'" class="hand zenith_slider" data-layout="'. ( !empty( $layout[0] ) ? $layout[0] : 'default' ) .'" data-direction="'. ( !empty( $direction ) ? $direction : 'horizontal' ) .'" data-autoplay="'. ( !empty( $autoplay ) ? $autoplay : 'on' ) .'" data-animation="'. $animation .'">';
			  $html .= '<div class="row">';
              $html .= '<div class="highlights"><ul>';	
			  foreach( $hgh_vals  as $hgh => $value )
	          {
			    	$html .= '<li class="highlight lhgh" data-index="'. $ix .'">';
					$html .= '<div class="highlight-title">';
					$html .= '<h3><span>'. $value['_zt_title'] .'</span></h3>';
					$html .= '<span class="fa '. $value['_zt_icon'] .' fa-2x '. ( !empty($iconStyle) ? $iconStyle : '' ) .'" ></span>';
					$html .= '</div>';
					$html .= '<p>'. $value['_zt_desc'] .'</p>';
					$html .= '</li>';
					$ix++;
			  } 
			  $html .='</ul></div>';
			  $html .= '<div class="phone-hand '. ( !empty($phone) ? $phone : 'wht' ) .'">';
			  $html .= '<div class="ratio"></div>';
			  $html .= '<div id="inner-screen">';
			  foreach( $hgh_vals  as $hgh => $value )
	          {
	          	$image = wp_get_attachment_image( $value['_zt_screen_id'] , array(190,320) );
			  	$html .= '<div class="hgi" data-index="'. $idx .'">';
			  	$html .= $image;
			  	$html .= '</div>';
			  	$idx++;
			  }
			  $html .= '</div></div></div>';
			  $html .= '<input class="passed-values" type="hidden" data-index="'. ( !empty( $index ) ? $index : 0 ) .'" data-ic="'. (!empty( $iconColor ) ? $iconColor : '#fffff0') .'" data-ac="'. (!empty( $iconAC ) ? $iconAC : 'crimson') .'" data-aus="true" data-autsp="'. (!empty($autDelay ) ? $autDelay : '3500') .'" data-slds="'. (!empty($slideSpeed ) ? $slideSpeed : '600') .'" data-margin="'. (!empty($margin) ? $margin : '65') .'" data-cc="'. (!empty( $iconBck ) ? $iconBck : 'cornflowerblue') .'" data-bck="'. ( !empty( $bck ) ? $bck : 'transparent' ) .'" />';
			  $html .= '</section>';
	        }
	        else if( 'screen' === $layout[0] )
	        {   	
	          $i = 0;
	          // Now the html markup
	          $html = '<section id="zenith_'. $slide->ID .'" class="zenith_slider" data-layout="'. ( !empty( $layout[0] ) ? $layout[0] : 'default' ) .'" data-direction="'. ( !empty( $direction ) ? $direction : 'horizontal' ) .'" data-autoplay="'. ( !empty( $autoplay ) ? $autoplay : 'on' ) .'" data-animation="'. $animation .'">';
	          $html .= apply_filters( 'zenith_screen_layout', $heading, $content, $screens );
	          $html .= '<input class="passed-values" type="hidden" data-index="'. ( !empty( $index ) ? $index : 0 ) .'"  data-aus="true" data-autsp="'. (!empty($autDelay ) ? $autDelay : '3500') .'" data-slds="'. (!empty($slideSpeed ) ? $slideSpeed : '600') .'" data-margin="'. (!empty($margin) ? $margin : '65') .'" data-bullets="'. (!empty($bullets) ? $bullets: 'on') .'" data-bulletsac="'. (!empty($bulletsAC) ? $bulletsAC : '#999') .'" data-bulletsc="'. (!empty($bulletsC) ? $bulletsC : '#fefefe') .'" data-arrows="'. (!empty($arrows) ? $arrows : 'on') .'" data-bck="'. ( !empty( $bck ) ? $bck : 'transparent' ) .'" />';
			  $html .= '</section>';
	      	}//else if()
          }//foreach()
          
          echo $html;
        }

    }//class ends
}//if !class_exists
?>