<?php
class Form {
	private static $pdo;

	private $id;
	private $type;
	private $pStatus;
	private $pwd;
	private $name;
	private $question;
	private $date;
	private $listePropositions;
	private $totalReponse;

	public static $lengthStartAutoComplete = 3;

	public function __construct($id, $nom, $question, $date){
		$this->id                = Util::secure($id);
		$this->name              = Util::secure($nom);
		$this->question          = Util::secure($question);
		$this->date              = Util::secure($date);
		$this->listePropositions = array();
		$this->totalReponse      = 0;
	}

	public static function setPdo($bdd){
		Form::$pdo = $bdd;
	}

	public function performCreate(){
		// On vérifie l'intégrité du Formulaire
		$msg = $this->checkIntegrity();
		if (sizeof($msg) == 0) {
			$formName = $this->getName();
			$formQuestion = $this->getQuestion();
			$time = time();
			$req = Form::$pdo->prepare("INSERT INTO form_name (nom, question, date) VALUES (:name, :question, :date)");
			$req->bindParam(':name', $formName);
			$req->bindParam(':question', $formQuestion);
			$req->bindParam(':date', $time);
			if ($req->execute()) {
				$idForm = Form::$pdo->lastInsertId();
				// On persiste chaque Proposition
				foreach ($this->getListeProposition() as $key => $proposition) {
					$req = Form::$pdo->prepare("INSERT INTO form_proposition (id_form, title) VALUES (:form_id, :prop_titre)");
					$req->bindParam(':form_id', $idForm);
					$req->bindParam(':prop_titre', $proposition['titre']);
					$req->execute();
				}
			}
			$msg['success'][] = array("message" => "Votre sondage a bien été enregistré.");
		}
		return $msg;
	}

	/**
	 * Permet de vérifier l'intégrité d'un Formulaire
	 */
	private function checkIntegrity(){
		$msg = array();
		if (strlen($this->getName()) < 3) {
			$msg['error'][] = array("message" => "Le nom du sondage doit faire minimum 3 caractères.");
		}
		if (strlen($this->getQuestion()) < 3) {
			$msg['error'][] = array("message" => "La question du sondage doit faire minimum 3 caractères.");
		}
		foreach ($this->getListeProposition() as $key => $proposition) {
			if (strlen($proposition['titre']) < 3) {
				$msg['error'][] = array("message" => "La proposition n°".($key+1)." doit faire minimum 3 caractères.");
			}
		}
		return $msg;
	}

	/**
	 * Permet de créer de nouvelles propositions pour un Formulaire
	 */
	public function createProp($listeProp){
		foreach($listeProp as $proposition) {
			$prop = array("id_proposition" => 0, "title" => Util::secure($proposition));
			$this->addProposition($prop, array());
		}
	}

	/**
	 * Ajoute une proposition à l'objet Formulaire
	 */
	private function addProposition($prop, $listEntry){
		$this->listePropositions[] = array("id" => $prop['id_proposition'],
																  "titre" => Util::secure($prop['title']),
																  "nbReponse" => sizeof($listEntry),
																  "listEntry" => $listEntry);
		$this->totalReponse += sizeof($listEntry);
	}

	public function getPropositionPercent($nbReponse){
		if ($this->getTotal() != 0 && $nbReponse != 0) {
			return round($nbReponse / $this->getTotal() * 100);
		} else {
			return 0;
		}
	}

