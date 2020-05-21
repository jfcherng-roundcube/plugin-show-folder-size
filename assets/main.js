const $ = global.$;
const rcmail = global.rcmail;

const plugin_name = 'show_folder_size';
const config = rcmail.env[`${plugin_name}.config`] || {};
const prefs = rcmail.env[`${plugin_name}.prefs`] || {};

const generatePopupContent = (resp) => {
  let html = `
    <table id="show-folder-size-table" class="records-table">
      <thead>
        <tr>
          <th>${rcmail.gettext('name', plugin_name)}</th>
          <th>${rcmail.gettext('size', plugin_name)}</th>
        </tr>
      </thead>
      <tbody>
  `;

  for (let [id, [size, size_humanized]] of Object.entries(resp)) {
    let mailbox = rcmail.env.mailboxes[id];
    let level = (id.match(/\//g) || []).length;

    html += `
      <tr>
        <td
          class="name ${mailbox.virtual ? 'virtual' : ''}"
          onclick="return ${
            mailbox.virtual ? 0 : 1
          } ? rcmail.command('list', '${id}', this, event) : ''"
          title="${id}"
        >
          <div style="margin-left: ${level * 1.5}em">${mailbox.name}</div>
        </td>
        <td data-size="${size}">${size_humanized}</td>
      </tr>
    `;
  }

  html += '</tbody></table>';

  return html;
};

rcmail.addEventListener('init', (evt) => {
  // register the main command
  rcmail.register_command(
    `plugin.${plugin_name}.show-data`,
    () => {
      rcmail.http_post(
        `plugin.${plugin_name}.get-folder-size`,
        {
          _callback: `plugin.${plugin_name}.show-data-callback`,
          _folders: rcmail.env.mailboxes_list,
        },
        rcmail.set_busy(true, 'loading')
      );
    },
    true
  );

  // the callback function when server-side's API responses.
  rcmail.addEventListener(`plugin.${plugin_name}.show-data-callback`, (resp) => {
    delete resp.event; // unused entry

    rcmail.show_popup_dialog(
      generatePopupContent(resp),
      rcmail.gettext('folder_size', plugin_name),
      {},
      { modal: false }
    );
  });
});
