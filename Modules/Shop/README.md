# Shop Module

A Laravel module for managing product attributes and attribute families in an e-commerce application.

## Features

- **Attributes Management**: Full CRUD for product attributes (text, select, boolean, number, date, textarea)
- **Attribute Families Management**: Group attributes into families for product types
- **Internationalization**: Arabic and English translations included
- **Permissions**: Granular permission system for access control

## Installation

1. Add the module to your Laravel application:
   ```bash
   composer require hadi/shop
   ```

2. Run migrations:
   ```bash
   php artisan migrate
   ```

3. Seed permissions:
   ```bash
   php artisan db:seed --class="Modules\\Shop\\Database\\Seeders\\ShopDatabaseSeeder"
   ```

## Structure

- `app/Models` - Eloquent models (Attribute, AttributeFamily)
- `app/Http/Controllers/Admin` - Admin controllers
- `app/Http/Requests` - Form requests with authorization
- `app/Repositories` - Repository pattern interfaces and implementations
- `app/Application` - Application services for business logic
- `app/Data` - Data transfer objects with validation (Spatie Laravel Data)
- `database/migrations` - Database migrations
- `database/seeders` - Database seeders for permissions
- `lang` - Translation files (JSON format)
- `resources/views/admin` - Blade views for admin panel
- `routes` - Route definitions (admin, web, api)

## Permissions

The module registers the following permissions:

- `Shop Management` - Main permission group
- `shop.attributes.view` - View attributes
- `shop.attributes.create` - Create attributes
- `shop.attributes.edit` - Edit attributes
- `shop.attributes.delete` - Delete attributes
- `shop.attribute_families.view` - View attribute families
- `shop.attribute_families.create` - Create attribute families
- `shop.attribute_families.edit` - Edit attribute families
- `shop.attribute_families.delete` - Delete attribute families

## Routes

Admin routes (prefixed with `admin.`):

- `admin.attributes.index` - List attributes
- `admin.attributes.create` - Show create form
- `admin.attributes.store` - Store new attribute
- `admin.attributes.edit` - Show edit form
- `admin.attributes.update` - Update attribute
- `admin.attributes.deleteMulti` - Bulk delete attributes
- `admin.attribute_families.index` - List attribute families
- `admin.attribute_families.create` - Show create form
- `admin.attribute_families.store` - Store new attribute family
- `admin.attribute_families.edit` - Show edit form
- `admin.attribute_families.update` - Update attribute family
- `admin.attribute_families.deleteMulti` - Bulk delete attribute families

## Models

### Attribute

- `code` (string, unique) - Unique identifier (e.g., "color", "size")
- `admin_name` (json, translatable) - Display name in admin panel
- `type` (string) - text, select, boolean, number, date, textarea
- `is_required` (boolean) - Whether attribute is required
- `is_unique` (boolean) - Whether attribute value must be unique
- `options` (json, nullable) - Options for select type

### AttributeFamily

- `code` (string, unique) - Unique identifier (e.g., "clothing", "electronics")
- `name` (json, translatable) - Display name

### Relationships

- Attribute belongsToMany AttributeFamily (pivot: attribute_family_attributes with sort_order)
- AttributeFamily belongsToMany Attribute (pivot: attribute_family_attributes with sort_order)

## Translation

The module uses Laravel's translation system with JSON files:
- `lang/en.json` - English translations
- `lang/ar.json` - Arabic translations

All UI strings are wrapped in `__()` helper for translation.