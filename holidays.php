
<?php
function getFrenchHolidays(int $year, ?string $region = null, string $tz = 'Europe/Paris'): array {
	$tzObj = new DateTimeZone($tz);
	$fmt = fn(DateTimeImmutable $d) => $d->format('d-m-Y'); // sortie au format d-m-Y


	// helper qui crée une date au format Y-m-d
	$mk = function(string $dateStr) use ($tzObj,$fmt): string {
		$d = new DateTimeImmutable($dateStr, $tzObj);
		return $fmt($d);
	};

	// jours fixes
	$holidays = [
		$mk("$year-01-01") => "Jour de l'an",
		$mk("$year-05-01") => "Fête du Travail",
		$mk("$year-05-08") => "Victoire 1945",
		$mk("$year-07-14") => "Fête nationale",
		$mk("$year-08-15") => "Assomption",
		$mk("$year-11-01") => "Toussaint",
		$mk("$year-11-11") => "Armistice 1918",
		$mk("$year-12-25") => "Noël",
	];
	$holidays = [
		$fmt(new DateTimeImmutable("$year-01-01", $tzObj)) => "Jour de l'an",
		$fmt(new DateTimeImmutable("$year-05-01", $tzObj)) => "Fête du Travail",
		$fmt(new DateTimeImmutable("$year-05-08", $tzObj)) => "Victoire 1945",
		$fmt(new DateTimeImmutable("$year-07-14", $tzObj)) => "Fête nationale",
		$fmt(new DateTimeImmutable("$year-08-15", $tzObj)) => "Assomption",
		$fmt(new DateTimeImmutable("$year-11-01", $tzObj)) => "Toussaint",
		$fmt(new DateTimeImmutable("$year-11-11", $tzObj)) => "Armistice 1918",
		$fmt(new DateTimeImmutable("$year-12-25", $tzObj)) => "Noël",
	];

    // Jours mobiles (calculés à partir de Pâques)
    $easter = (new DateTimeImmutable('@' . easter_date($year)))->setTimezone($tzObj);
    $holidays[$fmt($easter)] = "Pâques";
    $holidays[$fmt($easter->add(new DateInterval('P1D')))] = "Lundi de Pâques";
    $holidays[$fmt($easter->add(new DateInterval('P39D')))] = "Ascension";
    $holidays[$fmt($easter->add(new DateInterval('P49D')))] = "Pentecôte";
    $holidays[$fmt($easter->add(new DateInterval('P50D')))] = "Lundi de Pentecôte";

    // Jours supplémentaires pour Alsace-Moselle
    if ($region === 'alsace') {
        $holidays[$fmt($easter->sub(new DateInterval('P2D')))] = "Vendredi Saint";
        $holidays[$fmt(new DateTimeImmutable("$year-12-26", $tzObj))] = "Saint-Étienne";
    }

    ksort($holidays);
    return $holidays;

}

// Exemple d'utilisation:
#$year = 2025;
#$holidays = getFrenchHolidays($year, ''); // ou null pour France "classique"
#foreach ($holidays as $date => $name) {
#    echo "<p>$date — $name</p>";
#}

function getHolidayName(string $date, ?string $region = null): ?string {
    $dt = DateTime::createFromFormat('d-m-Y', $date);
    if (!$dt) return null; // format invalide
    $year = (int)$dt->format('Y');
    $holidays = getFrenchHolidays($year, $region);
    return $holidays[$date] ?? null;
}


// Exemple :
//echo getHolidayName('2025-05-01'); // → "Fête du Travail"

//$date = '2025-12-25';
//$isHoliday = array_key_exists($date, getFrenchHolidays(2025));


//echo "$date — $isHoliday\n";
//echo "$holidays[$date]";

// Vérifier si des paramètres sont passés via GET
if (isset($_GET['date'])) {
	header('Content-Type: application/json');
	echo json_encode(["holiday" => getHolidayName($_GET['date'])]);
}
?>

