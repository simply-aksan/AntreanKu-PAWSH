---
name: livewire-development
description: "Best practices, syntax rules, and guidelines for Livewire 4 Single File Components (SFC), Alpine.js reactivity, and form handling in Laravel. Always invoke when creating or editing Livewire components, managing wire:model, dispatching events, and handling lifecycle hooks."
license: MIT
metadata:
  author: HUNI Dev Team
---

# Livewire 4 & Alpine.js Development Guide

## 1. Single File Component (SFC) Architecture

In Livewire 4, components can be authored as Single File Components (`resources/views/components/⚡name.blade.php` or `resources/views/components/subfolder/⚡name.blade.php`).

### 1.1 SFC Structure
```blade
<?php

use Livewire\Component;

new class extends Component
{
    public string $name = '';
    public bool $isOpen = false;

    public function mount()
    {
        // Lifecycle initialization
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
        ]);
        // Action logic
    }

    public function with(): array
    {
        return [
            'items' => Item::latest()->get(),
        ];
    }
};
?>

<div>
    <!-- Template markup -->
    <input type="text" wire:model="name" />
    <button wire:click="save">Simpan</button>
</div>
```

> [!IMPORTANT]
> In Livewire 4 SFCs, do NOT call `return view(...)` in `render()`. Use `public function with(): array` to pass computed or queried data to the Blade template.

---

## 2. Reactivity & Modals

### 2.1 State Toggling
- For client-only toggles, prefer Alpine.js:
  ```html
  <div x-data="{ open: false }">
      <button @click="open = !open">Buka Menu</button>
      <div x-show="open" @click.outside="open = false" x-cloak>
          Konten dropdown...
      </div>
  </div>
  ```
- For server-driven modals requiring state clearance:
  ```html
  <button wire:click="$set('showModal', true)">Buka Modal</button>
  ```

---

## 3. Real-Time Form Validation & Error Feedback
- Always define clear custom Indonesian error messages in `$this->validate([...], [...])`.
- In templates, check errors with `@error('field') <span class="text-xs font-bold text-rose-600">{{ $message }}</span> @enderror`.

---

## 4. Flash Notifications & Session Alerts
- Dispatch session alerts cleanly:
  ```php
  session()->flash('success', 'Perubahan berhasil disimpan!');
  ```
- In the layout or component, render alerts using Soft Neo-Brutalist cards (`nb-card-sm p-4 bg-[#A6F4C5]`).
