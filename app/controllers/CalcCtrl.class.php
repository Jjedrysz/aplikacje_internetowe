<?php
// W skrypcie definicji kontrolera nie trzeba dołączać problematycznego skryptu config.php,
// ponieważ będzie on użyty w miejscach, gdzie config.php zostanie już wywołany.

require_once $conf->root_path.'/lib/smarty/Smarty.class.php';
require_once $conf->root_path.'/lib/Messages.class.php';
require_once $conf->root_path.'/app/CalcForm.class.php';
require_once $conf->root_path.'/app/CalcResult.class.php';

/** Kontroler kalkulatora
 * @author Przemysław Kudłacik
 *
 */
class CalcCtrl {

	private $msgs;   //wiadomości dla widoku
	private $form;   //dane formularza (do obliczeń i dla widoku)
	private $result; //inne dane dla widoku
	private $hide_intro; //zmienna informująca o tym czy schować intro

	/** 
	 * Konstruktor - inicjalizacja właściwości
	 */
	public function __construct(){
		//stworzenie potrzebnych obiektów
		$this->msgs = new Messages();
		$this->form = new CalcForm();
		$this->result = new CalcResult();
		$this->hide_intro = false;
	}
	
	/** 
	 * Pobranie parametrów
	 */
	public function getParams(){
		$this->form->x = isset($_REQUEST ['x']) ? $_REQUEST ['x'] : null;
		$this->form->op = isset($_REQUEST ['c']) ? $_REQUEST ['c'] : null;
	}
	
	/** 
	 * Walidacja parametrów
	 * @return true jeśli brak błedów, false w przeciwnym wypadku 
	 */
	//walidacja parametrów z przygotowaniem zmiennych dla widoku
	function validate(&$form,&$infos,&$messages,&$hide_intro){
		// sprawdzenie, czy parametry zostały przekazane
		if ( ! (isset($form['x']) && isset($form['currency']))) {
			// sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
			// teraz zakładamy, ze nie jest to błąd. Po prostu nie wykonamy obliczeń
			return false;
		}

		//parametry przekazane zatem
		//nie pokazuj wstępu strony gdy tryb obliczeń (aby nie trzeba było przesuwać)
		// - ta zmienna zostanie użyta w widoku aby nie wyświetlać całego bloku itro z tłem 
		$hide_intro = true;

		$infos [] = 'Przekazano parametry.';

		// sprawdzenie, czy potrzebne wartości zostały przekazane
		if ( $x == "") {
			$messages [] = 'Nie podano kwoty';
		}
			//nie ma sensu walidować dalej gdy brak parametrów
		if (count ( $messages ) != 0) return false;
			
		if (count ( $messages ) != 0) return false;
		else return true;
	}
	
	/** 
	 * Pobranie wartości, walidacja, obliczenie i wyświetlenie
	 */
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
	
	
	/**
	 * Wygenerowanie widoku
	 */
	public function generateView(){
		global $conf;
		
		$smarty = new Smarty();
		$smarty->assign('conf',$conf);
		
		$smarty->assign('page_title','Przykład 05');
		$smarty->assign('page_description','Kalkulator');
		$smarty->assign('page_header','Kalkulator');
				
		$smarty->assign('hide_intro',$this->hide_intro);
		
		$smarty->assign('msgs',$this->msgs);
		$smarty->assign('form',$this->form);
		$smarty->assign('res',$this->result);
		
		$smarty->display($conf->root_path.'/app/CalcView.html');
	}
}
