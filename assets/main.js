const $ = global.$;
const rcmail = global.rcmail;

const config = rcmail.env['show_folder_size.config'] || {};
const prefs = rcmail.env['show_folder_size.prefs'] || {};

const generatePopupContent = (resp) => {
  let html = `
    <table id="show-folder-size-table" class="records-table">
      <thead>
        <th>${rcmail.gettext('name', 'show_folder_size')}</th>
        <th>${rcmail.gettext('size', 'show_folder_size')}</th>
      </thead>
      <tbody>
  `;

  for (let [name, size] of Object.entries(resp)) {
    name = name.replace(/\//g, '<div class="path-separator"></div>');

    html += `
      <tr>
        <td>${name}</td>
        <td>${size}</td>
      </tr>
    `;
  }

  html += '</tbody></table>';

  return html;
};

rcmail.addEventListener('init', (evt) => {
  // register the main command
  rcmail.register_command(
    'plugin.show_folder_size.show-data',
    () => {
      rcmail.http_post(
        'plugin.show_folder_size.get-folder-size',
        {
          _callback: 'plugin.show_folder_size.show-data-callback',
          _folders: '__ALL__',
          _humanize: 1,
        },
        rcmail.set_busy(true, 'loading')
      );
    },
    true
  );

  /**
   * The callback function when server-side's API responses.
   *
   * @param {Object.<string, string|Number>} resp the response, { mailbox: size }
   */
  rcmail.addEventListener('plugin.show_folder_size.show-data-callback', (resp) => {
    delete resp.event; // unused entry

    rcmail.show_popup_dialog(
      generatePopupContent(resp),
      rcmail.gettext('folder_size', 'show_folder_size')
    );
  });
});
