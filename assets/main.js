const $ = global.$;
const rcmail = global.rcmail;

const config = rcmail.env['show_folder_size.config'] || {};
const prefs = rcmail.env['show_folder_size.prefs'] || {};

const generatePopupContent = (resp) => {
  let html = `
    <table id="show-folder-size-table" class="records-table">
      <thead>
        <tr>
          <th>${rcmail.gettext('name', 'show_folder_size')}</th>
          <th>${rcmail.gettext('size', 'show_folder_size')}</th>
        </tr>
      </thead>
      <tbody>
  `;

  for (let [id, [size, sizeHumanized]] of Object.entries(resp)) {
    let mailbox = rcmail.env.mailboxes[id];
    let level = (id.match(/\//g) || []).length;

    html += `
      <tr>
        <td title="${id}">
          <div
            class="name ${mailbox.virtual ? 'virtual' : ''}"
            style="margin-left: ${level * 1.5}em"
          >${mailbox.name}</div>
        </td>
        <td data-size="${size}">${sizeHumanized}</td>
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
          _folders: rcmail.env.mailboxes_list,
        },
        rcmail.set_busy(true, 'loading')
      );
    },
    true
  );

  // the callback function when server-side's API responses.
  rcmail.addEventListener('plugin.show_folder_size.show-data-callback', (resp) => {
    delete resp.event; // unused entry

    rcmail.show_popup_dialog(
      generatePopupContent(resp),
      rcmail.gettext('folder_size', 'show_folder_size')
    );
  });
});
