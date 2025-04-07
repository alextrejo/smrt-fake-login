<?php
/*
Plugin Name: Fake Login
Description: Admin users can log in as any other user
Version: 1.0
Author: Alexander Trejo
*/

class smrtFakeLogin{
  function __construct(){
    if(!session_id()) session_start();

    //Perform "Fake login"
    add_action( 'plugins_loaded', array($this, 'do_login') );

    //Add "Fake login" link to Dashboard top bar
    add_action('admin_bar_menu', array($this, 'add_toolbar_item'), 100);

    //Add admin page
    add_action( 'admin_menu', array($this, 'fake_login_menu') );

    //Clear variable on logout
    add_action( 'wp_logout', array($this, 'on_logout') );

    //Adds top bar when user is "Fake loggedin"
    add_action( 'wp_footer', array($this, 'footer') );

    //User logs out from "Fake login". Log him in back to his account
    add_action( 'template_redirect', array($this,'do_logout') );
  }

  function add_toolbar_item($admin_bar){
    $admin_bar->add_menu( array(
        'id'    => 'fake-login',
        'title' => '<span class="ab-icon dashicons-before dashicons-admin-network"></span><span class="ab-label">Log as another user</span>',
        'href'  => admin_url('/admin.php?page=fake-login'),
        'meta'  => array(
            'title' => 'Log as another user',
        ),
    ));
  }

  function fake_login_menu(){
    add_submenu_page( 'admin.php', 'Login as Another User', null, 'level_1', 'fake-login', array($this, 'fake_login_page') );
  }

  function fake_login_page(){
    include('inc/login.php');
  }

  function do_login(){
    if(!isset($_POST['do']) || $_POST['do'] != 'fake_login' ) return;

    $current = _wp_get_current_user();

    $user = get_user_by('email', $_POST['email']);
    if($user){
      if(in_array('subscriber', $user->roles) && !in_array('administrator', $user->roles) && !in_array('contributor', $user->roles) ){
        wp_logout();
        $_SESSION['fake_login'] = $current->ID;
        wp_set_auth_cookie($user->ID);
        wp_redirect( home_url('/') );
        exit();
      }else{
        wp_redirect( admin_url('/admin.php?page=fake-login&err=2') );
      }
    }else{
      wp_redirect( admin_url('/admin.php?page=fake-login&err=1') );
    }
  }

  function on_logout(){
    //Log in out "Fake login". Log user again
    if(isset($_SESSION['fake_login']) ){
      unset($_SESSION['fake_login']);
    }
  }

  function do_logout(){
    if(!isset($_GET['fakelogout']) || !isset($_SESSION['fake_login']) ) return;

    $uid = (int)$_SESSION['fake_login'];
    wp_logout();
    wp_set_auth_cookie( $uid  );
    unset($_SESSION['fake_login']);
    wp_redirect( admin_url() );
    exit();
  }

  function footer(){
    if(!isset($_SESSION['fake_login'])) return;

  ?>
  <link rel="stylesheet" href="<?php echo home_url('/wp-includes/css/admin-bar.min.css')?>">
  <script type="text/javascript">
  (function($){
    $bar = $('<div id="wpadminbar" class="nojq nojs" style="background: #ff0000; color: #fff; text-align: center;">You are logged in as another user. <b><a href="<?php echo home_url('/?fakelogout=1');?>">CLICK HERE</a></b> to return to your account</div>');
    $('body').append( $bar );
  })(jQuery);
  </script>
  <?php
  }
}

$smrtFakeLogin = new smrtFakeLogin();
