# Bookerville by Buildup Bookings

A lightweight WordPress plugin for Bookerville API integration with custom property listings.

## Installation

1. Upload the `bookerville` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to Bookerville Listings → Settings in the WordPress admin
4. Configure your Bookerville API credentials (Secret Key & Account ID)
5. Click "Save Settings" to sync your properties from Bookerville

## Features

- **Bookerville API Integration** - Seamlessly connect your WordPress site with Bookerville's property management system
- **Custom Post Type** - Dedicated "Bookerville Listings" post type for managing properties
- **Property Sync** - Automatically sync properties from Bookerville with scheduled updates
- **ACF Integration** - Custom field support with photo gallery functionality for property data
- **Multiple Display Options** - Show properties in slider or grid layouts
- **Search Widget** - Built-in property search functionality
- **Custom Templates** - Single property pages with custom views
- **Responsive Design** - Mobile-friendly property displays with Slick Carousel integration

## Usage

### Settings Configuration
1. Go to Bookerville Listings → Settings
2. Enter your Bookerville API Secret Key
3. Enter your Account ID
4. Save settings to automatically sync all properties

### Shortcodes

**Display Properties Slider:**
```
[bookerville_display_properties type="slider" slidesToShow="3" slidesToDisplay="6"]
```

**Display Properties Grid:**
```
[bookerville_display_properties type="grid" slidesToDisplay="12"]
```

**Search Widget:**
```
[bookerville_search_widget]
```

**Shortcode Attributes:**
- `type` - Display type: "slider" or "grid" (default: "slider")
- `slidesToShow` - Number of slides visible at once (default: 3)
- `slidesToDisplay` - Total number of properties to display (default: 6)

### Property Management
- Properties are automatically created/updated when syncing from Bookerville
- Each property includes: name, address, maximum occupancy, check-in/out times, and photos
- Offline properties are automatically set to draft status
- Single property pages use custom templates for enhanced display

---

**Version:** 1.1  
**Author:** Buildup Bookings  
**Website:** [buildupbookings.com](https://www.buildupbookings.com/)

