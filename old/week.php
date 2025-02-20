
<?php

class CalendarWeek {

    private $active_year, $active_month, $active_day, $week_no;
    private $events = [];

    public function __construct($date = null, $week_no = 1) {
	$this->week_no = $week_no;
        $this->active_year = $date != null ? date('Y', strtotime($date)) : date('Y');
        $this->active_month = $date != null ? date('m', strtotime($date)) : date('m');
        $this->active_day = $date != null ? date('d', strtotime($date)) : date('d');
    }

    public function add_event($txt, $date, $days = 1, $color = '') {
        $color = $color ? ' ' . $color : $color;
        $this->events[] = [$txt, $date, $days, $color];
    }

    public function __toString() {
        $num_days = date('t', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year));
        $num_days_last_month = date('j', strtotime('last day of previous month', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year)));
        $days = [0 => 'Lundi', 1 => 'Mardi', 2 => 'Mercredi', 3 => 'Jeudi', 4 => 'Vendredi', 5 => 'Samedi', 6 => 'Dimanche'];
        $months = [1 => 'Janvier', 2 => 'Fevrier', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Aout', 9 => 'Septembre',10 => 'Octobre', 11 => 'Novembre', 12 => 'Decembre'];
	$year = 2024;

        $creneau = [0 => ' ', 1 => '8h30-10h30', 2 => '10h30-12h30',3 => ' ', 4 => '15h30-17h30', 5 => '17h30-19h30'];

	$ouverture= array(
		array(2,0,0,2,1,1),
		array(2,0,0,2,0,0),
		array(2,1,1,2,1,1),
		array(2,0,0,2,0,0),
		array(2,0,0,2,1,1),
		array(2,1,1,2,0,0),
		array(2,0,0,2,0,0)
	);

	$date_now = new DateTime("NOW");
	$week_no = $this->week_no;
	$week_start = new DateTime();
	$week_start->setISODate($year,$week_no);
	$year_start = $week_start->format('Y');	
	$month_start = $week_start->format('n');	

	$week_end= new DateTime();
	$week_end->setISODate($year,$week_no);
	$week_end =$week_end->modify('+6 days');
	$year_end = $week_end->format('Y');	
	$month_end = $week_end->format('n');	

        $first_day_of_week = array_search(date('D', strtotime($this->active_year . '-' . $this->active_month . '-1')), $days);
        
	$html .= '<table style="width:100%">';
        $html .= '<caption>';
        $html .= 'semaine ' . $week_no;
	if ($month_end == $month_start)
	{
        	$html .= ' ( ' . $months[$month_start] . ' ' . $year_start . ' ) ';
	}
	else
	{
		if ($year_start == $year_end)
		{
        		$html .= ' ( ' . $months[$month_start] . ' / ' . $months[$month_end] . ' ' . $year_start . ' ) ';
		}
		else
		{
        		$html .= ' ( ' . $months[$month_start] . ' ' . $year_start . ' / ' . $months[$month_end] . ' ' . $year_end . ' ) ';
		}
	}
#date('F Y', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day));
        $html .= '</caption>';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th scope="col">plage horaire</th>';
	
	$cday=0;
        foreach ($days as $day) {
	    if ($week_start == $date_now)
	    {
            	$html .= '<th scope="col">' . "EE ". $day . ' ' . $week_start->format('d-M-Y') . '</th>';
	    }
            else
	    {
		if ($date_now->format("m:d:Y") == $week_start->format("m:d:Y"))
		{
            		$html .= '<th scope="col" bgcolor="green">' . $day . ' ' . $week_start->format('d-M-Y') . '</th>';
		}
		else
		{
            		$html .= '<th scope="col">' . $day . ' ' . $week_start->format('d-M-Y') . '</th>';
		}
	    }
	    $week_start =$week_start->modify('+1 day');
	    $cday +=1;
        }
        $html .= '</tr>';
        $html .= '</thead>';
        
        $html .= '<tbody>';

	$i_cren=0;
	foreach ($creneau as $cren)
	{
       	 	$html .= '<tr style="height:100px">';
        	$html .= '<th scope="row">' . $cren . '</th>';
		for ($i = 0; $i <= 6; $i++) 
		{

			// Chemin vers le fichier texte pour stocker les données
			$filePath = 'data.txt';
			// Charger les données depuis le fichier texte si elles existent
			if (file_exists($filePath)) {
				$jsonData = file_get_contents($filePath);
				$data = json_decode($jsonData, true) ?? $data;
			}
			$benevoles = array("Julien","Cat","Caro","test","Ariane");

			// affichage b
			if ($ouverture[$i][$i_cren] == 1)
			{
				$html .= '<td class="selectable" bgcolor="#F5F5F5"  onclick="toggleSelection(this)">';
				$html .= '<table class="nested_table" style="width: 100%; height: 100%;">';
				$ben_in = 0;
				foreach ($benevoles as $ben)
				{
					$cellule = 'J' . $i . '-C' . $i_cren . '-' . $ben;
					$html .= '<tr id="texte-' . $cellule . '" style="display: compact;">';
					$html .= '<td><span>' . $ben . '</span></td>';
					//$html .= '<td>' . $ben . '</td>';
					if ($_SESSION["username"] == $ben)
					{
						$html .= '<td align="right">';
						$html .= '<div class="me-auto">';
						$html .= '<button id="supprimer-' . $cellule . '" type="button" class="btn btn-outline-danger buttonDelete ms-0"  onclick="supprimerTexte(\'' . $cellule . '\')">';
						//$html .= '&nbsp;' . ($_SESSION["username"]) . '&nbsp;';//<i class="bi bi-plus"></i></button></td>';
						$html .= '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash bi-justify-right" viewBox="0 0 16 16">';
						$html .= '<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>';
						$html .= '<path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>';
						$html .= '</svg>';
						$html .= '</button>';
						$html .= '</div>';
						$html .= '</td>';
						$ben_in = 1;
					}
					else
					{
						//$html .= '<td>' . $ben . '</td>';
					}
					$html .= '</tr>';
				}
				//if ($ben_in == 0)
				{
					$cellule = 'J' . $i . '-C' . $i_cren . '-' . $_SESSION["username"];
					if ($ben_in == 0)
						$html .= '<tr id="ajouter-' . $cellule .'">';
					else
						$html .= '<tr id="ajouter-' . $cellule .'" style="display: none;">';
					//$html .= '<td></td>';
					$html .= '<td>';
					$html .= '<div class="mx-auto">';
					$html .= '<button type="button" class="buttonAdd w-100 btn btn-outline-danger py-0 pl-0 pr-2" onclick="ajouterTexte(\'' . $cellule . '\')">';
                			$html .= '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">';
 					$html .= '<path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"></path>';
					$html .= '</svg>';
					$html .= 'Ajouter';//<i class="bi bi-plus"></i></button></td>';
              				$html .= '</button>';
					$html .= '</div>';
					$html .= '</td>';
					$html .= '</tr>';

				}

				$html .= '</table>';
				$html .= '</td>';
			}
			else
			{
				$html .= '<td>' . ' ' . '</td>';
			}
        	}
        	$html .= '</tr>';
		$i_cren+=1;;
	}


        $html .= '</tbody>';
        $html .= '</table>';
        return $html;
    }

	}
?>
