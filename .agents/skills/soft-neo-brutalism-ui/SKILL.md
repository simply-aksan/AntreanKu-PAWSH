---
name: soft-neo-brutalism-ui
description: "Master guide and design system rules for Soft Neo-Brutalism UI/UX. Always invoke when designing, coding, reviewing, or styling UI components, pages, forms, modals, tables, and layouts in this aesthetic. Covers solid borders, offset drop shadows, tactile button physics, pop color palette, Space Grotesk typography, and component blueprints."
license: MIT
metadata:
  author: HUNI Design Team
---

# Soft Neo-Brutalism Design System

Soft Neo-Brutalism combines the high-contrast honesty, bold outlines, and solid drop shadows of classic Neo-Brutalism with smooth rounded corners, warm canvas backgrounds, and friendly pop pastel colors. It gives web interfaces an energetic, tactile, playful, and distinct character.

---

## 1. Core Visual Principles

### 1.1 Outlines & Borders
- **Border Weight**: Minimum `2px` solid black (`#121212` or `#000000`). Never use 1px subtle gray borders.
- **Divider Lines**: Always `border-b-2 border-black` or `border-t-2 border-black`.
- **Corner Radii (The "Soft" aspect)**:
  - Standard cards: `rounded-xl` (`14px`) or `rounded-2xl` (`16px`).
  - Small badges/chips: `rounded-md` (`6px`) or `rounded-lg` (`8px`).
  - Inputs & Buttons: `rounded-xl` (`12px`).
  - Round action icons/stickers: `rounded-2xl` or `rounded-full`.

### 1.2 Hard Solid Drop Shadows (No Blur!)
- Neo-brutalist shadows must be **completely solid with 0 blur**:
  - Standard Card: `shadow-[4px_4px_0px_0px_#121212]`
  - Small Card / Pill / Badge: `shadow-[2px_2px_0px_0px_#121212]`
  - Emphasized Card / Modal: `shadow-[8px_8px_0px_0px_#121212]`
- **Never use blurry CSS box-shadows** like `shadow-md`, `shadow-lg`, or `shadow-xl`.

### 1.3 Tactile Button Interactions
Buttons feel physically pressable like mechanical switches:
- **Default State**:
  ```html
  <button class="nb-btn px-4 py-2.5 rounded-xl bg-[#FFE600] text-black font-black text-xs uppercase tracking-wider">
    Aksi Utama
  </button>
  ```
- **Physics Behavior**:
  - Hover: `hover:translate-x-[-1px] hover:translate-y-[-1px] hover:shadow-[4px_4px_0px_0px_#121212]`
  - Active (Press): `active:translate-x-[2px] active:translate-y-[2px] active:shadow-[1px_1px_0px_0px_#121212]`

---

## 2. Color Palette & Hierarchy

| Role | Color Name | Hex Code | Purpose |
|------|------------|----------|---------|
| **Canvas** | Warm Off-White | `#FBF9F5` | Default page background (never stark sterile #FFF) |
| **Ink** | Jet Black | `#121212` / `#000` | Borders, typography, primary CTA contrast |
| **Primary Pop** | Canary Yellow | `#FFE600` | Primary buttons, booking highlights, active stickers |
| **Success / Mint** | Fresh Mint | `#A6F4C5` | Active lease badges, payment confirmed, available rooms |
| **Info / Sky** | Sky Blue | `#BAE6FD` | Property city tags, metric tiles, building facilities |
| **Warning / Orange**| Tangelo | `#FF6B35` | Urgent attention, room checkout confirm |
| **Alert / Danger** | Coral Rose | `#FF5E5B` / `#FF8080`| Suspended accounts, late overdue notices, delete actions |
| **Accent / Purple** | Lavender | `#E9D5FF` | Secondary metrics, platform governance tags |

---

## 3. Typography Rules

1. **Headlines, Stat Numbers, and Badge Labels**:
   - Font Family: `Space Grotesk`, sans-serif (`font-display`)
   - Font Weight: `font-black` (900) or `font-bold` (700)
   - Tracking: `tracking-tight` for large headings, `tracking-wider` / `uppercase` for small badges.

2. **Body Copy, Forms, and Tabular Data**:
   - Font Family: `Plus Jakarta Sans`, sans-serif (`font-sans`)
   - Font Weight: `font-medium` (500), `font-semibold` (600), or `font-bold` (700)
   - Avoid thin/light weights (300/400).

---

## 4. Reusable Component Patterns

### 4.1 Card (`nb-card`)
```html
<div class="nb-card p-6 sm:p-8 bg-white space-y-4">
  <span class="nb-badge px-2.5 py-0.5 rounded-md text-[10px] uppercase bg-[#BAE6FD] text-black inline-block font-black">
    Kategori
  </span>
  <h2 class="font-display text-2xl font-black text-black">Judul Kartu</h2>
  <p class="text-xs font-semibold text-stone-600 leading-relaxed">
    Deskripsi teks konten kartu.
  </p>
</div>
```

### 4.2 Form Inputs
```html
<div>
  <label class="block font-bold uppercase tracking-wider text-black text-xs mb-1.5">Label Input</label>
  <input 
    type="text" 
    class="w-full text-sm px-4 py-2.5 rounded-xl border-2 border-black bg-white shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
    placeholder="Masukkan data..."
  >
</div>
```

### 4.3 Badges / Stickers (`nb-badge`)
```html
<!-- Mint Success -->
<span class="nb-badge px-3 py-1 rounded-lg text-xs bg-[#A6F4C5] text-black uppercase font-black">
  [LUNAS & AKTIF]
</span>

<!-- Coral Warning -->
<span class="nb-badge px-3 py-1 rounded-lg text-xs bg-[#FF8080] text-black uppercase font-black">
  [SUSPEND]
</span>
```

### 4.4 Modals & Popups
Always use a deep backdrop with solid thick card:
```html
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-xs p-4">
  <div class="nb-card max-w-md w-full p-6 sm:p-8 shadow-[8px_8px_0px_0px_#000] space-y-5 bg-white">
    <div class="flex items-center justify-between pb-3 border-b-2 border-black">
      <h3 class="font-display text-2xl font-black text-black">Judul Modal</h3>
      <button class="text-black text-2xl font-black cursor-pointer">&times;</button>
    </div>
    <!-- Body -->
  </div>
</div>
```

---

## 5. Anti-Patterns to Avoid
1. [X] **No gradient backgrounds** (e.g. `bg-gradient-to-r`). Use flat vibrant pop colors.
2. [X] **No blurry drop shadows** (`shadow-md`, `shadow-xl`, `box-shadow: 0 10px 15px rgba(0,0,0,0.1)`).
3. [X] **No gray hair-thin borders** (`border border-gray-200`). Always use `border-2 border-black`.
4. [X] **No washed out low-contrast text** (`text-gray-400` on white). Use rich black (`text-black`) or deep stone (`text-stone-700`).
5. [X] **No pure sharp 90-degree corners** (that is classic brutalism, NOT soft neo-brutalism). Always use `rounded-xl` or `rounded-2xl`.
