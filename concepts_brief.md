# Omni Widget Pack: Core Layout & Brand Components Suite

* **Author:** zafajardo9
* **Date:** 2026-11-11

## Overview

The Omni Widget Pack is a WordPress plugin extending Elementor with a unified system of eight custom widgets designed to match the FilePino corporate design system. It addresses two core pain points:
1. **Layout Deadzone Inconsistency:** Without standardized section wrappers, page layouts suffer from inconsistent margins, gutters, and widths across pages and devices.
2. **Typography Fragmenting:** Elementor lacks a simple mechanism for an outer section to cascade typography down to all child widgets automatically without manual configuration on each individual widget.

This suite provides a parent layout container (`owp-container`) that enforces content width (1280px default) and horizontal deadzones (80px desktop, 40px tablet, 20px mobile) while exposing CSS custom properties so nested widgets (`owp-navbar`, `owp-footer`, `owp-hero`, `owp-cta-banner`, `owp-service-card`, `owp-accordion`, `owp-button`) inherit and mirror the container's font configuration automatically.

The visual system is constrained to four primary tokens:
* Yellow: `#f9d62c`
* Blue: `#1890c4`
* Red: `#de3632`
* White: `#ffffff`

---

## Codebase Audit Before Implementation

This table records the original scaffold before the Omni suite was implemented. See the [current widget inventory](README.md#current-widgets) for the implemented components.

| Component / Requirement | Status in Codebase | Findings & Technical Gap |
| :--- | :--- | :--- |
| **Plugin Skeleton** | **Implemented** | `omni-widget-pack.php` and `includes/Plugin.php` register the plugin, autoloader, and category `omni-widget-pack`. |
| **Shared Base Class** | **Implemented** | `includes/Widgets/BaseWidget.php` handles category and stylesheet assignment. |
| **Site Container (`owp-container`)** | **Missing (0%)** | Does not exist. No deadzone margin logic, no container wrapper, no CSS variable typography cascade. |
| **Navigation Bar (`owp-navbar`)** | **Missing (0%)** | Does not exist. No logo handling, menu rendering, mobile toggle, or action button. |
| **Universal Footer (`owp-footer`)** | **Missing (0%)** | Does not exist. No multi-column layout, social links, or copyright sub-bar. |
| **Hero Section (`owp-hero`)** | **Missing (0%)** | Does not exist. No split hero copy, dual action group, or road milestones. |
| **CTA Banner (`owp-cta-banner`)** | **Missing (0%)** | Does not exist. No two-column navy card or golden highlight accenting. |
| **Service Cards (`owp-service-card`)** | **Missing (0%)** | Does not exist. The only card present is `InfoCard.php`, which is a single-column generic box. |
| **Accordion (`owp-accordion`)** | **Missing (0%)** | Does not exist. No collapsible markup or toggle controls. |
| **Button (`owp-button`)** | **Missing (0%)** | Does not exist. No standalone button widget or style variant classes. |
| **Color Tokens** | **Missing (0%)** | The colors `#f9d62c`, `#1890c4`, `#de3632`, `#ffffff` are not defined in `omni-widget-pack.css` or control defaults. |

---

## Analysis

### Core Trade-offs & Technical Contradictions
1. **Elementor Nested Element Architecture:** Elementor widgets are typically isolated blocks. Creating a parent container (`owp-container`) that wraps arbitrary child widgets inside standard Elementor (especially Elementor Free without Theme Builder or nested flex container experimentation) requires determining whether `owp-container` is:
   - A standalone section/container widget using Elementor's inner column / DOM rendering, OR
   - A wrapper that targets child CSS classes via CSS custom properties (`--owp-font-family`, `--owp-text-color`).
2. **Typography Cascade Invariant:** If a child widget defines its own typography via Elementor controls, Elementor injects high-specificity CSS rules (e.g. `body .elementor-element.elementor-element-xxxx .owp-widget-title { font-family: ... }`). The container cascade must use CSS custom properties (`var(--owp-container-font-family, inherit)`) so container-level styling acts as the true fallback while permitting explicit child overrides without `!important` conflicts.
3. **Responsive Deadzone Bounds:** Fixed pixel deadzones (`80px`) on desktop can cause layout overflow on narrower desktop viewports (e.g., 1024px–1279px) if not calculated using `clamp()` or fluid percentages (e.g., `padding: 0 max(20px, 5.5vw)`).

---

## Goals vs. Non-Goals

### In-Scope Goals
* Build `owp-container` providing:
  * Standard content max-width (1280px default).
  * Standardized horizontal deadzones (80px desktop, 40px tablet, 20px mobile).
  * Root typography controls that inject scoped CSS custom properties inherited by all pack widgets.
* Build the seven functional widgets (`owp-navbar`, `owp-footer`, `owp-hero`, `owp-cta-banner`, `owp-service-card`, `owp-accordion`, `owp-button`) adhering strictly to the four brand colors and container inheritance.
* Provide responsive styles for desktop (1440px), tablet (768px), and mobile (390px/320px).
* Keep the plugin compatible with free Elementor 3.5+.

### Out-of-Scope Non-Goals
* Replacing Elementor's native Flexbox container engine or theme builder engine.
* Deep WooCommerce or dynamic ACF/Pods field mapping (focus is static/content configuration first).
* Bundling heavy third-party JavaScript libraries (sliders, animation engines) beyond lightweight vanilla JS for mobile nav toggles and accordions.

---

## Alternatives Weighed

* **Alternative 1: Using Global Elementor Site Settings Kit.**
  * *Trade-off:* Elementor Site Settings affect the entire theme and require administrator permissions across the whole site. If the plugin is installed on client sites with existing global styles, altering Site Settings risks breaking other pages.
  * *Resolution:* Use scoped container CSS variables (`.owp-container { --owp-font-family: ... }`) so styling is self-contained to sections where pack widgets are deployed.
* **Alternative 2: Shipping separate plugins per component.**
  * *Trade-off:* High maintenance overhead and fragmented updates.
  * *Resolution:* Single plugin with autoloaded modular widget registry.

---

## Key Decisions & Staging for Next Steps

1. **Staging:**
   - **Phase 1:** Container & typography cascade foundation, standalone button, navbar, footer.
   - **Phase 2:** Hero section and CTA card banner.
   - **Phase 3:** Service/feature cards and collapsible accordion.
2. **Pending User Input:**
   - Clarification on navigation menu data source (WordPress standard menus vs. Elementor custom repeater items).
   - Container nesting mechanism preference in Elementor.
