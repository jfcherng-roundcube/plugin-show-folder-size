const debug = false;

const rcmail = global.rcmail;
const plugin_button_selector = '.button.show-folder-size';

// button onclick function
global.pluginShowFolderSize = () => {
  let $btn = $(plugin_button_selector);

  if ($btn.hasClass('disabled')) {
    return;
  }

  $btn.addClass('disabled');

  rcmail.http_post('plugin.all-folder-size', {_humanize: 1}, true);
};

let hash_string_to_int = (str) => str.split('').reduce(
  (sum, char) => ((sum << 5) - sum) + char.charCodeAt(),
  0
);

let html_show_folder_size = (mailbox, size) => {
  const size_decorated = `(${size})`;
  const hash_id = 'folder-size-' + Math.abs(hash_string_to_int(mailbox));

  let $mailbox_a = $(`#mailboxlist a[rel="${mailbox}"]`);
  let $size_span = $(`#${hash_id}`, $mailbox_a);

  // no previous size has been appended yet, let's create a new one
  if ($size_span.length === 0) {
    $mailbox_a.append(` <span id="${hash_id}">${size_decorated}</span>`);
  }
  // update previous size
  else {
    $size_span.html(size_decorated);
  }
};

let callback_show_folder_size = (resp) => {
  if (debug) {
    console.log(resp);
  }

  $.each(resp, (mailbox, size) => {
    html_show_folder_size(mailbox, size);
  });

  $(plugin_button_selector).removeClass('disabled');
};

rcmail.addEventListener('plugin.callback_all_folder_size', callback_show_folder_size);
