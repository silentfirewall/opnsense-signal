# FIRST DRAFT - USE AT YOUR OWN PERIL

# OPNsense Signal Notifier Plugin - Build & Style Guide

## Build & Installation Commands
```bash
# Install dependencies and set up plugin
sh opnsense-signal-install.sh

# Test signal-cli installation
/usr/local/bin/signal-cli -v

# Register phone number with Signal (replace with your number)
/usr/local/bin/signal-cli -u +1234567890 register --voice

# Verify Signal registration (replace with your number and code)
/usr/local/bin/signal-cli -u +1234567890 verify 123456

# List Signal groups to find group ID
/usr/local/bin/signal-cli -u +1234567890 listGroups --json
```

## Project Structure
- OPNsense plugin for sending Signal notifications
- PHP-based application integrated with OPNsense event system
- Follows OPNsense MVC architecture pattern

## Code Style Guidelines
- **Namespaces**: All code under OPNsense\SignalNotifier namespace
- **Classes**: PascalCase (e.g., SignalNotifierPlugin)
- **Methods**: camelCase (e.g., sendNotification)
- **Variables**: camelCase
- **Indentation**: 4 spaces, no tabs
- **Docstrings**: PHPDoc style with @param and @return tags
- **Error Handling**: Use OPNsense logging system
- **Security**: Always escape shell arguments with escapeshellarg()
- **Type Safety**: Use type checking when processing user input
- **Validation**: Implement proper input validation before using
- **Copyright**: Include copyright header with BSD 2-Clause license
