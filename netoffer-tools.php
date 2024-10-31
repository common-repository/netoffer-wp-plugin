<?php
/*
Plugin Name: Netoffer Tools Access
Plugin URI:
Description: Manage newsletters and email campaigns for registered and non-registered netoffer users.
Author: Walter Brock
Version: 1.0.0
Author URI: http://netoffer.com
License: GPLv2
 * 
 */
define('NETOFFER_NEWSLETTER_BASE_URL', plugin_dir_url(__FILE__) );
define('NETOFFER_NEWSLETTER_BASE_PATH', plugin_dir_path(__FILE__) );
if(!class_exists('netOfferTools'))
    {
    class netOfferTools{
        public function __construct()
            {
            //when first installed user must signup for netoffer account.
            //option to check for no_tools_key
            $NO_key = get_option('no_tools_key');
            //if plugin has not been activated. display menu item to register and activate plugin else display plugin admin menu.
            if($NO_key===false || empty($NO_key))
                {
                add_action( 'admin_menu', array($this, 'create_activation_key_menu') );
                }
            else
                {
                add_action( 'admin_menu', array($this, 'no_tools_menu') );
                if(!class_exists('netofferEmailSignupClass'))
                    {                    
                    require_once(NETOFFER_NEWSLETTER_BASE_PATH.'widgets/netofferEmailSignupForm.php');                    
                    }
                }
            }
            
        function create_activation_key_menu()
            {
            add_menu_page( 'NetOffer Account Tools', 'NetOffer Tools', 'manage_options', 'no_activate_account', array($this, 'enter_activation_key'));
            add_submenu_page( 'no_activate_account', 'NetOffer Activate Account', 'Activate Account', 'manage_options', 'activate_netoffer_account', array($this, 'enter_activation_key'));
            }
        function no_tools_menu()
            {
            add_menu_page( 'NetOffer Account Tools', 'NetOffer Tools', 'manage_options', 'no_account_management', array($this, 'no_account_management_tools'), NETOFFER_NEWSLETTER_BASE_URL.'images/fav.png' );
            add_submenu_page( 'no_account_management', 'NetOffer Tools', 'NetOffer Activation Key', 'manage_options', 'edit_activation_key', array($this, 'no_account_management_tools'));
            add_submenu_page( 'no_account_management', 'NetOffer Tools', 'NetOffer User Name', 'manage_options', 'edit_netoffer_user_name', array($this, 'no_account_management_tools'));
            global $submenu;
            $netoffer_user_name = get_option('netoffer_user_name');
            if($netoffer_user_name)
                {
                add_menu_page( 'NetOffer Account C.O.R.E', 'C.O.R.E', 'manage_options', 'netoffer_core', array($this, 'no_account_management_tools'), NETOFFER_NEWSLETTER_BASE_URL.'images/fav.png' );
                $submenu['netoffer_core'][498] = array('?Need Help?', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/help-core-options.php');
                $submenu['netoffer_core'][500] = array('Upgrade', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/no-account-status.php');
                $submenu['netoffer_core'][502] = array('User Profile', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/no-profile.php');
                $submenu['netoffer_core'][504] = array('Business Profile', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/profile_company_information.php');
                $submenu['netoffer_core'][506] = array('- What We Sell', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/what_we_sell.php');
                $submenu['netoffer_core'][508] = array('- What We Buy', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/what_we_buy.php');
                $submenu['netoffer_core'][510] = array('Groups I\'ve Joined', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/no-mygroups.php');
                add_menu_page( 'NetOffer Account Members', 'Members', 'manage_options', 'netoffer_members', array($this, 'no_account_management_tools'), NETOFFER_NEWSLETTER_BASE_URL.'images/fav.png' );
                $submenu['netoffer_members'][498] = array('Member Sect Help?', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/help-members-options.php');
                $submenu['netoffer_members'][500] = array('Search NetOffer', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/no-find-members.php');
                $submenu['netoffer_members'][502] = array('Invite New Members', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/no-invitemembers.php');
                $submenu['netoffer_members'][504] = array('Email Notices', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/no-notifications.php');
                $submenu['netoffer_members'][505] = array('CRM System Help?', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/help-contact-manager.php');
                $submenu['netoffer_members'][506] = array('CRM Database', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/contact_manager_search.php');
                $submenu['netoffer_members'][508] = array('Edit CRM Classes', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/contact_manager_classes.php');
                $submenu['netoffer_members'][510] = array('Setup Directory A', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/contact_manager_configure_member_directory.php');
                $submenu['netoffer_members'][512] = array('Setup Directory B', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/contact_manager_configure_member_directory_b.php');
                $submenu['netoffer_members'][514] = array('Edit Reminders', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/contact_manager_reminder_admin.php');
                add_menu_page( 'NetOffer Account Classified Ads', 'Classified Ads', 'manage_options', 'netoffer_class_ads', array($this, 'no_account_management_tools'), NETOFFER_NEWSLETTER_BASE_URL.'images/fav.png' );
                $submenu['netoffer_class_ads'][500] = array('Post A Class Ad', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/no-post-classified.php');
                $submenu['netoffer_class_ads'][502] = array('Edit A Class Ad', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/no-edit-classified.php');
                $submenu['netoffer_class_ads'][504] = array('Class Ads', 'manage_options', 'http://class.netoffer.com/');
                add_menu_page( 'NetOffer Account Market Place', 'Market Place', 'manage_options', 'netoffer_market_place', array($this, 'no_account_management_tools'), NETOFFER_NEWSLETTER_BASE_URL.'images/fav.png' );
                $submenu['netoffer_market_place'][500] = array('Need Help?', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/help-marketplace.php');
                $submenu['netoffer_market_place'][500] = array('Search The Market', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/search_the_market.php');
                $submenu['netoffer_market_place'][502] = array('Offers Sent', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/search_the_market_offers_sent.php');
                $submenu['netoffer_market_place'][504] = array('Offers Received', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/search_the_market_offers_received.php');
                $submenu['netoffer_market_place'][506] = array('Inquiries Sent', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/search_the_market_inquiries_sent.php');
                $submenu['netoffer_market_place'][508] = array('Inquiries Received', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/search_the_market_inquiries_received.php');
                add_menu_page( 'NetOffer Account Reports', 'Reports', 'manage_options', 'netoffer_reports', array($this, 'no_account_management_tools'), NETOFFER_NEWSLETTER_BASE_URL.'images/fav.png' );
                $submenu['netoffer_reports'][500] = array('My Activities Help?', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/help-my-activities.php');
                $submenu['netoffer_reports'][502] = array('- 1-1 Meetings', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/contact_maager_OneToOne.php');
                $submenu['netoffer_reports'][504] = array('- Referrals Sent', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/contact_manager_referrals_sent.php');
                $submenu['netoffer_reports'][506] = array('- Referrals Received', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/contact_manager_referrals_received.php');
                $submenu['netoffer_reports'][508] = array('- Meetings Attended', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/contact_manager_meeting_attended.php');
                $submenu['netoffer_reports'][510] = array('- Testimonials Sent', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/contact_manager_testimonials_sent.php');
                $submenu['netoffer_reports'][512] = array('- Testimonials Received', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/contact_manager_testimonials_received.php');
                $submenu['netoffer_reports'][514] = array('Community Activities Help?', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/help-community-activities.php');
                $submenu['netoffer_reports'][512] = array('- 1-1 Meetings', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/contact_manager_OneToOne_reports.php');
                $submenu['netoffer_reports'][514] = array('- Referrals', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/contact_manager_referral_reports.php');
                $submenu['netoffer_reports'][514] = array('- Meeting Attendance', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/contact_manager_meeting_attended_reports.php');
                add_menu_page( 'NetOffer Account Affiliate Program', 'Affiliate Program', 'manage_options', 'netoffer_affiliate', array($this, 'no_account_management_tools'), NETOFFER_NEWSLETTER_BASE_URL.'images/fav.png' );
                $submenu['netoffer_affiliate'][500] = array('How It Works', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/no-referral-howitworks.php');
                $submenu['netoffer_affiliate'][502] = array('$ Earned & Available', 'manage_options', 'http://netoffer.com/'.$netoffer_user_name.'/wp-admin/no-ledger.php');
                
                }
            }
        function enter_activation_key()
            {            
            //page to display for user to enter the activation key from the NetOffer site.            
            if(!isset($_POST['key_submit']))
                {
                ?>
                <div class="netoffer_wrapper">
                <h2>Welcome to NetOffer.</h2>
                <p>You must first create an acount on the NetOffer system to use this plugin.</p>
                <p>Click <a href="http://netoffer-wp-plugin.com/register" target="_blank" />Here</a> to create your account.</p>
                <div>After you have created your account on the NetOffer system, you will be given a key to insert here. Once your key has been entered here this plugin will be activated and all features will be enabled.</div>
                <form action="" method="post">
                <span>Activation Key: <input type="text" name="no_tools_key" style="width:20%;" /></span>
                <br />
                <span>NetOffer User Name: <input type="text" name="no_tools_username" style="width:20%;" /></span>
                <br />
                <br />
                <input class="button-primary" type="submit" name="key_submit" value="Enter Key!" />
                </form>
                </div>            
                <?php
                }
            else
                {
                $key = trim($_POST['no_tools_key']);
                $no_user_name = trim($_POST['no_tools_username']);
                $keys = explode('-', $key);
                if(empty($key))
                    {                    
                    ?>
                    <div class="netoffer_wrapper">
                    <h2>The Key Field Cannot Be Empty.</h2>
                    <p>You must first create an acount on the NetOffer system to use this plugin.</p>
                    <p>Click <a href="http://netoffer.com/register" target="_blank" />Here</a> to create your account.</p>
                    <div>After you have created your account on the NetOffer system, you will be given a key to insert here. Once your key has been entered here this plugin will be activated and all features will be enabled.</div>
                    <form action="" method="post">
                    <span>Activation Key: <input type="text" name="no_tools_key" style="width:20%;" /></span>
                    <br />
                    <span>NetOffer User Name: <input type="text" name="no_tools_username" style="width:20%;" /></span>
                    <br />
                    <br />
                    <input class="button-primary" type="submit" name="key_submit" value="Enter Key!" />
                    </form>
                    </div>
                    <?php
                    }
                elseif(count($keys) != 3 || strlen($keys[0]) != 24)
                    {
                    ?>
                    <div class="netoffer_wrapper">
                    <h2>The Key your entered is invalid please copy and paste it from your NetOffer account.</h2>           
                    <form action="" method="post">
                    <span>Activation Key: <input type="text" name="no_tools_key" style="width:20%;" /></span>
                    <br />
                    <span>NetOffer User Name: <input type="text" name="no_tools_username" style="width:20%;" /></span>
                    <br />
                    <br />
                    <input class="button-primary" type="submit" name="key_submit" value="Enter Key!" />
                    </form>
                    </div>
                    <?php
                    }
                else
                    {                    
                    update_option('no_tools_key', $key);
                    if(!empty($no_user_name))
                        {
                        update_option('netoffer_user_name', $no_user_name);
                        }
                    ?>
                    <h3>You have successfully activated the plugin.</h3>
                    <p>Click on the dashboard menu item to refresh the dashboard.</p>
                    <?php
                    }
                }
            }
        function no_account_management_tools()
            {            
            if($_GET['page'] == 'edit_netoffer_user_name')
                {
                $netOfferUserName = get_option('netoffer_user_name');
                if($netOfferUserName)
                    {
                    if(!isset($_POST['addnetofferusername']))
                        {
                        ?>
                        <h3>Your current user name is: <?php echo $netOfferUserName; ?></h3>                        
                        <p><span style="font-weight:bold;">Note:</span> Username is case sensitive.</p>
                        <form action="" method="post">
                        <span style="font-weight:bold;">NEW</span> NetOffer User Name: <input type="text" name="netofferusername" style="width:200px;" />
                        <input type="submit" name="addnetofferusername" value="Update User Name" />
                        </form>
                        <?php
                        }
                    else
                        {
                        $netofferUserName = $_POST['netofferusername'];
                        update_option('netoffer_user_name', $netofferUserName);
                        ?>
                        <h3>You have successfully updated your NetOffer User Name!</h3>
                        <?php
                        }
                    }
                else
                    {
                    //create the form to enter your username.
                    if(!isset($_POST['addnetofferusername']))
                        {
                        ?>
                        <h3>You do not have a user name entered.</h3>
                        <p>To use the NetOffer Tools Menu items you will need to enter your username.</p>
                        <p><span style="font-weight:bold;">Note:</span> Username is case sensitive.</p>
                        <form action="" method="post">
                        NetOffer User Name: <input type="text" name="netofferusername" style="width:200px;" />
                        <input type="submit" name="addnetofferusername" value="Add User Name" />
                        </form>
                        <?php
                        }
                    else
                        {
                        $netofferUserName = $_POST['netofferusername'];
                        update_option('netoffer_user_name', $netofferUserName);
                        ?>
                        <h3>You have successfully entered your NetOffer User Name!</h3>
                        <?php
                        }
                    }             
                }
            if($_GET['page'] == 'edit_activation_key')
                {
                $netOfferActivationKey = get_option('no_tools_key');
                if($netOfferActivationKey)
                    {
                    if(!isset($_POST['addnetofferactivationkey']))
                        {
                        ?>
                        <h3>Your current activation key is: <?php echo $netOfferActivationKey; ?></h3>                        
                        <p><span style="font-weight:bold;">Note:</span> Key is case sensitive.</p>
                        <form action="" method="post">
                        <span style="font-weight:bold;">NEW</span> NetOffer Activation Key: <input type="text" name="netofferactivationkey" style="width:200px;" />
                        <input type="submit" name="addnetofferactivationkey" value="Update Activation Key" />
                        </form>
                        <?php
                        }
                    else
                        {
                        $netofferActivationKey = $_POST['netofferactivationkey'];                        
                        $netofferActivationKeys = explode('-', $netofferActivationKey);
                        
                        if(empty($netofferActivationKey))
                            {
                            ?>
                            <div class="netoffer_wrapper">
                            <h2>The Key Field Cannot Be Empty.</h2> 
                            <h3>Your current activation key is: <?php echo $netOfferActivationKey; ?></h3>                        
                            <p><span style="font-weight:bold;">Note:</span> Key is case sensitive.</p>
                            <form action="" method="post">
                            <span style="font-weight:bold;">NEW</span> NetOffer Activation Key: <input type="text" name="netofferactivationkey" style="width:200px;" />
                            <input type="submit" name="addnetofferactivationkey" value="Update Activation Key" />
                            </form>
                            </div>
                            <?php
                            }
                        elseif(strlen($netofferActivationKeys[0]) != 24 || count($netofferActivationKeys) != 3)
                            {
                            ?>
                            <div class="netoffer_wrapper">
                            <h2>The Key your entered is invalid please copy and paste it from your NetOffer account.</h2> 
                            <h3>Your current activation key is: <?php echo $netOfferActivationKey; ?></h3>                        
                            <p><span style="font-weight:bold;">Note:</span> Key is case sensitive.</p>
                            <form action="" method="post">
                            <span style="font-weight:bold;">NEW</span> NetOffer Activation Key: <input type="text" name="netofferactivationkey" style="width:200px;" />
                            <input type="submit" name="addnetofferactivationkey" value="Update Activation Key" />
                            </form>
                            </div>
                            <?php
                            }
                        else
                            {
                            update_option('no_tools_key', $netofferActivationKey);
                            ?>
                            <h3>You have successfully updated your NetOffer Activation Key!</h3>
                            <?php
                            }                        
                        }
                    }
                else
                    {
                    echo "There has been an error. Please contact NetOffer Support.";
                    }
                }
            }
            
        function no_add_css()
            {                
            wp_register_style('no_default', NETOFFER_NEWSLETTER_BASE_URL.'css/no_default.css');
            wp_enqueue_style('no_default');
            }
        }
    }
$tools = new netOfferTools();
?>
