<div class="wrap">

    <form method="POST" action="options.php">
        <?php settings_fields(AW_SLUG); ?>
        <?php do_settings_sections(AW_SLUG); ?>
        <?php submit_button(); ?>
    </form>

</div>
