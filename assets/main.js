const debug = false;

const $ = global.$;
const rcmail = global.rcmail;

const config = rcmail.env['show_folder_size.config'] || {};
const prefs = rcmail.env['show_folder_size.prefs'] || {};

/**
 * The jQuery selector for "Show folder size" buttons.
 *
 * @type {string}
 */
const plugin_button_selector = 'a.show-folder-size';

const set_mailbox_size_text = (mailbox, text) => {
  const attr_selector = mailbox ? `[rel="${mailbox}"]` : '[rel]';

  $(`#mailboxlist a${attr_selector}`).attr('data-folder-size', text);
};

const update_size_text = () => {
  const $btn = $(plugin_button_selector);

  if ($btn.hasClass('disabled')) {
    return;
  }

  $btn.addClass('disabled');

  // clear all size text
  set_mailbox_size_text(null, '');

  rcmail.http_post('plugin.show_folder_size.get-folder-size', {
    _callback: 'plugin.show_folder_size.update-data-callback',
    _folders: '__ALL__',
    _humanize: 1,
  });
};

rcmail.addEventListener('init', (evt) => {
  // register the main command
  rcmail.register_command('plugin.show_folder_size.update-data', update_size_text, true);

  /**
   * The callback function when server-side's API responses.
   *
   * @param {Object.<string, string|Number>} resp the response, { mailbox: size }
   */
  rcmail.addEventListener('plugin.show_folder_size.update-data-callback', (resp) => {
    if (debug) {
      console.log('callback_show_folder_size', resp);
    }

    $.each(resp, (mailbox, size) => {
      set_mailbox_size_text(mailbox, `(${size})`);
    });

    $(plugin_button_selector).removeClass('disabled');
  });
});

$(() => {
  // auto show folder size?
  if (config['auto_show_folder_size'] && $('#mailboxlist').length) {
    update_size_text();
  }
});
