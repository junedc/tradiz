# AGENTS.md

## Project Context

This project is a Laravel + Vue application for a blinds product builder, quoting system, and future manufacturing/inventory workflow.

The goal is to let users or staff configure blinds, calculate prices, save quotes, convert quotes to orders, and later support manufacturing, materials, stock, installation, and PDF generation.

This document captures the agreed architecture and development rules so Codex can continue work consistently.

---

# Core Product Vision

The application should support a guided product builder for installing blinds.

The builder should allow a user to:

1. Select a blind type
2. Enter window measurements
3. Choose fabric/materials
4. Choose control/options/components
5. Add installation details
6. See live pricing
7. Save a quote
8. Add multiple windows/blinds to one quote
9. Generate a PDF quote
10. Convert quote to order later

Initial product focus:

- Roller Blinds first

Future product types:

- Venetian Blinds
- Vertical Blinds
- Roman Blinds
- Panel Glide
- Curtains, if needed later

---

# Recommended Tech Stack

Use one Laravel project with Vue inside it.

Backend:

- Laravel 13
- MySQL
- Laravel API routes
- Laravel services for business logic
- Laravel validation/Form Requests
- Laravel API Resources
- PDF generation later

Frontend:

- Vue 3
- Composition API
- Pinia
- Vue Router
- PrimeVue
- Tailwind CSS
- Axios or similar API client

Do not introduce React, Livewire, jQuery, or a separate frontend project unless explicitly requested.

---

# Application Architecture

This should be one project:

```text
Laravel Backend + Vue Frontend = One Application
```

Use:

```text
Single repository
Single deployment
Single database
```

Laravel handles:

- Authentication
- API
- Database
- Pricing engine
- Quote saving
- Order processing
- PDF generation
- Inventory
- Admin panel
- Business logic

Vue handles:

- Product builder UI
- Step forms
- Live pricing display
- Dynamic material selection
- Quote management UI
- Dashboard UI
- Admin screens

---

# Blade and Vue Rule

Do not build the product builder in Blade.

Blade should only be used as a small entry shell for the Vue SPA.

Example:

```blade
<!-- resources/views/app.blade.php -->
<!DOCTYPE html>
<html>
<head>
    @vite(['resources/js/app.js'])
</head>
<body>
    <div id="app"></div>
</body>
</html>
```

Laravel route:

```php
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
```

Vue Router should handle frontend routes such as:

```text
/login
/dashboard
/product-builder
/quotes
/materials
/inventory
/orders
```

Avoid mixing Blade and Vue in the same feature.

Do not do this:

```blade
@foreach($items as $item)
    <my-vue-component :item="{{ $item }}"></my-vue-component>
@endforeach
```

Use API calls instead.

---

# Recommended Project Structure

Laravel:

```text
app/
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── Resources/
├── Models/
├── Services/
├── Actions/
├── DTOs/
└── Repositories/
```

Vue:

```text
resources/js/
├── app.js
├── router/
├── store/
├── layouts/
├── pages/
│   ├── LoginPage.vue
│   ├── DashboardPage.vue
│   ├── ProductBuilderPage.vue
│   └── QuotesPage.vue
├── components/
├── composables/
└── services/
```

Product builder components:

```text
resources/js/
├── pages/
│   └── ProductBuilderPage.vue
├── components/builder/
│   ├── ProductSelector.vue
│   ├── MeasurementStep.vue
│   ├── MaterialStep.vue
│   ├── OptionsStep.vue
│   ├── InstallationStep.vue
│   ├── PriceSummary.vue
│   └── QuoteItemList.vue
```

---

# Product Builder UI Design

Use a step-by-step builder, not one large form.

Desktop layout:

```text
------------------------------------------------
Header
Logo | Quote Number | Customer | Save Draft
------------------------------------------------

Left Side                  Center/Right
Steps/Menu                 Builder Content
------------------------------------------------
1. Product Type            Product form
2. Measurements            Options
3. Material/Fabric         Price preview
4. Controls/Parts
5. Installation
6. Review
------------------------------------------------
```

Preferred structure:

```text
Left: Builder steps
Center: Current form
Right: Live price summary
```

Mobile layout:

```text
Step form first
Price summary collapsible at bottom
```

The builder must allow multiple quote items/windows:

```text
Quote Items
- Bedroom Window    $440
- Lounge Window     $520
- Kitchen Window    $310
```

Each quote item represents one blind/window.

---

# Builder Flow

## Step 1: Product Type

Show cards:

```text
[ Roller Blind ] [ Venetian Blind ] [ Vertical Blind ]
[ Roman Blind  ] [ Panel Glide    ] [ Curtain       ]
```

Each card should have:

- Image
- Name
- Short description

