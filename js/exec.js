const $ = global.$;
const plugin_show_folder_size = global.plugin_show_folder_size;

$(() => {
  // fire API request only when the UI has the mailbox list
  if (!$('#mailboxlist').length) {
    return;
  }

  plugin_show_folder_size();
});
