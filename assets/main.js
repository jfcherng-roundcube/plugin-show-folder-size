const $ = global.$;
const rcmail = global.rcmail;

const plugin_name = 'show_folder_size';
const config = rcmail.env[`${plugin_name}.config`] ?? {};
const prefs = rcmail.env[`${plugin_name}.prefs`] ?? {};

/**
 * Format bytes as human-readable text.
 *
 * @param bytes Number of bytes.
 * @param use_si True to use metric (SI) units, aka powers of 1000. False to use
 *           binary (IEC), aka powers of 1024.
 * @param dp Number of decimal places to display.
 *
 * @return Formatted string.
 */
const humanizeBytes = (bytes, base_1k = false, dp = 1) => {
  const thresh = base_1k ? 1000 : 1024;

  if (Math.abs(bytes) < thresh) {
    return bytes + ' B';
  }

  const units = base_1k
    ? ['KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
    : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
  let u = -1;
  const r = 10 ** dp;

  do {
    bytes /= thresh;
    ++u;
  } while (Math.round(Math.abs(bytes) * r) / r >= thresh && u < units.length - 1);

  return bytes.toFixed(dp) + ' ' + units[u];
};

const generatePopupContent = (resp) => {
  const entries = Object.entries(resp);
  entries.sort((a, b) => a[0].localeCompare(b[0])); // sort by mailbox name

  let html = `
    <table id="show-folder-size-table" class="records-table" style="table-layout: auto;">
      <thead>
        <tr>
          <th>${rcmail.gettext('name', plugin_name)}</th>
          <th>${rcmail.gettext('size', plugin_name)}</th>
          <th>${rcmail.gettext('cumulative_size', plugin_name)}</th>
        </tr>
      </thead>
      <tbody>
  `;

  let total_size = 0;

  for (let [id, [size, cumulative_size]] of entries) {
    let mailbox = rcmail.env.mailboxes[id];
    let level = (id.match(/\//g) ?? []).length;

    // skip unsubscribed mailboxes
    if (!mailbox) {
      continue;
    }

    total_size += size !== false ? size : 0;

    html += `
      <tr>
        <td
          class="name ${mailbox.virtual ? 'virtual' : ''}"
          onclick="return ${mailbox.virtual ? 0 : 1} ? rcmail.command('list', '${id}', this, event) : ''"
          title="${id}"
        >
          <div style="margin-left: ${level * 1.5}em">${mailbox.name}</div>
        </td>
        <td data-size="${size !== false ? size : -1}">
          ${size !== false ? humanizeBytes(size) : '-'}
        </td>
        <td data-size="${cumulative_size !== false ? cumulative_size : -1}">
          ${cumulative_size !== false ? humanizeBytes(cumulative_size) : '-'}
        </td>
      </tr>
    `;
  }

  html += `
      </tbody>
      <tfoot>
        <tr>
          <th>${rcmail.gettext('total', plugin_name)}</th>
          <th data-size="${total_size}">${humanizeBytes(total_size)}</th>
          <th data-size="-1">-</th>
        </tr>
      </tfoot>
    </table>
  `;

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
        },
        rcmail.set_busy(true, 'loading')
      );
    },
    true
  );

  // the callback function when server-side's API responses.
  rcmail.addEventListener(`plugin.${plugin_name}.show-data-callback`, (resp) => {
    delete resp.event; // unused entry

    rcmail.simple_dialog(generatePopupContent(resp), rcmail.gettext('folder_size', plugin_name), null, {
      modal: false,
    });
  });
});
