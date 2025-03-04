# OPNsense Signal Notifier Plugin

This plugin allows OPNsense to send urgent notifications to a Signal group when important events occur on your firewall.

## Features

- Send notifications to a Signal group for various system events
- Configure which events trigger notifications
- Mark specific notifications as urgent
- Test notifications through the UI
- Support for all OPNsense system events:
  - Interface status changes
  - Service status changes
  - Firewall alerts
  - VPN connection status
  - IDS/IPS alerts
  - System events

## Requirements

- OPNsense 23.1 or later
- Java Runtime Environment (JRE) 8 or later
- Internet connectivity from OPNsense to Signal servers
- A registered Signal phone number

## Installation

1. Install the plugin from the OPNsense plugins page:
   System > Firmware > Plugins > search for "os-signalnotifier"

2. After installation, navigate to Services > Signal Notifier

3. You'll need to register a phone number with Signal. This can be done using:
   ```
   # signal-cli -u +1234567890 register --voice
   ```
   Replace +1234567890 with your actual phone number including country code.

4. Verify the registration with the code you receive:
   ```
   # signal-cli -u +1234567890 verify 123456
   ```
   Replace 123456 with the verification code you received.

5. List your Signal groups to find the group ID:
   ```
   # signal-cli -u +1234567890 listGroups --json
   ```
   Copy the group ID from the output to use in the plugin configuration.

## Configuration

1. **General Settings**:
   - Enable/disable the plugin
   - Set the path to signal-cli (default: /usr/local/bin/signal-cli)
   - Enter your registered phone number
   - Enter your Signal group ID
   - Enable/disable verbose logging

2. **Notification Triggers**:
   - Configure which events trigger notifications
   - Options include interfaces, services, firewall, VPN, IDS/IPS, and system events

3. **Urgency Settings**:
   - Configure which events are marked as urgent
   - Urgent notifications will have an "[URGENT]" prefix

4. **Test**:
   - Send a test notification to verify your configuration
   - Option to mark the test as urgent or normal

## Troubleshooting

- Check the OPNsense system logs for any errors related to the plugin
- Verify that signal-cli is installed and working correctly
- Ensure your phone number is registered with Signal
- Verify the group ID is correct
- Check that the Signal account can access the specified group

## Support

For issues and feature requests, please submit them on GitHub or the OPNsense forum.

## License

This plugin is licensed under the BSD 2-Clause License.
