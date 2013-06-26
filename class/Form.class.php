<?php
class Form {
	private static $pdo;

	private $id;
	private $name;
	private $question;
	private $date;
	private $listePropositions;
	private $totalReponse;

	public function __construct($id, $nom, $question, $date){
		$this->id                = $id;
		$this->name              = $nom;
		$this->question          = $question;
		$this->date              = $date;
		$this->listePropositions = array();
		$this->totalReponse      = 0;
	}

	public static function setPdo($bdd){
		Form::$pdo = $bdd;
	}

	/**
	 * Ajoute une proposition à l'objet Formulaire
	 */
	private function addProposition($prop, $listEntry){
		$this->listePropositions[$prop['id_proposition']] = array("id" => $prop['id_proposition'],
																  "titre" => $prop['title'],
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
		// On vérifie dabord que le client n'a pas déjà répondu au formulaire
		if (!$this->checkAlreadySubmit()) {
			if (!is_null($idProp)) {
				$time = time();
				$req = Form::$pdo->prepare("INSERT INTO form_entry VALUES (:id_prop, :ip, :time)");
				$req->bindParam(':id_prop', $idProp);
				$req->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
				$req->bindParam(':time', $time);
				if ($req->execute()) {
					return array("type" => "success", "message" => "Votre réponse au formulaire a bien été enregistré.");
				} else {
					return array("type" => "error", "message" => "Une erreur est survenue lors la soumission du formulaire.");
				}
			}
		// Si une réponse de ce client est déjà référencée dans ce Sondage
		} else {
			return array("type" => "error", "message" => "Vous avez déjà répondu à ce sondage.");
		}
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
			$listForm[] = new Form($row['id'], $row['nom'], $row['question'], $row['date']);
		}
		return $listForm;
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