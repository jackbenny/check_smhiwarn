# Check SMHI Warn #
This is a Nagios plugin that checks SMHI 
(Sveriges meteorologiska och hydrologiska institut) for weather alerts. For 
class 1 warning, the scripts exit with a warning state, for class 2 and 3
it exit with a critical-state. In the rare cases where more than one warning
is issued for the same area, the script exit with a critical state and a message
that more than one warning is issued for the area.

## Usage ##
The script only takes one argument, the area/district for which it should check
for weather alerts. The district must be entered exactly as it is on the SMHI
webpage. For a complete list, enter 'list' instead of a district for argument.
Don't forget to quote the district in the argument, such as the below example.

    check_smhiwarn.php 'Skåne län utom Österlen'

Create one instance of each district you want to monitor.

## Requirements ##
The script requires PHP5 and the PHP5 cURL module (php5-curl on Debian systems).

## Copyright ##
Original author is Jack-Benny Persson (jack-benny@cyberinfo.se).

This script is release under GNU GPL version 2. The script should not be used to
protect life and/or property.
