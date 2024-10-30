<?php /**
 * quick notes: to generate a new row with quick syntax tr.top>td[scope=row]{Preview}+td
 */
?>
<div class="wrap">
  <h1>Code View</h1>

  <form method="post" action="options.php">
	  <?php settings_fields( 'codeview_settings' ); ?>
    <table class="form-table">
      <tr class="top">
        <th scope="row"><?= _( 'Include Emblem' ); ?></th>
        <td>
          <label for="include_emblem">
            <input type="hidden" id="include_emblem-default" name="codeview_settings[include_emblem]" value="0"/>
            <input type="checkbox" id="include_emblem" name="codeview_settings[include_emblem]" <?= CodeView::getSetting( 'include_emblem' ) ? 'checked' : ''; ?> value="1"/>
          </label>
        </td>
      </tr>
      <tr class="top">
        <th scope="row"><?= _( 'Line Numbers' ); ?></th>
        <td>
          <label for="line_numbers">
            <input type="hidden" id="line-numbers-default" name="codeview_settings[line_numbers]" value="0"/>
            <input type="checkbox" id="line_numbers" name="codeview_settings[line_numbers]" <?= CodeView::getSetting( 'line_numbers' ) ? 'checked' : ''; ?> value="1"/>
          </label>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">Theme</th>

        <td>
          <select name="codeview_settings[cv_theme]" id="cv_theme">
			  <?php foreach ( CodeView::$themes as $theme_name ): ?>
                <option value="<?= $theme_name; ?>" <?= CodeView::getSetting( 'cv_theme' ) == $theme_name ? 'selected' : ''; ?> ><?= $theme_name; ?></option>
			  <?php endforeach; ?>
          </select>
        </td>
      </tr>
      <tr class="top">
        <th scope="row"><?= _( 'Preview' ); ?></th>
        <td>
          <link rel="stylesheet" href="<?= plugin_dir_url( CodeView::base_file() ) . 'assets/highlight/styles/' . CodeView::getSetting( 'cv_theme' ) . '.css'; ?>"
                data-base-path="<?= plugin_dir_url( CodeView::base_file() ) . 'assets/highlight/styles/'; ?>" id="preview-colors"/>
          <pre class="php"> <div class="code-curly"></div> <code>  // This is a preview of the selected theme.

  Class Sample{
    public function sample_output(){
      print "welcome";
    }
  }</code></pre>
        </td>
      </tr>

    </table>

	  <?php submit_button(); ?>

  </form>

  <h3>Sample Usage in your post:</h3>
  <pre>

I want to include some code in my blog post
[cv css]
.body{
  background: #000;
  color: #fff;
}
[/cv]
    </pre>

  <script>
      jQuery(function () {
          jQuery("#cv_theme").change(function () {
              var new_path = jQuery("#preview-colors").data('base-path') + jQuery(this).val() + ".css";
              jQuery("#preview-colors").prop('href', new_path);
          });
          jQuery("#include_emblem").change(function () {
              jQuery(".code-curly").toggle(jQuery(this).is(":checked"));
          }).change();
          jQuery("#line_numbers").change(function () {

              jQuery("pre").toggleClass('hide_line_numbers', !jQuery(this).is(":checked"));
          }).change();
      });
  </script>
</div>