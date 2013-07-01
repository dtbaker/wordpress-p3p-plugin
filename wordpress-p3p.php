<?php


/*
	Plugin Name: WordPress P3P Header
	Plugin URI: https://github.com/dtbaker/wordpress-p3p-plugin
	Description: A quick plugin to append a valid P3P header to WordPress sites
	Version: 1.01
    History:
    1.01 - 1/7/2013 - initial release
*/


class wordpress_p3p{
    public function __construct(){
        if(is_admin()){
            add_action('admin_menu', array($this, 'add_plugin_page'));
            add_action('admin_init', array($this, 'page_init'));
        }
        if(get_option('p3p_header')){
            //policyref=\"http://www.example.com/w3c/p3p.xml\",
            header("P3P: CP=\"".get_option('p3p_header')."\"");
        }
    }
	
    public function add_plugin_page(){
        // This page will be under "Settings"
        add_options_page('P3P Settings', 'P3P Settings', 'manage_options', 'p3p-setting-admin', array($this, 'create_admin_page'));
    }

    public function create_admin_page(){
        ?>
	<div class="wrap">
	    <?php screen_icon(); ?>
	    <h2>P3P Settings</h2>
	    <form method="post" action="options.php">
	        <?php
                    // This prints out all hidden setting fields
		    settings_fields('p3p_option_group');	
		    do_settings_sections('p3p-setting-admin');
		?>
	        <?php submit_button(); ?>
	    </form>
	</div>
	<?php
    }
	
    public function page_init(){		
	register_setting('p3p_option_group', 'p3p_settings_array', array($this, 'save_settings'));
		
        add_settings_section(
	    'setting_section_id',
	    'P3P Settings',
	    array($this, 'print_section_info'),
	    'p3p-setting-admin'
	);	
		
	add_settings_field(
	    'p3p_header_details',
	    'P3P Header:',
	    array($this, 'create_an_id_field'), 
	    'p3p-setting-admin',
	    'setting_section_id'			
	);		
    }
	
    public function save_settings($input){
		update_option('p3p_header', isset($input['p3p_header']) ? $input['p3p_header'] : '');
    }
	
    public function print_section_info(){
        print 'Please enter your P3P header here<br> eg #1: ALL IND DSP COR ADM CONo CUR CUSo IVAo IVDo PSA PSD TAI TELo OUR SAMo CNT COM INT NAV ONL PHY PRE PUR UNI <br> eg #2: CURa ADMa DEVa CONo HISa OUR IND DSP ALL COR <br> More information about which P3P header to use is available here: <a href="http://en.wikipedia.org/wiki/P3P" target="_blank">http://en.wikipedia.org/wiki/P3P</a>';
    }
	
    public function create_an_id_field(){
        ?><input type="text" name="p3p_settings_array[p3p_header]" value="<?php echo get_option('p3p_header');?>" /><?php
    }
}

$wordpress_p3p = new wordpress_p3p();