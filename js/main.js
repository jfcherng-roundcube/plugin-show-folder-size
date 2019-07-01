const debug = false;

const $ = global.$;
const rcmail = global.rcmail;

/**
 * The jQuery selector for "Show folder size" buttons.
 *
 * @type {string}
 */
const plugin_button_selector = 'a.show-folder-size';

/**
 * Button onclick function.
 *
 * @global
 *
 * @return {void}
 */
const plugin_show_folder_size = () => {
  const $btn = $(plugin_button_selector);

  if ($btn.hasClass('disabled')) {
    return;
  }

  $btn.addClass('disabled');

  get_mailbox_a().attr('data-folder-size', '');
  rcmail.http_post('plugin.folder-size', {_folders: '__ALL__', _humanize: 1}, true);
};

/**
 * The callback function when RC's API responses.
 *
 * @param  {Object.<string, string|Number>} resp the response, { mailbox: size }
 * @return {void}
 */
const callback_show_folder_size = (resp) => {
  if (debug) {
    console.log('callback_show_folder_size', resp);
  }

  $.each(resp, (mailbox, size) => {
    get_mailbox_a(mailbox).attr('data-folder-size', `(${size})`);
  });

  $(plugin_button_selector).removeClass('disabled');
};

/**
 * Get the jQuery DOM of a mailbox.
 *
 * @param  {string} mailbox the mailbox
 * @return {JQuery} the jQuery DOM
 */
const get_mailbox_a = (mailbox) => {
  const attr_selector = typeof mailbox !== 'undefined' ? `[rel="${mailbox}"]` : '[rel]';

  return $(`#mailboxlist a${attr_selector}`);
};

rcmail.addEventListener('plugin.callback_folder_size', callback_show_folder_size);

// expose
global.plugin_show_folder_size = plugin_show_folder_size;

/*
// the way to call the plugin API manually
$.ajax({
  type: 'POST',
  dataType: 'json',
  url: '?_action=plugin.folder-size',
  data: {
    _remote: 1, _unlock: 1, // Roundcube's must
    _folders: '__ALL__',
    _humanize: 1,
  },
  success: (data) => {
    const resp = data.callbacks[0][1];
    console.log(resp);

    callback_show_folder_size(resp);
  },
});
*/