## Step 2: Measurements

Fields:

```text
Room name
Window name
Width mm
Drop mm
Mount type: Inside / Outside
Quantity
```

Show a simple measurement diagram if possible.

## Step 3: Material Selection

For roller blinds:

```text
Fabric Range:
[ Essentials ] [ Premium ] [ Designer ]

Fabric Type:
[ Blockout ] [ Sunscreen ] [ Translucent ]

Colours:
[ White ] [ Grey ] [ Beige ] [ Black ]
```

For Venetian blinds:

```text
Slat Material:
[ Timber ] [ PVC ] [ Aluminium ]

Slat Width:
[ 25mm ] [ 50mm ] [ 63mm ]
```

## Step 4: Product Options

For roller blinds:

```text
Chain side:     Left / Right
Chain colour:   White / Black / Metal
Roll direction: Front Roll / Back Roll
Bottom rail:    Standard / Oval / Designer
Control:        Chain / Motorised
```

For Venetian blinds:

```text
Tilt control: Left / Right
Lift control: Left / Right
Slat size: 25mm / 50mm
Ladder: Cord / Tape
```

## Step 5: Installation

Fields:

```text
Installation required? Yes / No
Install location
Wall type
Notes
Upload photo
```

## Step 6: Review

Show full summary:

```text
Bedroom Window
Roller Blind

Base price        $120
Fabric upgrade    $30
Motor             $250
Installation      $40
----------------------
Total             $440
```

Actions:

```text
[ Add Another Window ]
[ Save Quote ]
[ Convert to Order ]
```

---

# Configuration-Driven Builder Rule

The product builder must be configuration-driven.

Do not hardcode:

- product options
- materials
- pricing
- validation limits
- product-specific fields

The backend should return product configuration, and Vue should render the UI from that configuration.

Example API payload:

```json
{
  "product": "Roller Blind",
  "steps": [
    {
      "name": "Measurements",
      "fields": [
        {
          "type": "number",
          "label": "Width",
          "code": "width",
          "unit": "mm",
          "required": true
        }
      ]
    }
  ]
}
```

Vue renders the fields dynamically.

---

# Core Database Design

## products

Stores blind/product types.

```text
products
- id
- name
- slug
- description
- is_active
- created_at
- updated_at
```

Examples:

```text
Roller Blind
Venetian Blind
Vertical Blind
Roman Blind
```

Purpose:

```text
What product is the customer building?
```

---

## product_options

Stores configurable fields for each product.

```text
product_options
- id
- product_id
- name
- code
- type
- is_required
- sort_order
- created_at
- updated_at
```

Examples for roller blinds:

```text
Chain Side
Roll Direction
Motorisation
Chain Colour
Bottom Rail
```

Purpose:

```text
What choices does this product need?
```

---

## product_option_values

Stores possible values for each option.

```text
product_option_values
- id
- product_option_id
- label
- value
- price_adjustment
- is_active
- created_at
- updated_at
```

Example:

```text
Chain Side:
- Left
- Right

Motorisation:
- No Motor: $0
- Motorised: $250
```

Purpose:

```text
What are the available choices, and do they add cost?
```

---

## fabrics

This table may exist initially, but long term fabrics should be represented as materials.

```text
fabrics
- id
- name
- range
- colour
- type
- price_group
- is_active
- created_at
- updated_at
```

Examples:

```text
White Blockout Fabric
Grey Sunscreen Fabric
Premium Translucent Fabric
```

Purpose:

```text
What fabric can be selected?
```

---

## price_tables

Stores base pricing based on product, fabric/material price group, width, and drop.

```text
price_tables
- id
- product_id
- price_group
- width_from
- width_to
- drop_from
- drop_to
- price
- created_at
- updated_at
```

Example:

```text
product_id | price_group | width_from | width_to | drop_from | drop_to | price
1          | A           | 0          | 900      | 0         | 1200    | 120
1          | A           | 901        | 1200     | 0         | 1200    | 145
1          | B           | 0          | 900      | 0         | 1200    | 150
```

Purpose:

```text
How much is the blind based on width, drop, and fabric group?
```

---

## customers

Stores customer details.

```text
customers
- id
- first_name
- last_name
- email
- phone
- address
- created_at
- updated_at
```

Purpose:

```text
Who is the quote for?
```

---

## quotes

Parent quote record.

```text
quotes
- id
- customer_id
- quote_number
- status
- subtotal
- gst
- total
- created_at
- updated_at
```

Example statuses:

```text
draft
sent
accepted
rejected
converted_to_order
```

Purpose:

```text
What is the overall quote?
```

---

## quote_items

Each row is one configured blind/window inside a quote.

