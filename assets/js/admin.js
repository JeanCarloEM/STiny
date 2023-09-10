jQuery(document).ready(function ($) {
  $('.jcem_wpAdmin .input.image button.upload').click(function (e) {
    e.preventDefault();
    var custom_uploader = wp.media({
      title: 'Custom Image',
      button: {
        text: 'Upload/Open Image'
      },
      multiple: false  /* Set this to true to allow multiple files to be selected */
    }).on('select', function () {
      var attachment = custom_uploader.state().get('selection').first().toJSON();
      console.log($(e));
      $($($(e.target).parent()).children('input')[0]).val(attachment.url);
    }).open();
  });
});