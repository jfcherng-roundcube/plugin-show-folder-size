function pluginShowFolderSize() {
  var rcmail = window.rcmail;

  $(function() {
    var mailboxes = rcmail.env.mailboxes_list;

    for (var idx in mailboxes) {
      (function(mailbox) {
        get_folder_size(mailbox, true, function(data) {
          data = JSON.parse(data);

          html_show_size(mailbox, extract_size_from_api_response(data.exec));
        });
      })(mailboxes[idx]);
    }
  });

  function html_show_size(mailbox, size) {
    var $mailbox_a = $('#mailboxlist a[rel="' + mailbox + '"]');
    var $size_span = $('.folder_size', $mailbox_a);

    var size_decorated = ' (' + size + ')';

    if ($size_span.length === 0) {
      $mailbox_a.append('<span class="folder_size">' + size_decorated + '</span>');
    } else {
      $size_span.html(size_decorated);
    }
  }

  function get_folder_size(mailbox, async, callback) {
    async = typeof async === 'undefined' ? false : Boolean(async);
    callback = typeof callback === 'undefined' ? function() {} : callback;

    $.ajax({
      type: 'post',
      url: rcmail.get_task_url('settings') + '&_framed=1&_action=folder-size',
      data: {
        _mbox: mailbox,
        _remote: '1',
        _unlock: 'loading' + Math.floor(Math.random() * 1e8)
      },
      async: async,
      success: callback
    });
  }

  function extract_size_from_api_response(string) {
    var folder_size = /(['"])([^'"]+)\1/.exec(string);

    return folder_size ? folder_size[2] : '';
  }
}
