const debug = false;

const $ = global.$;
const rcmail = global.rcmail;

const plugin_button_selector = 'a.show-folder-size';

// button onclick function
global.pluginShowFolderSize = () => {
  let $btn = $(plugin_button_selector);

  if ($btn.hasClass('disabled')) {
    return;
  }

  $btn.addClass('disabled');

  rcmail.http_post('plugin.folder-size', {_folders: '__ALL__', _humanize: 1}, true);
};

let callback_show_folder_size = (resp) => {
  if (debug) {
    console.log('callback_show_folder_size', resp);
  }

  $.each(resp, (mailbox, size) => {
    $(`#mailboxlist a[rel="${mailbox}"]`).attr('data-folder-size', `(${size})`);
  });

  $(plugin_button_selector).removeClass('disabled');
};

rcmail.addEventListener('plugin.callback_folder_size', callback_show_folder_size);
