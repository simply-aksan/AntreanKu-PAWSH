---
name: strict-no-emojis
description: "Strict enforcement of zero emojis across all codebases, UI elements, templates, comments, test descriptions, seed data, and agent communications. Always invoke to guarantee clean, professional, accessible, and icon-based UI without unicode emojis."
license: MIT
metadata:
  author: AntreanKu Architecture Team
---

# Strict Zero-Emoji Policy

## 1. Core Rule
Under NO circumstances should any emoji character (Unicode emojis such as smileys, animals, symbols, checkmarks, warning signs, rockets, stars, sparkles, etc.) be used:
- In chat and assistant responses to the user.
- In any source code: PHP files, Blade templates, Livewire components, Alpine.js scripts, JavaScript, or CSS.
- In database migrations, seeders, factories, or dummy data.
- In UI elements, buttons, badges, headers, alerts, or status pills.
- In automated test names, assertions, or test output.
- In commit messages and documentation.

## 2. Professional Alternatives

### 2.1 For UI Icons
Always use clean, accessible vector SVG icons instead of emojis:
- Use Lucide icons or Heroicons SVG paths.
- For pets/animals: Use `<svg>` icons for dog, cat, paw-print, scissors, bath, check, cross, calendar, clock.
- Set appropriate `stroke-width`, `viewBox`, and Tailwind dimensions (e.g., `w-4 h-4`, `w-5 h-5`).

### 2.2 For Status Badges and Labels
Instead of emoji prefixes, use clean uppercase text badges and high-contrast Soft Neo-Brutalism pills:
- Instead of green check emoji: Use `[SELESAI]` or `[LUNAS]` or an inline SVG check icon.
- Instead of hourglass / clock emoji: Use `[MENUNGGU]` or an inline SVG clock icon.
- Instead of warning / scissors emoji: Use `[SEDANG DILAYANI]` or an inline SVG scissors icon.
- Instead of cross emoji: Use `[BATAL]` or an inline SVG x icon.

### 2.3 For Communication
- Use clear, professional, structured formatting with bold headings, bullet points, and code spans.
- Do not add decorative emojis at the beginning or end of paragraphs, titles, or list items.
