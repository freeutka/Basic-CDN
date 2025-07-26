# 📷 DuckCDN

A simple and minimalistic image CDN built with PHP.

---

## 🚀 Features

* Lightweight and fast
* Self-hosted
* Simple upload API with `authKey`
* Automatic screenshot upload support (Linux & Windows)
* Built-in control panel for file management

---

## 📦 Installation (Linux Server)

### 🔧 Server Setup

1. Clone the repository:

   ```bash
   git clone https://github.com/freeutka/DuckCDN.git
   cd DuckCDN
   ```

2. Edit `config.php`:

   * Replace all parameters with the ones you need

3. (Optional) Test your setup using `curl`:

   ```bash
   curl -X POST https://your.domain/ \
     -F "auth_key=changethis" \
     -F "file=@/replace/this.png"
   ```

4. If successful, the output will be:

   ```
   https://your.domain/uploads/random.png
   ```

5. Proceed to configure automatic screenshot uploading below.

---

## 🖥️ Client Setup (Linux Screenshot Uploader)

### 1. Install dependencies:

```bash
sudo dnf install flameshot xclip curl
```

### 2. Create script `~/upload_screenshot.sh`:

```bash
nano ~/upload_screenshot.sh
```

```bash
#!/bin/bash

tmpfile="/tmp/screen_$(date +%s).png"

# Select area and save screenshot
flameshot gui -r > "$tmpfile"

# Upload to your server
response=$(curl -s -X POST https://your.domain/ \
  -F "auth_key=changethis" \
  -F "file=@$tmpfile")

# Handle response
if [[ "$response" == http* ]]; then
  echo "$response" | xclip -selection clipboard
  notify-send "✅ Uploaded" "$response"
else
  notify-send "❌ Upload failed" "$response"
fi

rm -f "$tmpfile"
```

### 3. Make it executable:

```bash
chmod +x ~/upload_screenshot.sh
```

### 4. Bind to a keyboard shortcut (GNOME example):

* Open **Settings → Keyboard → Custom Shortcuts**
* Add a new shortcut:

  * **Name**: CDN Screenshot
  * **Command**: `/home/your_username/upload_screenshot.sh`
  * **Shortcut**: e.g., `Shift + Print`

---

## 🪟 Client Setup (Windows Screenshot Uploader)

### 1. Requirements

* [ShareX](https://getsharex.com/) — free screenshot tool with upload scripting
* Your CDN API endpoint
* Your `auth_key`

---

### 2. Configure ShareX to upload screenshots

1. Open ShareX
2. Go to `Destinations → Custom uploader settings`
3. Click `New` and set the following:

**Request:**

* **Name**: `DuckCDN`
* **Request type**: `POST`
* **Request URL**:

  ```
  http://your.domain/   ← with trailing slash
  ```
* **File form name**: `file`
* **Arguments** (Form data `multipart/form-data`):

  ```json
  {
    "auth_key": "changethis"
  }
  ```

**Response:**

* **URL**: `$response$`
* **Regex for response URL**: `https?://.*` *(optional)*

---

### 3. Set up automatic behavior

1. In ShareX, go to `Task Settings → After capture tasks`

2. Enable:

   * `Upload image to host`
   * `Copy URL to clipboard`

3. In `Destinations → Image uploader`, select `Custom uploaders → DuckCDN`

---

### 4. Test

1. Press `PrintScreen` or select area
2. Screenshot is uploaded to your server
3. CDN link is copied to clipboard

---

## 🤝 Contributing

Want to improve or fix something?
Make a pull request and we'll review it.

---