```text
quote_items
- id
- quote_id
- product_id
- room_name
- window_name
- width
- drop
- quantity
- mount_type
- fabric_id
- configuration_json
- base_price
- options_price
- install_price
- total
- created_at
- updated_at
```

Example configuration:

```json
{
  "chain_side": "left",
  "roll_direction": "front_roll",
  "chain_colour": "black",
  "bottom_rail": "standard",
  "motorised": false
}
```

Purpose:

```text
What exactly did the customer build for this window?
```

Use `configuration_json` as a snapshot of selections at the time of quote.

---

# Material and Component System

The system should separate:

```text
1. Product Type
2. Materials
3. Components
4. Manufacturing Rules
```

A blind is built from:

```text
Product
+ Materials
+ Components
+ Options
+ Manufacturing Rules
```

Roller blind uses:

```text
- Fabric
- Tube
- Bottom Rail
- Chain
- Brackets
- Motor, optional
```

Venetian blind uses:

```text
- Slats
- Ladder tape
- Headrail
- Bottom rail
- Tilt mechanism
- Cord
```

---

# Material Database Design

## material_categories

Groups materials.

```text
material_categories
- id
- name
- created_at
- updated_at
```

Examples:

```text
Fabric
Tube
Bracket
Motor
Slat
Chain
Bottom Rail
Headrail
```

---

## materials

Master catalog of physical materials/components.

```text
materials
- id
- material_category_id
- sku
- name
- description
- unit
- cost_price
- sell_price
- stock_quantity
- price_group
- is_active
- created_at
- updated_at
```

Examples:

```text
White Blockout Fabric
38mm Roller Tube
Black Chain
50mm White Slat
Somfy Motor 1.1
```

Units examples:

```text
sqm
linear_meter
piece
set
```

---

## product_materials

Defines which material categories are required or optional for a product.

```text
product_materials
- id
- product_id
- material_category_id
- is_required
- selection_type
- sort_order
- created_at
- updated_at
```

Example for Roller Blind:

```text
Fabric       required
Tube         required
Bracket      required
Bottom Rail  required
Chain        required
Motor        optional
```

Purpose:

```text
What material categories does this product need?
```

---

## product_material_options

Defines actual selectable materials for a product material category.

```text
product_material_options
- id
- product_material_id
- material_id
- is_default
- created_at
- updated_at
```

Example for roller tubes:

```text
38mm Tube
45mm Tube
60mm Tube
```

Purpose:

```text
Which exact materials can be selected for this product?
```

---

# Bill of Materials / BOM

Professional manufacturing systems use BOM to calculate material usage.

## product_boms

Defines how much material is needed for each product.

```text
product_boms
- id
- product_id
- material_id
- calculation_type
- formula
- created_at
- updated_at
```

Examples:

Roller fabric:

```text
formula = width * drop
```

Tube:

```text
formula = width + 50mm
```

Chain:

```text
formula = drop * 0.75
```

Example customer dimensions:

```text
Width = 2000mm
Drop = 1800mm
```

System calculates:

```text
Fabric = 2.0m × 1.8m = 3.6 sqm
Tube = 2050mm
Chain = 1350mm
```

This enables:

- manufacturing cost calculation
- stock deduction
- purchasing estimates
- cutting lists
- work orders

---

# Recommended Phases

## Phase 1

Build quoting/product builder.

Tables:

```text
products
product_options
product_option_values
materials
material_categories
quotes
quote_items
price_tables
customers
```

Features:

```text
Authentication
Product Builder
Quote System
PDF Quotes
Basic Pricing
```

## Phase 2

Build manufacturing and inventory.

Tables/features:

```text
product_boms
inventory
stock_movements
manufacturing_jobs
cutting_lists
orders
```

## Phase 3

Build operations.

Features:

```text
Installer scheduling
Customer portal
Online payment
CRM
Analytics
Supplier integration
```

---

# Pricing Rules

All pricing must be calculated in Laravel, not Vue.

Pricing logic belongs in:

```text
PricingService
```

Pricing includes:

- base price
- fabric/material price group
- option adjustments
- motor add-ons
- installation cost
- GST
- discounts
- margins

Vue only displays calculated prices from the API.

Never duplicate pricing logic between backend and frontend.

Example backend flow:

```text
1. Check selected product
2. Check measurements
3. Check selected material/fabric price group
4. Find base price from price_tables
5. Add option prices
6. Add installation
7. Add GST
8. Return calculated summary
```

---

# Recommended Backend Services

Create service classes such as:

```text
ProductBuilderService
PricingService
ValidationService
QuoteService
MaterialService
BomCalculationService
```

Controllers should be thin.

Example:

