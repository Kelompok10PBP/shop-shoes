# Stride Footwear Project Guidelines

## Design Tokens & Visual Fidelity
- The visual design must match `reference/home.html` EXACTLY.
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

## Technical Architecture
- Laravel 12+, PHP 8.2+, MySQL (XAMPP).
- Database: `stride_shop`. Full export will be kept at `database/stride_shop.sql`.
- Tailwind CSS 3.4 via Vite.
- Alpine.js for UI interactivity.
- PricingService for calculating server-side discounts and final prices.
- Reusable Blade components for all UI elements.
- Admin Panel under `/admin` with role-based access.
