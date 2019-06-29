<?php

class TestClass {
  var $host;
  var $username;
  var $password;
  var $table;
  var $connexion;

  public function display_edit_page($title, $body){
	  $display_form_edit = <<<DEBUT_FORM_EDIT
		<h3>Formulaire de modification :</h3>
		<form action="{$_SERVER['PHP_SELF']}" method="post">
		  <label for="title">Title:</label>
		  <input name="title" id="title" value="{$title}" type="hidden"/>
		  <label for="bodytext">Body Text:</label>
		  <textarea name="bodytext" id="bodytext">{$body}</textarea>
		  <input name="action" id="action" value="modification" type="hidden"/>
		  <input type="submit" value="Update" />
		</form>
	  
DEBUT_FORM_EDIT;
	return $display_form_edit;
  }

  public function display_public() {
    $q = "SELECT * FROM testDB ORDER BY created DESC LIMIT 3";
    $r = mysqli_query($this->connexion, $q);

    if ( $r !== false && mysqli_num_rows($r) > 0 ) {
      $entry_display = <<<DEBUT_TABLE
	  
	  <table border=1>
		<tr>
			<th>Title</th>
			<th>Body</th>
			<th>Modifier</th>
			<th>Supprimer</th>
		</tr>
DEBUT_TABLE;
	  while ( $a = mysqli_fetch_assoc($r) ) {
        $title = stripslashes($a['title']);
        $bodytext = stripslashes($a['bodytext']);

        $entry_display .= <<<ENTRY_DISPLAY
    
		<tr>
			<td>$title</td>
			<td>$bodytext</td>
			<td>
				<a href='{$_SERVER['PHP_SELF']}?admin=1&title={$title}&body={$bodytext}'>Modifier</a>
			</td>
			<td>
				<a href='{$_SERVER['PHP_SELF']}?title={$title}'>Supprimer</a>
			</td>
		</tr>

ENTRY_DISPLAY;
      }
	  $entry_display = $entry_display.'</table>';
    } else {
      $entry_display = <<<ENTRY_DISPLAY

    <h2>This Page Is Under Construction</h2>
    <p>
      No entries have been made on this page. 
      Please check back soon, or click the
      link below to add an entry!
    </p>

ENTRY_DISPLAY;
    }
    $entry_display .= <<<ADMIN_OPTION

    <p class="admin_link">
      <a href="{$_SERVER['PHP_SELF']}?admin=1">Add a New Entry</a>
    </p>

ADMIN_OPTION;

    return $entry_display;
  }

  public function display_admin() {
    return <<<ADMIN_FORM

		<form action="{$_SERVER['PHP_SELF']}" method="post">
		  <label for="title">Title:</label>
		  <input name="title" id="title" type="text" maxlength="150" />
		  <label for="bodytext">Body Text:</label>
		  <textarea name="bodytext" id="bodytext"></textarea>
		  <input name="action" id="action" value="creation" type="hidden"/>
		  <input type="submit" value="Create This Entry!" />
		</form>

ADMIN_FORM;
  }
  
  public function remove($title){
	if ( $title )
      $title = mysqli_real_escape_string($this->connexion, $title);
      $sql = "DELETE FROM testDB WHERE title = '$title'";
	  mysqli_query($this->connexion, $sql);
	  return $this->display_public();
  }
  
  public function updateData($p){
	if ( $p['title'] )
      $title = mysqli_real_escape_string($this->connexion, $p['title']);
    if ( $p['bodytext'])
      $bodytext = mysqli_real_escape_string($this->connexion, $p['bodytext']);
    
	$created = time();
	$sql = "UPDATE testDB db SET db.bodytext = '$bodytext' WHERE db.title = '$title'";
	return mysqli_query($this->connexion, $sql);
	
	  
  }

  public function add($p) {
    if ( $p['title'] )
      $title = mysqli_real_escape_string($this->connexion, $p['title']);
    if ( $p['bodytext'])
      $bodytext = mysqli_real_escape_string($this->connexion, $p['bodytext']);
    if ( $title && $bodytext ) {
      $created = time();
      $sql = "INSERT INTO testDB VALUES('$title','$bodytext','$created')";
      return mysqli_query($this->connexion, $sql);
    } else {
      return false;
    }
}

// Connexion à la base de données 
  public function connect() {
    $this->connexion = mysqli_connect($this->host,$this->username,$this->password, $this->table) or die("Could not connect. " . mysql_error());
    mysqli_select_db($this->connexion, $this->table) or die("Could not select database. " . mysql_error());

    return $this->buildDB();
  }

// Création de la table testDB 
  private function buildDB() {
    $sql = <<<MySQL_QUERY
        CREATE TABLE IF NOT EXISTS testDB (
            title	VARCHAR(150),
            bodytext	TEXT,
            created	VARCHAR(100)
    )
MySQL_QUERY;

    return mysqli_query($this->connexion, $sql);
  }
}

?>