	/**
	 * Permet de savoir si le client à déjà répondu à ce sondage
	 */
	public function checkAlreadySubmit(){
		$ipClient = $_SERVER['REMOTE_ADDR'];
		foreach ($this->listePropositions as $prop) {
			foreach ($prop['listEntry'] as $entry) {
				if ($entry['ip'] == $ipClient) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Enregistre une nouvelle réponse pour un Formulaire
	 */
	public function addEntry($idProp){
		$msg = array();
		// On vérifie dabord que le client n'a pas déjà répondu au formulaire
		if (!$this->checkAlreadySubmit()) {
			if (!is_null($idProp)) {
				$time = time();
				$req = Form::$pdo->prepare("INSERT INTO form_entry VALUES (:id_prop, :ip, :time)");
				$req->bindParam(':id_prop', $idProp);
				$req->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
				$req->bindParam(':time', $time);
				if ($req->execute()) {
					$msg['success'][] = array("message" => "Votre réponse au sondage a bien été enregistré.");
				} else {
					$msg['error'][] = array("message" => "Une erreur est survenue lors la soumission du sondage.");
				}
			}
		// Si une réponse de ce client est déjà référencée dans ce Sondage
		} else {
			$msg['error'][] = array("message" => "Vous avez déjà répondu à ce sondage.");
		}
		return $msg;
	}

	/**
	 * Retourne une liste de Sondage triée par date de création
	 */
	public static function getLastForm($nb){
		$listForm = array();
		$req = Form::$pdo->prepare("SELECT * FROM form_name fp ORDER BY fp.date DESC LIMIT :nb");
		$req->bindValue(':nb', $nb, PDO::PARAM_INT);
		$req->setFetchMode(PDO::FETCH_ASSOC);
		$req->execute();
		// Ajoute les propositions à l'objet Formulaire
		while ($row = $req->fetch()) {
			$form = new Form($row['id'], $row['nom'], $row['question'], $row['date']);
			Form::hydrateForm($form);
			$listForm[] = $form;
		}
		return $listForm;
	}

	/**
	 * Retourne une liste de Sondage triée par nombre de vue
	 */
	public static function getBestForm($nb){
		$listForm = array();
		$req = Form::$pdo->prepare("SELECT id, nom, question, date, 
										(SELECT  COUNT(*)
										FROM form_entry fe 
										LEFT JOIN form_proposition fp ON fe.id_prop = fp.id_proposition
										WHERE fp.id_form = fn.id) 
										AS nbEntry 
									FROM form_name fn
									ORDER BY nbEntry DESC, fn.date DESC
									LIMIT :nb");
		$req->bindValue(':nb', $nb, PDO::PARAM_INT);
		$req->setFetchMode(PDO::FETCH_ASSOC);
		$req->execute();
		// Ajoute les propositions à l'objet Formulaire
		while ($row = $req->fetch()) {
			//Util::dump($row);
			$form = new Form($row['id'], $row['nom'], $row['question'], $row['date']);
			Form::hydrateForm($form);
			$listForm[] = $form;
		}
		return $listForm;
	}

	public static function getSearchForm($text) {
		$text = Util::secure($text);
		if (strlen($text) >= Form::$lengthStartAutoComplete) {
			$listForm = array();
			$req = Form::$pdo->prepare("SELECT * FROM form_name fp WHERE nom LIKE :search OR question LIKE :search ORDER BY fp.date DESC LIMIT :nb");
			$req->bindValue(':nb', 20, PDO::PARAM_INT);
			$req->bindValue(':search', '%'.$text.'%');
			$req->setFetchMode(PDO::FETCH_ASSOC);
			$req->execute();
			// Ajoute les propositions à l'objet Formulaire
			while ($row = $req->fetch()) {
				$form = new Form($row['id'], $row['nom'], $row['question'], $row['date']);
				Form::hydrateForm($form);
				$listForm[] = $form;
			}
			return $listForm;
		}
	}

	/**
	 * Permet de récupérer un Formulaire par ID
	 */
	public static function getForm($id){
		if (!is_null($id)) {
			// récupère le Formulaire
			$req  = Form::$pdo->prepare("SELECT * FROM form_name WHERE id = :id_form");
			$req->bindValue(':id_form', $id, PDO::PARAM_INT);
			$req->execute();
			$req->setFetchMode(PDO::FETCH_ASSOC);
			$data = $req->fetch();
			if ($data != false) {
				$form = new Form($data['id'], $data['nom'], $data['question'], $data['date']);
				Form::hydrateForm($form);
				return $form;
			}
		}
	}

	/**
	 * Permet d'hydrater l'objet
	 */
	public static function hydrateForm($form) {
		// Récupère les propositions du Formualaire
		$req = Form::$pdo->prepare("SELECT * FROM form_proposition
									WHERE id_form = :id_form");
		$req->bindValue(':id_form', $form->getId(), PDO::PARAM_INT);
		$req->setFetchMode(PDO::FETCH_ASSOC);
		$req->execute();
		// Ajoute les propositions à l'objet Formulaire
		while ($row = $req->fetch()) {
			// Pour chaque réponse, on récupère les réponses associées
			$reqProp = Form::$pdo->prepare("SELECT * FROM form_entry
										WHERE id_prop = :id_prop");
			$reqProp->bindValue(':id_prop', $row['id_proposition'], PDO::PARAM_INT);
			$reqProp->setFetchMode(PDO::FETCH_ASSOC);
			$reqProp->execute();
			$listEntry = $reqProp->fetchAll();
			// On ajoute au Formulaire
			$form->addProposition($row, $listEntry);
		}
	}

	/* ---- GETTERS ---- */
	public function getId(){
		return $this->id;
	}
	public function getName(){
		return $this->name;
	}
	public function getQuestion(){
		return $this->question;
	}
	public function getDate(){
		return $this->date;
	}
	public function getListeProposition(){
		return $this->listePropositions;
	}
	public function getTotal(){
		return $this->totalReponse;
	}
}