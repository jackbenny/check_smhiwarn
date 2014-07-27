#!/usr/bin/php
<?php

/*
    Copyright (C) 2014 Jack-Benny Persson <jack-benny@cyberinfo.se>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Define exit status
$ok = 0;
$warning = 1;
$critical = 2;
$unknown = 3;

$version = 0.1;
$program = $argv[0];

// Function for printing usage
function usage()
{
    print "check_smhiwarn version $GLOBALS[version]\n";
    print "Copyright (C) Jack-Benny Persson <jack-benny@cyberinfo.se>\n";
    print "Usage: $GLOBALS[program] 'District'\n";
    print "Example: $GLOBALS[program] 'Skåne län utom Österlen'\n";
}

// All of the avaliable districts
$availDistricts = array(
"Skagerack",
"Vänern",
"Kattegatt",
"Bälten",
"Sydvästra Östersjön",
"Södra Östersjön",
"Sydöstra Östersjön",
"Mellersta Östersjön",
"Mellersta Östersjön",
"Norra Östersjön",
"Rigabukten",
"Finska viken",
"Skärgårdshavet",
"Södra Bottenhavet",
"Norra Bottenhavet",
"Norra Kvarken",
"Bottenviken",
"Dalarnas län, Dalafjällen",
"Jämtlands län, Härjedalsfjällen",
"Jämtlands län, Jämtlandsfjällen",
"Västerbottens län, södra Lapplandsfjällen",
"Norrbottens län, norra Lapplandsfjällen",
"Skåne län utom Österlen",
"Skåne län, Österlen",
"Blekinge län",
"Hallands län",
"Kronobergs län, västra delen",
"Kronobergs län, östra delen",
"Kalmar län, öland",
"Gotlands län",
"Jönköpings län, västra delen utom syd om Vättern",
"Jönköpings län, östra delen",
"Kalmar län utom öland",
"Jönköpings län, syd om Vättern",
"Västra Götalands län, Sjuhäradsbygden och Göta älv",
"Västra Götalands län, Bohuslän och Göteborg",
"Västra Götalands län, inre Dalsland",
"Västra Götalands län, sydväst Vänern",
"Västra Götalands län, norra Västergötland",
"Värmlands län",
"Södermanlands län",
"Stockholms län utom Roslagskusten.",
"Västmanlands län",
"Uppsala län utom Upplandskusten",
"Stockholms län, Roslagskusten",
"Uppsala län, Upplandskusten",
"Dalarnas län utom Dalafjällen",
"Gävleborgs län kustland",
"Gävleborgs län inland",
"Västernorrlands län",
"Jämtlands län utom fjällen",
"Västerbottens län kustland",
"Västerbottens län inland",
"Norrbottens län kustland",
"Norrbottens län inland",
);

// Check if first argument is set
if (!isset($argv[1]))
{
    usage();
    exit($unknown);
}

// Set first argument to $district
$district = $argv[1];

// Simple way of listing all the avaliable districts
if ($district == 'list')
{
    foreach ($availDistricts as $dist)
    print "$dist\n";
    exit($unknown);
}

// Check if the district exists
if (!preg_grep("/^$district$/", $availDistricts))
{
    print "$district does not exists\n";
    print "List all avaliable districts by \"$program 'list'\"\n";
    exit($unknown);
}


// Retrive the data
//$data = file_get_contents("testing/smhi_alla_varningar.xml"); //For testing purposes
$data = shell_exec("curl -s http://www.smhi.se/weatherSMHI2/varningar/smhi_alla_varningar.xml");

// Regex the area (1st paranthesis is area, 2nd is warning class, 3rd is warning msg)
preg_match("/($district)(?:: )(?:Varning klass )([1-3]+)(?:,\s)([-a-z0-9åäö.,&\s]*)/i", 
$data, $matches);

// Count how many warnings are issued and issue a critical if more than one
preg_match_all("/$district/", $data, $counts);
$numberMatches = (count($counts[0]));
if ($numberMatches > 1)
{
    print "More than one warning are issued for $district, check smhi.se!";
    exit($critical);
}

// Define the paranthesis
if (isset($matches[2]))
{
    $warnLevel = $matches[2];
    $warnMsg = $matches[3];
}
else
{
    $warnLevel = 0;
}

// Check for warnings and exit with correct exit status
switch ($warnLevel)
{
    case 0:
        print "No warnings issued $district";
        exit($ok);
    case 1:
        print "Class 1 warning for $district issued, $warnMsg";
        exit($warning);
    case 2:
        print "Class 2 warning for $district, $warnMsg";
        exit($critical);
    case 3:
        print "Class 3 warning for $district, $warnMsg";
        exit($critical);
    default:
        print "Unknown error for $district";
        exit($unknown);
}

?>
