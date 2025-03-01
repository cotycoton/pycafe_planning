

<?php
function generateMapping($data) {
    $prenomCounts = array();
    
    // Compter l'occurrence de chaque prénom (en minuscule)
    foreach ($data as $entry) {
        $prenom = strtolower($entry['prenom']);
        if (!isset($prenomCounts[$prenom])) {
            $prenomCounts[$prenom] = 0;
        }
        $prenomCounts[$prenom]++;
    }
    
    $mapping = array();
    $mapping_inv = array();
    
    // Générer le mapping
    foreach ($data as $entry) {
        $prenom = strtolower($entry['prenom']);
        $nom = strtolower($entry['nom']);
        $id = strtolower($entry['id']);
        
        if ($prenomCounts[$prenom] > 1) {
            $identifier = ucwords($prenom) . " " . ucwords(substr($nom, 0, 2)) . '.';
        } else {
            $identifier = ucwords($prenom);
        }
        
        //$mapping[$identifier] = ['nom' => $nom, 'prenom' => $prenom];
	$mapping[$id] = $identifier;
	$mapping_inv[$identifier] = $id;
    }
    
    return compact('mapping','mapping_inv');
}


?>
