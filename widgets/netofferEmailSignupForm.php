<?php
class netofferEmailSignupClass extends WP_Widget {
    public function __construct()
        {
        $widgetOps = array('description'=>__('Netoffer Remote Newsletter Signup Form', 'newsletter_domain_remote'), );
        $controlsOps = array( 'width' => 250, 'height' => 350 );
        parent::__construct(
                'netoffer_remote_signup_form',//Base ID
                'NetOffer Remote Signup Form',//Name
                $widgetOps,
                $controlsOps
                );
        }
        
    public function widget( $args, $instance )
        {
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );
        $firstName = $instance['first_name'];
        $lastName = $instance['last_name'];
        $phone = $instance['phone'];
        echo $before_widget;
        if ( ! empty( $title ) )
            {
			echo $before_title . $title . $after_title;
            if(!isset($_GET['no_nl_success']))
                {
                $netOfferOption = get_option('no_tools_key');                
                ?>
                <div id="newsletter-form-wrapper">
                    <form action='http://netoffer.com/wp-admin/netoffer-tools-plugin-processing.php' method='post'>
                    <div><span style="padding-right:30px;">Email:</span>   <input class="nl-required" name="nl-email" /></div>
                    <?php                    
                    if($firstName == true)
                        {
                        ?>
                        <div>First Name: <input class="nl-required" name="nl-first_name" /></div>
                        <?php
                        }             
                    if($lastName == true)
                        {
                        ?>
                        <div>Last Name: <input class="nl-required" name="nl-last_name" /></div>
                        <?php
                        }                     
                    if($phone == true)
                        {
                        ?>
                        <div><span style="padding-right:25px;">Phone:</span> <input class="nl-required" name="nl-phone"/></div>
                        <?php
                        } 
                    ?>
                    <input type="text" value="" name="nv" style="display:none;" />
                    <input type="hidden" value="<?php echo "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI']; ?>" name="returnUrl" />                    
                    <input type="hidden" value="<?php echo $netOfferOption; ?>" name="key" />
                    <input type="hidden" value="<?php echo get_bloginfo('name'); ?>" name="blog_name" />
                    <input type="submit" value="Sign Up!" name="PI_signup_for_newsletter" />
                    </form>
                </div>
                <?php
                }
            else if($_GET['no_nl_success']==1)
                {
                echo "Thank you for your interst. You will be sent an email to confirm your registration. Follow the link on the email to cofirm within 24 hours.";
                }
            else if($_GET['no_nl_success']==2)
                {
                echo "Thank you!. Your registration is complete.";
                }
            else if($_GET['no_nl_success']==3)
                {
                echo "Sorry!. Your registration is not complete. You have waited beyond the 24 hour period and your registration details have been deleted. If you want to register you will need to fill in the form again and confirm within 24 hours.";
                }
            else if($_GET['no_nl_success']==4)
                {
                echo "Thank you!. Your registration has already been completed at an earlier date.";
                }
            else if($_GET['no_nl_success']==5)
                {
                echo "We're sorry. Your email address does not seem to be valid.";
                }
            else if($_GET['no_nl_success']==6)
                {
                echo "We're sorry. The Email Address Field cannot be empty.";
                }
            else if($_GET['no_nl_success']==7)
                {
                echo "Sorry there has been an error. Please inform the site administrator.";
                }
            //print_r($blogDetails->path);
            }
        //widget content end.
        echo $after_widget;
        }
        
    /*
     * form() is used to generate form fields editable in the admin area that is displayed on the widget.
     */      
    public function form($instance)
        {        
        if ( isset( $instance[ 'title' ] ) ) 
            {
			$title = $instance[ 'title' ];
            }
		else 
            {
			$title = __( 'Sign up for our newsletter', 'newsletter_domain_remote' );
            }        
       $email = $instance['email'];
       $firstName = $instance['first_name'];
       $lastName = $instance['last_name'];
       $phone = $instance['phone'];
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
        <p>        
        <label for="<?php echo $this->get_field_id("first_name");  ?>"><?php _e( 'Require First Name:' ); ?></label>
        <input type="checkbox" value="1" name="<?php echo  $this->get_field_name("first_name"); ?>" id="<?php  echo $this->get_field_id("first_name") ?>" <?php checked( 1, $firstName ); ?> />
        </p>
        <p>        
        <label for="<?php echo $this->get_field_id("last_name");  ?>"><?php _e( 'Require Last Name:' ); ?></label>
        <input type="checkbox" value="1" name="<?php echo  $this->get_field_name("last_name"); ?>" id="<?php  echo $this->get_field_id("last_name") ?>" <?php checked( 1, $lastName ); ?> />
        </p>
        <p>        
        <label for="<?php echo $this->get_field_id("phone");  ?>"><?php _e( 'Require Phone:' ); ?></label>
        <input type="checkbox" value="1" name="<?php echo  $this->get_field_name("phone"); ?>" id="<?php  echo $this->get_field_id("phone") ?>" <?php checked( 1, $phone ); ?> />
        </p>
		<?php 
        }
        
    /*
     * update() will update the title of the widget when saved in the widget options on the admin.
     */    
    public function update($new_instance, $old_instance)
        {
        $instance = $old_instance;
        
		$instance['title'] = strip_tags( $new_instance['title'] );
        $instance['first_name'] = strip_tags( $new_instance['first_name'] );
        $instance['last_name'] = strip_tags( $new_instance['last_name'] );
        $instance['phone'] = strip_tags( $new_instance['phone'] );        
		return $instance;   
        }
}

add_action( 'widgets_init', 'load_newsletter_remote_widget' );
function load_newsletter_remote_widget()
    {
    register_widget( 'netofferEmailSignupClass' );
    }
?>