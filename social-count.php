<?php
/**
 * Plugin Name: Social Count WP
 * Description: SocialCountWP is a small jQuery plugin for progressively enhanced, lazy loaded, mobile friendly social networking widgets.
 *
 * Version: 0.1
 *
 *
 * License: MIT
 */

 
if ( ! class_exists( 'SocialCountWP' ) ) :

class SocialCountWP {

	const version = '0.1';

	static function init() 
    {
		if ( is_admin() )
			return;

        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'add_styles') );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'add_scripts' ) );
	}

	static function add_scripts() 
    {
		wp_enqueue_script( 'socialcountwp-main-script',  self::get_url( 'dist/socialcount.min.js' ), array( 'jquery', 'jquery-sonar' ), self::version, true );
	}
    
    static function add_styles() 
    {
		wp_enqueue_style( 'socialcountwp-main-style',  self::get_url( 'dist/socialcount.min.css' ), array(), self::version, 'all' );
        wp_enqueue_style( 'socialcountwp-icon-style',  self::get_url( 'dist/socialcount-icons.min.css' ), array(), self::version, 'all' );

	}

	static function get_url( $path = '' ) 
    {
		return plugins_url( ltrim( $path, '/' ), __FILE__ );
	}
    
    public function show_SCWP( $url = NULL ) 
    {
        if ($url === NULL) :
            return;
        endif;
        
        require_once('dist/service/SocialCount.php');

        try {
            $social = new SocialCount($url);

            $social->addNetwork(new Twitter());
            $social->addNetwork(new Facebook());
            $social->addNetwork(new GooglePlus());
            // $social->addNetwork(new ShareThis());

            echo $social->toJSON();
        } catch(Exception $e) {
            echo '{"error": "' . $e->getMessage() . '"}';
        }
    }
}

        
SocialCountWP::init();

endif;
