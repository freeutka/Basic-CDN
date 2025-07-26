<?php
session_start();
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

function human_filesize($bytes, $decimals = 2) {
    $size = array('B','KB','MB','GB','TB','PB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . $size[$factor];
}

function relative_time($datetime) {
    $timestamp = is_numeric($datetime) ? $datetime : strtotime($datetime);
    $diff = time() - $timestamp;
    if ($diff < 60) return $diff . ' seconds ago';
    if ($diff < 3600) return floor($diff/60) . ' minutes ago';
    if ($diff < 86400) return floor($diff/3600) . ' hours ago';
    if ($diff < 2592000) return floor($diff/86400) . ' days ago';
    return date('Y-m-d', $timestamp);
}

$tab = $_GET['tab'] ?? 'files';
$files = glob(__DIR__ . '/../uploads/*.*');
$total_size = 0;
$file_list = [];

foreach ($files as $file) {
    $basename = basename($file);
    $size = filesize($file);
    $total_size += $size;
    $file_list[] = [
        'name' => $basename,
        'size' => human_filesize($size),
        'mtime' => filemtime($file),
        'mtime_str' => date('Y-m-d H:i:s', filemtime($file)),
        'mtime_relative' => relative_time(filemtime($file)),
        'url' => $cdn_base_url . $basename,
    ];
}

$trash_files = glob(__DIR__ . '/../uploads/.trash/*.*');
$trash_list = [];
foreach ($trash_files as $file) {
    $basename = basename($file);
    $size = filesize($file);
    $trash_list[] = [
        'name' => $basename,
        'size' => human_filesize($size),
        'mtime' => filemtime($file),
        'mtime_str' => date('Y-m-d H:i:s', filemtime($file)),
        'mtime_relative' => relative_time(filemtime($file)),
    ];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>DuckCDN</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
document.addEventListener('DOMContentLoaded', function () {
  const links = document.querySelectorAll('.filename-hover');
  const img = document.createElement('img');
  img.className = 'preview-tooltip';
  document.body.appendChild(img);

  links.forEach(link => {
    link.addEventListener('mouseover', (e) => {
      const url = e.target.dataset.full;
      img.src = url;
      img.style.display = 'block';
    });

    link.addEventListener('mousemove', (e) => {
      img.style.top = (e.clientY + 15) + 'px';
      img.style.left = (e.clientX + 15) + 'px';
    });

    link.addEventListener('mouseout', () => {
      img.style.display = 'none';
    });
  });

  // Confirm delete and logout
  document.querySelectorAll('.delete-link').forEach(function(el) {
    el.addEventListener('click', function(e) {
      if (!confirm('Are you sure you want to delete this file?')) e.preventDefault();
    });
  });
  var logoutBtn = document.getElementById('logoutBtn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', function(e) {
      if (!confirm('Are you sure you want to sign out?')) e.preventDefault();
    });
  }
});
    </script>
</head>
<body>
<div class="container">
    <h2>DuckCDN Panel</h2>
    <p style="text-align:center; margin-bottom: 18px;">
      <strong>Files:</strong> <?= count($file_list) ?> |
      <strong>Total size:</strong> <?= human_filesize($total_size) ?>
    </p>
    <form id="uploadForm" method="post" action="upload.php" enctype="multipart/form-data" style="margin-bottom: 0;">
        <div class="file-upload-container" id="fileUploadContainer">
            <input type="file" name="file" id="fileElem" required style="display:none;">
            <div class="file-upload-row">
                <button type="button" id="uploadBtn">Select file</button>
                <button type="submit" class="button" id="submitBtn">Upload</button>
            </div>
        </div>
    </form>
    <div class="tabs">
      <a href="dashboard.php"<?= $tab==='files'?' class="active"':'' ?>>Files</a>
      <a href="dashboard.php?tab=trash"<?= $tab==='trash'?' class="active"':'' ?>>Trash</a>
    </div>
    <?php if ($tab === 'trash'): ?>
      <div class="table-responsive">
        <table>
          <thead>
            <tr><th>File</th><th>Size</th><th>Date</th><th>Actions</th></tr>
          </thead>
          <tbody>
          <?php foreach ($trash_list as $f): ?>
            <tr>
              <td><?= htmlspecialchars($f['name']) ?></td>
              <td><?= $f['size'] ?></td>
              <td title="<?= $f['mtime_str'] ?>"><?= $f['mtime_relative'] ?></td>
              <td>
                <div class="trash-actions">
                  <a class="button" href="restore.php?f=<?= urlencode($f['name']) ?>">Restore</a>
                  <a class="button" href="trash_delete.php?f=<?= urlencode($f['name']) ?>" onclick="return confirm('Delete permanently?')">Delete</a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table>
            <thead>
            <tr><th>File</th><th>Size</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($file_list as $f): ?>
                <tr>
                    <td>
                      <span title="<?= htmlspecialchars($f['name']) ?>"><?= $f['icon'] ?></span>
                      <a class="filename-hover" data-full="<?= $f['url'] ?>" href="<?= $f['url'] ?>" target="_blank">
                        <?= htmlspecialchars($f['name']) ?>
                      </a>
                    </td>
                    <td><?= $f['size'] ?></td>
                    <td title="<?= $f['mtime_str'] ?>"><?= $f['mtime_relative'] ?></td>
                    <td>
                        <a class="button delete-link" href="delete.php?f=<?= urlencode($f['name']) ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
      </div>
    <?php endif; ?>
    <a href="logout.php" class="button" id="logoutBtn" style="margin-top:22px;">Sign out</a>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const fileElem = document.getElementById('fileElem');
  const uploadBtn = document.getElementById('uploadBtn');
  const fileUploadContainer = document.getElementById('fileUploadContainer');
  const uploadForm = document.getElementById('uploadForm');

  uploadBtn.addEventListener('click', () => fileElem.click());

  fileElem.addEventListener('change', function() {
    if (fileElem.files.length > 0) {
      uploadBtn.textContent = fileElem.files[0].name;
    } else {
      uploadBtn.textContent = 'Select file';
    }
  });

  // Drag & Drop upload for the whole window
  function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
  }
  ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    window.addEventListener(eventName, preventDefaults, false);
  });
  ['dragenter', 'dragover'].forEach(eventName => {
    window.addEventListener(eventName, () => fileUploadContainer.classList.add('highlight'), false);
  });
  ['dragleave', 'drop'].forEach(eventName => {
    window.addEventListener(eventName, () => fileUploadContainer.classList.remove('highlight'), false);
  });
  window.addEventListener('drop', function(e) {
    if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
      fileElem.files = e.dataTransfer.files;
      uploadBtn.textContent = fileElem.files[0].name;
    }
  });
});
</script>
</body>
</html>