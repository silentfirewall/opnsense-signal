#!/bin/sh

# Install signal-cli dependencies
pkg install -y java openjdk8 wget unzip

# Create directories
mkdir -p /usr/local/opnsense/service/conf/actions.d
mkdir -p /usr/local/share/signal-cli

# Download and install signal-cli
cd /tmp
wget https://github.com/AsamK/signal-cli/releases/download/v0.11.9/signal-cli-0.11.9.tar.gz
tar xf signal-cli-0.11.9.tar.gz -C /usr/local/share/
ln -sf /usr/local/share/signal-cli-0.11.9/bin/signal-cli /usr/local/bin/signal-cli

# Create actions file
cat > /usr/local/opnsense/service/conf/actions.d/actions_signalnotifier.conf << 'EOL'
[test]
command:/usr/local/bin/signal-cli -v
parameters:
type:script_output
message:Testing signal-cli installation

[register]
command:/usr/local/bin/signal-cli -u %s register --voice
parameters:%s
type:script_output
message:Registering phone number with Signal (voice verification)

[verify]
command:/usr/local/bin/signal-cli -u %s verify %s
parameters:%s %s
type:script_output
message:Verifying Signal registration with code

[list-groups]
command:/usr/local/bin/signal-cli -u %s listGroups --json
parameters:%s
type:script_output
message:Listing available Signal groups
EOL

# Set permissions
chmod +x /usr/local/bin/signal-cli
chown -R www:www /usr/local/share/signal-cli-0.11.9

echo "Signal Notifier plugin installation complete!"
echo "Please follow these steps to complete setup:"
echo "1. Install the plugin through System > Firmware > Plugins"
echo "2. Register your phone number: Services > Signal Notifier > Register"
echo "3. Configure group settings: Services > Signal Notifier > General Settings"
echo "4. Configure notification triggers: Services > Signal Notifier > Notification Triggers"
