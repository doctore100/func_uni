# Fictional University WordPress Project

## Project Overview
This is a WordPress project for a fictional university website. It features custom themes and plugins to create a comprehensive university website with features for events, programs, professors, and campuses.

## Themes

### Fictional Block Theme
A modern block-based WordPress theme that uses the Gutenberg editor. This theme is built with custom blocks to create a flexible and user-friendly content editing experience.

#### Custom Blocks
- **Banner** - A hero/banner section for pages
- **Events and Blocks** - Displays events and other content blocks
- **Footer** - Custom footer block
- **Generic Button** - Reusable button component with customizable options
- **Generic Heading** - Reusable heading component with size options (large, medium, small)
- **Header** - Custom header block
- **Slide** - Individual slide component
- **Slideshow** - A slideshow/carousel block

#### Theme Features
- Block-based templates
- Custom block patterns
- Responsive design
- Custom page banner functionality
- Custom REST API endpoints

### Fictional University Theme
A traditional WordPress theme (non-block-based) with template files for various content types.

#### Custom Post Types
- Events
- Programs
- Professors
- Campuses

#### Theme Features
- Custom page templates
- Archive templates for custom post types
- Single templates for custom post types
- Search functionality
- User notes functionality

## Plugins

### Core WordPress Plugins
- **Advanced Custom Fields** - For creating custom fields and meta boxes
- **Akismet** - For spam protection
- **WPForms Lite** - For creating contact and other forms
- **Members** - For user role management
- **Loco Translate** - For translation management
- **LiteSpeed Cache** - For performance optimization
- **Regenerate Thumbnails** - For regenerating image thumbnails
- **Manual Image Crop** - For manual image cropping

### Custom/Development Plugins
- **Block React Example** - Example plugin for creating React-based blocks
- **Featured Professor** - Custom block for featuring professors
- **My Unique David Plugin** - Custom functionality plugin
- **New Database Table** - Creates custom database tables
- **Our Word Filter Plugin** - Filters content for specific words

## Installation

1. Install WordPress
2. Clone or download this repository to your WordPress installation
3. Activate the desired theme (Fictional Block Theme or Fictional University Theme)
4. Activate the required plugins
5. Import sample content (if available)

## Development

### Theme Development
The block theme uses modern JavaScript and the WordPress block editor API. To work on the theme:

1. Navigate to the theme directory: `cd wp-content/themes/fictional-block-theme`
2. Install dependencies: `npm install`
3. Run build process: `npm run build`
4. For development with auto-reload: `npm run dev`

### Plugin Development
Custom plugins follow WordPress plugin development standards. Each plugin has its own directory in the `wp-content/plugins` folder.

## Usage

### Content Management
- Use the block editor to create and edit pages with custom blocks
- Manage events, programs, professors, and campuses through their respective admin sections
- Create and manage forms with WPForms

### User Management
- Use the Members plugin to manage user roles and capabilities
- The theme includes custom functionality for subscriber users

## License
This project is licensed under the GPL v2 or later.
