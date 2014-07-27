<?php
$ok = 0;
$warning = 1;
$critical = 2;
$unknown = 3;
$data = file_get_contents("smhi_alla_varningar.xml");

preg_match("/(Norrbottens län kustland)(?:: )(?:Varning klass )([1-3]+)(?:,\s)([-a-z0-9åäö.,&\s]*)/i", 
$data, $matches);


print "Area: $matches[1]\n";
print "Class: $matches[2]\n";
print "Msg: $matches[3]\n";
?>
