  <div id="wrap">
    <h1>Login as Another User</h1>
    <?php
    if($_GET['err'] == 1){
      echo '<div class="notice error is-dismissible"><p>User not found. Please check email address and try again</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
    }
    
    if($_GET['err'] == 2){
      echo '<div class="notice error is-dismissible"><p>You can not login as this user.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
    }
    ?>
    <p>To login as another user, please provide his/her email address.</p>
    <form method= "post">
    <input type="hidden" name="do" value="fake_login">
    <p><b>Email:</b> <input type="email" name="email" required><br><small>(You will be logged out from your account. You need to come back later)</small></p>
    <p><input type="submit" name="publish" id="publish" class="button button-primary button-large" value="Make log in"></p>
    </form>
  </div>