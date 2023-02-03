<?php ob_start();

include_once(BOOKERVILLE_DIR.'functions.php');

$secret_key = get_option('_bookerville_secret_key');
$account_id = get_option('_bookerville_account_id');

$err = "";

if(isset($_POST['save_settings'])){
    // ob_start();

    $secret_key = isset($_POST['secret_key']) ? $_POST['secret_key'] : get_option('_bookerville_secret_key');
    update_option('_bookerville_secret_key', $secret_key);

    $account_id = isset($_POST['account_id']) ? $_POST['account_id'] : get_option('_bookerville_account_id');
    update_option('_bookerville_account_id', $account_id);

    // // fetch and save listings locally
    _bookerville_fetch_all_properties();

    // // fetch listing details after 1 minute
	wp_schedule_single_event( (time() + 60), 'initialize_listings' );

    wp_redirect(admin_url("edit.php?post_type=bookerville_listing&page=settings&status=settings_saved"));
    exit();         

    // ob_end_flush();
}
?>

<style>
    .bookerville-form-input {
        margin-bottom:20px;
    }
    .bookerville-form-input label,
    .bookerville-form-input input {
        display:block;
        max-width:500px;
        width:100%;
    }
    .bookerville-form-input input[type=submit]{
        max-width:200px;
    }
    .bookerville-refresh_listings {
        padding: 3px 25px !important;
        font-size: 19px!important;
    }
    .bookerville-settings-content-1 {
        padding-bottom:25px;
        border-bottom:1px solid #d0d0d0;
    }
    .bookerville-settings-content-2,
    .bookerville-settings-content-3{
        padding-top:25px;
        padding-bottom:25px;
        border-bottom:1px solid #d0d0d0;
    }
</style>
<div class="wrap">
    <h2>Guest Settings</h2>

    <?php if(isset($_GET['status']) && $_GET['status'] == "settings_saved"): ?>
        <div class="notice notice-success is-dismissible">
            <p>You have successfully connected to Bookerville API</p>
            <p>We are fetching the listing from Bookerville in the background. It might take a couple of minutes to fetch all the listings.</p>
        </div>

    <?php endif; ?>

    <?php echo !empty($err) ? $err : ""; ?>

    <div class="bookerville-settings-content bookerville-settings-content-1">
        <h3>Settings</h3>
        <form method="post">
            <div class="bookerville-form-input">
                <label>API Key</label>
                <input type="text" name="secret_key" value="<?php echo isset($_POST['secret_key'])?$_POST['secret_key']: $secret_key; ?>"/>
            </div>
            <div class="bookerville-form-input">
                <label>Account ID</label>
                <input type="text" name="account_id" value="<?php echo isset($_POST['account_id'])?$_POST['account_id']: $account_id; ?>"/>
            </div>
            <div class="bookerville-form-input">
                <input type="submit" name="save_settings" class="button-primary" value="Save Settings"/>    
            </div>
        </form>
    </div>

</div>