```php
class ProductBuilderService
{
    public function buildQuoteItem(array $data): array
    {
        $this->validateConfiguration($data);

        $basePrice = app(PricingService::class)->calculateBasePrice(
            productId: $data['product_id'],
            width: $data['width'],
            drop: $data['drop']
        );

        $optionsPrice = app(PricingService::class)->calculateOptionsPrice(
            $data['options'] ?? []
        );

        $installPrice = app(PricingService::class)->calculateInstallPrice($data);

        $total = $basePrice + $optionsPrice + $installPrice;

        return [
            'base_price' => $basePrice,
            'options_price' => $optionsPrice,
            'install_price' => $installPrice,
            'total' => $total,
            'configuration' => $data['options'] ?? [],
        ];
    }

    protected function validateConfiguration(array $data): void
    {
        if ($data['width'] <= 0 || $data['drop'] <= 0) {
            throw new InvalidArgumentException('Width and drop are required.');
        }
    }
}
```

---

# API Design

All frontend communication should use `/api`.

Suggested endpoints:

```text
GET    /api/products
GET    /api/products/{product}
GET    /api/products/{product}/builder-config
GET    /api/materials
POST   /api/builder/calculate
POST   /api/quotes
GET    /api/quotes
GET    /api/quotes/{quote}
PUT    /api/quotes/{quote}
POST   /api/quotes/{quote}/items
PUT    /api/quote-items/{quoteItem}
DELETE /api/quote-items/{quoteItem}
```

Response format should be consistent:

```json
{
  "success": true,
  "data": {},
  "message": ""
}
```

Validation errors should use Laravel's normal validation response format unless a project-wide API format is introduced.

---

# Validation Rules

Validate:

- required fields
- width greater than 0
- drop greater than 0
- product is active
- selected materials are active
- selected options belong to selected product
- measurements are within product limits
- max width
- max drop
- max area
- fabric/material availability
- motorisation compatibility

Product-specific validation should be configurable, not hardcoded where possible.

---

# Inventory and Manufacturing Direction

The product builder is not only a form builder.

Long term, this project should behave as:

```text
Quote System
+ Manufacturing System
+ Inventory System
```

Do not design the data model in a way that blocks:

- stock tracking
- supplier purchasing
- cutting lists
- work orders
- stock deduction
- manufacturing status
- installer scheduling

---

# Development Rules

## Backend

- Business logic belongs in services.
- Controllers must remain thin.
- Use Form Requests for validation.
- Use API Resources for responses.
- Use transactions for multi-table writes.
- Avoid raw queries unless necessary.
- Keep pricing logic out of controllers.
- Keep pricing logic out of Vue.
- Add migrations for database changes.
- Add tests for pricing calculations.

## Frontend

- Use Vue 3 Composition API.
- Use Pinia for global state.
- Use Vue Router for navigation.
- Use PrimeVue components when useful.
- Use Tailwind utility classes.
- Avoid inline styles.
- Keep pages thin.
- Move reusable logic into composables.
- API calls should live in frontend service files.
- Do not hardcode product options in Vue.

---

# Setup Commands

Install dependencies:

```bash
composer install
npm install
```

Start development:

```bash
php artisan serve
npm run dev
```

Run migrations:

```bash
php artisan migrate
```

Run tests:

```bash
php artisan test
```

Build frontend:

```bash
npm run build
```

Lint frontend, if configured:

```bash
npm run lint
```

---

# Definition of Done

A task is complete only if:

- code builds successfully
- tests pass where applicable
- no obvious linting errors
- API responses are validated
- Vue pages render without console errors
- database changes include migrations
- pricing calculations are tested
- no secrets are committed
- no `.env` values are modified without explicit instruction

Before finishing major changes, run:

```bash
php artisan test
npm run build
```

---

# Boundaries

Never:

- modify `.env` unless explicitly requested
- commit secrets
- edit vendor files
- mix Blade templates with Vue product-builder UI
- duplicate pricing logic between backend and frontend
- introduce React
- introduce jQuery
- introduce Livewire

without explicit approval.

---

# Preferred Patterns

Preferred:

- Service classes
- Form Requests
- API Resources
- DTOs for complex data
- Repositories only for complex queries
- Vue Composition API
- Pinia stores
- Reusable Vue composables
- Configuration-driven UI
- Backend-calculated pricing

Avoid:

- fat controllers
- business logic in components
- duplicated validation
- hardcoded product options
- deeply nested Vue components
- calculating prices in JavaScript

---

# Escalation Rules for Codex

If unclear:

- ask before changing architecture
- ask before changing database structure
- ask before adding new dependencies
- ask before replacing the frontend stack

If blocked:

- explain the issue
- propose 2 or 3 options
- identify the recommended option

If the task is small and the path is clear:

- make the change directly
- keep the implementation consistent with this document
