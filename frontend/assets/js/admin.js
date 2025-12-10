// Super simple helpers for admin pages (no frameworks)
function adminSetStatus(message, type) {
  var el = document.getElementById('admin-status');
  if (!el) return;
  el.textContent = message || '';
  if (type === 'error') el.style.color = '#ff6b6b';
  else if (type === 'success') el.style.color = '#a0e7a0';
  else el.style.color = '#ddd';
}

function adminRenderTable(headers, rows) {
  var wrap = document.getElementById('admin-table');
  if (!wrap) return;
  var html = '<table><thead><tr>';
  for (var i = 0; i < headers.length; i++) html += '<th>' + headers[i] + '</th>';
  html += '</tr></thead><tbody>';
  for (var r = 0; r < rows.length; r++) {
    html += '<tr>';
    for (var c = 0; c < rows[r].length; c++) {
      var cell = rows[r][c];
      if (cell === null || cell === undefined) {
        cell = '';
      }
      html += '<td>' + cell + '</td>';
    }
    html += '</tr>';
  }
  html += '</tbody></table>';
  wrap.innerHTML = html;
}

// Example usage you can copy:
// adminSetStatus('Loading...');
// adminRenderTable(['ID','Name'], [[1,'Alice'],[2,'Bob']]);
