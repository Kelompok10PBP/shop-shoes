# Stride Footwear Design & Architecture Rules

## Visual Design & Styling
- The visual design must match `reference/home.html` EXACTLY.
- Port design tokens directly to Tailwind CSS (colors, fonts, fontSizes, spacing, borderRadius).
- Colors:
  - primary: #004725, primary-container: #1f5f3a, on-primary: #ffffff
  - surface / background: #f9f9ff, surface-container-low: #f1f3ff, surface-container: #e9edff, surface-container-high: #e1e8fd, surface-container-lowest: #ffffff
  - on-surface: #141b2b, on-surface-variant: #404941, outline: #707971
  - secondary: #496454, secondary-container: #c8e7d3, secondary-fixed: #cbead6
  - tertiary-fixed: #ffdeaa, tertiary-fixed-dim: #f5bd58, error: #ba1a1a
  - inverse-surface: #293040
- Fonts: Plus Jakarta Sans (headlines, labels, display), Inter (body).
- Icons: Google Material Symbols Outlined only.
- Border Radii: 8px buttons, 12px cards, 16px section containers, full for badges/pills.
- Container: max-w-[1440px], px-margin-mobile (1rem) on mobile, lg:px-margin (3rem) on desktop.
- Subtle shadows only (shadow-sm, hover:shadow-md). No unnatural AI gradients or blobs.
- No hotlinked external product images in production: fetch and serve from local storage.

## Architecture & Code Quality
- Laravel 12/13 with PHP 8.2+ / 8.3+, MySQL via XAMPP (`stride_shop` database).
- Tailwind CSS 3.4 for compatibility with the exact design tokens.
- Alpine.js for interactive UI (mini-cart drawer, live filters, modals, countdown timer, toasts).
- Vanilla fetch/axios against clean JSON API endpoints.
- Reusable Blade components: x-announcement-bar, x-header, x-mini-cart, x-footer, x-product-card, x-category-card, etc.
- No hardcoded data or dead links: dynamic database records for categories, products, variants, promotions, reviews, settings.
- Cart & Pricing: PricingService calculates discounts on server side. Never trust client prices.
- Guest cart in session, synced to database on login/register.
- Admin Panel under `/admin` with Blade views reusing the same design tokens.
