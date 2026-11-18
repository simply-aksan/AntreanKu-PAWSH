---
name: advanced-uiux-psychology
description: "Master guide and actionable heuristics for advanced UI/UX design, visual hierarchy, mobile responsive ergonomics, design systems, and behavioral UX psychology (based on Nielsen Norman Group, Gary Simon DesignCourse, Wish Naik Figma Architecture, Simplilearn Product Design, and modern conversion patterns). Always invoke when designing, reviewing, or refactoring user interfaces, mobile responsiveness, layouts, navigation, forms, and interaction flows."
license: MIT
metadata:
  author: Antigravity Design Intelligence
  sources:
    - Nielsen Norman Group (Psychology for UX)
    - DesignCourse / Gary Simon (Learn UI/UX in 30 Days)
    - Wish Naik (Figma Architecture & Responsive Systems)
    - Simplilearn (Product Design & Heuristics)
    - Ali Hassan (Modern High-Converting UI Patterns)
---

# Advanced UI/UX & Interaction Psychology Master Guide

This guide establishes the foundational design laws, behavioral psychology, and practical implementation standards for creating world-class digital experiences that are visually striking, cognitively effortless, and ergonomically optimized for mobile and desktop.

---

## 1. Behavioral UX Psychology & Cognitive Laws (Nielsen Norman Group)

### 1.1 Jakob's Law (Familiarity & Mental Models)
> *"Users spend most of their time on other sites. They expect your site to work like all the other sites they already know."*
- **Application**:
  - Keep core navigation conventions standard: logo at top-left, primary navigation centered or aligned, user profile/cart/auth at top-right.
  - Search inputs must look like search inputs (with magnifying glass icon or explicit placeholder).
  - Do not reinvent standard mechanics (e.g., checkout flows, modal dismissals, form submission). Innovate on branding, visual personality, and speed—not on fundamental UX patterns.

### 1.2 Fitts's Law (Mobile Ergonomics & Touch Targets)
> *"The time to acquire a target is a function of the distance to and size of the target."*
- **Application**:
  - **Minimum Touch Target**: Every interactive element on mobile must have a tap target of at least **`44px x 44px`** (or `48px x 48px` according to Material/Apple guidelines).
  - **The Thumb Zone**: On mobile devices, 75% of user interactions occur using a single thumb. Place primary high-frequency CTAs within the natural bottom-third arc of the screen.
  - **Sticky Bottom Action Bar**: For critical conversion flows (e.g., booking a room, purchasing), dock a sticky bar at the bottom of the mobile viewport containing the price summary and primary action button.

### 1.3 Hick's Law (Reducing Cognitive Decision Time)
> *"The time it takes to make a decision increases logarithmically with the number and complexity of choices."*
- **Application**:
  - Break long forms or multi-option choices into clear, chunked steps (**Progressive Disclosure**).
  - Limit primary filter options to 3–5 high-impact choices; hide granular parameters behind an expandable "Filter Lainnya" trigger.
  - Highlight one single primary Call to Action (CTA) per view. Secondary actions should be visually subordinated.

### 1.4 Miller's Law & Chunking
> *"The average human mind can hold only 7 (± 2) chunks of information in working memory simultaneously."*
- **Application**:
  - Group related data using **The Law of Common Region** (distinct card boundaries, background blocks).
  - Format raw data into scannable chunks: phone numbers (`0812-3456-7890`), prices (`Rp 1.500.000`), dates (`14 Nov 2026`).
  - Present room amenities and specifications in distinct thematic clusters (e.g., "Fasilitas Kamar" vs. "Fasilitas Gedung").

### 1.5 The Von Restorff Effect (Isolation Effect)
> *"When multiple similar objects are present, the one that differs from the rest will be most remembered and acted upon."*
- **Application**:
  - Apply the high-contrast accent color (`#FFE600` Canary Yellow or vibrant brand color) exclusively to the single target you want the user to click.
  - Use visual badges, tilt angles (`rotate-[-2deg]`), and hard offset dropshadows to isolate "Paling Populer", "Rekomendasi", or "Bebas Deposit".

### 1.6 Gestalt Principles of Visual Perception
1. **Law of Proximity**: Elements placed close together are perceived as belonging to the same group. Keep label and its input close (`mb-1` / `4px`), and separate adjacent field groups with larger spacing (`space-y-4` / `16px`).
2. **Law of Similarity**: Interactive elements sharing visual traits (border thickness, typography weight, color) must share identical behavior.
3. **Law of Common Region**: Enclosing elements in a bordered container (`nb-card`) creates an immediate psychological container that eliminates ambiguity.

---

## 2. Visual Hierarchy & Spacing Rhythm (Gary Simon / DesignCourse)

### 2.1 The 60-30-10 Color Distribution
- **60% Dominant Neutral**: Background canvas (`#FBF9F5` warm off-white, surfaces).
- **30% Structural Secondary**: Containers, borders, typography, cards (`#121212` black borders, white card backgrounds, dark body copy).
- **10% High-Energy Pop Accent**: Primary CTA buttons, active status pills, alert badges (`#FFE600`, `#A6F4C5`, `#FF5E5B`).
- *Golden Rule*: If everything is colorful and screaming for attention, nothing gets attention. Reserve pop colors for intentional anchors.

