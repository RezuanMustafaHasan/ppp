# Hasan — PHP Portfolio

A classy, minimal PHP portfolio that pulls your public GitHub repos and embeds your PDF resume.

## Quick Start (XAMPP on macOS)
1. Copy the folder `hasan-php-portfolio/` into your XAMPP htdocs. On macOS:
   - `/Applications/XAMPP/htdocs/`
2. Ensure PHP cURL is enabled (it is by default in XAMPP).
3. Visit: http://localhost/hasan-php-portfolio/
4. Replace `assets/cv.pdf` with your latest resume if needed.
5. To customize text/skills/links, edit `inc/config.php`.

## Deploy Anywhere
- This is plain PHP and static assets. Upload to any shared hosting or a VPS with PHP 8+.
- GitHub API calls are cached to `cache/github_repos.json` for 6 hours to avoid rate limits.

## Contact Form
- Uses `mail()` which needs SMTP configured on your server. Alternatively, replace the form POST with a service like Formspree/EmailJS.

## Structure
```
hasan-php-portfolio/
  assets/        # CSS/JS and your cv.pdf
  cache/         # API cache
  inc/           # config & helpers
  index.php      # main page
```
