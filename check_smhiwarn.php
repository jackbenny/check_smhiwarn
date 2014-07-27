<?php
// Define exit status
$ok = 0;
$warning = 1;
$critical = 2;
$unknown = 3;

$version = 0.1;
$program = $argv[0];

//Function for printing usage
function usage()
{
    print "check_smhiwarn version $GLOBALS[version]\n";
    print "Copyright (C) Jack-Benny Persson <jack-benny@cyberinfo.se>\n";
    print "Usage: $GLOBALS[program] 'District'\n";
    print "Example: $GLOBALS[program] 'Skåne län utom Österlen'\n";
}

//All of the avaliable districts
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

//Check if first argument is set
if (!isset($argv[1]))
{
    usage();
    exit($unknown);
}

//Set first argument to $district
$district = $argv[1];

//Simple way of listing all the avaliable districts
if ($district == 'list')
{
    foreach ($availDistricts as $dist)
    print "$dist\n";
    exit($unknown);
}

//Check if the district exists
if (!preg_grep("/^$district$/", $availDistricts))
{
    print "$district does not exists\n";
    print "List all avaliable districts by \"$program 'list'\"\n";
    exit($unknown);
}


// Retrive the data
$data = file_get_contents("smhi_alla_varningar.xml");

//Regex the area (1st parathentis is area, 2nd is warning class, 3rd is warning msg)
preg_match("/($district)(?:: )(?:Varning klass )([1-3]+)(?:,\s)([-a-z0-9åäö.,&\s]*)/i", 
$data, $matches);

//Define the paranthesis
if (isset($matches[2]))
{
    $warnLevel = $matches[2];
    $warnMsg = $matches[3];
}
else
{
    $warnLevel = 0;
}

//Check for warnings...
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