### 2.2 The 8pt Spatial Grid System
Every padding, margin, width, height, and gap must be a multiple of **`4px`** or **`8px`**:
- `4px` (`gap-1` / `p-1`): Micro gaps (between icon and text).
- `8px` (`gap-2` / `p-2`): Compact padding (small tags, chips).
- `12px` (`p-3`): Medium padding (badge pills, list rows).
- `16px` (`p-4` / `space-y-4`): Standard component padding (mobile cards, input fields).
- `24px` (`p-6` / `gap-6`): Generous section padding (desktop cards, bento grid gaps).
- `32px` (`p-8` / `py-8`): Large container padding.
- `48px` - `64px` (`py-12` - `py-16`): Section separation on desktop.

### 2.3 Typography Scale & Scannability
- **Major Third Scale (1.25 Ratio)**:
  - Display / Hero Heading: `48px` – `64px` (`font-black`, leading tight `1.05 - 1.15`).
  - Section Title (H2): `28px` – `36px` (`font-black`).
  - Card Title (H3): `20px` – `24px` (`font-bold`).
  - Subheadings / Badges: `13px` – `14px` (`font-bold`, uppercase, tracking wider).
  - Body Text: `14px` – `16px` (`font-medium`, line-height `1.5 - 1.6`).
  - Caption / Metadata: `11px` – `12px` (`font-semibold`).
- **Reading Comfort**: Keep line length between 45 and 75 characters for prose. Never let body text span unbounded across 1200px screens.

---

## 3. Responsive Constraints & Mobile Ergonomics (Wish Naik / Figma Systems)

### 3.1 Breakpoint Strategy
- **Mobile (`< 640px`)**: Single column layout, full-width buttons, horizontal scroll for chips, larger touch target paddings, floating bottom bar for actions.
- **Tablet (`640px - 1024px`)**: 2-column grids, adaptive navigation.
- **Desktop (`1024px+`)**: 3-column or 4-column bento grids, sticky sidebars, multi-pane drawers.

### 3.2 Mobile-First Component Guidelines
1. **Forms on Mobile**:
   - Inputs must be full-width (`w-full`).
   - Font size on inputs must be at least `16px` (`text-base` or `text-sm sm:text-xs`) to prevent iOS Safari auto-zooming.
   - Use native keyboard attributes: `type="tel"`, `type="email"`, `inputmode="numeric"`.
2. **Horizontal Scrolling Chips**:
   - For category pills or facility filters on mobile, allow clean horizontal scrolling (`flex overflow-x-auto no-scrollbar py-1 gap-2`) with `snap-x` so filters don't take up 5 lines of vertical screen space.
3. **Hero Viewport Height on Mobile**:
   - Avoid taking 100vh on mobile with empty whitespace. Ensure that the first row of content (search bar or first card) is partially visible above the fold so users immediately know to scroll.

---

## 4. Product Design & Heuristic Usability (Simplilearn / Nielsen 10 Heuristics)

1. **Visibility of System Status**: Always provide instant feedback on actions (loading spinners, "Sedang mengunci kamar 20 menit...", toast alerts, visual active states).
2. **Match Between System & Real World**: Use clear Indonesian terminology that users use in daily life ("Bebas Deposit Rp 0", "Kamar Mandi Dalam", "Jatuh Tempo Bulanan") instead of technical abstractions.
3. **User Control & Freedom**: Easy modal close buttons (`&times;`), clear "Batal" options, and back links on sub-pages.
4. **Error Prevention & Inline Guidance**: Explain password criteria *before* the user submits, show instant error messages beneath the exact field.
5. **Recognition Over Recall**: Keep essential order details (room name, monthly price, address) visible alongside the checkout form rather than asking the user to remember them.

---

## 5. Modern High-Converting Soft Neo-Brutalism Patterns (Ali Hassan / Gumroad Blend)

1. **Tactile Physics Micro-Interactions**:
   ```css
   .nb-btn {
     border: 2px solid #121212;
     box-shadow: 3px 3px 0px 0px #121212;
     transition: all 0.15s ease;
   }
   .nb-btn:hover {
     transform: translate(-1px, -1px);
     box-shadow: 4px 4px 0px 0px #121212;
   }
   .nb-btn:active {
     transform: translate(2px, 2px);
     box-shadow: 1px 1px 0px 0px #121212;
   }
   ```
2. **Asymmetric Bento Layouts**:
   - Pair an oversized primary card (`col-span-2`) with 2 stacked informational cards (`col-span-1`) featuring different pastel backgrounds (`#FFE600`, `#A6F4C5`, `#BAE6FD`).
3. **Sticker Angled Accents**:
   - Tilt floating stamps slightly (`rotate-[-2deg]`, `rotate-[12deg]`) with solid black borders and shadows to evoke physical, tactile paper stickers.
4. **Marquee Ticker Tape**:
   - Continuous looping ribbon delivering key value propositions with bold icons and uppercase typography.

---

## 6. Review Checklist Before Finalizing Any UI

- [ ] **Mobile Ergonomics**: Are all clickable buttons and inputs at least `44px` tall?
- [ ] **Thumb Zone**: Can the user execute the main action on mobile with one hand comfortably?
- [ ] **Visual Hierarchy**: Is the primary CTA instantly noticeable within 3 seconds of scanning?
- [ ] **Cognitive Load**: Is the layout chunked into clear, digestible bento boxes or sections?
- [ ] **Contrast & Accessibility**: Do all text elements meet WCAG AA contrast (minimum 4.5:1 ratio) against their background?
- [ ] **Touch Overflow**: Does the page scroll smoothly on mobile without unexpected horizontal jitter or clipping?
