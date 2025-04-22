# 🚀 SiteScope 🎮

SiteScope is a lightweight, privacy-friendly website analytics dashboard you can fully control and host yourself — no third-party services, no hidden costs.

## 📊 Features

SiteScope helps you track:
- Total visitors (footfall)
- Pages visited
- Visitor source (referrer)
- Visitor location (approximate)
- And more — all through a clean visual dashboard

## 🤔 Why SiteScope?

Whether you're applying for jobs, sharing your work, or growing your personal brand, it's important to know: **Is anyone actually seeing your content?** SiteScope gives you that insight — simply, quickly, and privately.

## 🌐 Compatible Shared Hosting Services

SiteScope works with popular shared hosting providers such as Hostinger, HostGator, Bluehost, GoDaddy, WP Engine (WordPress), etc.

## 🛠️ How to Use It

### Step 1: Download and Extract
Download the repository as a ZIP file, extract it, and rename the folder to `SiteScope`.

### Step 2: Upload to Your Server
Upload the `SiteScope` folder to your hosting server using its FTP console. Place it in the root directory of your portfolio (where you would typically find the `index.html` file).

### Step 3: Add Tracking Code
On whichever page you want to track, add this script just above the closing `</body>` tag:

```html
<script defer src="SiteScope/track.js"></script>
```

## ⚠️ Disclaimer
If you rename the downloaded repository, make sure to update:
1. The script path in Step 3 above
2. The paths in the `script.js` file where it references:
   ```javascript
   const statsResponse = await fetch(`/SiteScope/stats.php`);
   const analyticsResponse = await fetch(`/SiteScope/analytics.php`);
   const analyticsData = await analyticsResponse.json();
   ```

An `analytics.db` file will be created inside the repository folder when hosted. Keep that as a backup for your analysis data.

## 📈 Accessing Your Analytics
After installation, you can view your analytics by navigating to:
`https://your-website.com/SiteScope/`

## 🔧 Troubleshooting
- Make sure your hosting supports PHP and SQLite
- Check file permissions - the folder needs write permissions for the database
- If you're using a custom directory structure, adjust all paths accordingly

## 📜 License
[MIT License](LICENSE)