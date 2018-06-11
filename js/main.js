$(function() {
  setTimeout(function() {
    var mailboxes = rcmail.env.mailboxes_list;

    for (var i in mailboxes) {
      (function(mailbox) {
        get_folder_size(mailbox, true, function(data) {
          data = JSON.parse(data);

          var folder_size_str = extract_folder_size_from_string(data.exec);

          $(function() {
            show_size(mailbox, ' (' + folder_size_str + ')');
          });
        });
      })(mailboxes[i]);
    }
  }, 500);
  
  function show_size(mailbox, size) {
    var $mailbox_li_a = $('#mailboxlist > li.mailbox.' + mailbox.toLowerCase() + ' > a');
    var $size_span = $('.folder_size', $mailbox_li_a);
    
    if ($size_span.length === 0) {
      $mailbox_li_a.append('<span class="folder_size">' + size + '</span>');
    } else {
      $size_span.html(size);
    }
  }

  function get_folder_size(mailbox, async, callback) {
    async = typeof async === 'undefined' ? false: !!async;
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
  
  function extract_folder_size_from_string (string) {
    var folder_size = /(-?[0-9.,]+)\s*((?:byte|[kmgtp]?b)s?)/i.exec(string);
    var folder_size_str = folder_size[1] + ' ' + folder_size[2];
    
    return folder_size_str;
  }
});

