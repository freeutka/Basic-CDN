# 📷 Basic CDN

A simple and minimalistic image CDN built with PHP.

---

## 🚀 Features

- Lightweight and fast
- Self-hosted
- Simple upload API with `authKey`
- Automatic screenshot upload support (Linux)

---

## 📦 Installation (Linux Server)

### 🔧 Server Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/TheGamer3514/Basic-CDN.git
   cd Basic-CDN

2. Edit `index.php`:

   * Change the `authKey` to your own secure value (used to verify uploads)
   * Set the correct `file_url_base` to your domain (e.g., `https://your.domain/uploads/`)

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

5. (Optional) Proceed to configure automatic screenshot uploading on Linux.

---

### 🖥️ Client Setup (Linux Screenshot Uploader)

#### 1. Install dependencies:

```bash
sudo dnf install flameshot xclip curl
```

#### 2. Create script `~/upload_screenshot.sh`:

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

#### 3. Make it executable:

```bash
chmod +x ~/upload_screenshot.sh
```

#### 4. Bind to a keyboard shortcut (GNOME example):

* Open **Settings → Keyboard → Custom Shortcuts**
* Add a new shortcut:

  * **Name**: CDN Screenshot
  * **Command**: `/home/your_username/upload_screenshot.sh`
  * **Shortcut**: e.g., Shift + Print

---

## 🤝 Contributing

Want to improve or fix something?
Make a pull request and we'll review it!

---

## 📚 Resources

* [💬 Discord Server](https://discord.gg/sillydev)
* [🌐 Free Hosting](https://sillydev.co.uk)
* 📧 Contact: `gamer@sillydev.co.uk`

---
