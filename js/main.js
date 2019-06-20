global.pluginShowFolderSize = () => {
  let rcmail = global.rcmail;

  let hash_string_to_int = (str) => str.split('').reduce(
    (sum, char) => ((sum << 5) - sum) + char.charCodeAt(),
    0
  );

  let extract_size_from_api_response = (str) => {
    let size = /(['"])([^'"]+)\1/.exec(str);

    return size ? size[2] : '';
  };

  let html_show_size = (mailbox, size) => {
    let size_decorated = `(${size})`;

    let hash_id = 'folder-size-' + Math.abs(hash_string_to_int(mailbox));
    let $mailbox_a = $(`#mailboxlist a[rel="${mailbox}"]`);
    let $size_span = $(`#${hash_id}`, $mailbox_a);

    if ($size_span.length === 0) {
      $mailbox_a.append(` <span id="${hash_id}">${size_decorated}</span>`);
    } else {
      $size_span.html(size_decorated);
    }
  };

  let get_folder_size = (mailbox, async = false, callback = () => {}) => $.ajax({
    type: 'post',
    url: rcmail.get_task_url('settings') + '&_framed=1&_action=folder-size',
    data: {
      _mbox: mailbox,
      _remote: '1',
      _unlock: 'loading' + Math.floor(Math.random() * 1e8),
    },
    async: Boolean(async),
    success: callback,
  });

  // the real working horse
  $(() => {
    let mailboxes = rcmail.env.mailboxes_list;

    for (let mailbox of mailboxes) {
      get_folder_size(
        mailbox,
        true,
        (data) => html_show_size(
          mailbox,
          extract_size_from_api_response(JSON.parse(data).exec)
        )
      );
    }
  });
};
