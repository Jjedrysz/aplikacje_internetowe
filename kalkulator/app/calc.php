<?php
require_once dirname(__FILE__).'/../config.php';

// KONTROLER strony kalkulatora

// W kontrolerze niczego nie wysyła się do klienta.
// Wysłaniem odpowiedzi zajmie się odpowiedni widok.
// Parametry do widoku przekazujemy przez zmienne.

//ochrona kontrolera - poniższy skrypt przerwie przetwarzanie w tym punkcie gdy użytkownik jest niezalogowany
include _ROOT_PATH.'/app/security/check.php';

//pobranie parametrów
function getParams(&$x,&$currency){
	$x = isset($_REQUEST['x']) ? $_REQUEST['x'] : null;
	$y = isset($_REQUEST['y']) ? $_REQUEST['y'] : null;
	$currency = isset($_REQUEST['c']) ? $_REQUEST['c'] : null;	
}

//walidacja parametrów z przygotowaniem zmiennych dla widoku
function validate(&$x,&$currency,&$messages){
	// sprawdzenie, czy parametry zostały przekazane
	if ( ! (isset($x) && isset($currency))) {
		// sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
		// teraz zakładamy, ze nie jest to błąd. Po prostu nie wykonamy obliczeń
		return false;
	}

	// sprawdzenie, czy potrzebne wartości zostały przekazane
	if ( $x == "") {
		$messages [] = 'Nie podano kwoty';
	}
		//nie ma sensu walidować dalej gdy brak parametrów
	if (count ( $messages ) != 0) return false;
		
	if (count ( $messages ) != 0) return false;
	else return true;
}

function process(&$x,&$currency,&$messages,&$result){
	global $role;
		
	//konwersja parametrów na int
	$x = intval($x);
	
	//wykonanie operacji
	switch ($currency) {
		case 'euro' :
		if ($role == 'admin'){
				$result = $x * 4.3;
			} else {
				$messages [] = 'Funkcja dostępna tylko dla administratora !';
			}
			break;
		case 'dolar' :
			$result = $x * 4;
			break;
		case 'korona' :
			$result = $x * 0.2;
			break;
		default :
			$result = $x * 0.25;
			break;
	}
}

//definicja zmiennych kontrolera
$x = null;
$currency = null;
$result = null;
$messages = array();

//pobierz parametry i wykonaj zadanie jeśli wszystko w porządku
getParams($x,$currency);
if ( validate($x,$currency,$messages) ) { // gdy brak błędów
	process($x,$currency,$messages,$result);
}

// Wywołanie widoku z przekazaniem zmiennych
// - zainicjowane zmienne ($messages,$x,$y,$operation,$result)
//   będą dostępne w dołączonym skrypcie
include 'calc_view.php';